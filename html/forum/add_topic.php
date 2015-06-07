<?php

require('../../includes/php/db.inc.php');
require('../includes/php/userValidation.inc.php');
require('../includes/php/functions.inc.php');
require('functions.php');

//Revem per post les de creació de tema.
$topic=$_POST['topic'];
$teamId=$_POST['teamId'];
$detail=$_POST['detail'];
$userId=$user->id;

$datetime=date("d/m/y h:i:s");

//L'inserim.
$sql="INSERT INTO forumquestion(topic, id_team, detail, datetime, id_user)VALUES('$topic', '$teamId', '$detail', '$datetime', '$userId')";

$result=$conn->query($sql);

//Comprovem si s'ha inserit bé.
if($result){
	$status = true;
	$_SESSION['teamId'] = $teamId;
	$_SESSION['id'] = $conn->lastInsertId();
} else {
	$status = false;
}

$result=null;

//Passem per sessió la variable status. (informa de si s'ha fet bé la inserció, no s'utilitza).
$_SESSION['status'] = $status;
	
header("Location: view_topic.php");
?>