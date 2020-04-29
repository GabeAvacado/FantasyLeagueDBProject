<?php

if (isset($_POST['removeTeam-submit'])){
  require 'dbh-inc.php';

  $userTeam = $_POST['userTeam'];

  if(empty($userTeam)){
    header("Location: ../dbProject/removeLeague.php?error=emptyfield");
    exit();
  }
  else {
    //Check team or user exists
    $sql = "SELECT team_name, username FROM league_teams
            INNER JOIN users
            ON league_teams.user_id = users.id WHERE team_name = ? OR username = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../dbProject/removeLeague.php?error=sqlerror");
      exit();
    }
    else {
      mysqli_stmt_bind_param($stmt, "ss", $userTeam, $userTeam);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_store_result($stmt);
      $resultCheck = mysqli_stmt_num_rows($stmt);
      //if team or user doesn't exist, give error
      if ($resultCheck <= 0) {
        header("Location: ../dbProject/removeLeague.php?teamExist=false||usernameExist=false");
        exit();
      }
      else {
        $sql = "DELETE FROM user_team_players WHERE user_team_id = (SELECT id FROM league_teams WHERE team_name = ? OR user_id = (SELECT id FROM users WHERE username = ?))";
        $stmt = mysqli_stmt_init($conn);
        $sql2 = "DELETE FROM league_teams WHERE team_name = ? OR user_id = (SELECT id FROM users WHERE username = ?)";
        $stmt2 = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../dbProject/removeLeague.php?error=1stsqlerror");
          exit();
        }
        else if(!mysqli_stmt_prepare($stmt2, $sql2)){
          header("Location: ../dbProject/removeLeague.php?error=2ndsqlerror");
          exit();
        }
        else {
          mysqli_stmt_bind_param($stmt, "ss", $userTeam, $userTeam);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_bind_param($stmt2, "ss", $userTeam, $userTeam);
          mysqli_stmt_execute($stmt2);
          header("Location: ../dbProject/removeLeague.php?removeFromLeague=success");
          exit();
        }
      }
    }
  }
}
else {
  header("Location: ../dbProject/removeLeague.php");
  exit();
}
 ?>
