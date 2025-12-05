<?php
// Load session and functions BEFORE any HTML output
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/data_functions.php';

// Clear any existing messages
if (isset($_GET['clear_msgs'])) {
    unset($_SESSION['success_message']);
    unset($_SESSION['error_message']);
}

// Update last activity timestamp and check session validity
$last_activity = lastActivityUpdate($_SESSION['last_activity']);

// Ensure user is logged in
$user_id = checkUserID(); 
?>

