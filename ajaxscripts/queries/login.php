<?php
session_start();
header('Content-Type: application/json');
require_once '../../config.php';

// Check CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token.']);
    exit;
}

// Validate required fields
if (empty($_POST['username']) || empty($_POST['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];

$stmt = $mysqli->prepare("SELECT `uId`, `username`, `password`, `permission`, `dob`, `uStatus`, `role`, `address`, `phoneNumber`, `fullName`, `emailAddress` FROM `users` WHERE `username` = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Compare the entered password with the MD5 hashed password stored in the database
    if (md5($password) === $user['password']) {
        if ($user['uStatus'] === 1) {
            // Set session values
            $_SESSION['uId'] = $user['uId'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullName'] = $user['fullName'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['permissions'] = $user['permission'];

            // Regenerate CSRF token
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            echo json_encode([
                'success' => true,
                'message' => 'Login successful.',
                'redirect' => '/'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Your account is inactive.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
}

$stmt->close();
