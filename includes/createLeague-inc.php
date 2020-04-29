<?php

  if (isset($_POST['createLeague-submit'])){
    require 'dbh-inc.php';

    $teamname = $_POST['team_name'];
    $username = $_POST['uid'];

    if (empty($teamname) || empty($username)) {
      header("Location: ../dbProject/createLeague.php?error=emptyfields&uid=".$username."&team_name=".$teamname);
      exit();
    }
    //CHECK USER EXISTS
    else {
      $sql = "SELECT username FROM users WHERE username = ?";
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../dbProject/createLeague.php?error=sqlerror");
        exit();
      }
      else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $resultCheck = mysqli_stmt_num_rows($stmt);
        //Check if the user exists in the database
        if ($resultCheck <= 0) {
          header("Location: ../dbProject/createLeague.php?userexists=false".$username);
          exit();
        }
        else {
          //Get username by matching user_id int league_teams to id in users
          $sql = "SELECT user_id FROM league_teams WHERE user_id = (SELECT id FROM users WHERE username = ?)";
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../dbProject/createLeague.php?error=sqlerror");
            exit();
        }
          else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            //Check that if the user already has a team
            if ($resultCheck > 0) {
              header("Location: ../dbProject/createLeague.php?error=userinleague=".$username);
              exit();
            }
            else {
              $sql = "INSERT INTO league_teams (team_name, user_id) VALUES (?, (SELECT id FROM users WHERE username = ?))";
              $stmt = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../dbProject/createLeague.php?error=sqlerror");
                exit();
            }
              else {
                mysqli_stmt_bind_param($stmt, "ss", $teamname, $username);
                mysqli_stmt_execute($stmt);
                header("Location: ../dbProject/createLeague.php?addedtoleague=success");
                exit();
              }
            }
          }
        }
      }
    }
  }
  else {
    header("Location: ../tutorials/createLeague.php");
    exit();
  }
