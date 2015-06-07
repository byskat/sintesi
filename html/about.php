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
						<p>Aquesta aplicació es el resultat de l'experiència personal i professional que hem tingut els desenvolupadors d'aquest projecte durant la nostre formació. Hem pogut comprovar com en l'àmbit acadèmic els centres educatius rarament es compenetren i treballen plegats, tancant als seus estudiants nous horitzons i  la possibilitat d'entendre que en el món laboral es trobaran envoltats de professionals d'altres disciplines.</p>
					</div>
				</div>
				<div class="vidColumn">
					<div class="vidText">
					<h2>Què som?</h2>
						<p>Som una plataforma que permet acostar els diferents mons educatius, les diferents disciplines i comunitats acadèmiques perquè entre elles, pugin debatre, treballar i créixer en un únic lloc, sense preocupacions d'horaris ni localitat. Acostar als estudiants perquè tinguin la possibilitat d'aprendre junts i en equips multidisciplinaris, amb la supervisió de professors i personal acadèmic que acabi de donar l'ajuda que els estudiants necessiten.</p>
					</div>
				</div>
				<div class="vidColumn">
					<div class="vidText">
					<h2>On anem?</h2>
						<p>El futur es incert, i si bé mai hi ha res segur, si podem dir que si la gent troba útil i necessari aquest projecte, assegurem estar aquí i saber estar a la altura de les expectatives. Seguir millorant i treballant amb professors, alumnes i centres educatius que millorar l'experiència i seguir ajudant a tots aquells que realment estan disposats a aprendre i a desenvolupar-se en les seves especialitats, sense oblidar mai, que en aquest camí, no estan sols.</p>
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