<?php 
if(isset($_POST['login'])) {	
 	$username = $_POST['username'];
    $password = $_POST['password'];
	
	$statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username");
    $result = $statement->execute(array('username' => $username));
    $user = $statement->fetch();
	
	if ($username !== false && password_verify($password, $user['password'])) { 
        $_SESSION['username'] = $user['nickname'];
		$_SESSION['admin'] = $user['admin'];
        header("Location: index.php");
		exit; 
    } else {
        $errorMessage = "Benutzername oder Passwort ungültig<br>";
    }
    
}

 
if(isset($errorMessage)) {
    echo $errorMessage;
}
?>
 
 <form action="index.php" method="post">
	  Benutzername:<br>
	  <input type="text" name="username" required><br>
	  Passwort:<br>
	  <input type="password" name="password" required><br><br>
	 
	  <input type="submit" value="Anmelden" name = "login"><br><br>
</form> 

Noch nicht Registriert? <br><br>
<a href="?page=register">Registrierung</a>
