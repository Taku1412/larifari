<article class="col-xs-9">
    <section>
        <?php
        
        // Design-Vorschlag: https://bootsnipp.com/snippets/featured/message-chat-box
        
        if (isset($_GET["contact"])){
            if (isset($_POST["newMsg"])){
                // contact is taken from the formula (from the "Nachrichten" page)
                $contact = xss_protect($_POST["user"]);
            } else {
                $contact = xss_protect($_GET["contact"]);
            }
            
            // check if the contact username is valid
            $check = $pdo->prepare("SELECT * FROM member WHERE nickname='$contact'");
            $check->execute();
            $num_users = $check->rowCount();
            
            if ($num_users == 1){
                // User exists -> show messages
                // Messages are read -> set attribute auf 1
                $statement = $pdo->prepare("UPDATE messages SET opened = '1' WHERE sender = :sender AND receiver = :receiver");
                $result = $statement->execute(array("sender" => $contact, "receiver" => $_SESSION["username"]));

                if (isset($_POST["sendMsg"])){
                    // Save message to database
                    $statement = $pdo->prepare("INSERT INTO messages (sender, receiver, message, timestmp, opened) VALUES (:sender, :receiver, :message, :timestmp, :opened)");

                    $result = $statement->execute(array("sender" => $_SESSION["username"], 
                                            "receiver" => $contact, 
                                            "message" => xss_protect($_POST["msg"]),
                                           "timestmp" => time(),
                                           "opened" => 0));

                }
                
                echo "<h2>Nachrichten an $contact</h2>";

                // Show chat with one specific user
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
                    // No messages were sent to or from this user
                    echo "Keine Nachrichten<br>";
                }

                echo "<form action='?page=messages&contact=$contact' method='post'>";
                ?>
                    <input type="text" name="msg" required><input type="submit" name="sendMsg" value="Nachricht abschicken">
                </form>
                <?php
                // Reload the page to show new messages
                echo "<div style='float:right'><a href='?page=messages&contact=$contact'>Neu laden</a></div>";
            } else {
                // There is no user with the specific username: error
                echo "<h2>Nachrichten</h2>Es gibt keinen User mit dem Namen $contact";
            }
            
            
        } else {
            // Write a new message: show all users
            ?>
            <h2>Nachrichten</h2>
            <p> <form action="?page=messages&contact=" method="post">
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
            <h3></h3>
            <?php
            // Search for existing chats and show them here:
            $sql = "SELECT receiver AS contact FROM messages WHERE sender = '$_SESSION[username]' UNION SELECT sender AS contact FROM messages WHERE receiver = '$_SESSION[username]'";
            $empty = true;
            foreach ($pdo->query($sql) as $row) {
                // Show how many unread messages there are
                $count = $pdo->prepare("SELECT sender FROM messages WHERE (receiver = '$_SESSION[username]' AND sender = '$row[contact]' AND opened = '0')");
                $count->execute();
                $num_msg = $count->rowCount();
                echo "<p><a href='?page=messages&contact=$row[contact]'>$row[contact] ($num_msg)</a></p>";
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