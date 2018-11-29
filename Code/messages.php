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
        <h2>Nachrichten</h2>
        <?php
        
        // Design-Vorschlag: https://bootsnipp.com/snippets/featured/message-chat-box
        
        if (isset($_GET["contact"])){
            // todo control if the contact username is valid
            $contact = $_GET["contact"];
            
            // todo button zum reloaden
            
            // Messages are read -> set attribute auf 1
            $statement = $pdo->prepare("UPDATE messages SET opened = '1' WHERE sender = :sender AND receiver = :receiver");
            $result = $statement->execute(array("sender" => $contact, "receiver" => $_SESSION["username"]));
        
            if (isset($_POST["sendMsg"])){
                // Save message to database
                $statement = $pdo->prepare("INSERT INTO messages (sender, receiver, message, timestmp, opened) VALUES (:sender, :receiver, :message, :timestmp, :opened)");
                
                $result = $statement->execute(array("sender" => $_SESSION["username"], 
                                        "receiver" => $contact, 
                                        "message" => $_POST["msg"],
                                       "timestmp" => time(),
                                       "opened" => 0));
                
            }
            
            // Show messages with one specific user
            // If the user does not exist: show that there are no messages
            
            $sql = "SELECT receiver,sender,message FROM messages WHERE (sender = '$_SESSION[username]' and receiver = '$contact') or (receiver = '$_SESSION[username]' and sender = '$contact') ORDER BY timestmp ASC";
            $empty = true;
            foreach ($pdo->query($sql) as $row) {
                if ($row["sender"] == $contact){
                    // The message was sent to the user
                    echo "<div class='msg-received'>$row[sender]: $row[message]</div>";
                } else {
                    // The user is the sender -> show on right side
                    echo "<div class='msg-sent'>$row[sender]: $row[message]</div>";
                }
                $empty = false;
            }
            
            if ($empty){
                echo "Keine Nachrichten<br>";
            }
            
            echo "<form action='main.php?page=messages&contact=$contact' method='post'>";
            ?>
                <input type="text" name="msg" required><input type="submit" name="sendMsg" value="Nachricht abschicken">
            </form>
            <?php
            
        } else {
            // Write a new message: show all users
            // Todo nicht als dropdown sondern mit datalist
            ?>
            <p> <form action="main.php?page=messages">
                Neue Nachricht an:
            <datalist id="user">
            <?php
            $sql = "SELECT nickname FROM member WHERE nickname NOT IN ('$_SESSION[username]')";
            foreach ($pdo->query($sql) as $row) {
                echo "<option value='$row[nickname]'/>";
            } ?>
            </datalist>

            <input type="text" name="user" list="user" onchange="updateLink()">
            <input type="submit" name="newMsg" value="Auswählen">
    
            </form>
            </p>
            
            <p>Neue Nachricht an: <select onchange="window.location=this.value">
                <option value="main.php?page=messages">Bitte wählen</option>
            <?php
            $sql = "SELECT nickname FROM member WHERE nickname NOT IN ('$_SESSION[username]')";
            foreach ($pdo->query($sql) as $row) {
                echo "<option value='main.php?page=messages&contact=$row[nickname]'>$row[nickname]</option>";
            }        
            echo "</select></p>";
            
            // Search for existing chats and show them here:
            $sql = "SELECT receiver AS contact FROM messages WHERE sender = '$_SESSION[username]' UNION SELECT sender AS contact FROM messages WHERE receiver = '$_SESSION[username]'";
            $empty = true;
            foreach ($pdo->query($sql) as $row) {
                // Show how many unread messages there are
                $count = $pdo->prepare("SELECT sender FROM messages WHERE (receiver = '$_SESSION[username]' AND sender = '$row[contact]' AND opened = '0')");
                $count->execute();
                $num_msg = $count->rowCount();
                echo "<p><a href='main.php?page=messages&contact=$row[contact]'>$row[contact] ($num_msg)</a></p>";
                $empty = false;
            }        
            
            // If no messages were sent:
            if ($empty){
                echo "<p>Du hast noch keine Nachrichten verschickt.</p>";
            }
        }
        ?>
    </section>
</article>