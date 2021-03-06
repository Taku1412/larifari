<?php
// Test if offer should be saved
if (isset($_POST["submitOffer"])){
    // User wants to submit another offer
    // Save new offer to the database
    
    // Prepare the statement
    $statement = $pdo->prepare("INSERT INTO offer (title,author,offerer,offer_state,item_state,price,description,picture,isbn,edition) VALUES (:title,:author,:offerer,:offer_state,:item_state,:price,:description,:picture,:isbn,:edition)");

    $result = $statement->execute(array("title" => xss_protected($_POST["title"]), 
                                        "author" => xss_protected($_POST["author"]),
                                        "offerer" => xss_protected($_SESSION["username"]),
                                       "offer_state" => xss_protected($_POST["offer_state"]),
                                       "item_state" => xss_protected($_POST["item_state"]),
                                        "price" => xss_protected($_POST["price"]),
                                       "description" => xss_protected($_POST["description"]),
                                       "picture" => xss_protected($_POST["picture"]),
                                       "isbn" => xss_protected($_POST["isbn"]),
                                       "edition" => xss_protected($_POST["edition"])));
    // Save modules in seperate table
    $statement = $pdo->prepare("INSERT INTO offer_module (module,offer) VALUES (:module,:offer)");
    $offer_id = $pdo->lastInsertId();
    
    // There might be multiple modules: find all modules that were added to the page
    $mod_id = 0;
    $mod_el = "module0";
    while (isset($_POST[$mod_el])){
        if ($_POST[$mod_el] != ""){
            $result = $statement->execute(array("module" => $_POST[$mod_el],"offer" => $offer_id));
        }
        $mod_id += 1;
        $mod_el = "module".$mod_id;
    }
    
    // Save courses in seperate table
    $statement = $pdo->prepare("INSERT INTO offer_studypath (study_path,offer) VALUES (:module,:offer)");
    
    // Same as modules: find all 
    $course_id = 0;
    $course_el = "course0";
    while (isset($_POST[$course_el])){
        if ($_POST[$course_el] != ""){
            $result = $statement->execute(array("module" => $_POST[$course_el],"offer" => $offer_id));
        }
        $course_id += 1;
        $course_el = "course".$course_id;
    }
    
    // Set attribute to show the user that the offer was successfully inserted
    $showSuccess = true;
} else {
    $showSuccess = false;
}
?>

<article class="col-xs-9">
    <section class="col-xs-6">
       <h2>Eigene Anzeigen</h2>
        <?php
        $sql = "SELECT oID,title,author,price,offer_state.state AS offer_state FROM offer,offer_state WHERE offerer='$_SESSION[username]' AND offer.offer_state = offer_state.sID";
        ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Titel</th>
                <th>Autor</th>
                <th>Preis</th>
                <th></th>
                <th>Details</th>
            </tr>
        
        <?php
        foreach ($pdo->query($sql) as $row) {
            echo "<tr>
                <td>$row[oID]</td>
                <td>$row[title]</td>
                <td>$row[author]</td>
                <td>$row[price]</td>
                <td>$row[offer_state]</td>
                <td><a href='?page=details&offer=$row[oID]'>Mehr</a></td>
            </tr>";
        }
        
        ?>
        </table>
    </section>
    
    <section class="col-xs-6">
        <h2>Neue Anzeige</h2>
        <?php 
        if ($showSuccess){
            echo "Anzeige erfolgreich eingetragen.";
        }
        ?>
        
        <p>              
            <!-- form to add offers -->
            <form action="?page=myoffers" method="post">
                Titel: <br><input type="text" name="title" required> <br><br>
                Autor: <br><input type="text" name="author" required> <br><br>
                Module: <br><div id="input_module"><input type="text" name="module0" id="module0"></div>
                <input type="button" onclick="addModule('module')" value="+">
                <br><br>
                Studiengänge: <br><div id="input_course"><input type="text" name="course0" id="course0"></div>
                <input type="button" onclick="addCourse('course')" value="+">
                <br><br>
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