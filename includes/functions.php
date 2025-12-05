<?php 
// Utility functions for alerts and session checks

// Save success and error messages in session
function addSuccess(string $msg) {
  $_SESSION['success_message'] = $msg;
}
function addError(string $msg) {
  $_SESSION['error_message'] = $msg;
}

// Display success and error messages from session
function showMessages() {
  // Display success message if exists
  if (!empty($_SESSION['success_message'])) {
    echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
  }
  // Display error message if exists
  if (!empty($_SESSION['error_message'])) {
    echo '<div class="error-message">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
  }
}

// Helper to redirect to a specified page with success or error messages
function redirect_page(string $redirect_page, string $success = '', $errors = []): void {
  if ($success) {
    addSuccess($success);
  }
  if (!empty($errors)) {
    addError(is_array($errors) ? implode(', ', $errors) : $errors);
    error_log($_SESSION['error_message'], 3, __DIR__ . '/../logs/error_log.txt');
  }
  header("Location: " . $redirect_page );
  exit;
}

// Check which form is active for login/registration
function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : 'inactive';
}

// Check if story is selected in session
function checkStoryID() {
  if (!isset($_SESSION['story_id'])) {
    addError('No story selected. Please select a story to continue.');
    header ("Location: ../pages/stories.php");
    exit;
  } else {
    // Return the story ID as an integer
    return (int)$_SESSION['story_id'];
  }
}

// Check if user is logged in
function checkUserID() {
  if (empty($_SESSION['user_id'])) {
    addError('User not logged in. Please log in to continue.');
    header ("Location: ../pages/login.php");
  } else {
    // Return the user ID as an integer
    return (int)$_SESSION['user_id'];
  }
}

// Check if entry ID is provided
function checkEntryUpdate($entry_id = null) {
  if (empty($entry_id)) {
    return false;
  } else {
    // Return the entry ID as an integer
    return (int)$entry_id;
  }
}
// Check user status in session
function checkUserStatus() {
  if (empty($_SESSION['user_status'])) {
    addError('User status not found. Please log in to continue.');
    redirect_page('../pages/login.php');
  } elseif ($_SESSION['user_status'] === 'existing') {
    return false;
  } else {
    return true;
  }
}

// Check for session timeout
function lastActivityUpdate($lastActivity) {
  $session_timeout = 30 * 60; // 30 minutes
  if (isset($lastActivity) && (time() - $lastActivity > $session_timeout)) {
    // Remove all session variables
    session_unset();
    session_destroy();
    // Alert user about session expiration and redirect to login page
    addError('Session expired due to inactivity. Please log in again.');
    header ("Location: ../pages/login.php");
    exit;
  }
  // Update last activity time stamp
  return time();
}
?>