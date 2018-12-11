<?php
// Establish connection to database
try {
    $pdo = new PDO($_SESSION["source"],$_SESSION["user"],$_SESSION["password"]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindung fehlgeschlagen: ' . $e->getMessage();
}

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
?>

<article class="col-xs-9">
    <?php 
	if($_SESSION["admin"]==1){
		
	?>
	<section class="col-xs-6">
		
		
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
					<td><?php echo"<a href='main.php?page=foreignprofile&username=$row[nickname]' class='button'>Link zum Profil</a>" ?></td>
					<td>
						<form action="main.php?page=admin" method="post" name='delete_form' >
							<input type="hidden" name="name" value= "<?php echo $row["nickname"];?>">
							<input type="submit" value="X" name = "delete_user" ><br><br>
						</form></td>
				</tr>
			<?php
			}

			?>
			</table>

        </p>
    </section>
	<section class="col-xs-6">
        <h2>Anzeigeliste</h2>
        <p>
            Lösche Anzeigen
        </p>
        <p>
         
			<?php
			// Show all offers in a table
			// Default: order by oID

			$sql = "SELECT * FROM `offer`";
			?>
			<table>
				<tr>
					<th>Titel</th>
					<th>Autor</th>
					<th>Anbieter</th>
					<th>Details</th>
					<th>Löschen</th>
				</tr>

			<?php
			foreach ($pdo->query($sql) as $row) {?><tr>
					<td><?php echo $row['title']?></td>
					<td><?php echo $row['author']?></td>
					<td><?php echo $row['offerer']?></td>
					<td>Link zu Details</td>
					<td><form action="main.php?page=admin" method="post" name='delete_form' >
							<input type="hidden" name="oID" value= "<?php echo $row["oID"];?>">
							<input type="submit" value="X" name = "delete_offer" ><br><br>
						</form></td></td>
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