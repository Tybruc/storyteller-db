
<?php
/* Storyteller Database New Entry Location Management Page - new_locations.php
      Page to display and manage locations for the selected story.
      - checks session timeout and user authentication
      - fetches locations from the database for the selected story
      - displays locations in a table with options to add, edit, delete
      - includes modals for location creation, update, and deletion 
      - allows selection of locations for an Timeline Entry (plot or event)
      - uses conditional modals to warn about unsaved changes
      - integrates with CRUD endpoint to save location selections

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - updated the JS to handle unsaved changes when selecting locations
      - improved modal interactions for location selection
      - enhanced error handling and validation for location operations
      - changed SQL queries to use functions for data retrieval
  */

// Initialize session and functions
require_once __DIR__ . '/../includes/init.php';

// Verify if story is selected, set story title and fetch characters
$story_id = isset($_SESSION['story_id']) ? (int)$_SESSION['story_id'] : 
            redirect_page('../pages/new_story.php', '', ['error_message' => 'Please create a new entry before managing characters.']); 
$locations = getAllLocsByStory($conn, $story_id);
$title = $_SESSION['story_title'] ?? 'No Title Selected';
$timeline_id = (int)($_SESSION['timeline_id'] ?? 0);
$rows = getTimelineEntryData($conn, $timeline_id);
  $entry_title = $rows['title'] ?? '';
  $selected_location_id = (int)($rows['location_id'] ?? 0);

// Check if updating existing entry
$update = isset($_GET['update']) && $_GET['update'] == 1 ? true : false;

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
  <link rel="stylesheet" href="../assets/css/page.css">
  <link rel="stylesheet" href="../assets/css/modal.css">
  <style>
    html,body {margin: 0; padding: 0; height: 100%;}
    body {background: url('../assets/images/clouds_STDB.png') no-repeat center center fixed; background-size: cover;}
    .select-location.selected { outline: 2px solid #3b82f6; background: #eef6ff; }
  </style>
  <title>New / Update Entry Location Select</title>
</head>
<body>
  <main role="main">
    <div class="card" style="max-width: 900px; margin-left: auto; margin-right: auto; margin-top: 24px;">
      <?= showMessages(); ?> <!-- Display any success or error messages -->
      <div class="<?= !$update ? '' : 'hidden' ?>">
        <h2>Select Location for '<?= htmlspecialchars($entry_title) ?>'</h2>
        <p>In this section, you can select a location to associate with the timeline entry '<strong><?= htmlspecialchars($entry_title) ?></strong>'.
           Locations help ground your events in specific settings, enhancing the depth and immersion of your story.
        </p>
        <h2>Let's get started ....</h2>
      </div>
      <h2 class="<?= $update ? "" : "hidden" ?>">Update Location for '<?= htmlspecialchars($entry_title) ?>'</h2>
      <p> Below is a table that displays the locations created for "<?= htmlspecialchars($title) ?>". To assign 
        or unassign a location to "<?= htmlspecialchars($entry_title) ?>" use the radio button on each row. For new 
        locations, use the "Add Location" button to begin.</p>
    
      <div class="two-column-grid">
        <section class="grid-item header card">
            <div class="toolbar" style="max-width: 600px; margin-left: auto; margin-right: auto;">
              <input class="input" placeholder="Search locations..." />
              <button class="btn primary" data-open="#location_create_modal" data-redirect_page="../pages/new_locations.php" 
                      data-story_id="<?= htmlspecialchars($story_id) ?>">Add Location</button>
            </div>
            <form id="location_select_form" method="post" action="../crud/timeline.php">
              <input type="hidden" id="selected_location_id" name="location_id" value="<?= htmlspecialchars($selected_location_id) ?>" />
              <input type="hidden" name="action" value="update_location">
              <input type="hidden" name="timeline_id" value="<?= htmlspecialchars($timeline_id) ?>">
              <input type="hidden" name="title" value="<?= htmlspecialchars($entry_title) ?>">
              <table style="width:100%; border-collapse:collapse;">
                <thead>
                  <tr>
                    <th style="text-align:left; padding:6px;">Select</th>
                    <th style="text-align:left; padding:6px;">Name</th>
                    <th style="text-align:left; padding:6px;">Description</th>
                  </tr>
                </thead>
                <tbody id="location_rows">
                  <?php
                    if (empty($locations)) {
                      echo "<tr><td colspan='3' style='text-align:center;'>No Locations added. Click 'Add Location' to get started.</td></tr>";
                    } else {
                      foreach ($locations as $location) {
                        $location_id = (int)$location['location_id'];
                        $location_name = htmlspecialchars($location['name'], ENT_QUOTES);
                        $location_desc = htmlspecialchars($location['description']);
                        $isChecked = ($location_id === $selected_location_id) ? 'checked' : '';
                        echo "<tr>";
                        echo "<td style='padding:6px; vertical-align:top;'>";
                        echo "<input type='radio' name='location_radio' class='select-location' data-id='" . $location_id . 
                            "' data-name='" . $location_name . "' " . $isChecked . " />";
                        echo "</td>";
                        echo "<td style='padding:6px; vertical-align:top;'>" . $location_name . "</td>";
                        echo "<td style='padding:6px; vertical-align:top;'>" . $location_desc . "</td>";
                        echo "</tr>";
                      }
                    }
                  ?>
                </tbody>
              </table>
              <div class="<?= !$update ? '' : 'hidden' ?>">
                <div><p>Next we will add characters to your entry. Click continue to proceed. </p></div>
                <div><p>Or you can "Exit to the Dashboard" to leave with out saving,  to explore the other tools available.</p></div>
                <div class = "modal-actions-right" style="margin-top:12px;">
                  <button type="button" class="btn" onclick="window.location.href='../pages/dashboard.php';">Exit to Dashboard</button>
                  <button type="button" class="btn <?= !$new_user ? '' : 'hidden' ?>" onclick="window.location.href='../pages/timeline.php';">Cancel</button>
                  <button type="submit" class="btn primary" name="redirect_page" value="../pages/new_characters.php">Continue</button>
                </div>
              </div>    
              <!-- If updating, show different buttons --> 
              <div class="<?= $update ? '' : 'hidden' ?>">    
                <div class="modal-actions-right" style="margin-top:12px;">
                  <button type="button" class="btn" onclick="window.location.href='../pages/new_summary.php?update=1&timeline_id=<?= htmlspecialchars($timeline_id) ?>';">Cancel</button>
                  <button type="submit" class="btn primary" 
                          name="redirect_page" value="../pages/new_summary.php?update=1&timeline_id=<?= htmlspecialchars($timeline_id) ?>">Save</button>
                </div> 
              </div>  
            </form>
          </div>
        </section>
    </div>
    <?php require __DIR__ . '/../includes/footer.php'; ?>
  </main>
<script>
  // Find currently selected location
  const selectedInput = document.getElementById('selected_location_id');
  let initialSelected = selectedInput ? (selectedInput.value || '') : '';
  let pendingDestination = null;

  // Check for unsaved changes
  function hasUnsavedChanges() {
    if (!selectedInput) return false;
    return (selectedInput.value || '') !== String(initialSelected);
  }

  // Mark initial selection on load
  window.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.select-location').forEach(el => {
      if (el.dataset.id === String(initialSelected)) {
        el.classList.add('selected');
        if (el.type === 'radio') {
          el.checked = true;
        }
      }
    });
  });

  // Handle user radio clicks
  document.addEventListener('click', function (event) {
    const radio = event.target.closest('.select-location');
    if (!radio) return;

    const locationId = radio.dataset.id || '';

    // Update hidden input value
    if (selectedInput) {
      selectedInput.value = locationId;
    }

    // Visual highlight
    document.querySelectorAll('.select-location').forEach(r => {
      r.classList.toggle('selected', r === radio);
    });
  });

  // Intercept buttons that use redirect_page
  document.addEventListener('click', function (e) {
    const a = e.target.closest('a[name="redirect_page"]');
    if (!a) return;

    const dest = a.getAttribute('value') || '';

    if (!hasUnsavedChanges()) {
      // No unsaved changes continue
      if (dest) {
        e.preventDefault();
        window.location.href = dest;
      }
      return;
    }


    // Unsaved changes open confirm modal
    e.preventDefault();
    pendingDestination = dest || null;

    const modal = document.getElementById('unsaved_new_locations_modal');
    if (modal) modal.classList.add('open');
  });

  // Modal leave without saving / save selections
  document.addEventListener('click', function (event) {
    const btn = event.target.closest('#unsaved_new_locations_modal [data-action]');
    if (!btn) return;

    const actionValue = btn.getAttribute('data-action');
    const form = document.getElementById('unsaved_locations_form');
    if (!form) return;

    const modalAction = document.getElementById('modal_action');
    const modalRedirectPage = document.getElementById('modal_redirect_page');
    const modalLocationId = document.getElementById('modal_location_id');

    if (modalAction)       modalAction.value       = actionValue;
    if (modalRedirectPage) modalRedirectPage.value = pendingDestination || '../pages/timeline.php';
    if (modalLocationId && selectedInput) modalLocationId.value = selectedInput.value || '';

    // close modal
    const modal = document.getElementById('unsaved_new_locations_modal');
    if (modal) modal.classList.remove('open');

    form.submit();
  });
</script>
<script src="../assets/js/modal.js"></script>
</body>
</html>
