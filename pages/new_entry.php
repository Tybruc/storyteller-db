
  
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
            redirect_page('../pages/new_story.php', '', ['error_message' => 'Please create a new story before continuing.']); 
$title = $_SESSION['story_title'] ?? 'No Title Selected';

// Get timeline_id from session and timeline entry data 
if($timeline_id = (int)($_SESSION['timeline_id'] ?? 0)) { 
  $rows = getTimelineEntryData($conn, $timeline_id);
  $entry_title = $rows['title'] ?? '';
  $summary = $rows['summary'] ?? '';
  $entry_type = $rows['entry_type'] ?? '';
  $sequence_no = $rows['sequence_no'] ?? 0;
  $location_id = $rows['location_id'] ?? 0;
}
// Check if updating existing entry
$updated = isset($_GET['update']) && $_GET['update'] == 1 ? true : false;

// Check user status
$new_user = checkUserStatus();

// Initialize session and functions
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
        <p>Make any changes below then click "Save" or "Cancel" to return to the <?= $update ? 'Entry Summary' : 'Timeline' ?>.</p>
      </div>
      <div class="<?= !$update ? '' : 'hidden' ?> ">
        <h2>Creating a Timeline Entry</h2>
        <h3>Now lets create your first timeline event for <?= htmlspecialchars($_SESSION['story_title'] ?? 'No Title Selected') ?></h3>
        <p>Stories are made up of a series of events and moments that shape the narrative. Some events are pivotal, while others provide depth and context.
          In this section, you'll create timeline entries to map out these significant occurrences within your story. 
        </p>
        <h2>Let's get started ....</h2>
        <p>First, please provide the basic details of the event you wish to add to the timeline below.</p>
      </div>
      <div class="two-column-grid">
        <!-- Form to Update Timeline Entry -->
        <section class="grid-item header card <?= $update ? '' : 'hidden' ?>">  
          <form method="post" action="../crud/timeline.php">
            <!-- Hidden field to pass the story ID -->
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="timeline_id" value="<?= htmlspecialchars($timeline_id)?>">
            <input type="hidden" name="story_id" value="<?= htmlspecialchars($story_id); ?>">
            <input type="hidden" name="location_id" value="<?= htmlspecialchars($location_id); ?>">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id); ?>">
            <input type="hidden" name="sequence_no" value="<?= htmlspecialchars($sequence_no); ?>">
            <!-- Form fields for character details -->
            <div>
              <p style="text-align: left;">Title: <input type="text" name="title" class="input" required value="<?= htmlspecialchars($entry_title)?>"/></p>
              <div><p style="text-align: left;">What type of entry is this?</p></div>
              <p><em>("Plot Points" are key moments that drive the story forward, while "Events" provide context and depth.)</em></p>
              <select style="text-align: left;" name="entry_type" required>
                <option value="" style="color:#9ca3af" <?= empty($entry_type) ? 'selected' : '' ?>>Entry Type</option>
                <option value="plot" <?=  htmlspecialchars($entry_type) === 'plot' ? 'selected' : '' ?>>Plot Point</option>
                <option value="event" <?=  htmlspecialchars($entry_type) === 'event' ? 'selected' : '' ?>>Event</option>
              </select>      
              <p style="text-align: left;">Summary:</p>
              <textarea name="summary" class="resizable-input" rows="2" required><?= htmlspecialchars($summary) ?></textarea>
            </div> 
            <div class="modal-actions-right" style="margin-top:12px;">
              <button class="btn" onclick="window.location.href='../pages/new_summary.php?update=1&timeline_id=<?= htmlspecialchars($timeline_id) ?>';">Cancel</button>
              <button type="submit" class="btn primary" 
                      name="redirect_page" value="../pages/new_summary.php?update=1&timeline_id=<?= htmlspecialchars($timeline_id) ?>">Save</button>
            </div> 
          </form>
        </section>
        <!-- Form to Create New Timeline Entry -->
        <section class="grid-item header card <?= !$update ? '' : 'hidden' ?>">  
          <form method="post" action="../crud/timeline.php">
            <!-- Hidden field to pass the story ID -->
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="story_id" value="<?= htmlspecialchars($story_id); ?>">
            <input type="hidden" name="location_id" value="0">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id); ?>">
            <input type="hidden" name="sequence_no" value="0">
            <!-- Form fields for character details -->
            <div>
              <p style="text-align: left;">Title: <input type="text" name="title" class="input" required /></p>
              <div><p style="text-align: left;">What type of entry is this?</p></div>
              <p><em>("Plot Points" are key moments that drive the story forward, while "Events" provide context and depth.)</em></p>
              <select style="text-align: left;" name="entry_type" required>
                <option value="" style="color:#9ca3af">Entry Type</option>
                <option value="plot">Plot Point</option>
                <option value="event">Event</option>
              </select>      
              <p style="text-align: left;">Summary:</p>
              <textarea name="summary" class="resizable-input" rows="2" required></textarea>
              <div><p>Next we will add a location to your entry. Click continue to proceed. </p></div>
              <div><p>Or you can "Exit to the Dashboard" to leave with out saving,  to explore the other tools available.</p></div>
              <div class = "modal-actions-right" style="margin-top:12px;">
                <button type="button" class="btn" onclick="window.location.href='../pages/dashboard.php';">Exit to Dashboard</button>
                <button type="button" class="btn <?= !$new_user ? '' : 'hidden' ?>" onclick="window.location.href='../pages/timeline.php';">Cancel</button>
                <button type="submit" class="btn primary" name="redirect_page" value="../pages/new_locations.php">Continue</button>
              </div>
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