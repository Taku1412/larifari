<?php
// main php file: contains refs to other files

// Check if user is logged in


session_start();

// SESSION-Variable: Connection to the database and username
$_SESSION["user"] = "root";
$_SESSION["password"] = "larifari";
$server = "localhost";
$db = "larifari";
$_SESSION["source"] = "mysql:host=".$server.";dbname=".$db;

// Immer wenn eine Verbindung aufgebaut werden soll:
try {
    $pdo = new PDO($_SESSION["source"],$_SESSION["user"],$_SESSION["password"]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindung fehlgeschlagen: ' . $e->getMessage();
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Skripte 4 Studis</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="messages.css">
        <meta charset="utf-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="js/script.js"></script>
    </head>
    <body>
<?php

if (isset ($_SESSION["username"])){
    // user is logged in
    // check if logout was pressed
    if (isset($_GET["logout"])){
        session_destroy();
        header('Location: main.php');
    } else {
        // Hauptseite!
        // Header, aside und footer sind auf jeder Seite gleich:
        ?>
        <header class="page-header">
            <h1>Lehrbücher und Skriptaustauschplattform</h1>
            <nav class="navbar navbar-inverse">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" 
                        data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <!-- li:class="active", a:id="active"  -->
                            <li > <a href="main.php?page=start">Startseite</a> </li>
                            <li> <a href="main.php?page=offers">Anzeigen</a> </li>
                            <li> <a href="main.php?page=myoffers">Meine Angebote</a> </li>
                            <li> <a  href="main.php?page=messages">Nachrichten</a> </li>
							<?php
							//logged in user
							$statement = $pdo->prepare("SELECT admin FROM member WHERE nickname = :username");
							$result = $statement->execute(array('username' => $_SESSION["username"]));
							$user = $statement->fetch();
							if($user["admin"]==1){
								?>
								<li> <a  href="main.php?page=admin">Adminbereich</a> </li> <!--erscheint nur wenn admin auf 1 gesetzt-->
							<?php
							}
							?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        
        <div class="container">
        <div class="row">
        <?php

        // Lade jetzt entsprechende seite
        if (isset($_GET["page"])){
            if ($_GET["page"]=="favorites"){
                include('favorites.php');	
            } else if ($_GET["page"]=="myoffers"){
                include('myoffers.php');	
            } else if ($_GET["page"]=="offers"){
                include('offers.php');	
            } else if ($_GET["page"]=="profile"){
                include('profile.php');	
            } else if ($_GET["page"]=="messages"){
                include('messages.php');
			} else if ($_GET["page"]=="admin"){
                include('admin.php');
			} else if ($_GET["page"]=="foreignprofile"){
                include('foreignprofile.php');
            } else if ($_GET["page"]=="details"){
                include('details.php');
            } else {
                include("start.php");
            }
        } else {
            // lade startseite
            include("start.php");
        }

        // Danach noch Aside laden
        ?>
                <aside class="col-xs-3">
                    <p>
                        <a href="main.php?page=profile">Mein Profil</a>
                        <br>
                        <a href="main.php?logout=1">Logout</a>
                    </p>
                    <p>
                        <a href="main.php?page=favorites">Merkliste</a>
                    </p>
                </aside>
            </div>
        </div>
        
        <?php
    }
    
} else {
	if(isset($_GET["page"])){
		if($_GET["page"]=="register"){
			include("register.php");
		}
		else {
			// call login .php
    		include("login.php");	
		}
	}
	else{
		// call login .php
    	include("login.php");
	}
    
}
?>
        
        <footer>
            <p>Kontakt: larifari&#64;rwth-aachen.de </p>
            <p>&copy; larifari</p>
        </footer>
    </body>
</html>      