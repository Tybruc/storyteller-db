
<?php
/* Storyteller Database Locations CRUD - ../crud/locations.php
      Handles Create, Read, Update, Delete operations for location entries.
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
$location_id    = (int)($_POST['location_id'] ?? 0);
$name           = $_POST['name'] ?? '';
$description    = $_POST['description'] ?? '';
$redirect_page  = $_POST['redirect_page'] ?? '../pages/locations.php';

try {
    if ($action === 'create') {
        $stmt = $conn->prepare("INSERT INTO LOCATIONS (STORY_ID, USER_ID, NAME, DESCRIPTION) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $story_id, $user_id, $name, $description);
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Error creating location: " . $stmt->error);
        }
        $stmt->close();
        $success = "Location '" . htmlspecialchars($name) . "' was created successfully.";
        redirect_page($redirect_page, $success, []);
        
    } elseif ($action === 'update') {
        $stmt = $conn->prepare("UPDATE LOCATIONS SET NAME=?, DESCRIPTION=?, UPDATED_AT=NOW() WHERE LOCATION_ID=?");
        $stmt->bind_param("ssi", $name, $description, $location_id);
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Error updating location: " . $stmt->error);
        }
        $stmt->close();
        $success = "Location '" . htmlspecialchars($name) . "' updated successfully.";
        redirect_page($redirect_page, $success, []);
        
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM LOCATIONS WHERE LOCATION_ID=?");
        $stmt->bind_param("i", $location_id);
        $stmt->execute();
        if ($stmt->error) {
            throw new Exception("Error deleting location: " . $stmt->error);
        }
        $stmt->close();
        $success = "Location deleted successfully.";
        redirect_page($redirect_page, $success, []);
        
    } elseif (!$action) {
        throw new Exception('Unknown action');
    }
} catch (Exception $e) {
    $errors = ["Error: " . $e->getMessage() . ""];
    redirect_page($redirect_page, '', $errors);
}
?>