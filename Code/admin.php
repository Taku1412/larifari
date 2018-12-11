<?php
// Establish connection to database
try {
    $pdo = new PDO($_SESSION["source"],$_SESSION["user"],$_SESSION["password"]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindung fehlgeschlagen: ' . $e->getMessage();
}
?>

<article class="col-xs-9">
    <?php 
	if($_SESSION["admin"]==1){
		
	?>
	<section class="col-xs-6">
		
		
        <h2>Mitgliederliste</h2>
        <p>
            Lösche Mitglieder
        </p>
        <p>
        <?php
			// Show all members in a table

			$sql = "SELECT * FROM `member`";
			?>
			<table>
				<tr>
					<th>Title</th>
					<th>Autor</th>
					<th>Anbieter</th>
					<th>Preis</th>
					<th>Bild</th>
					<th>Details</th>
					<th>Löschen</th>
				</tr>

			<?php
			foreach ($pdo->query($sql) as $row) {
				echo '<tr>
					<td>$row[nickname]</td>
					<td>$row[firstName]</td>
					<td>$row[lastName]</td>
					<td>$row[studyPath]</td>
					<td>$row[startsem]</td>
					<td><a href="main.php?page=messages&foreignprofile=$user['nickname']" class="button">Link zum Profil</a></td>
					<td>Button zum Löschen</td>
				</tr>';
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
					<th>Title</th>
					<th>Autor</th>
					<th>Anbieter</th>
					<th>Preis</th>
					<th>Bild</th>
					<th>Details</th>
					<th>Löschen</th>
				</tr>

			<?php
			foreach ($pdo->query($sql) as $row) {
				echo "<tr>
					<td>$row[title]</td>
					<td>$row[author]</td>
					<td>$row[offerer]</td>
					<td>$row[price]</td>
					<td>$row[picture]</td>
					<td>Link zu Details</td>
					<td>Button zum Löschen</td>
				</tr>";
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