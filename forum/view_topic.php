<?php

require('db.inc.php');
require('functions.php');

if(isset($_POST['id'])){
	$id=$_POST['id'];
} else {
	if(isset($_SESSION['id'])) {
		$id = $_SESSION['id'];
	} else {
		header("Location: main_forum.php");
	}
}


if(isset($_POST['option'])){
	if($_POST['option']=="delete"){
		echo "delete";
	} else {
		echo "view";
	}
}

if(isset($_SESSION['status'])){
	if($_SESSION['status']){
		echo "S'ha afegit correctament.";
	} else {
		echo "No s'ha afegit correctament.";
	}
}

$sql="SELECT * FROM forum_question WHERE id='$id'";
$result=$conn->query($sql);
$rows=$result->fetch(PDO::FETCH_OBJ);
?>

<table width="400" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
	<tr>
		<td>
			<table width="100%" border="0" cellpadding="3" cellspacing="1" bordercolor="1" bgcolor="#FFFFFF">
				<tr>
					<td bgcolor="#F8F7F1"><strong><?php echo $rows->topic; ?></strong></td>
				</tr>

				<tr>
					<td bgcolor="#F8F7F1"><?php echo $rows->detail; ?></td>
				</tr>

				<tr>
					<td bgcolor="#F8F7F1"><strong>By :</strong> <?php echo $rows->name; ?> <strong>Email : </strong><?php echo $rows->email;?></td>
				</tr>

				<tr>
					<td bgcolor="#F8F7F1"><strong>Date/time : </strong><?php echo $rows->datetime; ?></td>
				</tr>
				<tr>
					<td bgcolor="#F8F7F1"><strong>Date/time : </strong><?php echo $rows->datetime; ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>

<?php

$sql2="SELECT * FROM forum_answer WHERE question_id='$id'";
$result2=$conn->query($sql2);
$rows=$result2->fetchAll(PDO::FETCH_OBJ);
foreach ($rows as $row){

?>

<table width="400" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
	<tr>
		<td>
			<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
				<tr>
					<td bgcolor="#F8F7F1"><strong>ID</strong></td>
					<td bgcolor="#F8F7F1">:</td>
					<td bgcolor="#F8F7F1"><?php echo $row->a_id; ?></td>
				</tr>
				<tr>
					<td width="18%" bgcolor="#F8F7F1"><strong>Name</strong></td>
					<td width="5%" bgcolor="#F8F7F1">:</td>
					<td width="77%" bgcolor="#F8F7F1"><?php echo $row->a_name; ?></td>
				</tr>
				<tr>
					<td bgcolor="#F8F7F1"><strong>Email</strong></td>
					<td bgcolor="#F8F7F1">:</td>
					<td bgcolor="#F8F7F1"><?php echo $row->a_email; ?></td>
				</tr>
				<tr>
					<td bgcolor="#F8F7F1"><strong>Answer</strong></td>
					<td bgcolor="#F8F7F1">:</td>
					<td bgcolor="#F8F7F1"><?php echo $row->a_answer; ?></td>
				</tr>
				<tr>
					<td bgcolor="#F8F7F1"><strong>Date/Time</strong></td>
					<td bgcolor="#F8F7F1">:</td>
					<td bgcolor="#F8F7F1"><?php echo $row->a_datetime; ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
 
<?php
}

addView($conn,$id);

$result=null;
$result2=null;

?>

<br>
<table width="400" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
	<tr>
		<form name="form1" method="post" action="add_answer.php" method="POST">
			<td>
				<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
					<input name="id" type="hidden" id="id" value="<?php echo $id; ?>"></td>
					<tr>
						<td width="18%"><strong>Name</strong></td>
						<td width="3%">:</td>
						<td width="79%"><input name="a_name" type="text" id="a_name" size="45"></td>
					</tr>
					<tr>
						<td><strong>Email</strong></td>
						<td>:</td>
						<td><input name="a_email" type="text" id="a_email" size="45"></td>
					</tr>
					<tr>
						<td valign="top"><strong>Answer</strong></td>
						<td valign="top">:</td>
						<td><textarea name="a_answer" cols="45" rows="3" id="a_answer"></textarea></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<input name="id" type="hidden" value="<?php echo $id; ?>">
						</td>
						<td>
							<input type="submit" name="Submit" value="Submit"> 
							<input type="reset" name="Submit2" value="Reset">
							<a href="main_forum.php">Back</a>
						</td>
					</tr>
				</table>
			</td>
		</form>
	</tr>
</table>