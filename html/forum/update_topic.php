<?php

	require('db.inc.php');

	$id=$_POST['id'];

	$updateTopic=$_POST['updateName'];
	$updateDetail=$_POST['updateText'];
	$datetime=date("d/m/y h:i:s");

	$sql="UPDATE forum_question SET topic = '$updateTopic', detail = '$updateDetail', datetime = '$datetime' WHERE id = '$id'";
	$result=$conn->query($sql);

	$_SESSION['id'] = $id;

	header("Location: view_topic.php");