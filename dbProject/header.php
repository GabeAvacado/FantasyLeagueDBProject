<?php
  session_start();
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="This is an example of a meta description. This will often show up in search results.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <!--<link rel="stylesheet" href="style.css">-->
  </head>
  <body>

    <header>
      <nav>
        <a href="#">
          <img src="" alt="">
        </a>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="players.php">View Players</a></li>
          <?php
            if (isset($_SESSION['userID'])) {
        echo  '<li><a href="league_team_players.php">View League Team Players</a></li>
        </ul>
        <div>

              <form action="../includes/logout-inc.php" method="post">
                <button class="logout" type="submit" name="logout-submit">Logout</button>
              </form>';
            }
            else {
              echo '  <form action="../includes/login-inc.php" method="post">
                  <input type="text" name="mailuid" placeholder="E-mail/Username">
                  <input type="password" name="pwd" placeholder="Password">
                  <button type="submit" name="login-submit">Login</button>
                </form>
                <a class = "signup" href="signup.php">Signup</a>';
            }
           ?>
        </div>
      </nav>
    </header>
