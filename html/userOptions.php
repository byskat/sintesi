<?php
	require('../includes/php/db.inc.php');
	require('./includes/php/userValidation.inc.php');
	require('./includes/php/functions.inc.php');
?>

<?php
	//Quan fa la validació d'usuari a l'include, obté totes les dades de l'usuari i les guardo per mostrar-les.
	$loggedName = $user->name;
	$loggedLastName = $user->lastName;
	$loggedBirthday = $user->birthday;	
	$loggedEmail = $user->email;

	//Si s'ha premut el botó d'actualitzar
	if(isset($_POST['update'])){

		// Assigno tot el rebut per post per fer les actualitzacions.
		$name = $_POST['name'];
		$lastName = $_POST['lastName'];
		$email = $_POST['email'];
		$birthday = $_POST['birthDay'];
		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];

		//Per defecte, response torna actualitzat correctament, en cas de no ser així aquest valor haura se modificat
		$response = "Actualitzat correctament";

		$arr = array(':name'=>strip_tags(trim($name)),
                 ':lastName'=>strip_tags(trim($lastName)),
                 ':email'=>strip_tags(trim($email)),
                 ':birthday'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $birthday))),
                 ':pass'=>strip_tags(trim(MD5($pass1))),
                 ':userId'=>$_SESSION['userID']
    );

		
		/*
		 * Hi haurà dos metodes dactualització de dades: un sense contrassenya i l'altre amb ella. d'entrada asumeixo que s'actualitza la contrassenya. 
		 * En cas contrari aquest string serà modificat.
		 */
		$sql = "UPDATE users SET name = :name, lastName = :lastName, email = :email, birthday = :birthday, password = :pass WHERE id = :userId";
	
		//Comprobo que estiguin tots els camps plens excepte les contrassenyes.
		if (!empty($name) && !empty($lastName) && !empty($birthday) && !empty($email) ){
		 	
		 	/*
		 	 * En primer lloc comprovo si les contrassenyes coincideixen.
		 	 * En segon lloc miro si els camps estan definits o son iguals.	
		 	 */	 		 		
		 	if($pass1 != $pass2){
		 		$response = "Els passwords no coincideicen";
		 	}else if(empty($pass1) && empty($pass2) || $pass1 == $pass2){
		 		
		 		//Ara, si les camps pass estan buits modifico l'String de update pq ho fasi sense contrassenya.
		 		if(empty($pass1) && empty($pass2)){
		 			unset($arr[':pass']);
		 			$sql = "UPDATE users SET name = :name, lastName = :lastName, email = :email, birthday = :birthday WHERE id = :userId ";
		 		}

		 		//Comprobo que l'email no estigui repetit
		 		$result = executePreparedQuery($conn, "SELECT * FROM users WHERE email = :email", array(':email'=>$email), false);
		 		//Si no torna cap resultat o el mail es el mateix que el de l'usuari loguejat
                if($result == false || $result->email == $loggedEmail){
                	//comprobo que la data introduïda no és més nova que actual.                	
                	
                	if(checkBirthday($birthday) == true){

						//Si es compleix tot l'anterior actualitzo a la base de dades.
                		executeInsertUpdateQuery($conn, $sql, $arr);

                		//Carrego les dades actualitzades a les variables per mosrtar-les.
                		$result = executePreparedQuery($conn, "SELECT * FROM users WHERE id = :userId", array( ':userId'=>$_SESSION['userID']), false);
						$loggedName = $result->name;
						$loggedLastName = $result->lastName;
						$loggedBirthday = $result->birthday;	
						$loggedEmail = $result->email;						               		

                	}else{
                		$response = "La data no es correcte";
                	}

                }else{
                	$response = "El mail ja existeix";
                }
		 	}	 	
		 	
		 }else{
		 	$response = "Has deixat algun camp buit";
		 }
	}		
?>


<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equips</title>        
    <?php require('./includes/php/header.inc.php'); ?>
</head>
<body>

	<?php
		require('./includes/php/topBar.inc.php');
		require('./includes/php/leftMenu.inc.php');		
	?>	

	<div class="panel">

		<div class="itemHeader">
			<div class="itemTitle">
				<h1>Opcións d'Usuari | <?php if($_SESSION['role'] == 1){ echo "Alumne"; }else{ echo "Professor"; }?> <span class="important"><?php echo $_SESSION['username'] ?></span></h1>
			</div>
		</div>

		<div class="itemList">

		<div class="optionsContainer">
			<form action="" method="POST" enctype="multipart/form-data">
				<div class="optionName">Nom:</div><input id="name" type="text" name="name" value="<?php echo $loggedName ?>"/><br>
				<div class="optionName">Cognoms:</div><input id="lastName" type="text" name="lastName" value="<?php echo $loggedLastName ?>"/><br>
				<div class="optionName">email:</div><input id="email" type="text" name="email" value="<?php echo $loggedEmail ?>"/><br>
				<div class="optionName">Data de naixement:</div><input id="datetimepicker" type="text" name="birthDay" value="<?php echo formatDate('Y-m-d', 'd/m/Y', $loggedBirthday) ?>"/><br>
				<div class="optionName">Contrassenya nova:</div><input id="pass1" type="password" name="pass1" /><br>
				<div class="optionName">Repeteix-la:</div><input id="pass2" type="password" name="pass2" /><br><br>
				<div class="optionName"></div><input class="redButton" type="submit" name="update" value="Actualitzar" /><br>				
			</form>						
			<div class="optionName"></div>
			<span id="notification" class="optionNotification"><?php if(isset($response)) { echo $response; } ?></span>

		</div>
		<div class="paddingTop"></div>
		<form action="connections.php" method="GET">
			<button class="backButton"><i class="fa fa-arrow-left"></i></button>
		</form>
	</div>

	<script type="text/javascript" src="./includes/js/functions.inc.js"></script>
	<script type="text/javascript">

		jQuery('#datetimepicker').datetimepicker({
		    timepicker:false,
		    format:'d/m/Y',
		    lang:'ca'
		});
		
	</script>	
</body>
</html>
