<script src='js/profile.js'></script>
<?php
if(isset($_POST['confirm_change'])) {
	
	//logged in user
	$statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username");
    $result = $statement->execute(array('username' => $_SESSION["username"]));
    $user = $statement->fetch();
	
	//input data
    $error = false;
	$Message = false;
    $username = $_POST['change_username'];
	$firstname =  $_POST["change_firstname"];
	$lastname = $_POST["change_lastname"];
	$course = $_POST["change_course"];
	$semester = $_POST["change_semester"];
	$description = $_POST["change_description"];
	$password = $_POST['change_password']; //new password
    $confirm_password = $_POST['confirm_password']; //old password to verify change


  
    if(!password_verify($confirm_password,$user['password'])) {
        //echo "falsches Passwort.<br>";
		$error = true;
        $Message = "Keine Änderungen durchgeführt. Das Passwort war falsch, bitte versuche es erneut.<br>";
    }
    
	//kein Fehler und Username soll aktualisiert werden
    if(!$error && $username != $user["nickname"]) { 
		
		//überprüfe ob dieser schon vergeben ist
        $statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username"); 
        $result = $statement->execute(array('username' => $username));
        $userTemp = $statement->fetch();
        
        if($userTemp !== false) {
            $Message =  "Der Benutzername $username ist leider schon vergeben, bitte wähle einen anderen. <br>";
            $error = true;
        }    
    }
    
    if(!$error) { 
		//if new password in input change password
		if((!password_verify($password,$user['password'])) && ($password != "") && ($password != null)) {
        	//change user password
			$passwordSave = password_hash($password, PASSWORD_DEFAULT);
			$statement = $pdo->prepare("UPDATE member SET password =:password, lastName =:lastname, firstName=:firstname, nickname=:username, studyPath =:course, description=:description, startsem=:semester WHERE nickname = :username_old");
        	$result = $statement->execute(array('username_old' => $user["nickname"],'username' => $username, "lastname"=> $lastname, "firstname" => $firstname, 'password' => $passwordSave, "course" => $course, "description" => $description, "semester" => $semester));
			
			if($result) {        
				$Message = "Passwort- und andere Änderungen erfolgreich. ";
				$_SESSION["username"]=$user["nickname"];

			} else {
				$Message = 'Es ist ein Fehler aufgetreten, bitte versuche es erneut.<br>';
				$error = true;
			}
    	}
		else{ //no new password 
			$statement = $pdo->prepare("UPDATE member SET lastName =:lastname, firstName=:firstname, nickname=:username, studyPath =:course, description=:description,startsem=:semester WHERE nickname = :username_old");
        	$result = $statement->execute(array('username_old' => $user["nickname"],'username' => $username, "lastname"=> $lastname, "firstname" => $firstname, "course" => $course, "description" => $description, "semester" => $semester));
        
			if($result) {        
				$Message = "Änderungen erfolgreich. ";
				session_destroy();
				session_start();
				$_SESSION["username"]=$user["nickname"]; //change session, because username might have changed
				header("Refresh:0"); //reload page

			} else {
				$Message = 'Es ist ein Fehler aufgetreten, bitte versuche es erneut.<br>';
				$error = true;
			}
		}
    
        
    } 
}


?>
	
<article class="col-xs-9">
	
    <section>
		
        <h2>Profilübersicht</h2>
		<p><?php
		if(isset($Message)) {
    		echo $Message;
		} 
        // Show userinfo in a table, input fields deactivated until button is pressed
		
		$statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username");
		$result = $statement->execute(array('username' => $_SESSION["username"]));
    	$user = $statement->fetch();
		?>
		</p>
		<form action="main.php?page=profile" method="post" name="change_form">
		<table>
		<tr>
			<td>
				Benutzername :
			</td>
			<td> 
				<input type="text" name="change_username" disabled value="<?php echo $user["nickname"]; ?>" > <!--enabled on click change_userdata-->
			</td>
		</tr>
		<tr>
			<td>
				Vorname :
			</td>
			<td> 
				<input type="text" name="change_firstname"  value= "<?php echo  $user["firstName"];?>" disabled> <!--enabled on click change_userdata-->
			</td>
        </tr>
		<tr>
			<td>
				Nachname :
			</td>
			<td> 
				<input type="text" name="change_lastname" value= "<?php echo $user["lastName"];?>" disabled> <!--enabled on click change_userdata-->
			</td>
        </tr>
		<tr>
			<td>
				Studiengang :
			</td>
			<td> 
				<input type="text" name="change_course" value= "<?php echo $user["studyPath"];?>" disabled> <!--enabled on click change_userdata-->
			</td>
        </tr>
		<tr>
			<td>
				Startsemester :
			</td>
			<td> 
				<input type="text" name="change_semester" value= "<?php echo $user["startsem"];?>" disabled> <!--enabled on click change_userdata-->
			</td>
        </tr>
		<tr>
			<td>
				Beschreibung :
			</td>
			<td> 
				<textarea name = "change_description" rows="4" cols="50" disabled><?php echo $user["description"];?></textarea>
			</td>
        </tr>
		<tr>
			<td>
				Neues Passwort :
			</td>
			<td> 
				<input type="password" name="change_password"  disabled> <!--enabled on click change_userdata-->
			</td>
        </tr>
			
		</table>
		
		<button type="button" id =change_userdata >bearbeiten</button><br><br>
		
		<!--confirm change by entering password and submitting-->
		
		<input type="password" name="confirm_password" style="display: none" placeholder="Passwort erforderlich..." required><br><br>

		<input type="submit" name = "confirm_change" style="display: none" value="Änderungen bestätigen" ><br><br>
		</form> 
		
		<?php
		
		if($user["admin"]==1)	{
			echo "<br>Du bist ein Admin, juchuh! <br>";
		}
       	
        ?>
        
    </section>
</article>
