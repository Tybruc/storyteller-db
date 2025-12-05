<?php
/* Storyteller Database Landing Page - index.php
      A welcoming introduction to the Storyteller Database application.
      Provides an overview of features and a call-to-action to register or log in.

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - Revised welcome text to better highlight features and benefits.
      - Updated call-to-action button styling for improved visibility.
      - Added additional context about target users.
*/

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    html,body {margin: 0; padding: 0; height: 100%;}
    body {background: url('assets/images/clouds_STDB.png') no-repeat center center fixed; background-size: cover;}
  </style>
  <title>Storyteller Database Landing Page</title>
</head>
<body>
  <header>
    <a href="index.php" style="text-decoration:none; color:inherit;">
      <h1>Storyteller Database</h1>
    </a>
    <div class="headerNav">
      <button class="btn" style="text-decoration:none;" onclick="window.location.href='pages/login.php'">Log In</button>
    </div>
  </header>
  <div class="header-spacer" aria-hidden="true"></div>
  <main>
    <div class="container">
      <div class="card" style="max-width: 900px; margin-left: auto; margin-right: auto; margin-top: 24px;">
        <h2>Welcome to Your Storytelling Command Center</h2><br>
          <p>
            Every great story begins with an idea — but keeping track of every
            <strong>character, location, and plot twist</strong> can quickly become overwhelming.
            The <strong>Storyteller Database</strong> was built to solve that problem. 
          </p><br>
          <p>
            This tool gives writers a central hub to
            <strong>organize, develop, and visualize their stories</strong> from start to finish.
            Whether you’re planning your first short story or managing an epic novel series,
            the Storyteller Database helps you stay focused, inspired, and in control.
          </p><br>
          <h2>Why It Exists</h2><br>
          <p>
            Writers often juggle scattered notes, tangled timelines, and forgotten character details.
            The Storyteller Database was created to bring <strong>structure to creativity</strong> —
            to give storytellers a place where imagination meets organization.
          </p><br>
          <h2>With this tool, you can:</h2><br>
          <ul>
            <li>Build and manage detailed <strong>characters</strong>, <strong>locations</strong>, and <strong>plot points</strong></li>
            <li>Track your story’s <strong>progress and timeline</strong></li>
            <li>See your story evolve with <strong>live updates and summaries</strong></li>
            <li>Stay productive with a simple, distraction-free design</li>
          </ul><br>
          <p>It’s more than a database — it’s your <strong>creative companion</strong>.</p><br>
          <h2>Who It’s For</h2><br>
          <p>Storyteller Database is designed for:</p><br>
          <ul>
            <li>Authors developing novels or short stories</li>
            <li>Game designers managing narrative assets</li>
            <li>Screenwriters outlining scripts</li>
            <li>Students and hobbyists exploring creative writing</li>
          </ul><br>
          <p>
            If you’ve ever struggled to keep your story’s world organized,
            this tool was made for you.
          </p><br>
          <h2>Start Writing Smarter</h2><br>
          <p>
            Create your account, start a story, and let your ideas grow.
            Every detail you enter builds your world — one scene, one character,
            one plot twist at a time.
          </p><br>
          <!-- Call-to-Action Button -->
          <div style="text-align: center; margin-top: 30px;">
            <button class="btn primary" style="font-size: x-large" onclick="location.href='pages/login.php?activeForm=register'">Click here to get started!</button>
          </div>
      </div>
    </div>
  <?= require __DIR__ . '/../includes/footer.php'; ?> 
  </main>
    
</body>

</html>
