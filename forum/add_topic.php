<?php

require('db.inc.php');

$topic=$_POST['topic'];
$detail=$_POST['detail'];
$name=$_POST['name'];
$email=$_POST['email'];

$datetime=date("d/m/y h:i:s"); //create date time

$sql="INSERT INTO forum_question(topic, detail, name, email, datetime)VALUES('$topic', '$detail', '$name', '$email', '$datetime')";
$result=$conn->query($sql);



if($result){
	$status = true;
	$_SESSION['id'] = $conn->lastInsertId();
} else {
	$status = false;
}

$result=null;

$_SESSION['status'] = $status;

header("Location: view_topic.php");
?>