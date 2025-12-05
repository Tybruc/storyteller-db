<?php
/* Storyteller Database Login CRUD Page - ../crud/login.php
      Page to handles user CRUD operations and validation.
      - handles user registration, login, password reset
      - redirects back to login.php with success or error messages
      - determines existing vs new user on login for first time experience
      
      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - Changed error handling to use exceptions for clarity.
      - Improved session management on login.
  */
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/db_config.php';
require __DIR__ . '/../includes/functions.php';

// Helper to redirect back to the login page and save params in session
function redirect_login($errors = [], $success = '', $active = '') {
    if ($success) {
    addSuccess($success);
    }
    if (!empty($errors)) {
      addError(is_array($errors) ? implode(', ', $errors) : $errors);
    }
    if ($active) {
      $_SESSION['login_active'] = $active;
    }
    header("Location: ../pages/login.php");
    exit;
}

// Get form data
$action     = $_POST['action'] ?? '';

$email      = trim($_POST['email'] ?? '');
$first      = trim($_POST['first'] ?? '');
$last       = trim($_POST['last'] ?? '');
$password   = $_POST['password'] ?? '';
$password2  = $_POST['password2'] ?? '';
$role       = trim($_POST['role'] ?? '');
$user_id    = (int)($_SESSION['user_id'] ?? 0);

try {
  // User Registration
  if ($action === 'register') {
      
      // Validate email
      if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $errors[] = 'You need to enter a valid e-mail address.';
      } else {
          $stmt = $conn->prepare("SELECT EMAIL FROM USERS WHERE EMAIL = ?");
          $stmt->bind_param('s', $email);
          $stmt->execute();
          if ($stmt->error) {
              $errors[] = 'Error verifying email: ' . $stmt->error;
          } else {
              $res = $stmt->get_result();
              if ($res && $res->num_rows > 0) {
                  $errors[] = 'Email is already registered!';
              }
          }
      }

      // Validate names
      if ($first === '' || $last === '') {
          $errors[] = 'You need to enter your first and last name.';
      }

      // Role
      if ($role === '') {
          $errors[] = 'You need to select a role.';
      }

      // Passwords
      if ($password === '') {
          $errors[] = 'You need to enter a password.';
      }
      if ($password2 === '') {
          $errors[] = 'You need to confirm your password.';
      }
      if ($password !== '' && $password2 !== '') {
          if (strlen($password) < 6) {
              $errors[] = 'The password is too short.';
          }
          if ($password !== $password2) {
              $errors[] = 'The passwords do not match.';
          }
      }
      if (!empty($errors)) {
          throw new Exception('Validation errors occurred.');
      }
      
      // Create user
      $password_hashed = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO USERS (EMAIL, FIRST_NAME, LAST_NAME, ROLE, PASSWORD) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('sssss', $email, $first, $last, $role, $password_hashed);
      $stmt->execute();
      if ($stmt->error) {
          throw new Exception('Error creating user: ' . $stmt->error);
          $active = 'register';
      }
      $success = 'Registration successful! Login to continue.';
      $active = 'login';
      redirect_login($errors, $success, $active);
  }

  // User Login
  if ($action === 'login') {
    if ($email === '' || $password === '') {
      $errors[] = 'Invalid email or password';
      throw new Exception('Verification failed');}

    $stmt = $conn->prepare("SELECT USER_ID AS user_id, EMAIL AS email, FIRST_NAME AS first_name, 
                LAST_NAME AS last_name, ROLE AS role, PASSWORD AS password FROM USERS WHERE EMAIL = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if ($stmt->error) {
      throw new Exception('Error during login: ' . $stmt->error);
    }

    $res = $stmt->get_result();
    if (!$res || $res->num_rows === 0) {
      $errors[] = 'Invalid email or password';
      throw new Exception('Verification failed');
    }

    $user = $res->fetch_assoc();
    if (!password_verify($password, $user['password'])) {
      $errors[] = 'Invalid email or password';
      throw new Exception('Verification failed');
    }

    // Successful login, set session variables
    $_SESSION['user']           = $user['first_name'];
    $_SESSION['name']           = $user['first_name'] . ' ' . $user['last_name'];
    $_SESSION['email']          = $user['email'];
    $_SESSION['user_id']        = (int)$user['user_id'];
    $_SESSION['last_activity']  = time();
    $_SESSION['role']           = $user['role'];
    

    // Check if user has any stories in the story table.
    // Assigning user status to session for first time user experience
    $loggedUserId = (int)$_SESSION['user_id'];
    $stmt2 = $conn->prepare("SELECT COUNT(story_id) AS count FROM STORIES WHERE USER_ID = ?");
    $stmt2->bind_param('i', $loggedUserId);
    $stmt2->execute();
    if ($stmt2->error) {
      throw new Exception('Error checking user stories: ' . $stmt2->error);
    } 

    $res2 = $stmt2->get_result();
    $row = $res2 ? $res2->fetch_assoc() : null;
    $storyCount = $row ? (int)$row['count'] : 0;
    if ($storyCount > 0) {
      $_SESSION['user_status'] = 'existing';
      // Redirect to dashboard for existing users
      redirect_page('../pages/dashboard.php', '', '', []);  
    } else {
      $_SESSION['user_status'] = 'new';

      // Redirect to story page for new users
      redirect_page('../pages/new_story.php', '', '', []);
    }
  }

  // Forgot Password - Check Registration
  if ($action === 'password_forgot') {

    if ($email === '') {
      $errors[] = 'Please provide your email address.';
      throw new Exception('Validation failed during password forgot.');
    }

    $stmt = $conn->prepare('SELECT USER_ID FROM USERS WHERE EMAIL = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if ($stmt->error) {
      throw new Exception('Error during password forgot: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    if (!$result || $result->num_rows === 0) {
      $errors[] = 'Email address not registered.';
      throw new Exception('Validation failed during password forgot.');
    }

    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = (int)$user['USER_ID'];
    redirect_login([], '', 'password_reset');
  }

  // Password Reset - Verify & Update Password
  if ($action === 'password_reset') {
    
    if ($user_id <= 0) {
      $errors[] = 'Session expired or invalid user. Please start the reset process again.';
    }
    if (strlen($password) < 6) {
      $errors[] = 'The password is too short.';
    }
    if ($password !== $password2) {
      $errors[] = 'The passwords do not match.';
    }
    if (!empty($errors)) {
      throw new Exception('Validation failed during password reset.');
    }

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('UPDATE USERS SET PASSWORD = ? WHERE USER_ID = ?');
    $stmt->bind_param('si', $password_hashed, $user_id);
    $stmt->execute();
    if ($stmt->error) {
      throw new Exception('Error during password reset: ' . $stmt->error);
    }

    $success = 'Password updated! Login to Continue.';
    redirect_login([], $success, 'login');
  }
  // If no action matched, throw error
  if ($action === null || $action === '') {
      $active = 'login';
      $errors[] = 'No action specified.';
      redirect_login($errors, '', $active);
  }
} catch (Exception $e) {
  // Default msg handling if no valid response and no errors set
  $errors = ["Error: " . $e->getMessage()];
  $active = $action !== '' ? $action : 'login';
  redirect_login($errors, '', $active);
}

?>