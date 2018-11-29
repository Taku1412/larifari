<?php
// Establish connection to database
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
<script src='js/profile.js'></script>
  <title>Login</title>    
</head> 
<body>
	
<?php
	
if(isset($errorMessage)) {
    echo $errorMessage;
}
 
if(isset($_POST['profile'])) {
	
	//logged in user
	$statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username");
    $result = $statement->execute(array('username' => $_SESSION["username"]));
    $user = $statement->fetch();
	
	//input data
    $error = false;
    $username = $_POST['change_username'];
	$firstname =  $_POST["change_firstname"];
	$lastname = $_POST["change_lastname"];
	$course = $_POST["change_course"];
	$semester = $_POST["change_semester"];
	$description = $_POST["change_description"];
	$password = $_POST['change_password'];
    $confirm_password = $_POST['confirm_password'];


  
    if(!password_verify($password_confirm,$user['password'])) {
        echo "falsches Passwort.<br>";
        $error = true;
    }
    
	//kein Fehler und Username soll aktualisiert werden
    if(!$error && $username != $user["nickname"]) { 
		
		//überprüfe ob dieser schon vergeben ist
        $statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username"); 
        $result = $statement->execute(array('username' => $username));
        $userTemp = $statement->fetch();
        
        if($userTemp !== false) {
            echo "Der Benutzername $username ist leider schon vergeben, bitte wähle einen anderen. <br>";
            $error = true;
        }    
    }
    
    if(!$error) { 
		//if new password in input change password
		if(!password_verify($password,$user['password'])) {
        	//change user password
			$passwordSave = password_hash($password, PASSWORD_DEFAULT);
			$statement = $pdo->prepare("UPDATE member SET password =:password, lastName =:lastname, firstName=:firstname, nickname=:username, studyPath =:course, description:=description,startsem=:semester WHERE nickname = :username_old");
        	$result = $statement->execute(array('username_old' => $user["nickname"],'username' => $username, "lastname"=> $lastname, "firstname" => $firstname, 'password' => $passwordSave, "course" => $course, "description" => $description, "semester" => $semester));
			
			if($result) {        
				echo "Passwort- und andere Änderungen erfolgreich. ";
					$_SESSION["username"]=$user["nickname"];

			} else {
				echo 'Es ist ein Fehler aufgetreten, bitte versuche es erneut.<br>';
			}
    	}
		else{ //no new password 
			$statement = $pdo->prepare("UPDATE member SET lastName =:lastname, firstName=:firstname, nickname=:username, studyPath =:course, description:=description,startsem=:semester WHERE nickname = :username_old");
        	$result = $statement->execute(array('username_old' => $user["nickname"],'username' => $username, "lastname"=> $lastname, "firstname" => $firstname, "course" => $course, "description" => $description, "semester" => $semester));
        
			if($result) {        
				echo "Änderungen erfolgreich. ";
					$_SESSION["username"]=$user["nickname"];

			} else {
				echo 'Es ist ein Fehler aufgetreten, bitte versuche es erneut.<br>';
			}
		}
    
        
    } 
}
?>
	
<article class="col-xs-9">
    <section>
        <h2>Profilübersicht</h2>
		<?php
        // Show all offers in a table
		
		$statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username");
		$result = $statement->execute(array('username' => $_SESSION["username"]));
    	$user = $statement->fetch();
		?>
		<form action="main.php?page=profile" method="post" name="change_form">
		<table>
		<tr>
		<td>
		Nickname :
		</td>
		<td> 
			<input type="text" name="change_username" value= <?php echo $user["nickname"];?> disabled> <!--enabled on click change_userdata-->
        </tr>
		<tr>
		<td>
		Vorname :
		</td>
		<td> 
			<input type="text" name="change_firstname" value= <?php echo $user["firstName"];?> disabled> <!--enabled on click change_userdata-->
        </tr>
		<tr>
		<td>
		Nachname :
		</td>
		<td> 
			<input type="text" name="change_lastname" value= <?php echo $user["lastName"];?> disabled> <!--enabled on click change_userdata-->
        </tr>
		<tr>
		<td>
		Studiengang :
		</td>
		<td> 
			<input type="text" name="change_course" value= <?php echo $user["studyPath"];?> disabled> <!--enabled on click change_userdata-->
        </tr>
		<tr>
		<td>
		Startsemester :
		</td>
		<td> 
			<input type="text" name="change_semester" value= <?php echo $user["startsem"];?> disabled> <!--enabled on click change_userdata-->
        </tr>
		<tr>
		<td>
		Beschreibung :
		</td>
		<td> 
			<textarea name = "change_description" rows="4" cols="50" disabled><?php echo $user["description"];?></textarea>
        </tr>
		<tr>
		<td>
		Neues Passwort :
		</td>
		<td> 
			<input type="password" name="change_password"  disabled> <!--enabled on click change_userdata-->
        </tr>
			
		</table>
		
		<button type="button" id =change_userdata >bearbeiten</button><br><br>
		
		<!--confirm change by entering password and submitting-->
		
		<input type="password" name="password" id = "confirm_password" style="display: none" placeholder="Passwort erforderlich..." required><br><br>

		<input type="submit" id = "confirm_button" name = "confirm_change" style="display: none" value="Änderungen bestätigen" ><br><br>
		</form> 
		
		<?php
		if($user["admin"]==0)	{
			echo "<br>Du bist ein Admin, juchuh! <br>";
		}
       
        ?>
        
    </section>
</article>
	
	
</body>
</html>