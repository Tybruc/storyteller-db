<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php 
  
  require_once __DIR__ . '/../includes/session.php';
  require_once __DIR__ . '/../includes/db_config.php';
  //require_once __DIR__ . '/../includes/functions.php';
  require_once __DIR__ . '/../includes/data_functions.php';
  //require_once __DIR__ . '/../includes/header.php';
  //include_once __DIR__ . '/../partials/modals/header_modals.php';
  //include_once __DIR__ . '/../partials/modals/character_modals.php';
  //include_once __DIR__ . '/../partials/modals/location_modals.php';
  //include_once __DIR__ . '/../partials/modals/story_modals.php';
  //include_once __DIR__ . '/../partials/modals/timeline_modals.php'; 
  
  
  
  
  ?>
  <main>
    <h1>Test Data_Functions</h1>
    <h2>By User_ID</h2>
    <!-- Test getUserData function -->
    <h3>1 Testing getUserData()</h3>
    <?php
    $test_user_id = 1; // Change as needed for testing
    $user_data = getUserData($conn, $test_user_id);
    var_dump($user_data);
    ?>
    <!-- Test getAllStoriesByUser() function -->
    <h3>2 Testing getAllStoriesByUser()</h3>
    <?php
    $story_data = getAllStoriesByUser($conn, $test_user_id);
    var_dump($story_data);
    ?>
    <!-- Test getAllStoriesByUser() function -->
    <h3>3 Testing getAllCharsByUser()</h3>
    <?php
    $character_data = getAllCharsByUser($conn, $test_user_id);
    var_dump($character_data);
    ?>
    <!-- Test getALLStoriesByUsera function -->
    <h3>4 Testing getAllLocsByUser()</h3>
    <?php
    $location_data = getAllLocsByUser($conn, $test_user_id);
    var_dump($location_data);
    ?>
    <!-- Test getALLStoriesByUsera function -->
    <h3>5 Testing getRecentStor()</h3>
    <?php
    $story_data = getRecentStor($conn, $test_user_id, 5);
    var_dump($story_data);
    ?>
    <h2>By Story_ID</h2>
    <!-- Test getStoryData function -->
    <h3>6 Testing getStoryData()</h3>
    <?php
    $test_story_id = 1; // Change as needed for testing
    $story_data = getStoryData($conn, $test_story_id);
    var_dump($story_data);
    ?>
    <!-- Test getAllCharsByStory() function -->
    <h3>7 Testing getAllCharsByStory()</h3>
    <?php
    $character_data = getAllCharsByStory($conn, $test_story_id);
    var_dump($character_data);
    ?>
    <!-- Test getAllLocsByStory() function -->
    <h3>8 Testing getAllLocsByStory()</h3>
    <?php
    $location_data = getAllLocsByStory($conn, $test_story_id);
    var_dump($location_data);
    ?>
    <!-- Test getTopCharsByStory() function -->
    <h3>9 Testing getTopCharsByStory()</h3>
    <?php
    $character_data = getTopCharsByStory($conn, $test_story_id);
    var_dump($character_data);
    ?>
    <!-- Test getRecentLocsByStory() function -->
    <h3>10 Testing getRecentLocsByStory()</h3>
    <?php
    $location_data = getRecentLocsByStory($conn, $test_story_id);
    var_dump($location_data);
    ?>
    <!-- Test getRecentTimeEntriesByStory() function -->
    <h3>11 Testing getRecentTimeEntriesByStory()</h3>
    <?php
    $entry_data = getRecentTimeEntriesByStory($conn, $test_story_id);
    var_dump($entry_data);
    ?>
    <h2>Character Functions</h2>
    <!-- Test getEntryCharID() function -->
    <h3>12 Testing getEntryCharID()</h3>
    <?php
    $test_timeline_id = 1; // Change as needed for testing
    $char_ids = getEntryCharID($conn, $test_timeline_id);
    var_dump($char_ids);
    ?>
    <!-- Test getCharacterName() function -->
    <h3>13 Testing getCharName()</h3>
    <?php
    $test_char_id = 1;
    $char_name = getCharName($conn, $test_char_id);
    var_dump($char_name);
    ?>
    <!-- Test getLocName() function -->
    <h3>14Testing getLocName()</h3>
    <?php
    $test_location_id = 1;
    $loc_name_data = getLocName($conn, $test_location_id);
    var_dump($loc_name_data);
    ?>
  </main>
  <?php require __DIR__ . '/../includes/footer.php'; ?>  




</body>
</html>