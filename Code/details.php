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
            
            $statement = $pdo->prepare("SELECT * FROM offer_state");
            $result = $statement->execute(array('oID' => $_GET["offer"]));
            $offer_state = $statement->fetch();
            
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
                </table>
                ";
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