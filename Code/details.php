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
            $statement = $pdo->prepare("SELECT * FROM offer WHERE nickname = :username");
            $result = $statement->execute(array('username' => $_SESSION["username"]));
            $offer = $statement->fetch();
        } else {
            ?>
            <h2>Anzeige</h2>
            <p>Es gibt keine Anzeige mit der angegebenen ID.</p>
            <?php
        }
        ?>
        
        
        
    </section>
</article>