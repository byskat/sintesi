<?php
require('db.inc.php');

session_start();

// POST value of id that sent from hidden field 

if(isset($_POST['id'])){
	$id=$_POST['id'];
} else {
	header("Location: main_forum.php");
}

// Find highest answer number. 
$sql="SELECT MAX(a_id) AS Maxa_id FROM forum_answer WHERE question_id='$id'";
$result=$conn->query($sql);
$rows=$result->fetch(PDO::FETCH_OBJ);

// add + 1 to highest answer number and keep it in variable name "$Max_id". if there no answer yet set it = 1 
if ($rows) {
$Max_id = $rows->Maxa_id + 1;
}
else {
$Max_id = 1;
}

// POST values that sent from form 
$a_name=$_POST['a_name'];
$a_email=$_POST['a_email'];
$a_answer=$_POST['a_answer']; 

$datetime=date("d/m/y H:i:s"); // create date and time

// Insert answer 
$sql2="INSERT INTO forum_answer(question_id, a_id, a_name, a_email, a_answer, a_datetime)VALUES('$id', '$Max_id', '$a_name', '$a_email', '$a_answer', '$datetime')";
$result2=$conn->query($sql2);

if($result2){
$status = true;
// If added new answer, add value +1 in reply column 
$sql3="UPDATE forum_question SET reply='$Max_id' WHERE id='$id'";
$result3=$conn->query($sql3);
}
else {
$status = false;
}

// Close connection
$result=null;
$conn=null;


$_SESSION['id'] = $id;
$_SESSION['status'] = $status;

header("Location: view_topic.php");
?>