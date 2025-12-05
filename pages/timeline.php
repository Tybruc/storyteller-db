
<?php
/* Storyteller Database Timeline Management Page - timeline.php
      Page to display and manage timeline entries for the selected story.
      - checks session timeout and user authentication
      - fetches timeline entries from the database for the selected story
      - displays timeline entries in a table with options to add, edit, delete
      - includes modals for timeline entry creation, update, and deletion 
      - draggable rows to reorder timeline entries
      - uses conditional modals to warn about unsaved changes
      - integrates with CRUD endpoint to save timeline entry selections
      - cannot be accessed without selecting a story

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
*/
// Initialize session and functions
require_once __DIR__ . '/../includes/init.php';

// Clear timeline_id if exists
if (isset($_SESSION['timeline_id']) && isset($_GET['clear_id'])) {
    unset($_SESSION['timeline_id']);
}
// Verify if story is selected
$story_id = checkStoryID(); 

// Initialize HTML header and modals
require_once __DIR__ . '/../includes/init_html.php';

// Fetch story data and user status
$story_data = getStoryData($conn, $story_id);
$title = $_SESSION['story_title'] ?? 'No Title Selected';
$name = $_SESSION['name'] ?? 'Author Unknown';
$new_user = checkUserStatus();

// Fetch timeline entries for the selected story or all locations if no story selected
$rows = getAllTimeEntriesByStory($conn, $story_id);
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
    .plot-row { cursor: move; }
    .plot-row td:first-child { cursor: grab; }
    .plot-row:hover { background: #f5f5f5; }
    .select-location.selected { outline: 2px solid #3b82f6; background: #eef6ff; }
    .entry_type.event { background-color: #6bab96ff; font-weight: bold; }
    .entry_type.plot { background-color: #374d6fff; font-weight: bold; }
  </style>
  <title>Story Dashboard</title>
</head>
<body>
  <main role="main">
    <div class="card " style="max-width: 1200px; margin-left: auto; margin-right: auto; margin-top: 24px;">
      <?= showMessages(); ?> <!-- Display any success or error messages -->
      <!-- Welcome Message for New Users -->
      <div class="<?= $new_user ? '' : 'hidden' ?>">
        <h2>Welcome to your Story's Timeline</h2>
        <p>Laying out the series of events and keeping them organized helps shape your story's trajectory. Here, you'll begin identifying 
          the major beats that will carry your narrative forward.</p>
        <h2>Let's get started ....</h2>
        <p>Below is a table that displays each entry you have created and allows you to edit or delete them 
          as needed. You can also access this same table from the Dashboard at anytime. For your first entry, 
          use the "Add Entry" button to begin.</p>
      </div>
      <!-- Timeline Header and Stats -->
      <div class="dashboard-grid">
        <!-- Timeline Stats for Existing Users -->
        <section class="grid-item header card <?= !$new_user ? '' : 'hidden' ?>" style="display:flex; align-items:center; justify-content:space-between;">
          <h3 style="margin:0;">Entries:</h3>
          <?php
          $stmt = $conn->prepare("SELECT COUNT(*) AS entry_count FROM timeline WHERE story_id = ?");
          $stmt->bind_param("i", $story_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $count = $result->fetch_assoc()['entry_count'] ?? 0;
          echo "<p style='margin:0;'>" . (int)$count . "</p>";
          echo "<h3 style='margin:0;'>Last Updated</h3>";
          $stmt = $conn->prepare("SELECT updated_at FROM timeline WHERE story_id = ? order by updated_at DESC LIMIT 1");
          $stmt->bind_param("i", $story_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $last_updated = $result->fetch_assoc()['updated_at'] ?? 'No updates';
          echo "<p style='margin:0;'>" . htmlspecialchars($last_updated) . "</p>";
          ?>
        </section>
        <!-- Timeline Entries Table -->
        <section class="grid-item header card">
          <div class="toolbar" style="max-width: 800px; margin-left: auto; margin-right: auto; justify-content: space-between;">
            <button id="saveOrderBtn" class="save" data-open="#timeline_save_order_modal">Save Order</button>
            <input class="input" style="max-width: 600px;" placeholder="Search entries..." />
            <button class="btn primary" onclick="window.location.href='../pages/new_entry.php'">Add Entry</button>
            <button class="btn primary" onclick="window.location.href='../pages/visual_timeline.php'">View Visual Timeline</button>
          </div>

          <table>
            <thead>
                <tr><th></th><th>Title</th><th>Type</th><th>Summary</th><th>Location</th><th>Characters</th><th></th><th></th></tr>
            </thead>
            <tbody id="entries_rows">
                <?php
                if (empty($rows)) {
                  echo "<tr><td colspan='7' style='text-align:center;'>No Entries. Click 'Add Entry' to get started.</td></tr>";
                } else {
                  foreach ($rows as $row) {
                    // Determine row class based on entry type
                    $rowTypeClass = 'entry_type ' . strtolower($row['entry_type']);
                    // Determine if new entry
                    $rowClass = 'entry-row' . ($row['sequence_no'] == 0 ? ' selected' : '');
                    echo "<tr draggable='true' data-id='" . $row['timeline_id'] . "' class='$rowClass'>";
                    // Drag handle
                    echo "<td>☰</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td class='$rowTypeClass'>" . strtoupper(htmlspecialchars($row['entry_type'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['summary']) . "</td>";
                    // Fetch and display associated location name
                    echo "<td>" . htmlspecialchars(getLocName($conn, $row['location_id'])) . "</td>";
                    // Fetch and display associated character names
                    echo "<td>" . htmlspecialchars(getEntryCharNames($conn, $row['timeline_id'])) . "</td>";
                    echo "<td>";
                    // Action buttons
                    echo "<div style='display:flex;gap:8px;justify-content:flex-end;'>";
                      echo "<button class='btn' onclick=\"window.location.href='../pages/new_summary.php?timeline_id=" . (int)$row['timeline_id'] . "&update=1'\">Edit</button>";
                      echo "<a class='btn' data-open='#timeline_delete_modal'"
                          . " data-timeline_id='" . (int)$row['timeline_id'] . "'"
                          . " data-title='" . htmlspecialchars($row['title'], ENT_QUOTES) . "'>Delete</a>";
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
  <script>
    // Drag & Drop Reordering Logic
    const tbody   = document.getElementById('entries_rows');
    const saveBtn = document.getElementById('saveOrderBtn');
    let draggedRow = null;

    // Drag & drop handlers
    if (tbody) {
      tbody.addEventListener('dragstart', e => {
        const row = e.target.closest('tr');
        if (!row) return;

        draggedRow = row;
        row.style.opacity = '0.5';

        // Mark that order changed so user knows to save
        if (saveBtn && !saveBtn.classList.contains('active')) {
          saveBtn.classList.add('active');
        }
      });
      
      // Drag end handler
      tbody.addEventListener('dragend', e => {
        const row = e.target.closest('tr');
        if (row) row.style.opacity = '';
      });

      // Drag over handler
      tbody.addEventListener('dragover', e => {
        e.preventDefault();
        const row = e.target.closest('tr');
        if (!row || !draggedRow || row === draggedRow) return;

        const rect    = row.getBoundingClientRect();
        const middleY = rect.top + rect.height / 2;

        // Insert dragged row before or after based on mouse position
        if (e.clientY < middleY) {
          row.parentNode.insertBefore(draggedRow, row);
        } else {
          row.parentNode.insertBefore(draggedRow, row.nextSibling);
        }
      });
    }

    // When user confirms from the modal, build the order & submit the form
    document.addEventListener('click', function (e) {
      const btn = e.target.closest('#confirm_save_order');
      if (!btn) return;

      // Gather form elements
      const form          = document.getElementById('save_order_form');
      const orderInput    = document.getElementById('modal_entry_order');
      const rows          = [...document.querySelectorAll('#entries_rows .entry-row')];

      //  Validate elements
      if (!form || !orderInput || rows.length === 0) return;

      // Serialize the order as "id:sequence_no" pairs separated by commas
      const serializedOrder = rows
        .map((row, index) => `${row.dataset.id}:${index + 1}`)
        .join(',');
      // Set hidden input value and submit the form
      orderInput.value = serializedOrder;
      form.submit();
    });
</script>
<script src="../assets/js/modal.js"></script>      
</body>
</html>