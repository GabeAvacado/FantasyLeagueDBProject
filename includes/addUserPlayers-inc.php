<?php

if (isset($_POST['addUserPlayers-submit'])){
  require 'dbh-inc.php';

  $team = $_POST['team_name'];
  $player = $_POST['player_name'];

  if(empty($team) || empty($player)){
    header("Location: ../dbProject/addUserPlayers.php?error=emptyfields");
    exit();
  }
  else {
    //Check if player exists in players table
    $sql = "SELECT ign FROM players WHERE ign=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../dbProject/addUserPlayers.php?error=sqlerror");
      exit();
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $player);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);
      //if player doesn't exist, give error
      if ($resultCheck <= 0) {
        header("Location: ../dbProject/addUserPlayers.php?playerExists=false");
        exit();
      }
      //player exists
      //Check if team exists
      else {
        $sql = "SELECT team_name FROM league_teams WHERE team_name = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../dbProject/addUserPlayers.php?error=sqlerror");
          exit();
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $team);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_store_result($stmt);
          $resultCheck = mysqli_stmt_num_rows($stmt);
          //If team doesn't exist, give error
          if ($resultCheck <= 0) {
            header("Location: ../dbProject/addUserPlayers.php?teamExist=false");
            exit();
          }
          //team does exist
          else {
            //Check if player is already in another users team
            $sql = "SELECT player_id FROM user_team_players WHERE player_id =  (SELECT id from players WHERE ign = ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
              header("Location: ../dbProject/addUserPlayers.php?error=sqlerror");
              exit();
            }
            else {
              mysqli_stmt_bind_param($stmt, "s", $player);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_store_result($stmt);
              $resultCheck = mysqli_stmt_num_rows($stmt);
              //If player in another team then give error
              if ($resultCheck > 0) {
                header("Location: ../dbProject/addUserPlayers.php?playerOwned=true");
                exit();
              }
              //player available
              else {
                //insert into user_team_players table the given player with the given team name
                $sql = "INSERT INTO user_team_players (user_team_id, player_id) VALUES ((SELECT id FROM league_teams WHERE team_name = ?),
                                                                                        (SELECT id FROM players WHERE ign = ?))";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../dbProject/addUserPlayers.php?error=sqlerror");
                exit();
                }
                else {
                  mysqli_stmt_bind_param($stmt, "ss", $team, $player);
                  mysqli_stmt_execute($stmt);
                  header("Location: ../dbProject/addUserPlayers.php?addPlayer=success");
                  exit();
                }
              }
            }
          }
        }
      }
    }
  }
}
else {
    header("Location: ../dbProject/addUserPlayers.php");
    exit();
}
