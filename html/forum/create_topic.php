<?php 
	require('db.inc.php'); 
	require('functions.php'); 
?>

<!DOCTYPE html>
<html>
<?php head("Forum | Nou Tema"); ?>
<body>
	<div class="panel">
		<?php newTopic(); ?>
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