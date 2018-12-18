<?php
//delete user and related
if(isset($_POST['delete_user'])) {
	//delete modules related to the offers 
	$statement = $pdo->prepare("DELETE FROM offer_module WHERE offer IN (SELECT oID FROM offer WHERE offer.offerer = :username)");
    $result = $statement->execute(array('username' => $_POST["name"]));
	
	//delete studypath related to the offers 
	$statement = $pdo->prepare("DELETE FROM offer_studypath WHERE offer IN (SELECT oID FROM offer WHERE offer.offerer = :username)");
    $result = $statement->execute(array('username' => $_POST["name"]));
	
	//delete Watchlist offer entry
	$statement = $pdo->prepare("DELETE FROM watch_list WHERE offer IN (SELECT oID FROM offer WHERE offer.offerer = :username)");
    $result = $statement->execute(array('username' => $_POST["name"]));
	
	//delete Watchlist from user
	$statement = $pdo->prepare("DELETE FROM watch_list WHERE nickname = :username");
    $result = $statement->execute(array('username' => $_POST["name"]));
	
	//delete offers 
	$statement = $pdo->prepare("DELETE FROM offer WHERE offerer = :username");
    $result = $statement->execute(array('username' => $_POST['name']));
	
	//delete user
	$statement = $pdo->prepare("DELETE FROM member WHERE nickname = :username");
    $result = $statement->execute(array('username' => $_POST['name']));
}

//delete offer
if(isset($_POST['delete_offer'])) {
	//delete modules related to the offers 
	$statement = $pdo->prepare("DELETE FROM offer_module WHERE offer = :id");
    $result = $statement->execute(array('id' => $_POST["oID"]));
	
	//delete studypath related to the offers 
	$statement = $pdo->prepare("DELETE FROM offer_studypath WHERE offer = :id");
    $result = $statement->execute(array('id' => $_POST["oID"]));
	
	//delete Watchlist offer entry
	$statement = $pdo->prepare("DELETE FROM watch_list WHERE offer = :id");
    $result = $statement->execute(array('id' => $_POST["oID"]));
	
	//delete offers 
	$statement = $pdo->prepare("DELETE FROM offer WHERE oID = :id");
    $result = $statement->execute(array('id' => $_POST['oID']));
}

//lock offer
if(isset($_POST['lock_offer'])) {
	// Insert new data
        $statement = $pdo->prepare("UPDATE offer SET offer_state=:offer_state WHERE oID=:id");

        $result = $statement->execute(array('id' => $_POST['oID'],
											"offer_state" => 2));
}
											
//unlock offer
if(isset($_POST['unlock_offer'])) {
	// Insert new data
        $statement = $pdo->prepare("UPDATE offer SET offer_state=:offer_state WHERE oID=:id");

        $result = $statement->execute(array('id' => $_POST['oID'],
											"offer_state" => 1));
}

?>

<article class="col-xs-9">
    <?php 
	if($_SESSION["admin"]==1){
		
	?>
	<section class="col-xs-5">
		
		
        <h2>Mitgliederliste</h2>
        <p>
            Lösche Mitglieder (Achtung, dadurch werden auch alle seine Anzeigen gelöscht)
        </p>
        <p>
        <?php
			// Show all members in a table

			$sql = "SELECT * FROM `member`";
			?>
			<table>
				<tr>
					<th>Benutzername</th>
					<th>Admin</th>
					<th>Details</th>
					<th>Löschen</th>
				</tr>

			<?php
			foreach ($pdo->query($sql) as $row) {
			?>
				<tr>
					<td><?php echo $row['nickname'] ?></td>
					<td><?php 
						if($row['admin']==1){
							echo "Ja";
						}else{
							echo "Nein";
						}
						?>
					</td>
					<td><?php echo"<a href='?page=foreignprofile&username=$row[nickname]' class='button'>Link zum Profil</a>" ?></td>
					<td>
						<form action="?page=admin" method="post" name='delete_form' >
							<input type="hidden" name="name" value= "<?php echo $row["nickname"];?>">
							<input type="submit" value="Löschen" name = "delete_user" ><br><br>
						</form>
					</td>
					
				</tr>
			<?php
			}

			?>
			</table>

        </p>
    </section>
	<section class="col-xs-7">
        <h2>Anzeigeliste</h2>
        <p>
            Lösche Anzeigen
        </p>
        <p>
         
			<?php
			// Show all offers in a table
			// Default: order by oID

			$sql = "SELECT * FROM `offer` 
					JOIN offer_state ON sID = offer_state";
			?>
			<table>
				<tr>
					<th>Titel</th>
					<th>Status</th>
					<th>Anbieter</th>
					<th>Details</th>
					<th>Löschen</th>
					<th>Sperren</th>
					<th>Entsperren</th>
				</tr>

			<?php

			foreach ($pdo->query($sql) as $row) {?><tr>
					<td><?php echo $row['title']?></td>
					<td><?php echo $row['state']?></td>
					<td><?php echo $row['offerer']?></td>
					<td>Link zu Details</td>
					<td><form action="?page=admin" method="post" name='delete_form' >
							<input type="hidden" name="oID" value= "<?php echo $row["oID"];?>">
							<input type="submit" value="Löschen" name = "delete_offer" ><br><br>
						</form></td>
					</td>
					<td>
						<form action="?page=admin" method="post" name='lock_form' >
							<input type="hidden" name="oID" value= "<?php echo $row["oID"];?>">
							<input type="submit" value="Sperren" name = "lock_offer" ><br><br>
						</form>
					</td>
					<td>
						<form action="?page=admin" method="post" name='unlock_form' >
							<input type="hidden" name="oID" value= "<?php echo $row["oID"];?>">
							<input type="submit" value="Entsperren" name = "unlock_offer" ><br><br>
						</form>
					</td>
				</tr>
			<?php

			}

			?>
			</table>
        </p>
    </section>
	<?php
	}else{
	?>
		<p> Kein Zugriff </p> 
	<?php
	}
	?>
</article>