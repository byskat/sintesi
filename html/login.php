<?php
	error_reporting(E_ALL ^ E_NOTICE);

	session_start();

	require('../includes/php/db.inc.php');

	
	//Eliminar o comentar un cop s'acabin les proves.
	if($_POST['username']=="godmode") {
		$_SESSION['username'] = "godmode";
		$_SESSION['userID'] = "godmode";
		header('Location: resources.php');
	}
	
	if (isset($_POST['username']) && isset($_POST['password'])){

		$sql = "SELECT * FROM `users` WHERE username = :user AND password = md5(:pass)";

				$query = $conn->prepare($sql);				
				$query->execute(array(':user'=>$_POST['username'],
									  ':pass'=>$_POST['password']));		 
				
		$count = $query->rowCount();	
		if ($count == 1){
			$result = $query->fetch(PDO::FETCH_OBJ);
			$_SESSION['userID'] = $result->id;
			$_SESSION['username'] = $result->username;
		}else{
			$msg = "Credencials invàlides.";
		}
	}

	if (isset($_SESSION['userID'])){
		$username = $_SESSION['username'];
		header('Location: resources.php');
	}
?>

	<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title>Login</title>        
	    
	    <link rel="stylesheet" type="text/css" href="./includes/css/normalize.css">
	    <link rel="stylesheet" type="text/css" href="./includes/css/jquery-ui-1.11.4/jquery-ui.css">
	    <link rel="stylesheet" type="text/css" href="./includes/css/style.css" />
	    <link rel="stylesheet" type="text/css" href="./includes/fonts/font-awesome/css/font-awesome.min.css">
	    <link rel="stylesheet" type="text/css" href="./includes/datetimepicker/jquery.datetimepicker.css"/>

	    <script src="./includes/js/jquery-2.1.4/jquery-2.1.4.js"></script>
	    <script src="./includes/js/jquery-ui-1.11.4/jquery-ui.js"></script>
	    <script src="./includes/datetimepicker/jquery.datetimepicker.js"></script>
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
			</div>
			<div class="msgBox">
				<p><?php if(isset($msg) & !empty($msg)){ echo $msg; ?> <script> $('.msgBox').addClass('activeMsg', 1000, "easeOutBounce"); </script> <?php } ?></p>
			</div>
		</div>
</body>
</html>
