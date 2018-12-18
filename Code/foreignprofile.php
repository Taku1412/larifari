<script src='js/profile.js'></script>
	
<article class="col-xs-9">
	
    <section>
		
        
		<?php
        // Show foreign user info
		
		if(isset($_GET['username'])) {
		
	
		//foreign user
		$statement = $pdo->prepare("SELECT * FROM member WHERE nickname = :username");
		$result = $statement->execute(array('username' => $_GET["username"]));
		$user = $statement->fetch();
		if($user!=NULL){
            ?>
            <h2>Profilübersicht</h2>
            <br>
            <table>
            <tr>
                <td>
                    Benutzername :
                </td>
                <td> 
                    <?php echo $user["nickname"]; ?>
                </td>
            </tr>
            <tr>
                <td>
                    Vorname :
                </td>
                <td> 
                    <?php echo  $user["firstName"];?>
                </td>
            </tr>
            <tr>
                <td>
                    Nachname :
                </td>
                <td> 
                    <?php echo $user["lastName"];?>
                </td>
            </tr>
            <tr>
                <td>
                    Studiengang :
                </td>
                <td> 
                    <?php echo $user["studyPath"];?>
                </td>
            </tr>
            <tr>
                <td>
                    Startsemester :
                </td>
                <td> 
                    <?php echo $user["startsem"];?>
                </td>
            </tr>
            <tr>
                <td>
                    Beschreibung :
                </td>
                <td> 
                    <?php echo $user["description"];?>
                </td>
            </tr>

            </table>

            <?php

                if($user["admin"]==1)	{
                    echo "<br>Dieses Mitglied ist ein Admin, cool! <br>";
                }
            ?>
            <br>
            <a href="?page=messages&contact=<?php echo $user['nickname']?>" class="button">Nachricht senden</a> 

            <?php

            }else{
                echo "Das Mitglied mit dem Benutzernamen \"$_GET[username]\" existiert nicht. ";
            }
		}else{
			echo 'Es wurde kein Benutzername abgefragt. ' ;
		}
        ?>
    </section>
</article>
