<?php	
	session_start();
	if (isset($_SESSION['userID'])){
		$userID = $_SESSION['userID'];
		$user = $_SESSION['username'];
	} else {
		header('Location: ./login.php');
	}
?>