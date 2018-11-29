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
        if (isset($_GET["contact"])){
            // Show messages with one specific user
            // If the user does not exist: show that there are no messages
            $contact = $_GET["contact"];
            echo "Hier: $contact";
            $sql = "SELECT receiver,sender,message FROM messages WHERE sender = '$_SESSION[username]' or receiver = '$_SESSION[username]' ORDER BY timestmp DESC";
            foreach ($pdo->query($sql) as $row) {
                echo "$row[message]";
            }
            
        } else {
            // Search for existing chats and show them here:
            $sql = "SELECT receiver AS contact FROM messages WHERE sender = '$_SESSION[username]' UNION SELECT sender AS contact FROM messages WHERE receiver = '$_SESSION[username]'";
            $empty = true;
            foreach ($pdo->query($sql) as $row) {
                echo "<p><a href='main.php?page=messages&contact=".$row["contact"]."'>".$row["contact"]."</a></p>";
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