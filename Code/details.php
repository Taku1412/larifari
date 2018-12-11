<script src='js/profile.js'></script>
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
        <?php
        if (isset($_GET["offer"])){
            $id = $_GET["offer"];
            # Get relevant data from database
            $statement = $pdo->prepare("SELECT oID,title,author,offerer,item_state,price,description,picture,isbn,edition, offer_state.state AS offer_state FROM offer,offer_state WHERE oID = :oID AND offer.offer_state = offer_state.sID");
            $result = $statement->execute(array('oID' => $_GET["offer"]));
            $offer = $statement->fetch();
            
            $statement = $pdo->prepare("SELECT * FROM offer_module WHERE offer = :oID");
            $result = $statement->execute(array('oID' => $_GET["offer"]));
            $offer_module = $statement->fetch();
            
            $statement = $pdo->prepare("SELECT * FROM offer_studypath WHERE offer = :oID");
            $result = $statement->execute(array('oID' => $_GET["offer"]));
            $offer_studypath = $statement->fetch();
            
            if ($offer == null){
                ?>
                <h2>Anzeige</h2>
                <p>Es gibt keine Anzeige mit der ID <?php echo $_GET["offer"];?>.</p>
                <?php 
            } else {
                echo "
                <h2>$offer[title]</h2>
                <table>
                    <tr>
                        <td>Anzeigen-ID</td><td>$offer[oID]</td>
                    </tr>
                    <tr>
                        <td>Titel</td><td>$offer[title]</td>
                    </tr>
                    <tr>
                        <td>Anbieter</td><td><a href='main.php?page=foreignprofile&username=$offer[offerer]'>$offer[offerer]</a> (<a href='main.php?page=messages&contact=$offer[offerer]'>Nachricht senden</a>)</td>
                    </tr>
                    <tr>
                        <td>Autor</td><td>$offer[author]</td>
                    </tr>
                    <tr>
                        <td>Zustand</td><td>$offer[item_state]</td>
                    </tr>
                    <tr>
                        <td>Anzeige</td><td>$offer[offer_state]</td>
                    </tr>";
                
                echo "<tr><td>Zugehörige Module</td><td>";
                $sql = "SELECT module FROM offer_module WHERE offer = $_GET[offer]";
                $res = [];
                foreach ($pdo->query($sql) as $row) {
                    array_push($res,$row["module"]);
                }
                if (empty($res)){
                    echo "Keine Module zugeordnet.";
                } else {
                   echo implode(", ",$res);
                }
                echo "</td></tr>";
                
                echo "<tr><td>Zugehörige Studiengänge</td><td>";
                $sql = "SELECT study_path FROM offer_studypath WHERE offer = $_GET[offer]";
                $res = [];
                foreach ($pdo->query($sql) as $row) {
                    array_push($res,$row["study_path"]);
                }
                if (empty($res)){
                    echo "Keine Studiengänge zugeordnet.";
                } else {
                   echo implode(", ",$res); 
                }
                echo "</td></tr>";
                
                if ($offer["price"] != null){
                    echo "<tr><td>Preis</td><td>$offer[price]</td></tr>";
                }
                if ($offer["description"] != ""){
                    echo "<tr><td>Beschreibung</td><td>$offer[description]</td></tr>";
                }
                if ($offer["picture"] != null || $offer["picture"] != ""){
                    echo "<tr><td>Bild</td><td>$offer[picture]</td></tr>";
                }
                if ($offer["isbn"] != null || $offer["isbn"] != ""){
                    echo "<tr><td>ISBN</td><td>$offer[isbn]</td></tr>";
                }
                if ($offer["edition"] != ""){
                    echo "<tr><td>Edition</td><td>$offer[edition]</td></tr>";
                }
                  
                echo "</table>";
            }            
        } else {
            ?>
            <h2>Anzeige</h2>
            <p>Um eine konkrete Anzeige anzuzeigen, bitte geben Sie eine ID an.</p>
            <?php
        }
        ?>
        
        
        
    </section>
</article>