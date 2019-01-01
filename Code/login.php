<?php 
if(isset($_POST['login'])) {	
 	$username = xss_protected($_POST['username']);
    $password = xss_protected($_POST['password']);
	
	$statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username");
    $result = $statement->execute(array('username' => $username));
    $user = $statement->fetch();
	
	if ($username !== false && password_verify($password, $user['password'])) { 
        $_SESSION['username'] = xss_protected($user['nickname']);
		$_SESSION['admin'] = xss_protected($user['admin']);
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
 <head>
<link rel="stylesheet" type="text/css" href="style/login.css">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  </head>

<body id="LoginForm">
<div class="login-form">
<div class="main-div">
    <div class="panel">
   <h2>Admin Login</h2>
   <p>Please enter your email and password</p>
   </div>
<form action="index.php" id="Login" method="post">

        <div class="form-group">
            <input type="text" name="username" required class="form-control" id="inputEmail" placeholder="Benutzername">
        </div>

        <div class="form-group">
            <input type="password" name="password" required class="form-control" id="inputPassword" placeholder="Password">
        </div>
    
        <button type="submit" name="login" class="btn btn-primary">Anmelden</button>
    <br><br>
    <p>Noch nicht Registriert?</p>
<a href="index.php?page=register">Registrierung</a>
    </form>
</div>
</div>


</body>


