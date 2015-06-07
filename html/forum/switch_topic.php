<?php
	require('../../includes/php/db.inc.php');
	require('../includes/php/userValidation.inc.php');
	require('../includes/php/functions.inc.php');
	require('functions.php');

	//Rebem les dades del tema, per poder canviar el seu estat, permetre o no comentaris.

	$id=$_POST['id'];
	$teamId=$_POST['teamId'];
	$teamName = $_POST['teamName'];
	$option=$_POST['option'];

	//Option es el paràmete que ens indica si el tema esta obert a comentaris o no.
	$option = intval($option);

	if($option){
		$option=0;
	} else {
		$option=1;
	}

	$sql="UPDATE forumquestion SET open = '$option' WHERE id = '$id'";
	
	$result=$conn->query($sql);

	$_SESSION['teamId'] = $teamId;
	$_SESSION['teamName'] = $teamName;

	header("Location: main_forum.php");