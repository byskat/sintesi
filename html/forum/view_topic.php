<?php

require('db.inc.php');
require('functions.php');

?>

<!DOCTYPE html>
<html>
<?php head("Forum | Temes"); ?>
<body>

<?php

if(isset($_POST['id'])){
	$id=$_POST['id'];
} else {
	if(isset($_SESSION['id'])) {
		$id = $_SESSION['id'];
	} else {
		header("Location: main_forum.php");
	}
}

session_destroy();

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
?>

<div class="panel">

	<?php

		$open = loadTopic($conn,$id);
		loadComments($conn,$id,$open);
		if($open) commentSection($id);

		if(isset($_POST['visit'])){
			addViewer($conn,$id);
		}

	?>
	
	<a class="backButton" href="main_forum.php"><i class="fa fa-arrow-left"></i></a>
	
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			$('.panel').width($(window).width()-75);
			$(window).resize(function(){
				$('.panel').width($(window).width()-75);
			});
			
		});
	</script>

</body>
</html>
