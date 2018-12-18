<article class="col-xs-9">
    <section>
        <h2>Hier alle Anzeigen, mit Filter</h2>
        <?php
        // Show all offers in a table
        // Default: order by oID
        
        $sql = "SELECT * FROM `offer` 
                    JOIN offer_state ON sID = offer_state
                    WHERE state = 'offen'";
        ?>
        <table>
            <tr>
                <th>Titel</th>
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
                <td><a href='?page=details&offer=$row[oID]'>Mehr</a></td>
            </tr>";
        }

        ?>
        </table>
        
    </section>
</article>