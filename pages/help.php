<?php
/* Storyteller Database Help Page - help.php
      Page to provide help and FAQ information for users.
      Under Construction.

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
     */

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
      html,body {margin: 0; padding: 0; height: 100%;}
      body {background: url('../assets/images/clouds_STDB.png') no-repeat center center fixed; background-size: cover;}
    </style>
    <title>Storyteller Database Help</title>
</head>
<body>
    <header>
      <a href="../index.php" style="text-decoration:none; color:inherit;">
        <h1>Storyteller Database</h1>
      </a>
      <div class="headerNav">
        <a class="btn" style="text-decoration:none;" href="../pages/help.php">Help</a>
        <a class="btn" style="text-decoration:none;" href="../pages/login.php">Log In</a>
      </div>
    </header>
  <div class="header-spacer" aria-hidden="true"></div>
  <main>
    <div class="container">
      <div class="card">
        <h2>Help / FAQ</h2><br>

            <p>
                TO BE COMPLETED: This page will contain help information and frequently asked questions about the Storyteller Database application. 
            </p><br>


            <!-- Call-to-Action Button -->
            <div style="text-align: center; margin-top: 30px;">
                <a type="button" href="<?= htmlspecialchars($previous_page) ?>" class="btn" style="font-size: x-large">Return to previous page</a>
            </div>
        </div>
    </div>
</div> 
<?php require __DIR__ . '/../includes/footer.php'; ?>
  </main>
    
</body>
</html>