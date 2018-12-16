<?php
    $NEWS_LIMIT = 3;
    $NEW_OFFERS_LIMIT = 3;
?>

<article class="col-xs-9">
    <section>
        <h2>Willkommen auf der Internetseite zum Austausch von gebrauchten Lehrbüchern und Skripten.</h2>
        <h3>Nachrichten der Admins:</h3>
        <?php
        // Blackboard: Read data from database
        // Limit the number of entries: show only the newest        
        $sql = "SELECT content FROM news ORDER BY timestmp DESC LIMIT $NEWS_LIMIT";
        foreach ($pdo->query($sql) as $row) {
           echo "<p>".$row['content']."</p>";
        }

        ?>
        <p>
            "WebTech statt HighTech!" ~ Sophie Hallstedt
        </p>
    </section>
    <section>
        <h2>Hier die neusten Anzeigen</h2>
        <?php
            // Newest offers: Read data from database
            // Limit the number of entries: show only the newest        
            $sql = "SELECT * FROM `offer`
                    JOIN offer_state ON sID = offer_state
                    WHERE state = 'offen'
                    ORDER BY timestmp DESC LIMIT $NEW_OFFERS_LIMIT";
        
            require("src/offerFunctions.php");
            foreach(renderOffers($pdo, $pdo->query($sql)) as $offer)
                echo $offer;

            ?>
        </table>
    </section>
</article>