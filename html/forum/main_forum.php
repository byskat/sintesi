<?php 
	require('db.inc.php'); 
	require('functions.php'); 
?>

<!DOCTYPE html>
<html>
<?php head("Forum"); ?>
<body>

	<div class="panel">
		<?php 
			//Crida a la funció que genera el forum.
			mainForum($conn);
			$conn=null;
		?>
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
