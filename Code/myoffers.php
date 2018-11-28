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
    $Statement = $pdo->prepare("INSERT INTO offer (title,author,offerer,offer_state,item_state,price,description,picture,isbn,edition) VALUES (:title,:author,:offerer,:offer_state,:item_state,:price,:description,:picture,:isbn,:edition)");
    $Statement -> bindParam(':title',$_POST["title"]);
    $Statement -> bindParam(':author',$_POST["author"]);
    $Statement -> bindParam(':offerer',"lverscht"); //$_SESSION["username"]
    $Statement -> bindParam(':offer_state',$_POST["offer_state"]);
    $Statement -> bindParam(':item_state',$_POST["item_state"]);
    $Statement -> bindParam(':price',$_POST["price"]);
    $Statement -> bindParam(':description',$_POST["description"]);
    $Statement -> bindParam(':picture',$_POST["picture"]);
    $Statement -> bindParam(':isbn',$_POST["isbn"]);
    $Statement -> bindParam(':edition',$_POST["edition"]);
    $Statement -> execute();
    
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
                Module: Hier mit einzelnen Textfeldern? Füge selbst hinzu, je nachdem wie viele man braucht<br><br>
                Studiengänge: Selbes<br><br>
                Zustand: <br><input type="text" name="state" required> <br><br>
                Anzeige ist:<br><select name="offer_state">
                    <option value="" selected>Keine Angabe</option>
                    <?php
                    $sql = "SELECT sID,state FROM offer_state";
                    foreach ($pdo->query($sql) as $row) {
                       echo "<option value='$row[sID]'>$row[state]</option>";
                    }
                    ?>
                </select><br><br>
                Preis: <br><input type="text" name="author" pattern="[0-9]{0,6}[,][0-9]{2}"> &euro;<br><br>
                Beschreibung: <br> <textarea name="description" placeholder="Beschreiben Sie das abzugebende Buch" rows="4" cols="50"></textarea><br><br>
                Bild: <br><br>
                ISBN: <br><input type="text" name="isbn" required> <br><br>
                Auflage: <br><input type="text" name="edition"><br><br>
                <input type="submit" name="submitOffer">        
            </form>
        
        
        </p>

    </section>
</article>