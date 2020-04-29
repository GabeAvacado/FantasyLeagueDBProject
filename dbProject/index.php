<!--//main account page-->
<?php
  require "header.php";
  require '../includes/dbh-inc.php';
  $sql = "SELECT * FROM league_teams
          INNER JOIN users
          ON league_teams.user_id = users.id;";
          $result = $conn->query($sql);



        if (isset($_SESSION['userID'])) {
          //have league user is in, user's owned players, and link to all players page
          echo '<form action="createLeague.php" method="post">
                  <button type="submit" name="createLeague">Add to League</button>
                </form>
                <form action="updateLeague.php" method="post">
                  <button type="submit" name="updateLeague">Update Wins/Losses</button>
                </form>
                <form action="removeLeague.php" method="post">
                  <button type="submit" name="removeLeague">Remove From League</button>
                </form>

                <main>
                  <table align = "right" border = "1px" style="width:600px; line-height:40px">
                    <tr>
                      <th colspan="4"><h2>Your League</h2></th>
                    </tr>
                    <t>
                      <th>Team Name</th>
                      <th>Username</th>
                      <th>Wins</th>
                      <th>Losses</th>
                    </t>';

                     while($row = mysqli_fetch_assoc($result)){
                     echo '<tr>';
                           echo  '<td>'.$row['team_name'].'</td>';
                           echo  '<td>'.$row['username'].'</td>';
                           echo  '<td>'.$row['wins'].'</td>';
                           echo  '<td>'.$row['losses'].'</td>';
                     echo '<tr>';
                     }
                 echo '</table>


                <p>You are logged in!</p>';
        }
        else {
          echo '<p>You are logged out!</p>';
        }
       ?>
    </main>

<?php
  require "footer.php";
 ?>
