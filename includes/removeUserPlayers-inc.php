<?php

if (isset($_POST['removeUserPlayers-submit'])){
  require 'dbh-inc.php';

  $player = $_POST['player_name'];

  if(empty($player)){
    header("Location: ../dbProject/removeUserPlayers.php?error=emptyfield");
    exit();
  }
  //Check if player is owned
  else {
    $sql = "SELECT ign FROM league_teams l INNER JOIN user_team_players u ON l.id = u.user_team_id INNER JOIN players p ON p.id = u.player_id WHERE ign = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../dbProject/removeUserPlayers.php?error=sqlerror");
      exit();
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $player);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);
      //if player not owned, give error
      if ($resultCheck <= 0) {
        header("Location: ../dbProject/removeUserPlayers.php?playerOwned=false");
        exit();
      }
      //player is owned so delete player from user's team
      else {
        $sql = "DELETE FROM user_team_players WHERE player_id = (SELECT id FROM players WHERE ign = ?);";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../dbProject/removeUserPlayers.php?error=sqlerror");
        exit();
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $player);
          mysqli_stmt_execute($stmt);
          header("Location: ../dbProject/removeUserPlayers.php?removePlayer=success");
          exit();
        }
      }
    }
  }
}
else {
    header("Location: ../dbProject/removeUserPlayers.php");
    exit();
}
