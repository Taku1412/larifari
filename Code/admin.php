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
    <?php 
	if($_SESSION["admin"]==1){
		
	?>
	<section class="col-xs-4">
		
		
        <h2>Mitgliederliste</h2>
        <p>
            Lösche Mitglieder
        </p>
        <p>
          
		Stille Nacht, heilige Nacht!
		Alles schläft, einsam wacht
		Nur das traute, hochheilige Paar.
		Holder Knabe im lockigen Haar,
		Schlaf in himmlischer Ruh,
		Schlaf in himmlischer Ruh.

		Stille Nacht! Heil’ge Nacht!
		Gottes Sohn! O! wie lacht
		Lieb’ aus Deinem göttlichen Mund,
		Da uns schlägt die rettende Stund;
		Jesus! in Deiner Geburth!
		Jesus in Deiner Geburth!


		Stille Nacht, heilige Nacht!
		Gottes Sohn, o wie lacht
		Lieb aus deinem göttlichen Mund,
		Da uns schlägt die rettende Stund,
		Christ, in deiner Geburt,
		Christ, in deiner Geburt.

        </p>
    </section>
	<section class="col-xs-4">
        <h2>Anzeigeliste</h2>
        <p>
            Lösche Anzeigen
        </p>
        <p>
                Tochter Zion, freue dich, jauchze laut, Jerusalem!
				Sieh, dein König kommt zu dir, ja, er kommt, der Friedefürst.
				Tochter Zion, freue dich, jauchze laut, Jerusalem!

				Hosianna, Davids Sohn, sei gesegnet deinem Volk!
				Gründe nun dein ewges Reich, Hosianna in der Höh!
				Hosianna, Davids Sohn, sei gesegnet deinem Volk!

				(Sieh! er kömmt demüthiglich
				Reitet auf dem Eselein,
				Tochter Zion freue dich!
				Hol ihn jubelnd zu dir ein.)[8]

				Hosianna, Davids Sohn, sei gegrüßet, König mild!
				Ewig steht dein Friedensthron, du des ewgen Vaters Kind.
				Hosianna, Davids Sohn, sei gegrüßet, König mild!
        </p>
    </section>
	<?php
	}else{
	?>
		<p> Kein Zugriff </p> 
	<?php
	}
	?>
</article>