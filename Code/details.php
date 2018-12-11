<script src='js/details.js'></script>
<?php
// Establish connection to database
try {
    $pdo = new PDO($_SESSION["source"],$_SESSION["user"],$_SESSION["password"]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindung fehlgeschlagen: ' . $e->getMessage();
}

if(isset($_POST['confirm_change'])) {
    // Write new data to the database
    
    // Check if the correct password was entered
    $statement = $pdo->prepare("SELECT password FROM member WHERE nickname = :username");
    $result = $statement->execute(array('username' => $_SESSION["username"]));
    $user = $statement->fetch();
    
    if(!password_verify($_POST['confirm_password'],$user['password'])) {
        $message = "Keine Änderungen durchgeführt. Das Passwort war falsch, bitte versuche es erneut.<br>";
    } else {
        // Correct password: Update data
        $offer_id = $_GET["offer"];
        
        // Delete modules and courses
        $statement = $pdo->prepare("DELETE FROM offer_module WHERE offer=:id");
        $result = $statement->execute(array('id' => $offer_id));
        $statement = $pdo->prepare("DELETE FROM offer_studypath WHERE offer=:id");
        $result = $statement->execute(array('id' => $offer_id));
        
        // Insert new data
        $statement = $pdo->prepare("UPDATE offer SET title=:title, author=:author, offerer=:offerer, offer_state=:offer_state, item_state=:item_state, price=:price, description=:description, picture=:picture, isbn=:isbn, edition=:edition WHERE oID=:id");

        $result = $statement->execute(array("title" => $_POST["change_title"], 
                                            "author" => $_POST["change_author"],
                                            "offerer" => $_SESSION["username"],
                                           "offer_state" => $_POST["change_offer_state"],
                                           "item_state" => $_POST["change_item_state"],
                                            "price" => $_POST["change_price"],
                                           "description" => $_POST["change_description"],
                                           "picture" => $_POST["change_picture"],
                                           "isbn" => $_POST["change_isbn"],
                                           "edition" => $_POST["change_edition"],
                                           "id" => $offer_id));        
        
        // Save modules
        $statement = $pdo->prepare("INSERT INTO offer_module (module,offer) VALUES (:module,:offer)");
        
        $mod_id = 0;
        $mod_el = "change_module0";
        while (isset($_POST[$mod_el])){
            if ($_POST[$mod_el] != ""){
                $result = $statement->execute(array("module" => $_POST[$mod_el],"offer" => $offer_id));
            }
            $mod_id += 1;
            $mod_el = "change_module".$mod_id;
        }

        //Save courses
        $statement = $pdo->prepare("INSERT INTO offer_studypath (study_path,offer) VALUES (:module,:offer)");

        $course_id = 0;
        $course_el = "change_course0";
        while (isset($_POST[$course_el])){
            if ($_POST[$course_el] != ""){
                $result = $statement->execute(array("module" => $_POST[$course_el],"offer" => $offer_id));
            }
            $course_id += 1;
            $course_el = "change_course".$course_id;
        }

        $message = "Änderung erfolgreich durchgeführt.<br>";
        
        
    }
}

?>
<article class="col-xs-9">
    <section>
        <?php
        if (isset($message)){
            echo $message;
        }
        
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
                // Different views depending on the offerer
                
                if ($offer["offerer"] == $_SESSION["username"]){
                    // The user can edit the offer
                    echo "
                    <h2>$offer[title]</h2>
                    <form action='main.php?page=details&offer=$id' method='post'>
                    <table>
                        <tr>
                            <td>Anzeigen-ID</td><td>$offer[oID]</td>
                        </tr>
                        <tr>
                            <td>Titel</td><td>
                            <input type='text' name='change_title' disabled value='$offer[title]'>
                            </td>
                        </tr>
                        <tr>
                            <td>Autor</td><td>
                            <input type='text' name='change_author' disabled value='$offer[author]'>
                            </td>
                        </tr>
                        <tr>
                            <td>Zustand</td><td>
                            <input type='text' name='change_item_state' disabled value='$offer[item_state]'>
                            </td>
                        </tr>
                        <tr>
                            <td>Anzeige</td><td>";
                        echo "<select name='change_offer_state' disabled required>";
                        
                        $sql = "SELECT sID,state FROM offer_state";
                        foreach ($pdo->query($sql) as $row) {
                            if ($row[state] == $offer[offer_state]){
                                echo "<option value='$row[sID]' selected='selected'>$row[state]</option>";
                            } else {
                                echo "<option value='$row[sID]'>$row[state]</option>";
                            }
                        }
                        echo "</select></td>
                        </tr>";

                    echo "<tr><td>Zugehörige Module</td><td>";
                    echo "<div id='input_module'>";
                    $sql = "SELECT module FROM offer_module WHERE offer = $_GET[offer]";
                    $ind = 0;
                    $empty = true;
                    foreach ($pdo->query($sql) as $row) {
                        if ($ind != 0){
                            echo "<br>";
                        }
                        echo "<input type='text' name='change_module$ind' disabled value='$row[module]'>";
                        $empty = false;
                        $ind += 1;
                    }
                    echo "</div>";
                    if ($empty){
                        echo "<input type='text' name='change_module0' placeholder='Kein Modul angegeben' disabled>";
                    }
                    ?>
                    <input type="button" onclick="addModule('change_module')" name="change_add_module" value="+" disabled>
                    <?php
                    echo "</td></tr>";
                    
                    echo "<tr><td>Zugehörige Studiengänge</td><td>";
                    echo "<div id='input_course'>";
                    $sql = "SELECT study_path FROM offer_studypath WHERE offer = $_GET[offer]";
                    $ind = 0;
                    $empty = true;
                    foreach ($pdo->query($sql) as $row) {
                        if ($ind != 0){
                            echo "<br>";
                        }
                        echo "<input type='text' name='change_course$ind' value='$row[study_path]' disabled>";
                        $empty = false;
                        $ind += 1;
                    }
                    echo "</div>";
                    if ($empty){
                        echo "<input type='text' name='change_course0234' placeholder='Kein Studiengang angegeben' disabled>";
                    }
                    ?>
                    <input type="button" onclick="addCourse('change_course')" name="change_add_course" value="+" disabled>
                    <?php
                    echo "</td></tr>";

                    if ($offer["price"] != null){
                        echo "<tr><td>Preis</td><td>
                        <input type='text' name='change_price' pattern='[0-9]{0,6}[.][0-9]{2}' value='$offer[price]' disabled> &euro;
                        </td></tr>";
                    } else {
                        echo "<tr><td>Preis</td><td>
                        <input type='text' name='change_price' pattern='[0-9]{0,6}[.][0-9]{2}' disabled> &euro;
                        </td></tr>";
                    }
                    if ($offer["description"] != ""){
                        echo "<tr><td>Beschreibung</td><td>
                        <textarea name='change_description' placeholder='Beschreibe das abzugebende Buch' disabled> $offer[description] </textarea>
                        </td></tr>";
                    } else {
                       echo "<tr><td>Beschreibung</td><td>
                        <textarea name='change_description' placeholder='Beschreibe das abzugebende Buch' rows='4' cols='50' disabled></textarea>
                        </td></tr>"; 
                    }
                    
                    if ($offer["picture"] != null || $offer["picture"] != ""){
                        echo "<tr><td>Bild</td><td>
                        <input type='text' name='change_picture' value='$offer[picture]' disabled>
                        </td></tr>";
                    } else {
                        echo "<tr><td>Bild</td><td>
                        <input type='text' name='change_picture' disabled>
                        </td></tr>";
                    }
                    
                    if ($offer["isbn"] != null || $offer["isbn"] != ""){
                        echo "<tr><td>ISBN</td><td>
                        <input type='text' name='change_isbn' value='$offer[isbn]' disabled>
                        </td></tr>";
                    } else {
                        echo "<tr><td>ISBN</td><td>
                        <input type='text' name='change_isbn' disabled>
                        </td></tr>";
                    }
                    
                    if ($offer["edition"] != ""){
                        echo "<tr><td>Edition</td><td>
                        <input type='text' name='change_edition' value='$offer[edition]' disabled>
                        </td></tr>";
                    } else {
                        echo "<tr><td>Edition</td><td>
                        <input type='text' name='change_edition' disabled>
                        </td></tr>";
                    }
                    
                  
                    echo "</table>
                    <button type='button' id='change_data' >bearbeiten</button><br><br>
                    <input type='password' name='confirm_password' style='display: none' placeholder='Passwort erforderlich...' required><br><br>
                    <input type='submit' name = 'confirm_change' style='display: none' value='Änderungen bestätigen' ><br><br>
                    </form>";
                     
                    
                } else {
                    // The user didn't write the offer, thus can't edit it
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