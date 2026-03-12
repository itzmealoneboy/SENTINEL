<?php
// backend/collect_fingerprint.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$report_id = intval($input['report_id'] ?? 0);
$role = $input['role'] ?? 'scammer'; // 'scammer' or 'suspect'
$fingerprint_data = $input['fingerprint'] ?? [];

// Generate SHA256 hash from behavioral data
$hash_input = [
    'typing_rhythm' => $fingerprint_data['typing_rhythm'] ?? '',
    'screen_resolution' => $fingerprint_data['screen_resolution'] ?? '',
    'browser' => $fingerprint_data['browser'] ?? '',
    'os' => $fingerprint_data['os'] ?? '',
    'font_size' => $fingerprint_data['font_size'] ?? '',
    'canvas_fingerprint' => $fingerprint_data['canvas_fingerprint'] ?? '',
    'webgl_fingerprint' => $fingerprint_data['webgl_fingerprint'] ?? '',
    'timezone' => $fingerprint_data['timezone'] ?? '',
    'device_memory' => $fingerprint_data['device_memory'] ?? ''
];

$hash = hash('sha256', json_encode($hash_input));

// Store fingerprint
$conn = getDBConnection();

if ($role === 'scammer' && $report_id > 0) {
    $stmt = $conn->prepare("UPDATE reports SET fingerprint_hash = ? WHERE id = ?");
    $stmt->bind_param('si', $hash, $report_id);
    $stmt->execute();

    // Store in fingerprint_hashes
    $label = 'Report #' . $report_id . ' - Scammer';
    $tx = '0x' . bin2hex(random_bytes(16));
    $stmt2 = $conn->prepare("INSERT IGNORE INTO fingerprint_hashes (hash, linked_report_id, label, blockchain_tx) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param('siss', $hash, $report_id, $label, $tx);
    $stmt2->execute();
}

echo json_encode(['success' => true, 'hash_generated' => true]);
$conn->close();
?>
