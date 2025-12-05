<?php
/* Storyteller Database Story Management Page - stories.php
      Page to display and manage stories created by user.
      - checks session timeout and user authentication
      - fetches stories from the database for the user
      - displays stories in a table with options to add, edit, delete
      - includes modals for story creation, update, and deletion 
      - allows selection of stories to work on
      - uses conditional modals to warn about unsaved changes
      - integrates with CRUD endpoint to save story selections

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
       
  */

// Initialize session and functions
require_once __DIR__ . '/../includes/init.php';

// Initialize HTML header and modals
require_once __DIR__ . '/../includes/init_html.php';

// Fetch stories for the user
$rows = getAllStoriesByUser($conn, $user_id);

$name = $_SESSION['name'] ?? 'Author Unknown';
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
  <title>Story Management</title>
</head>
<body>
  <main role="main">
    <div class="card" style="max-width: 1200px; margin-left: auto; margin-right: auto; margin-top: 24px;">
      <?= showMessages(); ?> <!-- Display any success or error messages -->
      <!-- Story Header for User's Stories -->
      <div class="<?= !$new_user ? '' : 'hidden' ?>" style= "justify-content: center;">
        <h2>Manage Stories created by <?= htmlspecialchars($name) ?></h2>
      </div>
      <!-- Welcome Message for New Users -->
      <div class="<?= $new_user ? '' : 'hidden' ?>">
        <h2>Welcome your Story Manager!</h2>
        <p>Every writing project starts with anchoring the core idea. This page invites you to define the 
          essential framework of your story so future development feels intentional and grounded.</p>
        <h2>Let's get to work....</h2>
        <p>You can select a story to work, create a new one, or make some updates using the options below.</p>
      </div>
      <div class=two-column-grid>
        <!-- Story Stats for Existing Users -->
        <section class="grid-item header card <?= !$new_user ? '' : 'hidden' ?>" style="display:flex; align-items:center; justify-content:space-between;">
          <h3 style="margin:0;">Stories</h3>
          <?php
          echo "<p>" . countStoriesByUser($conn, $user_id) . "</p>";
          echo "<h3 style=\"margin:0;\">Last Updated</h3>";
          $stmt = $conn->prepare("SELECT last_activity FROM stories order by last_activity DESC LIMIT 1");
          $stmt->execute();
          $result = $stmt->get_result();
          $last_updated = $result->fetch_assoc()['last_activity'] ?? 'No updates';
          echo "<p>" . htmlspecialchars($last_updated) . "</p>";
          ?>
        </section>
        <section class="grid-item header card">  
          <div class="toolbar" style="max-width: 600px; margin-left: auto; margin-right: auto;">
            <input class="input" placeholder="Search stories..." />
            <button class="btn primary" onclick="window.location.href='../pages/new_story.php'">Add Story</button>
          </div>
        
          <table>
            <thead>
              <tr><th>Title</th><th>Genre</th><th>Synopsis</th><th></th></tr>
            </thead>
            <tbody>
              <?php
              if (empty($rows)) {
                echo "<tr><td colspan='4' style='text-align:center;'>You have not created any stories yet. Click 'Add Story' to get started.</td></tr>";
              } else {
                foreach ($rows as $row) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['genre']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['synopsis']) . "</td>";
                  echo "<td>";
                  echo "<div style='display:flex;gap:8px;justify-content:flex-end;margin-top:12px;'>";
                    echo "<button class='btn' data-open='#story_update_modal'"
                      . " data-story_id='" . (int)$row['story_id'] . "'"
                      . " data-title='" . htmlspecialchars($row['title'], ENT_QUOTES) . "'"
                      . " data-genre='" . htmlspecialchars($row['genre'], ENT_QUOTES) . "'"
                      . " data-synopsis='" . htmlspecialchars($row['synopsis'], ENT_QUOTES) . "'>Edit</button>";
                    echo "<button class='btn' data-open='#story_delete_modal'"
                      . " data-story_id='" . (int)$row['story_id'] . "'"
                      . " data-title='" . htmlspecialchars($row['title'], ENT_QUOTES) . "'>Delete</button>";
                    echo "<button class='btn' data-open='#story_select_modal'"
                      . " data-story_id='" . (int)$row['story_id'] . "'"
                      . " data-title='" . htmlspecialchars($row['title'], ENT_QUOTES) . "'>Select</button>";
                  echo "</div>";
                  echo "</td>";
                  echo "</tr>";
                }
              }
              ?>
            </tbody>
          </table>
        </section>  
      </div>
    </div>
    <?php require __DIR__ . '/../includes/footer.php'; ?>
  </main>
<script src="../assets/js/modal.js"></script>
</body>
</html>