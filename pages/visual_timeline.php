
<?php
/* Storyteller Database Visual Timeline Page - visual_timeline.php
      Page to display a visual representation of the timeline for the selected story.
      - checks session timeout and user authentication
      - fetches all timeline entries from the database for the selected story
      - displays timeline entries in a vertical timeline with center line
      - entries alternate left and right in sequence order
      - no editing capabilities, read-only view

      Created by [Ty Curneen], 2025

      Updated: December 2025
      Changes:
      - Refactored to display vertical timeline with center line
      - Entries alternate left (plot points) and right (timeline events)
      - Merged plot points and timeline events into single sequence
  */

// Load session and functions BEFORE any HTML output
require_once __DIR__ . '/../includes/init.php';

// Verify current status and set variables BEFORE any output
$story_id = checkStoryID();

// NOW load header and modals (this outputs HTML)
require_once __DIR__ . '/../includes/header.php';

// Fetch all timeline entries in sequence
$rows = getAllTimeEntriesByStory($conn, $story_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/page.css">
  <link rel="stylesheet" href="../assets/css/modal.css">
  <link rel="stylesheet" href="../assets/css/visual_timeline.css">
  <style>
    html, body { margin: 0; padding: 0; height: 100%; }
    body { background: url('../assets/images/clouds_STDB.png') no-repeat center center fixed; background-size: cover; }
  </style>
  <title>Visual Timeline</title>
</head>
<body>
  <main role="main">
    <?= showMessages(); ?>
    
    <!-- Visual Timeline Container -->
    <div class="timeline-container">
      <div class="card" style="max-width: 1200px; margin-left: auto; margin-right: auto; margin-top: 24px; padding: 20px;">
        <?= showMessages(); ?> <!-- Display any success or error messages -->
        <div class="timeline-header">  
          <h1>Story Timeline</h1>
          <button class="btn primary" onclick="window.location.href='../pages/timeline.php'">Back to Timeline Management</button>      
        </div>
        <!-- Vertical Timeline -->
        <div class="timeline-wrapper">
          <!-- Center line -->
          <div class="timeline-center-line"></div>
          
          <!-- Timeline entries -->
          <div class="timeline-entries">
            <?php
            if (empty($rows)) {
              echo "<div style='text-align:center; padding: 40px; color: #666;'><p>No timeline entries found.</p></div>";
            } else {
              foreach ($rows as $index => $entry) {
                $title = htmlspecialchars($entry['title'] ?? '', ENT_QUOTES);
                $summary = htmlspecialchars($entry['summary'] ?? '', ENT_QUOTES);
                $entryType = htmlspecialchars($entry['entry_type'] ?? 'plot', ENT_QUOTES);
                
                // Alternate left and right
                $position = ($index % 2 === 0) ? 'left' : 'right';
                $typeClass = strtolower($entryType);
                
                echo "<div class='timeline-item timeline-{$position} timeline-type-{$typeClass}'>";
                echo "  <div class='timeline-dot'></div>";
                echo "  <div class='timeline-content'>";
                echo "    <div class='timeline-label'>" . ucfirst($typeClass) . "</div>";
                echo "    <h3 class='timeline-title'>$title</h3>";
                echo "    <p class='timeline-summary'>$summary</p>";
                echo "  </div>";
                echo "</div>";
              }
            }
            ?>
          </div>
        </div>
    </div>
    </div>
    <?php require __DIR__ . '/../includes/footer.php'; ?>
  </main>
</body>
</html>
