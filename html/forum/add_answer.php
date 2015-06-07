<?php
	require('../../includes/php/db.inc.php');
	require('../includes/php/userValidation.inc.php');
	require('../includes/php/functions.inc.php');
	require('functions.php');

//Es comprova que es passa la id de la pregunta, si no, es retorna a main_forum.
if(isset($_POST['id'])){
	$id=$_POST['id'];
} else {
	header("Location: main_forum.php");
}

// Es busca el nombre de resposta més alt.
$sql="SELECT MAX(a_id) AS Maxa_id FROM forumanswer WHERE question_id='$id'";
$result=$conn->query($sql);
$rows=$result->fetch(PDO::FETCH_OBJ);

// S'afegeix un al nombre de respostes.
if ($rows) {
	$Max_id = $rows->Maxa_id + 1;
}
else {
	$Max_id = 1;
}

// S'agafen totes les variables POST del formulari de creació.
$a_answer=$_POST['a_answer']; 
$userId=$user->id;
$datetime=date("d/m/y H:i:s");

// S'insereix la resposta.
$sql="INSERT INTO forumanswer(question_id, a_id, a_answer, a_datetime, id_user)VALUES('$id', '$Max_id', '$a_answer', '$datetime', '$userId')";

$result=$conn->query($sql);

if($result){
$status = true;
// S'afegeix una nova resposta a la suma total.
$sql="UPDATE forumquestion SET reply='$Max_id' WHERE id='$id'";
$result=$conn->query($sql);
}

$result=null;
$conn=null;

//Es passa per sessió la id del tema per tal de que no ens "expulsi" de view topic.
$_SESSION['id'] = $id;

header("Location: view_topic.php");
?>