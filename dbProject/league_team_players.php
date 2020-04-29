<?php
  require "header.php";
if (isset($_SESSION['userID'])) {
  require '../includes/dbh-inc.php';

//joins league_teams and user_team_players and players
//use to display what players are on what user teams
  $sql = "SELECT team_name, ign, kills, deaths, assist, position FROM league_teams l
          INNER JOIN user_team_players u ON l.id = u.user_team_id INNER JOIN players p
          ON p.id = u.player_id ORDER BY team_name;";
  $result = $conn->query($sql);
?>

        <form class="addUserPlayers"action="addUserPlayers.php" method="post">
          <button type="submit" name="addUserPlayers">Add Players</button>
        </form>
        <form class="removeUserPlayers" action="removeUserPlayers.php" method="post">
          <button type="submit" name="removeUserPlayers">Remove Players</button>
        </form>
        <!--search bar for players in owned players table-->
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
?>
