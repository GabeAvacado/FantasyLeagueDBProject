<?php

if (isset($_POST['removePlayer-submit'])){
  require 'dbh-inc.php';

  $player = $_POST['playerName'];

  if(empty($player)){
    header("Location: ../dbProject/removePlayer.php?error=emptyfield");
    exit();
  }
  //Check player exists
  else {
    $sql = "SELECT ign FROM players WHERE ign = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../dbProject/removePlayer.php?error=sqlerror1");
      exit();
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $player);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);
      //Player doesn't exist, give error
      if ($resultCheck <= 0) {
        header("Location: ../dbProject/removePlayer.php?playerExists=false");
        exit();
      }
      //Check if player is owned by user
      else {
        $sql = "SELECT player_id FROM user_team_players WHERE player_id = (SELECT id FROM players WHERE ign = ?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../dbProject/removePlayer.php?error=sqlerror2");
          exit();
        }
        else {
          mysqli_stmt_bind_param($stmt, "s", $player);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_store_result($stmt);
          $resultCheck = mysqli_stmt_num_rows($stmt);
          //Player not owned
          if ($resultCheck <= 0) {
            //Check if only player on team before delete
            $sql = "SELECT ign FROM players WHERE team_id = (SELECT team_id from players WHERE ign = ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../dbProject/removePlayer.php?error=sqlerror3");
            exit();
            }
            else {
              mysqli_stmt_bind_param($stmt, "s", $player);
              mysqli_stmt_execute($stmt);
              mysqli_stmt_store_result($stmt);
              $resultCheck = mysqli_stmt_num_rows($stmt);
              //If so, delete team then delete player
              if ($resultCheck = 1) {
                //Get the team_id from player wanting to delete
                $team_id = "SELECT id FROM teams WHERE id =(SELECT team_id FROM players WHERE ign = ?)";
                //Store team_id into $result
                $result = $conn->query($team_id);
                $row = mysqli_fetch_assoc($result);
                //Delete player from players table based on ign given
                $sql = "DELETE FROM players WHERE ign = ?";
                $stmt = mysqli_stmt_init($conn);
                //Delete from teams where id = team_id stored in $result
                $sql2 = "DELETE FROM teams WHERE id = ?";
                $stmt2 = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../dbProject/removePlayer.php?error=sqlerror4");
                exit();
                }
                else if (!mysqli_stmt_prepare($stmt2, $sql2)) {
                header("Location: ../dbProject/removePlayer.php?error=sqlerror5");
                exit();
                }
                else {
                  mysqli_stmt_bind_param($stmt, "s", $player);
                  mysqli_stmt_execute($stmt);
                  mysqli_stmt_bind_param($stmt2, "s", $row['id']);
                  mysqli_stmt_execute($stmt2);
                  header("Location: ../dbProject/removePlayer.php?removePlayer=success&");
                  exit();
                }
              }
              //Not last player, just delete from players table
              else {
                $sql = "DELETE FROM players WHERE ign = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../dbProject/removePlayer.php?error=sqlerror4");
                exit();
                }
                else {
                  mysqli_stmt_bind_param($stmt, "s", $player);
                  mysqli_stmt_execute($stmt);
                  header("Location: ../dbProject/removePlayer.php?removePlayer=success");
                  exit();
                }
              }
            }
          }
          //Player is owned, delete from user_team_players first
          else {
            $sql = "DELETE FROM user_team_players WHERE player_id = (SELECT id FROM players WHERE ign = ?);";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../dbProject/removePlayer.php?error=sqlerror4");
            exit();
            }
            else {
              mysqli_stmt_bind_param($stmt, "s", $player);
              mysqli_stmt_execute($stmt);

              //Check if last player on team
              $sql = "SELECT ign FROM players WHERE team_id = (SELECT team_id from players WHERE ign = ?)";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
              header("Location: ../dbProject/removePlayer.php?error=sqlerror3");
              exit();
              }
              else {
                mysqli_stmt_bind_param($stmt, "s", $player);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $resultCheck = mysqli_stmt_num_rows($stmt);
                //If so, delete player then delete team
                if ($resultCheck = 1) {
                  $sql = "DELETE FROM teams WHERE id = (SELECT team_id FROM players WHERE ign = ?)";
                  $stmt = mysqli_stmt_init($conn);
                  $sql2 = "DELETE FROM players WHERE ign = ?";
                  $stmt2 = mysqli_stmt_init($conn);
                  if (!mysqli_stmt_prepare($stmt, $sql)) {
                  header("Location: ../dbProject/removePlayer.php?error=sqlerror4");
                  exit();
                  }
                else if (!mysqli_stmt_prepare($stmt2, $sql2)) {
                  header("Location: ../dbProject/removePlayer.php?error=sqlerror5");
                  exit();
                  }
                  else {
                    mysqli_stmt_bind_param($stmt, "s", $player);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_param($stmt2, "s", $player);
                    mysqli_stmt_execute($stmt2);
                    header("Location: ../dbProject/removePlayer.php?removePlayer=success");
                    exit();
                  }
              }
              //Not last player, delete from players
              else {
                $sql = "DELETE FROM players WHERE ign = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../dbProject/removePlayer.php?error=sqlerror4");
                exit();
                }
                else {
                  mysqli_stmt_bind_param($stmt, "s", $player);
                  mysqli_stmt_execute($stmt);
                  header("Location: ../dbProject/removePlayer.php?removePlayer=success");
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
}
else {
    header("Location: ../dbProject/removePlayer.php");
    exit();
}
