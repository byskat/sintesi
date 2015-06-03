<?php
	session_start();

    require('../includes/php/db.inc.php');
    require('./includes/php/functions.inc.php');	

	
	if (isset($_POST['username']) && isset($_POST['password'])){

		$sql = "SELECT * FROM `users` WHERE username = :user AND password = md5(:pass)";							
		$arr = array(':user'=>$_POST['username'], ':pass'=>$_POST['password']);		 

		$result = executePreparedQuery($conn, $sql, $arr, false);

		if ($result != false){
			$_SESSION['userID'] = $result->id;
			$_SESSION['username'] = $result->username;
			$_SESSION['role'] = $result->role;
		}else{
			$msg = "Credencials invàlides.";
			$msgColor = 2;
		}
	}
	if (isset($_SESSION['userID'])){
		$username = $_SESSION['username'];		
		header('Location: connections.php');
	}
?>

	<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Login</title>    
	    <?php require('./includes/php/header.inc.php'); ?>
	</head>
	<body>

		<div class="imgBg">
			<div class="wrapper">
			</div>
		</div>

		<div class="paddingTop"></div>
		
		<div class="container">
			<div class="subcontainer">
				<h1>Login</h1>
				<form action="" method="POST">
				  <p>
						<input id="username" class="text" type="text" name="username" placeholder="usuari" />
					</p>
				 
				  <p>
					  <input id="password" class="text" type="password" name="password" placeholder="contrasenya" />
					</p>
				  
					<p class="center">
				  	<input class="redButton" type="submit" name="submit" value="Login" /><br />
				  	<a class="link" href="register.php">Registre</a>
					</p>
				</form>
				<?php require('./includes/php/showMessage.inc.php'); ?>	
			</div>
			
			<div style="clear:both"></div>
		</div>
</body>
</html>
