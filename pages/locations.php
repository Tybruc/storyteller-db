<?php
/* Storyteller Database Location Management Page - locations.php
      Page to display and manage locations for the selected story.
      - checks session timeout and user authentication
      - fetches locations from the database for the selected story
      - displays locations in a table with options to add, edit, delete
      - includes modals for location creation, update, and deletion 

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - Added conditional display of welcome message for new users.
      - Removed unnecessary script.js inclusion. 
  */
// Initialize session and functions
require_once __DIR__ . '/../includes/init.php';

// Initialize HTML header and modals
require_once __DIR__ . '/../includes/init_html.php';

// Verify story_id and user_id in session
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

// Fetch locations for the selected story or all locations if no story selected
$rows = isset($story_id) ? getAllLocsByStory($conn, $story_id) : getAllLocsByUser($conn, $user_id) ?? [];
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
  <title>Story Dashboard</title>
</head>
<body>
  <main role="main">
    <div class="card" style="max-width: 1200px; margin-left: auto; margin-right: auto; margin-top: 24px;">
      <?= showMessages(); ?> <!-- Display any success or error messages -->
      <div class="<?= $new_user ? ' hidden' : '' ?>">
        <div class="<?= !$storyHeader ? '' : 'hidden' ?>" style= "justify-content: center;">
          <h2>Manage Locations created by <?= htmlspecialchars($name) ?></h2>
          <button class="btn primary" href="../pages/stories.php">Select Story</button>
        </div>
        <div class="toolbar<?= $storyHeader ? '' : 'hidden' ?>" style= "justify-content: center;">
          <h2>Manage Locations for <?= htmlspecialchars($title) ?></h2>
          <button class="btn primary" href="../pages/stories.php">Change Story</button>
        </div>
      </div>
      <!-- Welcome Message for New Users -->
      <div class="<?= $new_user ? '' : 'hidden' ?>">
        <h2>Welcome to Location Development</h2>
        <p>Your characters need places to live, move, struggle, and grow. Here, you’ll start sketching out the 
          landscapes, settings, and environments that support your narrative.</p>
        <h2>Let's get started ....</h2>
        <p>Below is a table that displays each location you have create and allows you to edit or delete them 
          as needed. You can also access this same table from the Dashboard at anytime. For your first location, 
          use the "Add location" button to begin.</p>
      </div>
    <div class="two-column-grid">
      <!-- Location Stats for Existing Users -->
      <section class="grid-item header card <?= !$new_user ? '' : 'hidden' ?>" style="display:flex; align-items:center; justify-content:space-between;">
        <h3 style="margin:0;">Locations</h3>
        <?php
        if (!isset($story_id)) {
            echo "<p>" . countLocsByUser($conn, $user_id) . "</p>";
        } else {
            echo "<p>" . countLocsByStory($conn, $story_id) . "</p>";
        }
        echo "<h3 style=\"margin:0;\">Last Updated</h3>";
        $stmt = $conn->prepare("SELECT updated_at FROM locations order by updated_at DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $last_updated = $result->fetch_assoc()['updated_at'] ?? 'No updates';
        echo "<p>" . htmlspecialchars($last_updated) . "</p>";
        ?>
      </section>
      <!-- Locations Table -->
      <section class="grid-item header card">
        <div class="card">
          <div class="toolbar" style="max-width: 600px; margin-left: auto; margin-right: auto;">
            <input class="input" placeholder="Search locations..." />
            <button class="btn primary <?= $storyHeader ? '' : 'hidden' ?>" data-open="#location_create_modal" data-story_id="<?= $story_id ?>"
                    data-redirect_page="../pages/locations.php">Add Location</button>
            <button class="btn primary <?= !$storyHeader ? '' : 'hidden' ?>" data-open="#location_create_modal"
                    data-redirect_page="../pages/locations.php">Add Location</button>
          </div>
          
          <table>
            <thead>
              <tr><th>Name</th><th>Description</th><th></th></tr>
            </thead>
            <tbody>
              <?php
                if (empty($rows)) {
                  echo "<tr><td colspan='2' style='text-align:center;'>No Locations. Click 'Add Location' to get started.</td></tr>";
                } else {
                  foreach ($rows as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>";
                    // Use dash-style data attributes (data-id) so dataset properties are predictable
                    echo "<div style='display:flex;gap:8px;justify-content:flex-end;margin-top:12px;'>";
                      echo "<button class='btn' data-open='#location_update_modal'"
                          . " data-location_id='" . (int)$row['location_id'] . "'"
                          . " data-name='" . htmlspecialchars($row['name'], ENT_QUOTES) . "'"
                          . " data-description='" . htmlspecialchars($row['description'], ENT_QUOTES) . "'>Edit</button>";
                      echo "<button class='btn' data-open='#location_delete_modal'"
                          . " data-location_id='" . (int)$row['location_id'] . "'"
                          . " data-name='" . htmlspecialchars($row['name'], ENT_QUOTES) . "'>Delete</button>";
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