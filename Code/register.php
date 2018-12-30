<?php 
$showFormular = true;
 
if(isset($_POST['register'])) {
    $error = false;
    $username = xss_protect($_POST['username']);
	$firstname =  xss_protect($_POST["firstname"]);
	$lastname = xss_protect($_POST["lastname"]);
	$course = xss_protect($_POST["course"]);
	$semester = xss_protect($_POST["semester"]);
	$description = xss_protect($_POST["description"]);
    $password = xss_protect($_POST['password']);
    $passwordVerify = xss_protect($_POST['passwordVerify']);

  
    if($password != $passwordVerify) {
        $errorMessage = "Die Passwörter stimmen nicht überein.<br>";
        $error = true;
    }
    
    if(!$error) { 
        $statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username");
        $result = $statement->execute(array('username' => $username));
        $user = $statement->fetch();
        
        if($user !== false) {
            $errorMessage = "Der Benutzername $username ist leider schon vergeben, bitte wähle einen anderen. <br>";
            $error = true;
        }    
    }
    
    if(!$error) {     
        $passwordSave = password_hash($password, PASSWORD_DEFAULT);
        
        $statement = $pdo->prepare("INSERT INTO member (nickname, lastName, firstName, password, studyPath, description, startsem ) VALUES (:username, :lastname, :firstname, :password, :course, :description, :semester )");
        $result = $statement->execute(array('username' => $username, "lastname"=> $lastname, "firstname" => $firstname, 'password' => $passwordSave, "course" => $course, "description" => $description, "semester" => $semester));
        
        if($result) {        
            $errorMessage = "Registrierung erfolgreich. Logge dich ein um fortzufahren: <a href='index.php'>Login</a>";
            $showFormular = false;
        } else {
            $errorMessage ='Es ist ein Fehler aufgetreten, bitte versuche es erneut.<br>';
        }
    } 
}
 
if($showFormular) {
    # Gebe bereits eingegebene Daten aus, zum Beispiel wenn das Passwort falsch eingegeben wurde
?>
 <head>
<link rel="stylesheet" type="text/css" href="style/register.css">
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  </head>

<body id="RegisterForm">
<div class="register-form">  
<div class="main-div">
    <div class="panel">
        <h2>Registrierung</h2>
        <p>Please fill in to register</p>
    </div>

 <form action="register.php" id="Register" method="post">
	 <?php 
		if(isset($_POST['username'])){
	 		$username=xss_protect($_POST["username"]);
		} 
		if(isset($_POST['firstname'])){
		 	$firstname=xss_protect($_POST['firstname']);
		}
		if(isset($_POST['lastname'])){
			echo $lastname=xss_protect($_POST['lastname']);
		}
		if(isset($_POST['course'])){
			echo $course=xss_protect($_POST['course']);
		}
		if(isset($_POST['semester'])){
			echo $semester=xss_protect($_POST['semester']);
		}
		if(isset($_POST['description'])){
			echo $descriptiom=xss_protect($_POST['description']);
		}
	 
	 ?>
     <div class="form-group">
         <label for="uname">Benutzername:</label>
	  <input type="text" name="username" required class="form-control" id="uname" placeholder="Benutzername eingeben" <?php if(isset($_POST['username'])){ echo "value='$username'";} ?> ><br>
     </div>
     
     <div class="form-group">
         <label for="fname">Vorname:</label>
         <input type="text" name="firstname" required class="form-control" id="fname" placeholder="Vorname eingeben" <?php if(isset($_POST['firstname'])){ echo "value='$firstname'";} ?>><br>
     </div>
     
     <div class="form-group">
         <label for="lname">Nachname:</label>
	 <input type="text" name="lastname" required class="form-control" id="lname" placeholder="Nachname eingeben" <?php if(isset($_POST['lastname'])){ echo "value='$lastname'";} ?> ><br>
     </div>
     <div class="form-group">
         
         <label for="ucourse">Studiengang (optional):</label>
	  <input type="text" name="course" class="form-control" id="ucourse" placeholder="Studiengang eingeben"<?php if(isset($_POST['course'])){ echo "value='$course'";} ?> ><br>
     </div>
     
     <div class="form-group">
         <label for="ssem">Startsemester (optional):</label>
      <input type="text" name="semester" class="form-control" id="ssem" placeholder="Startsemester eingeben" <?php if(isset($_POST['semester'])){ echo "value='$semester'";} ?> ><br>
     </div>
     
     <div class="form-group">
         <label for="udescription">Beschreibung (optional):</label>
	 <textarea name="description" rows="4" cols="50" class="form-control" id=udescription placeholder="Schreibe etwas über dich..." ><?php if(isset($_POST['description'])){ echo "'$description'";} ?></textarea> <br>
     </div>
     
     <div class="form-group">
         <label for="pword">Passwort:</label>
	  <input type="password" name="password" id="pword" required class="form-control" placeholder="Passwort eingeben"><br>
     </div>
     
     <div class="form-group">
         <label for="pword1">Passwort bestätigen:</label>
	  <input type="password" name="passwordVerify" id="pword1" required class="form-control" placeholder="Passwort bestätigen">
     </div>
         <button type="submit" name="register" class="btn btn-primary" value="Registrieren">Registrieren</button> 
         <br><br>
     
</form>
</div>
</div>
    
    
</body>
<?php
} //fi($showFormular)
if(isset($errorMessage)) {
    echo $errorMessage;
}
?>