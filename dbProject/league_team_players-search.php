<?php
  require 'header.php';
  if (isset($_SESSION['userID'])) {
  require '../includes/dbh-inc.php';

  $search = $_POST['search'];
  //sql for searching
  $sql = "SELECT team_name, ign, kills, deaths, assist, position FROM league_teams l
          INNER JOIN user_team_players u ON l.id = u.user_team_id INNER JOIN players p
          ON p.id = u.player_id WHERE team_name = ? OR ign = ?;";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: players.php?error=sqlerror");
    exit();
  }
  else {
    mysqli_stmt_bind_param($stmt, "ss", $search, $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
?>
    <form class="addUserPlayers"action="addUserPlayers.php" method="post">
      <button type="submit" name="addUserPlayers">Add Players</button>
    </form>
    <form class="removeUserPlayers" action="removeUserPlayers.php" method="post">
      <button type="submit" name="removeUserPlayers">Remove Players</button>
    </form>
    <form  action="league_team_players-search.php" method="post">
      <input type="text" name="search" placeholder="Search..">
    </form>
      <table align = "center" border="1px">
            <tr>
              <th colspan="6"><h2>Owned Players</h2></th>
            </tr>
              <t>
                <th style ="width: 125px;">Team</th>
                <th style=" width: 100px;">Player</th>
                <th>Kills</th>
                <th>Deaths</th>
                <th>Assits</th>
                <th>Position</th>
              </t>
<?php
while($row = mysqli_fetch_assoc($result)){
      echo '<tr>';
            echo  '<td>'.$row['team_name'].'</td>';
            echo  '<td>'.$row['ign'].'</td>';
            echo  '<td>'.$row['kills'].'</td>';
            echo  '<td>'.$row['deaths'].'</td>';
            echo  '<td>'.$row['assist'].'</td>';
            echo  '<td>'.$row['position'].'</td>';
      echo '<tr>';
   }
    echo '</table>';
  }
  require "footer.php";
}
?>
