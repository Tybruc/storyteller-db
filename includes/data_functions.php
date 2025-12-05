<!-- Storyteller Database Read Functions - ..includes/data_functions.php
      Provides functions to fetch user, story, character, location, and timeline data.
      - used by various pages to retrieve and display data

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - added functions to get timeline entries and associated characters
      - improved data retrieval functions
      - added comments for clarity
  -->

<?php  
/* Storyteller Database Read Functions - ..includes/data_functions.php
      Provides functions to fetch user, story, character, location, and timeline data.
      - used by various pages to retrieve and display data

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - added functions to get timeline entries and associated characters
      - improved data retrieval functions
      - added comments for clarity
  */
/* BY USER ID*/
  // Fetch user data from a given user id
  function getUserData($conn, $user_id) {
    $stmt = $conn->prepare("SELECT email, first_name, last_name, role, password FROM USERS WHERE USER_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();
    return $user_data;
  } 
  function countStoriesByUser($conn, $user_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS story_count FROM STORIES WHERE USER_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['story_count'] ?? 0;
  }

  // Fetch all stories for a given user id
  function getAllStoriesByUser($conn, $user_id) {
    $stmt = $conn->prepare("SELECT story_id, title, genre, synopsis, last_activity FROM stories WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stories = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $stories;
  }

  // Find number of characters by user id
  function countCharsByUser($conn, $user_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS character_count FROM CHARACTERS WHERE USER_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['character_count'] ?? 0;
  }
    // Fetch all character data for a given user id
  function getAllCharsByUser($conn, $user_id) {
    $stmt = $conn->prepare("SELECT character_id, name, age, description, notes FROM CHARACTERS 
                            WHERE USER_ID = ? ORDER BY name ASC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $characters = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $characters;
  }
  
  // Find number of locations by user id
  function countLocsByUser($conn, $user_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS location_count FROM LOCATIONS WHERE USER_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['location_count'] ?? 0;
  }
// Fetch all location data for a given user id
  function getAllLocsByUser($conn, $user_id) {
    $stmt = $conn->prepare("SELECT location_id, name, description FROM LOCATIONS 
                            WHERE USER_ID = ? ORDER BY name ASC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $locations = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $locations;
  }

// Fetch recent stories for a given user id
  function getRecentStor($conn, $user_id, $limit = 5) {
    $stmt = $conn->prepare("SELECT story_id, title, last_activity FROM STORIES WHERE USER_ID = ? 
                            ORDER BY last_activity DESC LIMIT ?");
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $recent_stories = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $recent_stories;
  }

/* BY STORY ID*/
  // Fetch story data from a given story id
  function getStoryData($conn, $story_id) {
    $stmt = $conn->prepare("SELECT title, genre, synopsis, created_at, last_activity FROM STORIES WHERE STORY_ID = ?");
    $stmt->bind_param("i", $story_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $story_data = $result->fetch_assoc();
    $stmt->close();
    return $story_data;
  }
  // Find number of locations by story id
  function countCharsByStory($conn, $story_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS character_count FROM CHARACTERS WHERE STORY_ID = ?");
    $stmt->bind_param("i", $story_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['character_count'] ?? 0;
  }
  // Fetch all character data for a given story id
  function getAllCharsByStory($conn, $story_id) {  
    $stmt = $conn->prepare("SELECT character_id, name, age, description, notes 
                            FROM CHARACTERS WHERE STORY_ID = ? ORDER BY name ASC");
    $stmt->bind_param("i", $story_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $characters = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $characters;
  }
  
  // Find number of locations by story id
  function countLocsByStory($conn, $story_id) { 
    $stmt = $conn->prepare("SELECT COUNT(*) AS location_count FROM LOCATIONS WHERE STORY_ID = ?");
    $stmt->bind_param("i", $story_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['location_count'] ?? 0;
  }
  // Fetch all location data for a given story id
  function getAllLocsByStory($conn, $story_id) {  
    $stmt = $conn->prepare("SELECT location_id, name, description 
                            FROM LOCATIONS WHERE STORY_ID = ? ORDER BY name ASC");
    $stmt->bind_param("i", $story_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $locations = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $locations;
  }
  // Fetch all Timeline entries for a given story ID
  function getAllTimeEntriesByStory($conn, $story_id) {
  $stmt = $conn->prepare("SELECT timeline_id, title, summary,  location_id, sequence_no, entry_type, created_at
                          FROM TIMELINE WHERE STORY_ID = ? ORDER BY sequence_no ASC");
  $stmt->bind_param("i", $story_id);
  $stmt->execute();
  $timeline_entries = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $timeline_entries;
  } 

  // Find top 5 most used characters for a given story ID
  function getTopCharsByStory($conn, $story_id) {
  $stmt = $conn->prepare("SELECT character_id, COUNT(timeline_id) AS entry_count FROM timeline_characters 
                          WHERE story_id = ? GROUP BY character_id ORDER BY entry_count DESC LIMIT 5;");
  $stmt->bind_param("i", $story_id);
  $stmt->execute();
  $top_characters = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  if (empty($top_characters)) {
      return [];
  }
  $character_ids = array_column($top_characters, 'character_id');
  foreach ($character_ids as $id) {
      $character_names[] = getCharName($conn, $id);
  }
  return $character_names;
  }

  // Find 5 most recently added locations for a given story ID
  function getRecentLocsByStory($conn, $story_id) {
  $stmt = $conn->prepare("SELECT name FROM locations 
                          WHERE story_id = ? ORDER BY created_at DESC LIMIT 5;");
  $stmt->bind_param("i", $story_id);
  $stmt->execute();
  $top_locations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $top_locations;
  }

  // Find 10 most recent timeline entries for a given story ID
  function getRecentTimeEntriesByStory($conn, $story_id) {   
    $stmt = $conn->prepare("SELECT timeline_id, title, summary, sequence_no, entry_type, location_id, updated_at 
                            FROM timeline WHERE story_id = ? ORDER BY updated_at DESC LIMIT 10;");
    $stmt->bind_param("i", $story_id);
    $stmt->execute();
    $recent_timeline_entries = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $recent_timeline_entries;
  }

  // Fetch timeline entry data from a given timeline ID
  function getTimelineEntryData($conn, $timeline_id) {
    $stmt = $conn->prepare("SELECT title, summary, sequence_no, location_id, entry_type 
                            FROM TIMELINE WHERE timeline_id = ?");
    $stmt->bind_param("i", $timeline_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $entry_data = $result->fetch_assoc() ?: [];
    $stmt->close();
    return $entry_data;
  }

/* TIMELINE, CHARACTERS & LOCATIONS*/
  // Function to find character IDs associated with a given timeline timeline ID
  function getEntryCharID($conn, $timeline_id) {
  $stmt = $conn->prepare("SELECT CHARACTER_ID FROM TIMELINE_CHARACTERS WHERE TIMELINE_ID = ?");
  $stmt->bind_param("i", $timeline_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $characters = [];
  while ($row = $result->fetch_assoc()) {
    $characters[] = $row['CHARACTER_ID'];
  }
  return $characters;
  }

  // Function to get character name by character ID
  function getCharName($conn, $character_ids) {
    $stmt = $conn->prepare("SELECT NAME FROM CHARACTERS WHERE CHARACTER_ID = ?");
    $stmt->bind_param("i", $character_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['NAME'] : 'Unknown Character';
  }

  // Function to get comma-separated character names for a given timeline timeline ID
  function getEntryCharNames($conn, $timeline_id) {
    $character_ids = getEntryCharID($conn, $timeline_id);
    $character_names = [];
    if (!empty($character_ids)) {
        foreach ($character_ids as $char_id) {
            $character_names[] = getCharName($conn, $char_id);
        }
        return implode(", ", $character_names);
    }
    return 'No Characters';
  }

  // Function to get location name by location ID
  function getLocName($conn, $location_id) {
    $stmt = $conn->prepare("SELECT NAME FROM LOCATIONS WHERE LOCATION_ID = ?");
    $stmt->bind_param("i", $location_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['NAME'] : 'Unknown Location';
  }
?>


