<!-- Aquesta capçalera comprova si l'usuai s'ha loguejat a partir de la variable de sessió userID
     Si s'ha loguejat consulta totes les seves dades i les guarda en una variable per ser usades. -->
<?php	
	session_start();
	if (isset($_SESSION['userID'])){
		$sql="SELECT * FROM users WHERE id = " . $_SESSION['userID'] . ";";
		$result=$conn->query($sql);
		$user=$result->fetch(PDO::FETCH_OBJ);
	} else {
		header('Location: ./login.php');
	}
?>