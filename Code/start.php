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
    <section>
        <h2>Willkommen auf der Internetseite zum Austausch von gebrauchten Lehrbüchern und Skripten.</h2>
        <h3>Nachrichten der Admins:</h3>
        <?php
        // Blackboard: Read data from database
        // Limit the number of entries: show only the newest
        $limit = 3; 
        
        $sql = "SELECT * FROM `news` ORDER BY `news`.`timestmp` DESC LIMIT $limit";
        foreach ($pdo->query($sql) as $row) {
           echo "<p>".$row['content']."</p>";
        }

        ?>
    </section>
    <section>
        <h2>Hier die neusten Anzeigen</h2>
    </section>
</article>