<?php
// backend/realtime_scan.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

require_once 'config.php';
$conn = getDBConnection();

$conn->query("
    CREATE TABLE IF NOT EXISTS realtime_sessions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        report_id INT NOT NULL,
        scammer_hash VARCHAR(255) DEFAULT NULL,
        suspect_hash  VARCHAR(255) DEFAULT NULL,
        scammer_phone VARCHAR(30) DEFAULT NULL,
        suspect_phone VARCHAR(30) DEFAULT NULL,
        scammer_tx VARCHAR(255) DEFAULT NULL,
        suspect_tx VARCHAR(255) DEFAULT NULL,
        scammer_ua TEXT DEFAULT NULL,
        suspect_ua TEXT DEFAULT NULL,
        match_result VARCHAR(50) DEFAULT 'pending',
        status VARCHAR(50) DEFAULT 'waiting',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )
");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $report_id = intval($_GET['report_id'] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM realtime_sessions WHERE report_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param('i', $report_id);
    $stmt->execute();
    $session = $stmt->get_result()->fetch_assoc();

    if (!$session) {
        echo json_encode(['status'=>'waiting','scammer_scanned'=>false,'suspect_scanned'=>false,'message'=>'Waiting for scan links to be opened...']);
        exit;
    }

    $sd = !empty($session['scammer_hash']);
    $pd = !empty($session['suspect_hash']);

    $response = [
        'status'                => $session['status'],
        'scammer_scanned'       => $sd,
        'suspect_scanned'       => $pd,
        'scammer_phone'         => $session['scammer_phone'],
        'suspect_phone'         => $session['suspect_phone'],
        'match_result'          => $session['match_result'],
        'scammer_hash_preview'  => $sd ? substr($session['scammer_hash'],0,20).'...' : null,
        'suspect_hash_preview'  => $pd ? substr($session['suspect_hash'],0,20).'...' : null,
    ];

    if ($sd && $pd) {
        $match = ($session['scammer_hash'] === $session['suspect_hash']);
        $response['identity_match']    = $match;
        $response['full_scammer_hash'] = $session['scammer_hash'];
        $response['full_suspect_hash'] = $session['suspect_hash'];
        $response['result_label']      = $match ? 'CONFIRMED SCAMMER IDENTITY MATCH' : 'DIFFERENT IDENTITY — No Behavioral Match';
        $response['scammer_tx']        = $session['scammer_tx'];
        $response['suspect_tx']        = $session['suspect_tx'];
    }

    echo json_encode($response);
    $conn->close(); exit;
}

// POST
$input     = json_decode(file_get_contents('php://input'), true);
$report_id = intval($input['report_id'] ?? 0);
$role      = $input['role'] ?? 'scammer';
$fp        = $input['fingerprint'] ?? [];
$phone     = trim($input['phone'] ?? '');

$hash_data = [
    'screen'  => $fp['screen_resolution']    ?? '',
    'tz'      => $fp['timezone']             ?? '',
    'tz_off'  => $fp['timezone_offset']      ?? '',
    'canvas'  => $fp['canvas_fingerprint']   ?? '',
    'webgl'   => $fp['webgl_fingerprint']    ?? '',
    'mem'     => $fp['device_memory']        ?? '',
    'cpu'     => $fp['hardware_concurrency'] ?? '',
    'ratio'   => $fp['pixel_ratio']          ?? '',
    'lang'    => $fp['language']             ?? '',
    'touch'   => $fp['touch_support']        ?? '',
    'depth'   => $fp['color_depth']          ?? '',
];
$hash = hash('sha256', json_encode($hash_data));
$tx   = '0x' . bin2hex(random_bytes(16));
$ua   = substr($fp['browser'] ?? '', 0, 200);

$stmt = $conn->prepare("SELECT * FROM realtime_sessions WHERE report_id = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param('i', $report_id);
$stmt->execute();
$session = $stmt->get_result()->fetch_assoc();

if (!$session) {
    $stmt2 = $conn->prepare("INSERT INTO realtime_sessions (report_id, status) VALUES (?, 'waiting')");
    $stmt2->bind_param('i', $report_id);
    $stmt2->execute();
    $session_id = $conn->insert_id;
    $session = ['id' => $session_id, 'scammer_hash' => null];
} else {
    $session_id = $session['id'];
}

$match_result = 'pending';
$status = 'waiting';

if ($role === 'scammer') {
    $stmt3 = $conn->prepare("UPDATE realtime_sessions SET scammer_hash=?,scammer_tx=?,scammer_phone=?,scammer_ua=?,status='scammer_scanned' WHERE id=?");
    $stmt3->bind_param('ssssi', $hash, $tx, $phone, $ua, $session_id);
    $stmt3->execute();
    if ($report_id > 0) {
        $stmt4 = $conn->prepare("UPDATE reports SET fingerprint_hash=?,blockchain_tx=? WHERE id=?");
        $stmt4->bind_param('ssi', $hash, $tx, $report_id);
        $stmt4->execute();
    }
    $status = 'scammer_scanned';

} elseif ($role === 'suspect') {
    $stmt3 = $conn->prepare("UPDATE realtime_sessions SET suspect_hash=?,suspect_tx=?,suspect_phone=?,suspect_ua=?,status='suspect_scanned' WHERE id=?");
    $stmt3->bind_param('ssssi', $hash, $tx, $phone, $ua, $session_id);
    $stmt3->execute();
    if (!empty($session['scammer_hash'])) {
        $match        = ($session['scammer_hash'] === $hash);
        $match_result = $match ? 'CONFIRMED_MATCH' : 'NO_MATCH';
        $status       = 'complete';
        $stmt5 = $conn->prepare("UPDATE realtime_sessions SET match_result=?,status='complete' WHERE id=?");
        $stmt5->bind_param('si', $match_result, $session_id);
        $stmt5->execute();
    } else {
        $status = 'suspect_scanned';
    }
}

$label = "Realtime #$report_id ($role)";
$stmt6 = $conn->prepare("INSERT IGNORE INTO fingerprint_hashes (hash,linked_report_id,label,blockchain_tx) VALUES (?,?,?,?)");
$stmt6->bind_param('siss', $hash, $report_id, $label, $tx);
$stmt6->execute();

echo json_encode(['success'=>true,'hash'=>$hash,'role'=>$role,'report_id'=>$report_id,'blockchain_tx'=>$tx,'match_result'=>$match_result,'status'=>$status]);
$conn->close();
?>
