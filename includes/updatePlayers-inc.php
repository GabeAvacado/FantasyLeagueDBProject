<?php

if (isset($_POST['updatePlayers-submit'])){
  require 'dbh-inc.php';

  $player = $_POST['playerName'];
  $team = $_POST['teamName'];
  $kills = $_POST['kills'];
  $deaths = $_POST['deaths'];
  $assists = $_POST['assists'];
  $position = $_POST['position'];

  if(empty($player)){
    header("Location: ../dbProject/updatePlayers.php?error=emptyplayerfield");
    exit();
  }
  else {
    //Check if player exists in players table
    $sql = "SELECT ign FROM players WHERE ign=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../dbProject/updatePlayers.php?error=sqlerror1");
      exit();
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $player);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);
      //if player doesn't exist, give error
      if ($resultCheck <= 0) {
        header("Location: ../dbProject/updatePlayers.php?playerExists=false");
        exit();
      }
      else {
        //player exists, update
        $sql = "UPDATE players SET team_id = (SELECT id FROM teams WHERE name = ?), kills = ?, deaths = ?, assist = ?, position = ? WHERE ign = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../dbProject/updatePlayers.php?error=sqlerror2");
          exit();
        }
        else {
          mysqli_stmt_bind_param($stmt, "siiiss", $team, $kills, $deaths, $assists, $position, $player);
          mysqli_stmt_execute($stmt);
          header("Location: ../dbProject/updatePlayers.php?updatePlayer=success");
          exit();
        }
      }
    }
  }
}
else {
    header("Location: ../dbProject/updatePlayers.php");
    exit();
}
