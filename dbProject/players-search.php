<?php
  require 'header.php';
  require '../includes/dbh-inc.php';

  $search = $_POST['search'];
  $sql = "SELECT * FROM players
          INNER JOIN teams
          ON players.team_id = teams.id WHERE ign =? OR name =? OR position =? ORDER BY ign";
  $stmt = mysqli_stmt_init($conn);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: players.php?error=sqlerror");
    exit();
  }
  else {
    mysqli_stmt_bind_param($stmt, "sss", $search, $search, $search);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (isset($_SESSION['userID'])) {
  ?>
    <form class="addPlayers" action="addPlayers.php" method="post">
      <button type="submit" name="addPlayer">Add Player</button>
    </form>
    <form class="updatePlayers" action="updatePlayers.php" method="post">
      <button type="submit" name="updatePlayers">Update Player</button>
    </form>
    <form class="removePlayers" action="removePlayer.php" method="post">
      <button type="submit" name="removePlayer">Remove Player</button>
    </form>
  <?php } ?>
    <form action="players-search.php" method="post">
      <input type="text" name = "search" placeholder="Search..">
    </form>
      <table align ="center" border="1px" style="width:600px; line-height:40px">
      <tr>
        <th colspan="6"><h2>NA LCS Players</h2></th>
      </tr>
      <t>
          <th style=" width: 200px;">name</th>
          <th>team</th>
          <th>kills</th>
          <th>deaths</th>
          <th>assits</th>
          <th>position</th>
      </t>
<?php
    while($row = mysqli_fetch_assoc($result)){
    echo '<tr>';
          echo  '<td>'.$row['ign'].'</td>';
          echo  '<td>'.$row['name'].'</td>';
          echo  '<td>'.$row['kills'].'</td>';
          echo  '<td>'.$row['deaths'].'</td>';
          echo  '<td>'.$row['assist'].'</td>';
          echo  '<td>'.$row['position'].'</td>';
    echo '<tr>';
  }
  echo '</table>';
}



  require "footer.php";
 ?>
