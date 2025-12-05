<?php
/* Storyteller Database Login Page - login.php
      Page to handle user login and registration.
      - checks session timeout and user authentication
      - displays login and registration forms with validation
      - includes alerts for errors and success messages

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - Improved form switching logic for better user experience.
      - Enhanced error and success message display for clarity.
      - Added session clearing on logout.
  */
require_once __DIR__ . '/../includes/session.php';
include __DIR__ . '/../includes/functions.php';
if (isset($_SESSION['user_id']) && isset($_GET['clear_id'])) {
    session_unset();
    session_destroy();
}

$activeForm = $_SESSION['login_active'] ?? 'login'; // Default to login form
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/login.css">
  <style>
    /* Background style to compensate for browser*/
    html,body {margin: 0; padding: 0; height: 100%;}
    body {background: url('../assets/images/clouds_STDB.png') no-repeat center center fixed; background-size: cover;}
  </style>
  <title>Storyteller Database Login</title>
</head>
<body>
    <header>
    <a href="../index.php" style="text-decoration:none; color:inherit;">
      <h1>Storyteller Database</h1>
    </a>
    <div class="headerNav">
      <button class="btn" style="text-decoration:none;" onclick="window.location.href='../pages/login.php'">Log In</button>
    </div>
  </header>
  <div class="header-spacer" aria-hidden="true"></div>
 <main>
  <div class="container">
    <!-- Registration Form -->
    <?php $errors_html = !empty($errors) ? implode('<br>', array_map('htmlspecialchars', $errors)) : ''; ?>
    <div class="form-box <?= isActiveForm('register',$activeForm); ?>" id="register-form">
      <form method="post" action="../crud/login.php">
          <h2>Register</h2>
          <?= ($activeForm === 'register') ? showMessages() : '' ; ?>
          <input type="hidden" name="action" value="register"/> 
          <input type="text" name="first" placeholder="First Name" required/>
          <input type="text" name="last" placeholder="Last Name" required/>
          <input type="email" name="email" placeholder="Email" required />
          <select name="role" required>
              <option value="" style="color:#9ca3af">Select Role</option>
              <option value="author" style="color:#111827">Author</option>
              <option value="editor" style="color:#111827">Editor</option>
              <option value="admin" style="color:#111827">Admin</option>
          </select>
          <input type="password" name="password" placeholder="New Password" required/> 
          <input type="password" name="password2" placeholder="Confirm Password" required/>
          <p><em>(Passwords are case-sensitive and must be at least 6 characters long)</em></p>
          <!--Submit Form-->
          <button type="submit" name="register">Register</button>
          <!--Switch to login form-->
          <p>Already registered? <a href="#" onclick="showForm('login-form') ">Login</a></p> 
      </form>
    </div>
    <!-- Login Form -->
    <div class="form-box <?= isActiveForm('login',$activeForm); ?>" id="login-form">
      <form method="post" action="../crud/login.php"> 
        <h2>Login</h2>
        <?= ($activeForm === 'login') ? showMessages() : '' ; ?>
        <input type="hidden" name="action" value="login"/> 
        <input type="text" name="email" placeholder="Email" required/>
        <input type="password" name="password" placeholder="Password" required/> 
        <p><em>(Passwords are case-sensitive and must be at least 6 characters long)</em></p>
        <!--Submit Form (success continue to story.php, error show above-->
        <button type="submit" name="login">Login</button>
        <!--Switch to Registration form-->
        <p>Don't have an account? <a href="#" onclick="showForm('register-form')">Register</a></p>
        <!--Switche to Forgot Password-->
        <p>Forgot Password? <a href="#" onclick="showForm('forgot-form')">Reset</a></p>
      </form> 
    </div>
    <!-- Forgot Password Form-->
    <div class="form-box <?= isActiveForm('password_forgot',$activeForm); ?>" id="forgot-form">   
      <form method="post" action="../crud/login.php<?php echo SID; ?>"> 
        <h2>Forgot Password</h2>
        <?= ($activeForm === 'password_forgot') ? showMessages() : '' ; ?>
        <p>Enter your email below to verify your registration.</p>
        <input type="hidden" name="action" value="password_forgot"/> 
        <input type="text" name="email" placeholder="Email" required/>
        <!--Submit Form (success forward to login, error show above)-->
        <button type="submit" name="password_forgot">Submit</button> 
        <!--Switch to Login Form-->
        <p><a href="#" onclick="showForm('login-form')">Return to Login</a></p>
        <!--Switch to Registration form-->
        <p>Don't have an account?<a href="#" onclick="showForm('register-form')">Register</a></p> 
      </form> 
    </div>

    <div class="form-box <?= isActiveForm('password_reset',$activeForm); ?>" id="reset-form">
      <form method="post" action="../crud/login.php<?php echo SID; ?>"> 
        <h2>Forgot Password</h2>
        <?= ($activeForm === 'password_reset') ? showMessages() : '' ; ?>
        <input type="hidden" name="action" value="password_reset"/> 
        <input type="password" name="password" placeholder="New Password" required/>
        <input type="password" name="password2" placeholder="Confirm Password" required/>
        <p><em>(Passwords are case-sensitive and must be at least 6 characters long)</em></p>
        <!--Submit Form (success back to login, error show above)-->
        <button type="submit" name="password_reset">Submit</button> 
        <!--Switch to Login Form-->
        <p><a href="#" onclick="showForm('login-form')">Return to Login</a></p>
        <!--Switch to Registration form-->
        <p>Don't have an account?<a href="#" onclick="showForm('register-form')">Register</a></p> 
      </form> 
    </div>
  </div>
  <?php require __DIR__ . '/../includes/footer.php'; ?>
</main>
  
  <script src="../assets/js/script.js"></script>
</body>
</html>