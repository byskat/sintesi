<?php 
	require('db.inc.php'); 
	require('functions.php'); 
?>

<!DOCTYPE html>
<html>
<?php head("Nou Tema"); ?>
<body>

	<table method="POST">
		<tr>
			<form id="form1" name="form1" method="post" action="add_topic.php">
			<td>
			<table>
			<tr>
			<td>Create New Topic</td>
			</tr>
			<tr>
			<td><strong>Topic</strong></td>
			<td>:</td>
			<td><input name="topic" type="text" id="topic" size="50" /></td>
			</tr>
			<tr>
			<td valign="top"><strong>Detail</strong></td>
			<td valign="top">:</td>
			<td><textarea name="detail" cols="50" rows="3" id="detail"></textarea></td>
			</tr>
			<tr>
			<td><strong>Name</strong></td>
			<td>:</td>
			<td><input name="name" type="text" id="name" size="50" /></td>
			</tr>
			<tr>
			<td><strong>Email</strong></td>
			<td>:</td>
			<td><input name="email" type="text" id="email" size="50" /></td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="submit" name="Submit" value="Submit" /> <input type="reset" name="Submit2" value="Reset" /></td>
			</tr>
			</table>
			</td>
			</form>
		</tr>
	</table>

</body>
</html>