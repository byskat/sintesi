<?php 
	//Aixó es una vista com deu mana!
	require('../../includes/php/db.inc.php');
	require('../includes/php/userValidation.inc.php');
	require('../includes/php/functions.inc.php');
	require('functions.php');
?>

<!DOCTYPE html>
<html>
<?php head("Forum | Nou Tema"); ?>
<body>

	<?php
		require('../includes/php/topBar.inc.php');
		require('../includes/php/leftMenu.inc.php');		
	?>	

	<div class="panel">
		<?php newTopic(); ?>
		<a class="backButton" href="main_forum.php"><i class="fa fa-arrow-left"></i></a>
	</div>
	<script type="text/javascript" src="../includes/js/functions.inc.js"></script>
</body>
</html>