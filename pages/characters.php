<?php
/* Storyteller Database Character Management Page - characters.php
      Page to display and manage characters for the selected story.
      - checks session timeout and user authentication
      - fetches characters from the database for the selected story
      - displays characters in a table with options to add, edit, delete
      - includes modals for character creation, update, and deletion 

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - Added conditional display of welcome message for new users.
      - Removed unnecessary script.js inclusion. 

      Future updates:
      - add option to add characters to multiple stories.
      - show story name in character list when no story selected.
  */
// Initialize session and functions
require_once __DIR__ . '/../includes/init.php';

// Initialize HTML header and modals
require_once __DIR__ . '/../includes/init_html.php';

// Get story data and set conditional variables
$story_id = isset($_SESSION['story_id']) ? (int)$_SESSION['story_id'] : null;
if ($story_id) {
    $storyHeader = true;
    $story_data = getStoryData($conn, $story_id);
    $title = $_SESSION['story_title'] ?? 'No Title Selected';
} else {
    $storyHeader = false;
}
$name = $_SESSION['name'] ?? 'Author Unknown';
$new_user = checkUserStatus();

// Fetch characters for the selected story or all characters if no story selected
$rows = isset($story_id) ? getAllCharsByStory($conn, $story_id) : getAllCharsByUser($conn, $user_id) ?? [];
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/page.css">
  <link rel="stylesheet" href="../assets/css/modal.css">
  <style>
    html,body {margin: 0; padding: 0; height: 100%;}
    body {background: url('../assets/images/clouds_STDB.png') no-repeat center center fixed; background-size: cover;}
  </style>
  <title>Characters Page</title>
</head>
<body>
  <main role="main">
    <div class="card" style="max-width: 1200px; margin-left: auto; margin-right: auto; margin-top: 24px;">
      <?= showMessages(); ?> <!-- Display any success or error messages -->
      <div class="<?= $new_user ? ' hidden' : '' ?>">
        <div class="<?= !$storyHeader ? '' : 'hidden' ?>" style= "justify-content: center;">
          <h2>Manage Characters created by <?= htmlspecialchars($name) ?></h2>
          <button class="btn primary" href="../pages/stories.php">Select Story</button>
        </div>
        <div class="toolbar<?= $storyHeader ? '' : 'hidden' ?>" style= "justify-content: center;">
          <h2>Manage Characters for <?= htmlspecialchars($title) ?></h2>
          <button class="btn primary" href="../pages/stories.php">Change Story</button>
        </div>
      </div>
      <!-- Welcome Message for New Users -->
      <div class="<?= $new_user ? '' : 'hidden' ?>">
        <h2>Welcome to your Story's Character Roster</h2>
          <p>Characters give your story width, depth, and emotional resonance. This page helps you create and 
            refine the people who will bring your narrative to life.</p>
          <h2>Let's get started ....</h2>
          <p>Below is a table that displays each character you have create and allows you to edit or delete them 
            as needed. You can also access this same table from the Dashboard at anytime. For your first character, 
            use the "Add Character" button to begin.</p>
      </div>
      <div class="two-column-grid">
        </section>
        <!-- Character Stats for Existing Users -->
        <section class="grid-item header card <?= !$new_user ? '' : 'hidden' ?>" style="display:flex; align-items:center; justify-content:space-between;">
          <h3 style="margin:0;">Characters</h3>
          <?php
          if (!isset($story_id)) {
              echo "<p>" . countCharsByUser($conn, $user_id) . "</p>";
          } else {
              echo "<p>" . countCharsByStory($conn, $story_id) . "</p>";
          }
          echo "<h3 style=\"margin:0;\">Last Updated</h3>";
          $stmt = $conn->prepare("SELECT updated_at FROM characters order by updated_at DESC LIMIT 1");
          $stmt->execute();
          $result = $stmt->get_result();
          $last_updated = $result->fetch_assoc()['updated_at'] ?? 'No updates';
          echo "<p>" . htmlspecialchars($last_updated) . "</p>";
          ?>
        </section>
        <!-- Welcome Message for New Users -->
        
        <!-- Character Table -->
        <section class="grid-item header card">
          <div class="card">
            <div class="toolbar" style="max-width: 600px; margin-left: auto; margin-right: auto;">
              <input class="input" placeholder="Search characters..." />
              <!-- use data attribute that matches the modal input name (story_id) so modal.js populates it -->
              <button class="btn primary <?= $storyHeader ? '' : 'hidden' ?>" data-open="#character_create_modal" data-story_id="<?= $story_id ?>"
                    data-redirect_page="../pages/characters.php">Add Character</button>
            <button class="btn primary <?= !$storyHeader ? '' : 'hidden' ?>" data-open="#character_create_modal"
                    data-redirect_page="../pages/characters.php">Add Character</button>
            </div>
            
            <table>
              <thead>
                <tr><th>Name</th><th>Age</th><th>Description</th><th>Notes</th><th></th></tr>
              </thead>
              <tbody>
                <?php
                if (empty($rows)) {
                  echo "<tr><td colspan='3' style='text-align:center;'>No Characters. Click 'Add Character' to get started.</td></tr>";
                } else {
                  foreach ($rows as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['notes']) . "</td>";
                    echo "<td>";
                    // Use dash-style data attributes (data-id) so dataset properties are predictable
                    echo "<div style='display:flex;gap:8px;justify-content:flex-end;margin-top:12px;'>";
                      echo "<button class='btn' data-open='#character_update_modal'"
                        . " data-character_id='" . (int)$row['character_id'] . "'"
                        . " data-name='" . htmlspecialchars($row['name']) . "'"
                        . " data-age='" . htmlspecialchars($row['age']) . "'"
                        . " data-description='" . htmlspecialchars($row['description']) . "'"
                        . " data-notes='" . htmlspecialchars($row['notes']) . "'>Edit</button>";
                      echo "<button class='btn' data-open='#character_delete_modal'"
                        . " data-character_id='" . (int)$row['character_id'] . "'"
                        . " data-name='" . htmlspecialchars($row['name']) . "'>Delete</button>";
                    echo "</div>";
                    echo "</td>";
                    echo "</tr>";
                  }
                }
                ?>
              </tbody>
            </table>  
          </div>
        </section>
      </div>
      <?php require __DIR__ . '/../includes/footer.php'; ?>
  </main>
  <script src="../assets/js/modal.js"></script>
</body>
</html>