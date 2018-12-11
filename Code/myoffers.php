<?php
// Establish connection to database
try {
    $pdo = new PDO($_SESSION["source"],$_SESSION["user"],$_SESSION["password"]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindung fehlgeschlagen: ' . $e->getMessage();
}

// Test if offer should be saved
if (isset($_POST["submitOffer"])){
    // Write data in database
    $statement = $pdo->prepare("INSERT INTO offer (title,author,offerer,offer_state,item_state,price,description,picture,isbn,edition) VALUES (:title,:author,:offerer,:offer_state,:item_state,:price,:description,:picture,:isbn,:edition)");

    $result = $statement->execute(array("title" => $_POST["title"], 
                                        "author" => $_POST["author"],
                                        "offerer" => $_SESSION["username"],
                                       "offer_state" => $_POST["offer_state"],
                                       "item_state" => $_POST["item_state"],
                                       "price" => $_POST["price"],
                                       "description" => $_POST["description"],
                                       "picture" => $_POST["picture"],
                                       "isbn" => $_POST["isbn"],
                                       "edition" => $_POST["edition"]));
    
    $showSuccess = true;
} else {
    $showSuccess = false;
}
?>

<article class="col-xs-9">
    <section>
        <h2>Eigene Anzeigen</h2>
        <?php 
        if ($showSuccess){
            echo "Anzeige erfolgreich eingetragen.";
        }
        ?>
        
        <p>              
            <!-- form to add offers -->
            <form action="main.php?page=myoffers" method="post">
                Titel: <br><input type="text" name="title" required> <br><br>
                Autor: <br><input type="text" name="author" required> <br><br>
                Module: <br><div id="input_module"><input type="text" name="mod0"></div>
                <input type="button" onclick="addModule()" value="+">
                <br><br>
                Studiengänge: Selbes<br><br>
                Zustand: <br><input type="text" name="item_state" required> <br><br>
                Anzeige ist:<br><select name="offer_state" required>
                    <?php
                    $sql = "SELECT sID,state FROM offer_state";
                    foreach ($pdo->query($sql) as $row) {
                       echo "<option value='$row[sID]'>$row[state]</option>";
                    }
                    ?>
                </select><br><br>
                Preis: <br><input type="text" name="price" pattern="[0-9]{0,6}[.][0-9]{2}"> &euro;<br><br>
                Beschreibung: <br> <textarea name="description" placeholder="Beschreibe das abzugebende Buch" rows="4" cols="50"></textarea><br><br>
                Bild: <br><input type="text" name="picture"><br><br>
                ISBN: <br><input type="text" name="isbn"> <br><br>
                Auflage: <br><input type="text" name="edition"><br><br>
                <input type="submit" name="submitOffer">        
            </form>
        
        
        </p>

    </section>
</article>