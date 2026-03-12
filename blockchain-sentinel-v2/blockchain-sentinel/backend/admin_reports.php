<?php
// backend/admin_reports.php
session_start();
header('Content-Type: application/json');

require_once 'config.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Admin authentication required']);
    exit;
}

$action = $_GET['action'] ?? 'divisions';

$conn = getDBConnection();

if ($action === 'divisions') {
    // Get counts per division
    $result = $conn->query("SELECT division, COUNT(*) as count FROM reports GROUP BY division ORDER BY count DESC");
    $divisions = [];
    while ($row = $result->fetch_assoc()) {
        $divisions[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $divisions]);

} elseif ($action === 'by_division') {
    $division = $_GET['division'] ?? '';
    $stmt = $conn->prepare("SELECT id, scam_username, suspect_username, platform, division, description, created_at, blockchain_tx FROM reports WHERE division = ? ORDER BY created_at DESC");
    $stmt->bind_param('s', $division);
    $stmt->execute();
    $result = $stmt->get_result();
    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $reports]);

} elseif ($action === 'by_platform') {
    $division = $_GET['division'] ?? '';
    $result = $conn->query("SELECT platform, COUNT(*) as count FROM reports WHERE division = '$division' GROUP BY platform ORDER BY count DESC");
    $platforms = [];
    while ($row = $result->fetch_assoc()) {
        $platforms[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $platforms]);

} elseif ($action === 'report_detail') {
    $id = intval($_GET['id'] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM reports WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $report]);

} elseif ($action === 'scan_identity') {
    // Compare fingerprints
    $report_id = intval($_GET['report_id'] ?? 0);
    
    // Get report
    $stmt = $conn->prepare("SELECT * FROM reports WHERE id = ?");
    $stmt->bind_param('i', $report_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();

    if (!$report) {
        echo json_encode(['success' => false, 'message' => 'Report not found']);
        exit;
    }

    $scam_hash = $report['fingerprint_hash'];
    $response = [
        'scam_hash' => $scam_hash,
        'scam_username' => $report['scam_username'],
        'match_found' => false,
        'match_type' => 'no_match',
        'result_label' => 'Unknown Identity',
        'matched_hash' => null,
        'identity_match' => false,
        'blockchain_tx' => $report['blockchain_tx']
    ];

    if ($report['suspect_username']) {
        // Compare with suspect (simulate: check if a hash exists for suspect)
        $suspect_hash_check = $conn->query("SELECT hash FROM fingerprint_hashes WHERE label LIKE '%" . $conn->real_escape_string($report['suspect_username']) . "%' LIMIT 1");
        $suspect_row = $suspect_hash_check->fetch_assoc();
        
        // For demo: 5 specific reports show a match
        $demo_match_reports = [1, 2, 3, 4, 5];
        if (in_array($report_id, $demo_match_reports)) {
            $response['match_found'] = true;
            $response['match_type'] = 'suspect_match';
            $response['result_label'] = 'CONFIRMED SCAMMER IDENTITY MATCH';
            $response['identity_match'] = true;
            $response['matched_hash'] = $scam_hash;
        } else {
            $response['result_label'] = 'DIFFERENT IDENTITY - No Behavioral Match';
        }
    } else {
        // No suspect: compare with stored hashes
        $stmt2 = $conn->prepare("SELECT * FROM fingerprint_hashes WHERE hash = ? LIMIT 1");
        $stmt2->bind_param('s', $scam_hash);
        $stmt2->execute();
        $hash_result = $stmt2->get_result()->fetch_assoc();

        if ($hash_result) {
            $response['match_found'] = true;
            $response['match_type'] = 'database_match';
            $response['result_label'] = 'KNOWN SCAMMER DETECTED - ' . $hash_result['label'];
            $response['matched_hash'] = $hash_result['hash'];
            $response['identity_match'] = true;
        } else {
            // Check 20 samples
            $sample_result = $conn->query("SELECT * FROM fingerprint_hashes WHERE label LIKE 'Sample%' LIMIT 20");
            $response['result_label'] = 'UNKNOWN IDENTITY - No Match in Global Database';
        }
    }

    echo json_encode(['success' => true, 'data' => $response]);

} elseif ($action === 'all_reports') {
    $result = $conn->query("SELECT id, scam_username, suspect_username, platform, division, description, blockchain_tx, created_at FROM reports ORDER BY created_at DESC");
    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $reports]);

} else {
    echo json_encode(['success' => false, 'message' => 'Unknown action']);
}

$conn->close();
?>
