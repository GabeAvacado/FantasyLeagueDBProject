<?php

if (isset($_POST['addPlayers-submit'])){
  require 'dbh-inc.php';

  $team = $_POST['teamName'];
  $player = $_POST['playerName'];
  $kills = $_POST['kills'];
  $deaths = $_POST['deaths'];
  $assists = $_POST['assists'];
  $position = $_POST['position'];

  if(empty($team) || empty($player) || empty($position)){
    header("Location: ../dbProject/addPlayers.php?error=emptyfields");
    exit();
  }
  else {
    //Check if team exists in teams table
    $sql = "SELECT name FROM teams WHERE name=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../dbProject/addPlayers.php?error=sqlerror1");
      exit();
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $team);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);
      //if team doesn't exist, create team in teams table
      if ($resultCheck <= 0) {
        $sql = "INSERT INTO teams (name) VALUES (?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../dbProject/addPlayers.php?error=sqlerror3");
          exit();
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $team);
          mysqli_stmt_execute($stmt);
          //Check if player already exists
          $sql = "SELECT ign FROM players WHERE ign = ?";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../dbProject/addPlayers.php?error=sqlerror4");
            exit();
          }
          else {
            mysqli_stmt_bind_param($stmt, "s", $player);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            //if player already exists, give error
            if ($resultCheck > 0) {
              header("Location: ../dbProject/addPlayers.php?playerExists=true");
              exit();
            }
            else {
              //insert into players table
              $sql = "INSERT INTO players (ign, team_id, kills, deaths, assist, position) VALUES (?, (SELECT id FROM teams WHERE name = ?), ?, ?, ?, ?)";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../dbProject/addPlayers.php?error=sqlerror5");
                exit();
              }
              else {
                mysqli_stmt_bind_param($stmt, "ssiiis", $player, $team, $kills, $deaths, $assists, $position);
                mysqli_stmt_execute($stmt);
                header("Location: ../dbProject/addPlayers.php?addPlayer=success");
                exit();
              }
            }
          }
        }
      }
      else if($resultCheck > 0){
        //team already exists, so check if player exists
        //Check if player already exists
        $sql = "SELECT ign FROM players WHERE ign = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../dbProject/addPlayers.php?error=sqlerror4");
          exit();
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $player);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_store_result($stmt);
          $resultCheck = mysqli_stmt_num_rows($stmt);
          //if player already exists, give error
          if ($resultCheck > 0) {
            header("Location: ../dbProject/addPlayers.php?playerExists=true");
            exit();
          }
          else {
            //insert into players table
            $sql = "INSERT INTO players (ign, team_id, kills, deaths, assist, position) VALUES (?, (SELECT id FROM teams WHERE name = ?), ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              header("Location: ../dbProject/addPlayers.php?error=sqlerror5");
              exit();
            }
            else {
              mysqli_stmt_bind_param($stmt, "ssiiis", $player, $team, $kills, $deaths, $assists, $position);
              mysqli_stmt_execute($stmt);
              header("Location: ../dbProject/addPlayers.php?addPlayer=success");
              exit();
            }
          }
        }
      }
    }
  }
}
else {
    header("Location: ../dbProject/addPlayers.php");
    exit();
}
