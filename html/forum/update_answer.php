<?php

	require('db.inc.php');

	$question_id=$_POST['question_id'];
	$a_id=$_POST['a_id'];
	$a_answer=$_POST['a_answer'];
	$datetime=date("d/m/y h:i:s");

	$sql="UPDATE forum_answer SET a_answer = '$a_answer', a_datetime = '$datetime' WHERE a_id = '$a_id' AND question_id = '$question_id'";
	$result=$conn->query($sql);

	$_SESSION['id'] = $question_id;

	header("Location: view_topic.php");