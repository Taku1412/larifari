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
 <form action="?page=register" method="post">
	 Benutzername:<br>
	  <input type="text" name="username" <?php if(isset($_POST['username'])){ echo "value='$_POST[username]'";} ?> required><br>
	 Vorname:<br>
	  <input type="text" name="firstname" <?php if(isset($_POST['firstname'])){ echo "value='$_POST[firstname]'";} ?> required><br>
	 Nachname :<br>
	  <input type="text" name="lastname" <?php if(isset($_POST['lastname'])){ echo "value='$_POST[lastname]'";} ?> required><br>
	 Studiengang (optional): <br>
	  <input type="text" name="course" <?php if(isset($_POST['course'])){ echo "value='$_POST[course]'";} ?> ><br>
	 Startsemester (optional): <br>
	  <input type="text" name="semester" <?php if(isset($_POST['semester'])){ echo "value='$_POST[semester]'";} ?> ><br>
	 Beschreibung (optional): <br>
	 <textarea name="description" rows="4" cols="50" placeholder="Schreibe etwas über dich..." ><?php if(isset($_POST['description'])){ echo "$_POST[description]";} ?></textarea> <br>
	 Passwort:<br>
	  <input type="password" name="password" required><br>
	 Passwort bestätigen:<br>
	  <input type="password" name="passwordVerify" required><br><br>
	 
	  <input type="submit" value="Registrieren" name = "register"> 
<?php
} //fi($showFormular)
if(isset($errorMessage)) {
    echo $errorMessage;
}
?>