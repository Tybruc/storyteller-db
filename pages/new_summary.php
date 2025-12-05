
  
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
       
  */
      
// Initialize session and functions
require_once __DIR__ . '/../includes/init.php';

// Verify if story is selected
$story_id = isset($_SESSION['story_id']) ? (int)$_SESSION['story_id'] : 
            redirect_page('../pages/new_story.php', '', ['error_message' => 'Please create a new entry before viewing summary.']);
$title = $_SESSION['story_title'] ?? 'No Title Selected';

if (isset($_GET['update']) && $_GET['update'] == 1) {
  $update = true;
  $timeline_id = (int)($_GET['timeline_id'] ?? 0);
} else {
  $update = false;
  $timeline_id = (int)($_SESSION['timeline_id'] ?? 0);
}

// Get timeline_id from session or URL parameter
if (empty($timeline_id)) {
  redirect_page('../pages/new_entry.php', '', ['error_message' => 'No timeline entry selected.']);
}
$_SESSION['timeline_id'] = $timeline_id;

// Fetch timeline entry data
$rows = getTimelineEntryData($conn, $timeline_id);
if (empty($rows)) {
  redirect_page('../pages/new_entry.php', '', ['error_message' => 'Timeline entry not found.']);
}
$entry_title = $rows['title'] ?? '';
$summary = $rows['summary'] ?? '';
$entry_type = $rows['entry_type'] ?? '';
$sequence_no = $rows['sequence_no'] ?? 0;
$location_id = (int)($rows['location_id'] ?? 0);
$location = getLocName($conn, $location_id);
$character_list = getEntryCharNames($conn, $timeline_id);

// Check user status
$new_user = checkUserStatus();

// Initialize HTML header and modals
require_once __DIR__ . '/../includes/init_html.php';
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
  <title>New / Update Timeline Entry</title>
</head>
<body>  
  <main role="main">
    <div class="card" style="max-width: 900px; margin-left: auto; margin-right: auto; margin-top: 24px;">
      <?= showMessages(); ?> <!-- Display any success or error messages -->
      <div class="<?= $update ? '' : 'hidden' ?> ">
        <h2>Updating <?= htmlspecialchars($title) ?> for <?= htmlspecialchars($_SESSION['story_title'] ?? 'No Title Selected') ?></h2>
      </div>
      <div class="<?= !$update ? '' : 'hidden' ?> ">
        <h2>Summary for <?= htmlspecialchars($title) ?> in <?= htmlspecialchars($_SESSION['story_title'] ?? 'No Title Selected') ?></h2>
      </div>
      <p>Review the details of your timeline entry below. You can edit any section before saving and submitting.</p>
      <div class=two-column-grid>
        <section class="grid-item header card"> 
          <h2><?= htmlspecialchars($entry_title) ?> Summary</h2>
          <p>Title: <?= htmlspecialchars($entry_title); ?></p>
          <p>Type: <?= htmlspecialchars($entry_type); ?></p>
          <p>Summary: <?= htmlspecialchars($summary); ?></p>
          <button class="btn" onclick="window.location.href='../pages/new_entry.php?update=1'">Edit</button>

          <p>Location: <?= htmlspecialchars($location); ?></p>
          <button class="btn" onclick="window.location.href='../pages/new_locations.php?update=1'">Edit</button>

          <p>Characters: <?= htmlspecialchars($character_list); ?></p>
          <button class="btn" onclick="window.location.href='../pages/new_characters.php?update=1'">Edit</button>

          <div><p>Hit "Save & Exit" finish this entry and go to the Timeline Page. </p></div>
          <div class="<?= !$update ? '' : 'hidden' ?> "><p>Or you can "Exit to the Dashboard" to explore the other tools available.</p></div>
          <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:12px;">
              <!-- Submit using hidden action -->
              <button class="btn <?= !$update ? '' : 'hidden' ?> " onclick="window.location.href='../pages/dashboard.php';">Exit to Dashboard</button>
              <button class="btn primary" onclick="window.location.href='../pages/timeline.php'">Save & Exit</button>
          </div>
        </section>
      </div>
    </div>
   <?php require __DIR__ . '/../includes/footer.php'; ?>
  </main>
</body>
</html>