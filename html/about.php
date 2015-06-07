<?php
	require('../includes/php/db.inc.php');
	require('./includes/php/userValidation.inc.php');
	require('./includes/php/functions.inc.php');
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sobre Nosaltres</title>        
    <?php require('./includes/php/header.inc.php'); ?>
</head>
<body>
	<?php
		require('./includes/php/topBar.inc.php');
		require('./includes/php/leftMenu.inc.php');
	?>
	<div class="panel darkBg" style="border: 0; right: 0;">
		<div class="vidContainer">

			<video autoplay loop muted class="videoHeader" id="videoHeader">
				<source src="images/The Mountain_sd.mp4" type="video/mp4">
				<source src="images/The Mountain_sd.ogv" type="video/ogv">
				<source src="images/The Mountain_sd.webm" type="video/webm">
			</video>
			<div class="vidMarkee"></div>

			<div class="vidSection">
				<h1>quant a <span class="important">Nosaltres</span></h1>

				<div class="vidColumn">
					<div class="vidText">
					<h2>D'on venim?</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					</div>
				</div>
				<div class="vidColumn">
					<div class="vidText">
					<h2>Què som?</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					</div>
				</div>
				<div class="vidColumn">
					<div class="vidText">
					<h2>On anem?</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="./includes/js/functions.inc.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$('.panel').height($('.vidSection').outerHeight());
		$(window).resize(function (){
			$('.panel').height($('.vidSection').outerHeight());
		});
	});
	</script>
</body>
</html>