<?php
include __DIR__ . '/../partials/modals/header_modals.php';

// auto-detect current page name (e.g. "dashboard")
$activePage = $_SESSION['page'] ?? basename($_SERVER['PHP_SELF'], '.php');

// Define navigation links
$links = [
  'dashboard'   => '../pages/dashboard.php?clear_msgs=1',
  'stories'     => '../pages/stories.php?clear_msgs=1',
  'characters'  => '../pages/characters.php?clear_msgs=1',
  'locations'   => '../pages/locations.php?clear_msgs=1',
  'timeline'    => '../pages/timeline.php?clear_msgs=1',
  'google_drive' => "javascript:window.open('https://drive.google.com/drive','_blank')",
];

$showNav = array_key_exists($activePage, $links);
?>


<!-- Header Section -->
<header>
  <a href="../index.php" style="text-decoration:none; color:inherit;">
    <h1>Storyteller Database</h1>
  </a>
  <div class="headerNav">
    <?php if (isset($_SESSION['story_title'])) {
      echo "<span style=\"padding-right: 10px;\"> Story: " . htmlspecialchars($_SESSION['story_title']) . "</span>";
    } else {
      echo "<span style=\"padding-right: 10px;\"> Story: Not Selected (User Mode)</span>";
    }?>
    |
    <?php if (isset($_SESSION['user'])) {
      echo "<span style=\"padding-right: 10px; padding-left: 10px;\">Welcome, " . htmlspecialchars($_SESSION['user']) . "</span>";
    }?>
    <!-- Log Out button opens logout modal -->
    <button class="btn" data-open="#logout_modal">Log Out</button>
  </div>
</header>

<script src="../assets/js/modal.js"></script>

<div class="app">
<!-- Sidebar Navigation -->
  <nav class="<?= $showNav ? '' : 'hidden' ?>" id="sidebar" aria-label="Primary">
    <?php 
    foreach ($links as $key => $href): ?>
      <a href="<?= $href ?> " class="<?= ($activePage === $key ? 'active' : '') ?>">
        <?= ucwords(str_replace('_', ' ', $key)) ?>
      </a>
    <?php endforeach; ?>
  </nav>

