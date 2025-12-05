
<?php
/* Storyteller Database Characters CRUD - ../crud/characters.php
      Handles Create, Read, Update, Delete operations for character entries.
      - expects POST requests with action parameter: create, update, delete
      - returns JSON responses indicating success or failure 

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - added redirect after operation to function
      - improved error handling and validation (try catch)
  */
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/db_config.php';
require __DIR__ . '/../includes/functions.php';

// Get form data
$action         = $_POST['action'] ?? '';
$user_id        = (int)($_SESSION['user_id'] ?? 1);
$story_id       = (int)($_POST['story_id'] ?? 0);
$character_id   = (int)($_POST['character_id'] ?? 0);
$name           = trim($_POST['name'] ?? '');
$age            = (int)($_POST['age'] ?? 0);
$description    = trim($_POST['description'] ?? '');
$notes          = trim($_POST['notes'] ?? '');
$last_page      = $_POST['last_page'] ?? '';
$redirect_page  = $_POST['redirect_page'] ?? '../pages/characters.php';

try {
  if ($action === 'create') {
    $stmt = $conn->prepare("INSERT INTO characters (story_id, user_id, name, age, description, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissis", $story_id, $user_id, $name, $age, $description, $notes);
    $stmt->execute();
    if ($stmt->error) {
        throw new Exception("Error creating story: " . $stmt->error);
    }
    $stmt->close();
    $success = "'" . htmlspecialchars($name) . "' was created successfully.";
    redirect_page($redirect_page, $success, []);
  } elseif ($action === 'update') {
    $stmt = $conn->prepare("UPDATE characters SET name=?, age=?, description=?, notes=?, updated_at=NOW() WHERE character_id=?");
    $stmt->bind_param("sissi", $name, $age, $description, $notes, $character_id);
    $stmt->execute();
    if ($stmt->error) {
        throw new Exception("Error updating story: " . $stmt->error);
    }
    $stmt->close();
    $success = "'" . htmlspecialchars($name) . "' was updated successfully.";
    redirect_page($redirect_page, $success, []);
  } elseif ($action === 'delete') {
    $stmt = $conn->prepare("DELETE FROM characters WHERE character_id=?");
    $stmt->bind_param("i", $character_id);
    $stmt->execute();
    if ($stmt->error) {
        throw new Exception("Error deleting story: " . $stmt->error);
    }
    $stmt->close();
    $success = "Character was deleted successfully.";
    redirect_page($redirect_page, $success, []);
  } elseif (!$action) {
    throw new Exception('Unknown action');
  }
} catch (Exception $e) {
  $errors = ["Error: " . $e->getMessage() . ""];
  redirect_page($redirect_page, '', $errors);
}
?>

