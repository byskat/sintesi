<?php

	require('db.inc.php');

	$id=$_POST['id'];

	$sql="UPDATE forum_question SET open = 1 WHERE id = '$id'";
	$result=$conn->query($sql);

	header("Location: main_forum.php");