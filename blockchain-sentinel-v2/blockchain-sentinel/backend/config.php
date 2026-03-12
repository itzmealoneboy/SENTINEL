<?php
// backend/config.php - Database Connection

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blockchain_sentinel');

function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

// Session helper
function isAdminLoggedIn() {
    session_start();
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function isPublicLoggedIn() {
    session_start();
    return isset($_SESSION['public_logged_in']) && $_SESSION['public_logged_in'] === true;
}

// SHA256 helper
function generateFingerprint($data) {
    return hash('sha256', json_encode($data));
}

// CORS Headers for API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
?>
