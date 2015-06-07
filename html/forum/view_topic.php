<?php
	require('../../includes/php/db.inc.php');
	require('../includes/php/userValidation.inc.php');
	require('../includes/php/functions.inc.php');
	require('functions.php');
?>

<!DOCTYPE html>
<html>
<?php head("Forum | Temes"); ?>
<body>

<?php
	require('../includes/php/topBar.inc.php');
	require('../includes/php/leftMenu.inc.php');		

//Es comprova que es passa correctament la id, sigui per get o per sessio.
if(isset($_POST['id'])){
	$id=$_POST['id'];
} else {
	if(isset($_SESSION['id'])&&isset($_SESSION['teamId'])) {
		$id = $_SESSION['id'];
		$teamId = $_SESSION['teamId'];
	} else {
		header("Location: main_forum.php");
	}
}

?>

<div class="panel">
	<?php
		$open = loadTopic($conn,$id,$user);
		loadComments($conn,$id,$open,$user);
		//Es comprova que la opció de comentar es troba habilitada.
		if($open) commentSection($id);

		if(isset($_POST['visit'])){
			addViewer($conn,$id);
		}
	?>
	<a class="backButton" href="main_forum.php"><i class="fa fa-arrow-left"></i></a>
	</div>
	<script type="text/javascript" src="../includes/js/functions.inc.js"></script>
</body>
</html>