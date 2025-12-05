<?php
/* Storyteller Database New Entry Character Management Page - new_characters.php
      Page to display and manage characters for the selected story.
      - checks session timeout and user authentication
      - fetches characters from the database for the selected story
      - displays characters in a table with options to add, edit, delete
      - includes modals for character creation, update, and deletion 
      - allows selection of characters for an Timeline Entry (plot or event)
      - uses conditional modals to warn about unsaved changes
      - integrates with CRUD endpoint to save character selections

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      
  */

// Initialize session and functions
require_once __DIR__ . '/../includes/init.php';

// Verify if story is selected, set story title and fetch characters
$story_id = isset($_SESSION['story_id']) ? (int)$_SESSION['story_id'] : 
            redirect_page('../pages/new_story.php', '', ['error_message' => 'Please create a new entry before managing characters.']); 
$characters = getAllCharsByStory($conn, $story_id);
$title = $_SESSION['story_title'] ?? 'No Title Selected';
$timeline_id = (int)($_SESSION['timeline_id'] ?? 0);
$rows = getTimelineEntryData($conn, $timeline_id);
  $entry_title = $rows['title'] ?? '';
  $selectedCharacterIds = getEntryCharID($conn, $timeline_id) ? : [];
  $initialSelectedCharacters = implode(',', $selectedCharacterIds);

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
  </style>
  <title>New / Update Entry Characters Select</title>
</head>
<body>
  <main role="main">
    <div class="card" style="max-width: 900px; margin-left: auto; margin-right: auto; margin-top: 24px;">
      <?= showMessages(); ?> <!-- Display any success or error messages -->
      <div class="<?= $update ? '' : 'hidden' ?>">
        <h2>Update characters for <?= htmlspecialchars($title) ?></h2>
        <p>Make any changes below then click "Save" or "Cancel" to return to the Entry Summary.</p>
      </div>
      <div class="<?= !$update ? '' : 'hidden' ?>">
        <h2>Select Characters for "<?= htmlspecialchars($entry_title) ?>"</h2>
        <p>Characters give your story width, depth, and emotional resonance. This page helps you create and 
          refine the people who will bring your narrative to life.</p>
        <h2>Let's get started ....</h2>
      </div>
      <p> Below is a table that displays the characters created for "<?= htmlspecialchars($title) ?>". To assign 
        or unassign a character to "<?= htmlspecialchars($entry_title) ?>" use the checkbox on each row. For new 
        characters, use the "Add Character" button to begin.</p>
      <div class="two-column-grid">
        <section class="grid-item header card">
          <form id="entry_characters_form" method="post" action="../crud/timeline.php">
            <input type="hidden" name="action" value="update_characters">
            <input type="hidden" name="timeline_id" value="<?= (int)$timeline_id ?>">
            <?php if ($update){ 
            echo '<input type="hidden" name="redirect_page"
              value="../pages/new_summary.php?update=1&timeline_id=' . (int)$timeline_id . '">';
            } else{
              echo '<input type="hidden" name="redirect_page" value="../pages/new_summary.php">';
            }
            ?>
            <input type="hidden" name="character_data" id="form_character_data" value="">

            <div class="toolbar" style="max-width: 600px; margin-left: auto; margin-right: auto;">
              <input class="input" placeholder="Search characters..." />
              <!-- use data attribute that matches the modal input name (story_id) so modal.js populates it -->
              <button class="btn primary" data-open="#character_create_modal" 
                      data-story_id="<?= htmlspecialchars($story_id); ?>" 
                      data-redirect_page="../pages/new_characters.php">Add Character</button>
            </div>
            
            <!-- Hidden container to hold selected character IDs -->
            <input type="hidden" id="selected_characters" name="character_ids" value="<?= htmlspecialchars($initialSelectedCharacters) ?>"/>
                
            <table style="width:100%; border-collapse:collapse;">
              <thead>
                <tr>
                  <th style="text-align:left; padding:6px;"><input type="checkbox" id="select_all_characters" /> Select All</th>
                  <th style="text-align:left; padding:6px;">Name</th>
                  <th style="text-align:left; padding:6px;">Age</th>
                  <th style="text-align:left; padding:6px;">Description</th>
                  <th style="text-align:left; padding:6px;">Notes</th>
                  </tr>
              </thead>
              <tbody id="character_rows">
                <?php
                  if (empty($characters)) {
                    echo "<tr><td colspan='7' style='text-align:center;'>No Characters added. Click 'Add Plot Point' to get started.</td></tr>";
                  } else {
                    foreach ($characters as $character) {
                      $character_id = (int)$character['character_id'];
                      $character_name = htmlspecialchars($character['name'], ENT_QUOTES);
                      $character_age = (int)$character['age'];
                      $character_desc = htmlspecialchars($character['description']);
                      $character_notes = htmlspecialchars($character['notes']);
                      // Check if this character is selected for the plot point
                      $isChecked = in_array($character_id, $selectedCharacterIds, true) ? 'checked' : '';
                      echo "<tr>";
                      echo "<td style='padding:6px; vertical-align:top;'><input type='checkbox' class='select-character' data-id='" . $character_id . "' " . $isChecked . " /></td>";
                      echo "<td style='padding:6px; vertical-align:top;'>" . $character_name . "</td>";
                      echo "<td style='padding:6px; vertical-align:top;'>" . $character_age . "</td>";
                      echo "<td style='padding:6px; vertical-align:top;'>" . $character_desc . "</td>";
                      echo "<td style='padding:6px; vertical-align:top;'>" . $character_notes . "</td>";
                      echo "</tr>";
                    }
                  }
                ?>
              </tbody>
            </table>  
            
            <div class="<?= !$update ? '' : 'hidden' ?>">
              <div><p>Click continue to review your New Timeline Entry. </p></div>
              <div><p>Or you can "Exit to the Dashboard" to leave without saving...</p></div>    
              <div class = "modal-actions-right" style="margin-top:12px;">
                <button type="button" class="btn" onclick="window.location.href='../pages/dashboard.php';">Exit to Dashboard</button>
                <button type="button" class="btn <?= !$new_user ? '' : 'hidden' ?>" onclick="window.location.href='../pages/timeline.php';">Cancel</button>
                <button id="save_entry_characters" class="btn primary" type="button">Continue</button>
              </div>
            </div>
            <div class="<?= $update ? '' : 'hidden' ?>">
              <div class="modal-actions-right" style="margin-top:12px;">
                <button type="button" class="btn" onclick="window.location.href='../pages/new_summary.php?update=1&timeline_id=<?= htmlspecialchars($timeline_id) ?>';">Cancel</button>
                <button type="button" id="save_entry_characters_update" class="btn primary" type="button">Save</button>
              </div> 
            </div>  
          </form>
        </section>
      </div>
    </div>
    <?php require __DIR__ . '/../includes/footer.php'; ?>
  </main>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Selected characters management
    const selectedHidden   = document.getElementById('selected_characters'); 
    const selectAllBox     = document.getElementById('select_all_characters');
    
    const form             = document.getElementById('entry_characters_form');  
    const formCharData     = document.getElementById('form_character_data');    

    const saveBtnNew       = document.getElementById('save_entry_characters');         
    const saveBtnUpdate    = document.getElementById('save_entry_characters_update');  

    let initialSelected    = selectedHidden ? (selectedHidden.value || '') : '';
    let unsavedChanges     = false;
  

    // Functions to update selected characters for select all and individual checkboxes
    function updateSelectedCharacters() {
      const ids           = [];
      const allCheckboxes = document.querySelectorAll('.select-character');
      
      // Gather all checked character IDs
      allCheckboxes.forEach(cb => {
        if (cb.checked) {
          ids.push(cb.dataset.id);
        }
      });
      // Update hidden input value comma separated
      if (selectedHidden) {
        selectedHidden.value = ids.join(',');
      }

      // Update "Select All"
      if (selectAllBox) {
        if (allCheckboxes.length > 0) {
          selectAllBox.checked = (ids.length === allCheckboxes.length);
        } else {
          selectAllBox.checked = false;
        }
      }
      // Check for unsaved changes
      const current = selectedHidden ? (selectedHidden.value || '') : '';
      unsavedChanges = (current !== initialSelected);
    }

    // Submit the form to timeline.php with the current character selection
    function submitCharacterForm() {
      if (!form || !formCharData || !selectedHidden) return;

      // Ensure selectedHidden reflects the latest checkbox state
      updateSelectedCharacters();

      // Move the selected character IDs to the form hidden input
      formCharData.value = selectedHidden.value || '';

      // Reset unsavedChanges flag
      unsavedChanges = false;

      form.submit();
    }

    // Make sure selectedHidden reflects the current checked boxes
    updateSelectedCharacters();
    initialSelected = selectedHidden ? (selectedHidden.value || '') : '';
    unsavedChanges  = false;

    // Helper for individual checkboxes and Select All
    document.addEventListener('change', function (event) {
      const target = event.target;

      // Individual character checkbox
      if (target.classList && target.classList.contains('select-character')) {
        updateSelectedCharacters();
        return;
      }

      // "Select All" checkbox
      if (target.id === 'select_all_characters') {
        const isChecked = target.checked;
        document.querySelectorAll('.select-character').forEach(cb => {
          cb.checked = isChecked;
        });
        updateSelectedCharacters();
        return;
      }
    });

    // Handle "Continue" (new entry flow)
    if (saveBtnNew) {
      saveBtnNew.addEventListener('click', function (e) {
        e.preventDefault();
        submitCharacterForm();
      });
    }

    // Handle "Save" (update existing entry flow)
    if (saveBtnUpdate) {
      saveBtnUpdate.addEventListener('click', function (e) {
        e.preventDefault();
        submitCharacterForm();
      }); 
    }
  });

</script>
<script src="../assets/js/modal.js"></script>
</body>
</html>