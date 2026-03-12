<?php
// backend/submit_report.php
session_start();
header('Content-Type: application/json');

require_once 'config.php';

if (!isset($_SESSION['public_logged_in']) || !$_SESSION['public_logged_in']) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$scam_username = trim($input['scam_username'] ?? '');
$scam_phone    = trim($input['scam_phone'] ?? '') ?: null;
$suspect_username = trim($input['suspect_username'] ?? '') ?: null;
$suspect_phone = trim($input['suspect_phone'] ?? '') ?: null;
$platform = trim($input['platform'] ?? '');
$description = trim($input['description'] ?? '');

// Use phone as username fallback if username empty
if (!$scam_username && $scam_phone) $scam_username = $scam_phone;

// Auto-assign division based on keywords + platform
function assignDivision($description, $platform) {
    $desc_lower = strtolower($description);
    if (preg_match('/crypto|bitcoin|eth|nft|token|wallet|blockchain|defi|coin/i', $desc_lower)) return 'Crypto Scam';
    if (preg_match('/invest|stock|forex|trading|return|profit|fund|portfolio/i', $desc_lower)) return 'Investment Scam';
    if (preg_match('/identity|passport|kyc|document|personal|id|verify/i', $desc_lower)) return 'Identity Fraud';
    if (preg_match('/digital|online|software|app|metaverse|virtual|nft|influencer/i', $desc_lower)) return 'Digital Scam';
    return 'Money Scam';
}

$division = assignDivision($description, $platform);

// Generate SHA256 fingerprint from report data
$fingerprintData = [
    'scam_username' => $scam_username,
    'platform' => $platform,
    'description' => $description,
    'timestamp' => time()
];
$fingerprint_hash = hash('sha256', json_encode($fingerprintData));

// Simulate blockchain transaction hash
$blockchain_tx = '0x' . bin2hex(random_bytes(16));

// Insert to database
$conn = getDBConnection();
$stmt = $conn->prepare(
    "INSERT INTO reports (scam_username, suspect_username, platform, division, description, fingerprint_hash, blockchain_tx) 
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param('sssssss', $scam_username, $suspect_username, $platform, $division, $description, $fingerprint_hash, $blockchain_tx);

if ($stmt->execute()) {
    $report_id = $conn->insert_id;

    // Store fingerprint in blockchain hashes table
    $label = 'Report #' . $report_id . ' - ' . $scam_username;
    $stmt2 = $conn->prepare("INSERT INTO fingerprint_hashes (hash, linked_report_id, label, blockchain_tx) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param('siss', $fingerprint_hash, $report_id, $label, $blockchain_tx);
    $stmt2->execute();

    // Send to Node.js blockchain API (non-blocking simulation)
    // In production: call your Hardhat/blockchain API here
    // $ch = curl_init('http://localhost:3001/store-hash');
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['hash' => $fingerprint_hash, 'reportId' => $report_id]));
    // curl_exec($ch);

    echo json_encode([
        'success' => true,
        'report_id' => $report_id,
        'message' => 'THREAT REPORT SUCCESSFULLY STORED',
        // Do NOT expose hash to public user
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$conn->close();
?>
