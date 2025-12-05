<?php
/* Storyteller Database Timeline CRUD - ../crud/timeline.php
      Handles Create, Read, Update, Delete operations for timeline entries.
      - expects POST requests with action parameter: create, update, delete, resequence
      - returns JSON responses indicating success or failure 

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - added redirect after operations to return to timeline page
      - improved error handling and validation (try catch)
      - set content-type to application/json for API responses
      - added support for updating associated characters for timeline entries
      - added support for updating associated locations for timeline entries
*/
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/db_config.php';
require __DIR__ . '/../includes/functions.php';

/*echo '<pre>';
print_r($_POST);
echo '</pre>';
exit;*/

// Get form data
$action         = $_POST['action'] ?? '';
$story_id       = (int)($_SESSION['story_id'] ?? 0);
$user_id        = (int)($_SESSION['user_id'] ?? 1);
$timeline_id    = (int)($_POST['timeline_id'] ?? null);
$title          = $_POST['title'] ?? '';
$summary        = $_POST['summary'] ?? '';
$location_id    = (int)($_POST['location_id'] ?? null);
$sequence_no    = (int)($_POST['sequence_no'] ?? 0);
$entry_type     = $_POST['entry_type'] ?? 'event';
$redirect_page  = $_POST['redirect_page'] ?? '../pages/timeline.php';

$character_data = $_POST['character_data'] ?? []; 
if (is_string($character_data)) {
    $character_data = array_filter(array_map('intval', explode(',', $character_data)));
}

try {
  // Create new timeline entry
  if ($action === 'create') {
    $stmt = $conn->prepare("INSERT INTO timeline (story_id, user_id, title, summary, location_id, sequence_no, entry_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssis", $story_id, $user_id, $title, $summary, $location_id, $sequence_no, $entry_type);
    $stmt->execute();
    if ($stmt->error) {
        throw new Exception($stmt->error);
    }
    $timeline_id = (int)$conn->insert_id;
    $_SESSION['timeline_id'] = $timeline_id;
    $_SESSION['timeline_title'] = $title;
    $success = "'" . $title . "'was created successfully.</h2>";
    redirect_page($redirect_page, $success, []);

  // Update entire existing timeline entry  
  } elseif ($action === 'update') {
    $stmt = $conn->prepare("UPDATE timeline SET title=?, summary=?, location_id=?, sequence_no=?, entry_type=? WHERE timeline_id=?");
    $stmt->bind_param("sssisi", $title, $summary, $location_id, $sequence_no, $entry_type, $timeline_id);
    $stmt->execute();
    if ($stmt->error) {
      throw new Exception("Error updating timeline entry: " . $stmt->error);
    }
    $success = "'" . $title . "' was updated successfully.</h2>";
    redirect_page($redirect_page, $timeline_id, $success, []);
  // Update only location of existing timeline entry
  } elseif ($action === 'update_location') {
    $stmt = $conn->prepare("UPDATE timeline SET location_id=? WHERE timeline_id=?");
    $stmt->bind_param("ii", $location_id, $timeline_id);
    $stmt->execute();
    if ($stmt->error) {
      throw new Exception("Error updating timeline entry: " . $stmt->error);
    }
    $success = "'" . $title . "' was updated successfully.</h2>";
    redirect_page($redirect_page, $success, []);

  }elseif ($action === 'update_characters') {
    // Delete existing character associations
    $stmt_del = $conn->prepare("DELETE FROM timeline_characters WHERE timeline_id=?");
    $stmt_del->bind_param("i", $timeline_id);
    $stmt_del->execute();
    if ($stmt_del->error) {
      throw new Exception("Error clearing timeline characters: " . $stmt_del->error);
    }
    // Insert new character associations
    if (!empty($character_data)) {
      $stmt_ins = $conn->prepare("INSERT INTO timeline_characters (timeline_id, character_id, story_id) 
                                  VALUES (?, ?, ?)");
      foreach ($character_data as $char_id) {
        $stmt_ins->bind_param("iii", $timeline_id, $char_id, $story_id);
        $stmt_ins->execute();
        if ($stmt_ins->error) {
          throw new Exception("Error adding timeline character: " . $stmt_ins->error);
        }
      }
    }
    $success = "'" . $title . "' characters updated successfully.</h2>";
    redirect_page($redirect_page, $success, []);

  // Delete existing timeline entry
  } elseif ($action === 'delete') {
    $stmt = $conn->prepare("DELETE FROM timeline WHERE timeline_id=?");
    $stmt->bind_param("i", $timeline_id);
    $stmt->execute();
    if ($stmt->error) {
      throw new Exception("Error deleting timeline entry: " . $stmt->error);
    }
    $success = "'" . $title . "' was deleted successfully.</h2>";
    redirect_page($redirect_page, $success, []);
    
  // Resequence timeline entries
  } elseif ($action === 'update_sequence') {
    // expects POST: entry_order[] in new order
    $order_data = $_POST['entry_order'] ?? '';
    $pairs = is_string($order_data) ? explode(',', $order_data) : $order_data;
    $stmt = $conn->prepare("UPDATE timeline SET sequence_no=? WHERE timeline_id=? AND story_id=?");
    foreach ($pairs as $pair) {
      [$id, $seq] = array_map('intval', explode(':', $pair));
      if ($id > 0 && $seq > 0) {
        $stmt->bind_param("iii", $seq, $id, $story_id);
        $stmt->execute();
        if ($stmt->error) {
          throw new Exception("Error resequencing timeline: " . $stmt->error);
        }
      }
    }
    $success = "'Timeline' was reordered successfully.</h2>";
    redirect_page('../pages/timeline.php', $success, []);

  // Redirect if no changes on new_locations.php
  } elseif ($action === 'no_change') {
    redirect_page($redirect_page, '', []);
  } else {
    throw new Exception('Unknown action');
  }
} catch (Exception $e) {
  $errors = ["Error: " . $e->getMessage()];
  redirect_page($redirect_page, '', $errors);
}
?>
