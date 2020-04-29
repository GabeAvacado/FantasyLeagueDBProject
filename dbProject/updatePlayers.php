<!--Update players page-->
<?php
  require 'header.php';
  if (isset($_SESSION['userID'])) {
  require '../includes/dbh-inc.php';
  $sql = "SELECT * FROM players
          INNER JOIN teams
          ON players.team_id = teams.id ORDER BY ign;";
  $result = $conn->query($sql);
 ?>

     <main>
       <form class="addPlayers" action="addPlayers.php" method="post">
         <button type="submit" name="addPlayer">Add Player</button>
       </form>
       <form class="updatePlayers" action="../includes/updatePlayers-inc.php" method="post">
         <input type="text" name="playerName" placeholder="Player..">
         <input type="text" name="teamName" placeholder="Team..">
         <input type="text" name="kills" placeholder="Kills..">
         <input type="text" name="deaths" placeholder="Deaths..">
         <input type="text" name="assists" placeholder="Assists..">
         <input type="text" name="position" placeholder="top/mid/jun/adc/sup">
         <button type="submit" name="updatePlayers-submit">Update</button>
       </form>
       <form class="removePlayers" action="removePlayer.php" method="post">
         <button type="submit" name="removePlayer">Remove Player</button>
       </form>
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
         ?>
     </main>


 <?php
   require "footer.php";
 }
  ?>
