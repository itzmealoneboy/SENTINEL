<?php
// backend/login.php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$type = $input['type'] ?? '';

if ($type === 'public') {
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if ($email === 'public123' && $password === 'pass123') {
        $_SESSION['public_logged_in'] = true;
        echo json_encode(['success' => true, 'message' => 'ACCESS GRANTED', 'type' => 'public']);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'ACCESS DENIED: Invalid credentials']);
    }

} elseif ($type === 'admin') {
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        echo json_encode(['success' => true, 'message' => 'ADMIN ACCESS GRANTED', 'type' => 'admin']);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'ACCESS DENIED: Invalid credentials']);
    }

} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request type']);
}
?>
