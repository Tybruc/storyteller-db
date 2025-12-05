
<?php
/* Storyteller Database Dashboard Page - dashboard.php
    TODO - Rework this  
      Page to display and manage dashboard for the selected user.
      - checks session timeout and user authentication
      - verifies story selection and allows clearing selection
      - fetches data for dashboard display
      -- provides quick links to create new elements
      -- shows recent activity and most used elements for selected story

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - Added conditional display of welcome message for new users.
      - Removed unnecessary script.js inclusion. 
      - Cleared timeline_id and story_id from session on request.
      - Verified story_id and user status.
      - Fetched data for dashboard display including recent stories, top characters, recent locations, and recent timeline entries.
      - Reworked HTML structure to display dashboard information based on story selection.
*/
// Initialize session and functions
require_once __DIR__ . '/../includes/init.php';

// Clear timeline_id if exists
if (isset($_SESSION['timeline_id']) && isset($_GET['clear_timeline'])) {
    unset($_SESSION['timeline_id']);
}

// Clear story_id if exists
if (isset($_SESSION['story_id']) && isset($_GET['clear_story'])) {
    unset($_SESSION['story_id']);
    unset($_SESSION['story_title']);
} else { // Get story data if story_id is set
    $story_id = isset($_SESSION['story_id']) ? (int)$_SESSION['story_id'] : null;
    $story_data = $story_id ? getStoryData($conn, $story_id) : null;
    $title = $story_data['title'] ?? 'No Story Selected';
}

// Verify story_id and user status
$new_user = checkUserStatus(); // may not be needed

// Fetch data for dashboard display
$user_stories = getAllStoriesByUser($conn, $user_id);

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
    .entry_type.event { color: #10b981; font-weight: bold; }
    .entry_type.plot { color: #3b82f6; font-weight: bold; }
  </style>
  <title>Story Dashboard</title>
</head>
<body>
  <main role="main">
    <div class="card" style="max-width: 1200px; margin-left: auto; margin-right: auto; margin-top: 24px;">
      <?= showMessages(); ?> <!-- Display any success or error messages -->
      <!-- Welcome Message for users with no story selected -->
      <div class="<?= !$story_data ? '' : 'hidden' ?>">
        <h2>Welcome to your Storyteller Dashboard!</h2>
        <p>From here you can manage multiple stories and their elements. </p>

        <h2>Let's get started ....</h2>
          <ul>
            <li>Select or Create a Story to start managing the elements within it.</li>
            <li>You can also manage your <strong>Characters</strong> and <strong>Locations</strong> with or with out selecting a story.</li>
            <li>Use the quicklinks below to Create new elements on the fly.</li>
            <li>If you want to jump right into writing sign into your Google Drive.</strong></li>
          </ul><br> 
          
        <div class="three-column-grid">
          <!-- Recent Stories Section -->
          <section class="grid-item box-a card">
            <h2>Recent Stories</h2>
            <div class="toolbar" style="display:flex;gap:8px;justify-content:center;margin-top:12px;">
              <button class="btn primary" onclick="window.location.href='../pages/stories.php'">Manage Stories</button>
            </div>
            <table>
              <thead>
                <tr><th>Title</th><th>Last Activity</th><th></th></tr>
              </thead>
              <tbody>
                <?php
                if (empty($user_stories)) {
                  echo "<tr><td colspan='4' style='text-align:center;'>You have not created any stories yet. Click 'Add Story' to get started.</td></tr>";
                } else {
                  foreach ($user_stories as $story) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($story['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($story['last_activity']) . "</td>";
                    echo "<td>";
                    echo "<div style='display:flex;gap:8px;justify-content:flex-end;margin-top:12px;'>";
                      echo "<button class='btn' data-open='#story_select_modal'"
                        . " data-story_id='" . (int)$story['story_id'] . "'"
                        . " data-title='" . htmlspecialchars($story['title'], ENT_QUOTES) . "'>Select</button>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                  }
                }
                ?>
              </tbody>
            </table>
          </section>
          <!-- Quick Actions Section -->
          <section class="grid-item box-b card" style ="flex-direction:column; display:flex; align-items:center; padding-top:20px;">
            <h2>Quick Actions</h2>
            <button class="btn primary" style="margin-bottom: 8px; min-width: 150px;" data-open="#character_create_modal" 
                    data-redirect_page="../pages/characters.php">Add Character</button>
            <button class="btn primary" style="margin-bottom: 8px; min-width: 150px;" data-open="#location_create_modal" 
                    data-redirect_page="../pages/locations.php">Add Location</button>
            <button class="btn primary" style="min-width: 150px;" onclick="window.location.href='../pages/new_story.php'">Add Story</button>
          </section>
        </div>
      </div>
      <!-- Welcome message for new users with a selected story -->
      <div class=" <?= $story_data ? '' : 'hidden' ?>">
        <h2>Welcome to your Story Dashboard!</h2>
        <p>From here you can manage your story's elements. The info displayed below is specific to the selected story. The goal is to 
          give you insight in to you recent andtivity and most used characters.
        </p>

        <h3>Let's get started ....</h3>
          <ul>
              <li>Now that you have selected a story, you can create Timeline Events.</li>
              <li>Use the navigation bar to manage the different elements of your story.</li>
              <li>Use the quicklinks below to create new elements on the fly.</li>
              <li>If you want to jump right into writing, sign into your Google Drive.</strong></li>
              <li>To change the selected story, use the "Change Story" button below or go to the Stories page.</li>
          </ul><br> 
        <div class="three-column-grid">
          <!-- Story Summary Section -->
          <section class="grid-item box-a card">
            <h2>Story Summary</h2>
            <div class="toolbar" style="display:flex;gap:8px;justify-content:center;margin-top:12px;">
              <button class="btn primary" onclick="window.location.href='../pages/stories.php'">Change Story</button>
              <button class="btn primary" style="margin-left: 8px;" data-open="#story_sunset_modal" data-title="<?= htmlspecialchars($title) ?>">Close Story</button>
            </div>
            <div><p>Title: <strong><?= htmlspecialchars($story_data['title'] ?? '') ?></strong></p></div>
            <div><p>Created: <?= htmlspecialchars($story_data['created_at'] ?? '') ?></p></div>
            <div><p>Last Activity: <?= htmlspecialchars($story_data['last_activity'] ?? '') ?></p></div>
            <div><p>Genre: <?= htmlspecialchars($story_data['genre'] ?? '') ?></p></div>
            <div><p>Synopsis: <br><?= htmlspecialchars($story_data['synopsis'] ?? '') ?></p></div>
            <div><p>Total Characters: <?= htmlspecialchars(countCharsByStory($conn, $story_id) ?? '') ?></p></div>
            <div><p>Total Locations: <?= htmlspecialchars(countLocsByStory($conn, $story_id) ?? '') ?></p></div>
          </section>
          <!-- Quick Actions Story Selected Section -->
          <section class="grid-item box-b card" style ="flex-direction:column; display:flex; align-items:center; padding-top:20px;">
            <h2>Quick Actions</h2>
            <button class="btn primary" style="margin-bottom: 8px; min-width: 150px;" data-open="#character_create_modal" 
                    data-story_id="<?= $story_id ?>" data-redirect_page="../pages/characters.php" >Add Character</button>
            <button class="btn primary" style="margin-bottom: 8px; min-width: 150px;" data-open="#location_create_modal" 
                    data-story_id="<?= $story_id ?>" data-redirect_page="../pages/locations.php" >Add Location</button>
            <button class="btn primary" style="margin-bottom: 8px; min-width: 150px;" onclick="window.location.href='../pages/new_entry.php'">Add Timeline Entry</button>
          </section>
        </div>
        <div class="two-column-grid" style="margin-top: 24px;">
          <!-- Most Used Characters Section -->
          <section class="grid-item box-c card">
            <h2>Most Used Characters</h2>
            <table>
              <thead>
                <tr><th>Name</th><th></th></tr>
              </thead>
              <tbody>
                <?php
                $top_characters = $story_id ? getTopCharsByStory($conn, $story_id) : [];
                if (empty($top_characters)) {
                  echo "<tr><td colspan='4' style='text-align:center;'>No Characters.</td></tr>";
                } else {
                  foreach ($top_characters as $char) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($char) . "</td>";
                    echo "</tr>";
                  }
                }
                ?>
              </tbody>
            </table>
          </section>
          <!-- Recently Added Locations Section -->
          <section class="grid-item box-d card">
            <h2>Recently Added Locations</h2>
            <table>
              <thead>
                <tr><th>Name</th><th></th></tr>
              </thead>
              <tbody>
                <?php
                $locations = $story_id ? getRecentLocsByStory($conn, $story_id) : [];
                if (empty($locations)) {
                  echo "<tr><td colspan='4' style='text-align:center;'>No Locations.</td></tr>";
                } else {
                  foreach ($locations as $location) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars(getLocName($conn, $location)) . "</td>";
                    echo "</tr>";
                  }
                }
                ?>
              </tbody>
            </table>
          </section>
          <!-- Recent Timeline Entries Section -->
          <section class="grid-item header card">
            <h2>Recent Timeline Entries</h2>
            <table>
              <thead>
                  <tr><th>Title</th><th>Type</th><th>Summary</th><th>Location</th><th>Characters</th></tr>
              </thead>
              <tbody id="entries_rows">
                  <?php
                  $timeline_entries = $story_id ? getRecentTimeEntriesByStory($conn, $story_id) : [];
                  if (empty($timeline_entries)) {
                    echo "<tr><td colspan='7' style='text-align:center;'>No Entries.</td></tr>";
                  } else {
                    foreach ($timeline_entries as $entry) {
                      // Determine row class based on entry type
                      $rowTypeClass = 'entry_type ' . strtolower($entry['entry_type']);
                      echo "<tr>";
                      echo "<td>" . htmlspecialchars($entry['title']) . "</td>";
                      echo "<td class='$rowTypeClass'>" . strtoupper(htmlspecialchars($entry['entry_type'])) . "</td>";
                      echo "<td>" . htmlspecialchars($entry['summary']) . "</td>";
                      // Fetch and display associated location name
                      echo "<td>" . htmlspecialchars(getLocName($conn, $entry['location_id'])) . "</td>";
                      // Fetch and display associated character names
                      echo "<td>" . htmlspecialchars(getEntryCharNames($conn, $entry['timeline_id'])) . "</td>";
                      echo "</tr>";
                    }
                  }
                  ?>
              </tbody>
            </table>  
          </section>
        </div>
      </div>  
    </div>
    <?php require __DIR__ . '/../includes/footer.php'; ?>
  </main>
<script src="../assets/js/modal.js"></script>
<body>
</html>