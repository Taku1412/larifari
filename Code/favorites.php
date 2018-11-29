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
        <h2>Merkliste</h2>
        <p>
            Markiere Angebote als interessant.
        </p>
        <p>
            Rest now, My warrior
            Rest now, your hardship is over.
            Live
            Wake up. Wake up.
            And let the cloak of life cling to your bones
            Cling to your bones.
            Wake up, wake up.
            Live.
            Wake up. Wake up.
            And let the cloak of life cling to your bones.
            Cling to your bones
            Wake up. Wake up!
            Live
            Wake up! Wake up!
            And let the cloak of life cling to your bones!
            Cling to your bones!

            Wake up! Wake up!

            Wake up! Wake up!
        </p>
    </section>
</article>