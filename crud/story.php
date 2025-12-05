<?php
/* Storyteller Database Story CRUD - ../crud/story.php
      Handles Create, Read, Update, Delete operations for story entries.
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
$user_id        = $_SESSION['user_id'] ?? 1;
$story_id       = (int)($_POST['story_id'] ?? 0);
$title          = $_POST['title'] ?? '';
$genre          = $_POST['genre'] ?? ''; 
$synopsis       = $_POST['synopsis'] ?? '';
$redirect_page  = $_POST['redirect_page'] ?? '../pages/stories.php';
$nextPage       = (int)($_POST['nextPage'] ?? 0);

try {
  // Create story
  if ($action === 'create') {
    $stmt = $conn->prepare("INSERT INTO STORIES (USER_ID, TITLE, SYNOPSIS, GENRE) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $synopsis, $genre);
    $stmt->execute();
    if ($stmt->error) {
      throw new Exception("Error creating story: " . $stmt->error);
    }
      
      $story_id = (int)($conn->insert_id);
      $_SESSION['story_id'] = $story_id;
      $_SESSION['story_title'] = $title;
      $success = "'" . $title . "' was created successfully.</h2>";
      redirect_page($redirect_page, $success, []);

  } elseif ($action === 'update') {
    $stmt = $conn->prepare("UPDATE STORIES SET TITLE=?, GENRE=?, SYNOPSIS=?, UPDATED_AT=NOW() WHERE STORY_ID=?");
    $stmt->bind_param("sssi", $title, $genre, $synopsis, $story_id);
    $stmt->execute();
    if ($stmt->error) {
      throw new Exception("Error updating story: " . $stmt->error);
    }
      $success = "'" . $title . "' was updated successfully.";
      redirect_page($redirect_page, $success, []);

  } elseif ($action === 'delete') {
    $stmt = $conn->prepare("DELETE FROM STORIES WHERE STORY_ID=?");
    $stmt->bind_param("i", $story_id);
    $stmt->execute();
    if ($stmt->error) {
      throw new Exception("Error deleting story: " . $stmt->error);
    }
      $success = "Story was deleted successfully.";
      redirect_page($redirect_page, $success, []);
    
  } elseif ($action === 'select') {
    $stmt = $conn->prepare("SELECT TITLE FROM STORIES WHERE STORY_ID=?");
    $stmt->bind_param("i", $story_id);
    $stmt->execute();
    if ($stmt->error) {
      throw new Exception("Error selecting story: " . $stmt->error);
    }
    $result = $stmt->get_result()->fetch_assoc();
    $title = $result['TITLE'] ?? 'No Title';
    $_SESSION['story_title'] = $title;
    $_SESSION['story_id'] = $story_id;
    $success = "'" . $_SESSION['story_title'] . "' was selected.";
    redirect_page($redirect_page, $success, []);
  } 
} catch (Exception $e) {
    $errors = ["Error: " . $e->getMessage() . ""];
    redirect_page($redirect_page, '', $errors);
}
?>