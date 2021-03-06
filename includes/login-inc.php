<?php

if (isset($_POST['login-submit'])){
  require 'dbh-inc.php';

  $mailuid = $_POST['mailuid'];
  $password = $_POST['pwd'];

  if (empty($mailuid) || empty($password)){
    header("Location: ../dbProject/index.php?error=emptyfields");
    exit();
  }
  else {
    $sql = "SELECT * FROM users WHERE username=? OR email=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../dbProject/index.php?error=sqlerror");
      exit();
    }
    else {
      mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result)) {
        $pwdCheck = password_verify($password, $row['pwd']);
        if ($pwdCheck != true) {
          header("Location: ../dbProject/index.php?error=wrongpwd");
          exit();
        }
        else {
          session_start();
          $_SESSION['userID'] = $row['id'];
          $_SESSION['username'] = $row['username'];

          header("Location: ../dbProject/index.php?login=success");
          exit();
        }
      }
      else {
        header("Location: ../dbProject/index.php?error=nouser");
        exit();
      }
    }
  }
}
else {
  header("Location: ../dbProject/index.php");
  exit();
}
