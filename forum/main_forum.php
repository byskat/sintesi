<?php 
	require('db.inc.php'); 
	require('functions.php'); 
?>

<!DOCTYPE html>
<html>
<?php head("Forum"); ?>
<body>

	<div class="panel">
		<div class="itemList">

			<?php 
				mainForum($conn);
				$conn=null;
			?>

		</div>
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
