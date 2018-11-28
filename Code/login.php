<?php 
// Immer wenn eine Verbindung aufgebaut werden soll:
try {
    $pdo = new PDO($_SESSION["source"],$_SESSION["user"],$_SESSION["password"]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindung fehlgeschlagen: ' . $e->getMessage();
}

if(isset($_POST['login'])) {	
 	$username = $_POST['username'];
    $password = $_POST['password'];
	
	$statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username");
    $result = $statement->execute(array('username' => $username));
    $user = $statement->fetch();
	
	if ($username !== false && password_verify($password,$user['password'])) { 
        $_SESSION['username'] = $user['nickname'];
        header("Location: main.php");
		exit; 
    } else {
        $errorMessage = "E-Mail oder Passwort war ungültig<br>";
    }
    
}

?>

<!DOCTYPE html> 
<html> 
<head>
  <title>Login</title>    
</head> 
<body>
 
<?php 
if(isset($errorMessage)) {
    echo $errorMessage;
	session_destroy();
}
?>
 
 <form action="main.php" method="post">
	  Benutzername:<br>
	  <input type="text" name="username" required><br>
	  Passwort:<br>
	  <input type="password" name="password" required><br><br>
	 
	  <input type="submit" value="Anmelden" name = "login"><br><br>
</form> 
<form action="register.php">
	Noch nicht Registriert? <br><br>
    <input type="submit" value="Registrieren" />
</form>
	
</body>
</html>