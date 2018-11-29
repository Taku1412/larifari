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
        <h2>Hier alle Anzeigen, mit Filter</h2>
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
            </tr>";
        }

        ?>
        </table>
        
    </section>
</article>