<?php 
session_start();

// SESSION-Variable: Connection to the database and username
$_SESSION["username"] = "root";
$_SESSION["password"] = "larifari";

$server = "localhost";
$db = "larifari";
$_SESSION["source"] = "mysql:host=".$server.";dbname=".$db;

// Immer wenn eine Verbindung aufgebaut werden soll:
try {
    $pdo = new PDO($_SESSION["source"],$_SESSION["username"],$_SESSION["password"]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindung fehlgeschlagen: ' . $e->getMessage();
}
?>

<!DOCTYPE html> 
<html> 
<head>
  <title>Registrierung</title>   
</head> 
<body>
 
<?php 
if(isset($errorMessage)) {
    echo $errorMessage;
	session_destroy();
}
?>
 <?php
$showFormular = true;
 
if(isset($_POST['register'])) {
    $error = false;
    $username = $_POST['username'];
	$firstname =  $_POST["firstname"];
	$lastname = $_POST["lastname"];
	$course = $_POST["course"];
	$semester = $_POST["semester"];
	$description = $_POST["description"];
	$password = $_POST['password'];
    $passwordVerify = $_POST['passwordVerify'];

  
    if($password != $passwordVerify) {
        echo "Die Passwörter stimmen nicht überein.<br>";
        $error = true;
    }
    
    if(!$error) { 
        $statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username");
        $result = $statement->execute(array('username' => $username));
        $user = $statement->fetch();
        
        if($user !== false) {
            echo "Der Benutzername $username ist leider schon vergeben, bitte wähle einen anderen. <br>";
            $error = true;
        }    
    }
    
    if(!$error) {     
        $passwordSave = password_hash($password, PASSWORD_DEFAULT);
        
        $statement = $pdo->prepare("INSERT INTO member (nickname, lastName, firstName, password, studyPath, description, startsem ) VALUES (:username, :lastname, :firstname, :password, :course, :description, :semester )");
        $result = $statement->execute(array('username' => $username, "lastname"=> $lastname, "firstname" => $firstname, 'password' => $passwordSave, "course" => $course, "description" => $description, "semester" => $semester));
        
        if($result) {        
            echo "Registrierung erfolgreich. Logge dich ein um fortzufahren: <a href='login.php'>Login</a>";
            $showFormular = false;
        } else {
            echo 'Es ist ein Fehler aufgetreten, bitte versuche es erneut.<br>';
        }
    } 
}
 
if($showFormular) {
?>
 <form action="register.php" method="post">
	 Benutzername:<br>
	  <input type="text" name="username" required><br>
	 Vorname:<br>
	  <input type="text" name="firstname" required><br>
	 Nachname :<br>
	  <input type="text" name="lastname" required><br>
	 Studiengang (optional): <br>
	  <input type="text" name="course" ><br>
	 Startsemester (optional): <br>
	  <input type="text" name="semester" ><br>
	 Beschreibung (optional): <br>
	 <textarea name = "description" rows="4" cols="50" placeholder="Schreibe etwas über dich..."></textarea> <br>
	 Passwort:<br>
	  <input type="password" name="password" required><br>
	 Passwort bestätigen:<br>
	  <input type="password" name="passwordVerify" required><br><br>
	 
	  <input type="submit" value="Registrieren" name = "register"> 
<?php
} //fi($showFormular)
?>
</form> 
</body>
</html>