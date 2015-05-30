<?php
	require('../includes/php/db.inc.php');
	require('./includes/php/userValidation.inc.php');
	require('./includes/php/functions.inc.php');

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panell</title>        
    <?php require('./includes/php/header.inc.php'); ?>
</head>
<body>

	<?php
		require('./includes/php/topBar.inc.php');
		require('./includes/php/leftMenu.inc.php');
	?>	

	<div class="panel">
		<div class="itemList">			

			<?php

				//Obtinc el rol i la id del centre l'usuari connectat
				
				$result = executeQuery($conn, "SELECT id, role FROM users WHERE id ='" . $userID . "'");
				
				$userNameId = $result->id;
				$userRole = $result->role;

				//A partir de la ID de l'usuari connectat trec la id del centre al que pertany
				
				$result = executeQuery($conn, "SELECT centers_id FROM inscriptions WHERE users_id ='" . $userNameId . "'");				
				$userNameCenterId = $result->centers_id;
				
				//Selecciono tota la taula connexions per mostrar-les
				
				$results = executeQuery($conn, "SELECT * FROM connections");

			    foreach ($results as $result) {

			    	$connId = $result->id;
			    	$connName = $result->name;
			    	$startDate = formatDate('Y-m-d', 'd/m/Y', $result->startDate);
			    	$endDate = formatDate('Y-m-d', 'd/m/Y', $result->endDate);        			

			    	//A partir de la id de cada centre en trec el seu nom. Per cada cas comprobo tambe la id de la taula connexio per saver les ID dels centres en questio
			    	$result = executeQuery($conn, "SELECT c.name FROM centers c, connections conn WHERE c.id = conn.idcenter1 AND conn.id =" . $connId);
			    	$center1 = $result->name;

                	$result = executeQuery($conn, "SELECT c.name FROM centers c, connections conn WHERE c.id = conn.idcenter2 AND conn.id =" . $connId);
                	$center2 = $result->name;
                	
			?>
			    	<div class="item shadowBox">
				    	
				    	<form id="<?php echo $connId ?>" action="">

				    		<input id="hiddenIdConn" type="hidden" name="hiddenIdConn" value="<?php echo $connId ?>" />
							<input id="hiddenName" type="hidden" name="hiddenName" value="<?php echo $connName ?>" />							
							<input id="hiddenCenter2" type="hidden" name="hiddenCentre2" value="<?php echo $center2 ?>" />
							<input id="hiddenStartDate" type="hidden" name="hiddenStartDate" value="<?php echo $startDate ?>" />
							<input id="hiddenEndDate" type="hidden" name="hiddenEndDate" value="<?php echo $endDate ?>" />							
							
							<div class="headerItem">
								<h2 id="connectionName"> <?php echo $connName ?> </h2>
								<?php if($userRole == 2){ ?>
								<input id="settings" type="button" onClick="openConfig($(this.form),event)" class="settings" value="&#xf013;">
								<?php } ?>
								 
							</div>
							<div class="bodyHeader">
								<h3 id="connectionCenters"><?php echo $center1 . " & " . $center2 ?></h3>								
							</div>							
					</form>
					<form action="projects.php" id="toProjects" method="POST">
						<input id="toProjectsIdConn" type="hidden" name="toProjectsIdConn" value="<?php echo $connId ?>" />
						<input type="submit" value="Projects">
					</form>
				</div>			    

		    <?php } ?>			

		</div>
		<?php if($userRole == 2){ ?>
		<div class="itemAdd shadowBox">
			<button onclick="createNew()">Afegir nou<br><i class="fa fa-plus"></i></button>
		</div>
		<?php } ?>
	</div>

	<div class="blackScreen" style="display:none">
		<div class="formBox shadowBox" >
			<form action="">
				<input id="hiddenIdConn" type="hidden" name="hiddenIdConn" value="<?php echo $userNameCenterId ?>" />
				<h3>Edició de la conexió <span id="nameHeader" class="important"></span></h3>
				<span class="tag">Nom:</span><input id="nameConfig" name="name" class="tag">
				<span class="tag">Data inici:</span><input id="startDate" disabled name="startDate" class="tag">
				<span class="tag">Data fi:</span><input id="endDate" name="endDate" class="tag">
				<span class="tag">Centre 2:</span><select id="center" name="center" class="tag">
					<?php echo fillDropDownCenters($conn) ?>
				</select>
				<p class="center"><input type="button" class="redButton" onClick="saveConfig($(this.form),event)" value="enviar"></p>
			</form>
		</div>
	</div>
	<script src="./includes/js/resources.js"> </script>
</body>
</html>
