<!-- Storyteller Database New Entry Story Management Page - new_story.php
      Page to display and manage stories for the selected user.
      - checks session timeout and user authentication
      - uses guidetext to help new users set up their first story
      - uses conditional modals to warn about unsaved changes
      - integrates with CRUD endpoint to save story selections
      - user can choose to continue setup or go to dashboard after creating story

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - added welcome text for new users only
       
  -->
  
<?php
/* Storyteller Database New Entry Story Management Page - new_story.php
      Page to display and manage stories for the selected user.
      - checks session timeout and user authentication
      - uses guidetext to help new users set up their first story
      - uses conditional modals to warn about unsaved changes
      - integrates with CRUD endpoint to save story selections
      - user can choose to continue setup or go to dashboard after creating story

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - added welcome text for new users only
       
  */

// Initialize session and functions
require __DIR__ . '/../includes/init.php';

// Initialize HTML header and modals
require __DIR__ . '/../includes/init_html.php';

// Check user status
$new_user = checkUserStatus();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/modal.css">
  <link rel="stylesheet" href="../assets/css/page.css">
  <style>
    html,body {margin: 0; padding: 0; height: 100%;}
    body {background: url('../assets/images/clouds_STDB.png') no-repeat center center fixed; background-size: cover;}
  </style>
  <title>New User's Story</title>
</head>
<body>  
  <main role="main">
    <div class="card" style="max-width: 900px; margin-left: auto; margin-right: auto; margin-top: 24px;">
      <?= showMessages(); ?> <!-- Display any success or error messages -->
      <h2 class="<?= $new_user ? '' : 'hidden' ?>">Welcome to your new Storyteller Database!</h2>
      <h2 class="<?= !$new_user ? '' : 'hidden' ?>">New Story</h2>
      <p>Every writing project starts with anchoring the core idea. This page invites you to define the 
         essential framework of your story so future development feels intentional and grounded.</p>
      <h2>Let's get started ....</h2>
      <p>First, please provide the basic details of your story below.</p>
      <div class=two-column-grid>
        <section class="grid-item header card">  
          <form method="post" action="../crud/story.php">
            <!-- Hidden field to pass the story ID -->
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id); ?>">
            <!-- Form fields for character details -->
            <div><p style="text-align: left;">Title: <input type="text" name="title" class="input" required/></p></div>
            <div><p style="text-align: left;">Genre: <input type="text" name="genre" class="input" required/></p></div>
            <div><p style="text-align: left;">Synopsis:</p>
                <textarea name="synopsis" class="resizable-input" rows="2" required></textarea>
            </div>
            <div><p>To add an entry to your stories timeline click "Continue".</p></div>
            <div class="<?= !$new_user ? '' : 'hidden' ?>"><p>Click "Save" to save this story and return to your dashboard.</p></div>
            <div class="<?= !$new_user ? '' : 'hidden' ?>"><p>Or click "Cancel" to exit without saving.</p></div>
            <div class="<?= $new_user ? '' : 'hidden' ?>"><p>Click "Exit to Dashboard" to save this story and go to your dashboard.</p></div>
            <div class="modal-actions-right">
                <!-- Submit using hidden action -->
                <button type="button" class="btn <?= !$new_user ? '' : 'hidden' ?>" onclick="window.location.href='../pages/new_summary.php';">Cancel</button>
                <button type="submit" class="btn primary <?= !$new_user ? '' : 'hidden' ?>" name="redirect_page" value="../pages/new_summary.php">Save</button>
                <button type="button" class="btn <?= $new_user ? '' : 'hidden' ?>" onclick="window.location.href='../pages/dashboard.php';">Exit to Dashboard</button>
                <button type="submit" class="btn primary" name="redirect_page" value="../pages/new_entry.php">Continue</button>
            </div>
          </form>
        </section>
      </div>
    </div>
    <?php require __DIR__ . '/../includes/footer.php'; ?>
  </main>
  <script src="../assets/js/modal.js"></script>
</body>
</html>