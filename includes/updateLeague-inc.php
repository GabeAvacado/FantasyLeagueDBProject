<?php

if (isset($_POST['updateLeague-submit'])){
  require 'dbh-inc.php';

  $team = $_POST['team_name'];
  $wins = $_POST['wins'];
  $losses = $_POST['losses'];

  if(empty($team)){
    header("Location: ../dbProject/updateLeague.php?team_name=empty");
    exit();
  }
  //Check team exists
  else {
    $sql = "SELECT team_name FROM league_teams WHERE team_name = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../dbProject/updateLeague.php?error=sqlerror");
      exit();
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $team);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);
      //If team doesn't exist, give error
      if ($resultCheck <= 0) {
        header("Location: ../dbProject/updateLeague.php?teamExist=false");
        exit();
      }
      //team exists, execute
      else {
        $sql = "UPDATE league_teams SET wins = ?, losses = ? WHERE team_name = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../dbProject/updateLeague.php?error=sqlerror");
        exit();
        }
        else {
          mysqli_stmt_bind_param($stmt, "iis", $wins, $losses, $team);
          mysqli_stmt_execute($stmt);
          header("Location: ../dbProject/updateLeague.php?updateLeague=success");
          exit();
        }
      }
    }
  }
}
else {
  header("Location: ../dbProject/addPlayers.php");
  exit();
}
