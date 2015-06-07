<?php
	require('../../includes/php/db.inc.php');
	require('../includes/php/userValidation.inc.php');
	require('../includes/php/functions.inc.php');
	require('functions.php'); 

	//Comprovem que revem teamId, per validar que l'usuari ha accedit aqui correctament.
	if(isset($_GET["teamId"])){	
		$teamId = $_GET["teamId"];
		$teamName = $_GET['teamName'];
		//Si les rebem les passem per sessió.
		$_SESSION["teamId"] = $teamId;
		$_SESSION["teamName"] = $teamName;
		
	} else {
		//Si no, comprovem que efectivament estiguin a la sessió.
		if(isset($_SESSION["teamId"])){
			$teamId = $_SESSION["teamId"];
			$teamName = $_SESSION['teamName'];
		} else {
			//Si no, es que intenta accedir des d'un lloc no autoritzat.
			header('Location: ../login.php');
		}
	}
?>

<!DOCTYPE html>
<html>
<?php head("Forum"); ?>
<body>

	<?php
		require('../includes/php/topBar.inc.php');
		require('../includes/php/leftMenu.inc.php');		
	?>	

	<div class="panel">
		<?php 
			//Crida a la funció que genera el forum.
			mainForum($conn,$teamId,$teamName,$user);
			$conn=null;
		?>
		<div class="paddingTop"></div>
		<form action="../resources.php" method="GET">
			<input name="hiddenTeamId" id="hiddenTeamId" type="hidden" value="<?php echo $teamId; ?>">
			<input name="hiddenTeamName" id="hiddenTeamName" type="hidden" value="<?php echo $teamName ?>">
			<button class="backButton"><i class="fa fa-arrow-left"></i></button>
		</form>
	</div>
	<script type="text/javascript" src="../includes/js/functions.inc.js"></script>
</body>
</html>
