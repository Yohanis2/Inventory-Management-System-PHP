<?php
// Check if a session is already active before modifying session parameters
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '', // Set to your domain if needed
        'secure' => true, // Set true if using HTTPS
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['Emp_id'])) {
    $_SESSION['message'] = "You Are Not Logged In! Please Login to access this page";
    header("Location: login.php");
    exit();
}

// User is logged in
$username = $_SESSION['Emp_id'];
?>
