<?php
	require('../../includes/php/db.inc.php');
	require('../includes/php/userValidation.inc.php');
	require('../includes/php/functions.inc.php');
	require('functions.php');

	//Es reben les dades per post per tal de modificarles.
	$id=$_POST['id'];
	$updateTopic=$_POST['updateName'];
	$updateDetail=$_POST['updateText'];
	$datetime=date("d/m/y h:i:s");

	$sql="UPDATE forumquestion SET topic = '$updateTopic', detail = '$updateDetail', datetime = '$datetime' WHERE id = '$id'";
	$result=$conn->query($sql);

	$_SESSION['id'] = $id;

	header("Location: view_topic.php");