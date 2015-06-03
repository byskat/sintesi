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
							    
			    $results = executeQuery($conn, "SELECT * FROM connections");	

			    foreach ($results as $result) {

			    	$connId = $result->id;
			    	$userNameCenterId = $result->idcenter1;			    	
			    	$connName = $result->name;
			    	$startDate = formatDate('Y-m-d', 'd/m/Y', $result->startDate);
			    	$endDate = formatDate('Y-m-d', 'd/m/Y', $result->endDate);
			    	$outdated = $result->outdated;        			

			    	//A partir de la id de cada centre en trec el seu nom. Per cada cas comprobo tambe la id de la taula connexio per saver les ID dels centres en questio
			    	$result = executePreparedQuery($conn, "SELECT c.name FROM centers c, connections conn WHERE c.id = conn.idcenter1 AND conn.id = :connId", array(':connId'=>$connId), false);			    	
			    	$nameCenter1 = $result->name;

                	$result = executePreparedQuery($conn, "SELECT c.name FROM centers c, connections conn WHERE c.id = conn.idcenter2 AND conn.id = :connId", array(':connId'=>$connId), false);
                	$nameCenter2 = $result->name;
                	
			?>
			    	<div class="item shadowBox">
				    	
				    	<form id="<?php echo $connId ?>" action="">

				    		<input id="hiddenIdConn" type="hidden" name="hiddenIdConn" value="<?php echo $connId ?>" />
							<input id="hiddenName" type="hidden" name="hiddenName" value="<?php echo $connName ?>" />							
							<input id="hiddenCenter2" type="hidden" name="hiddenCentre2" value="<?php echo $nameCenter2 ?>" />
							<input id="hiddenStartDate" type="hidden" name="hiddenStartDate" value="<?php echo $startDate ?>" />
							<input id="hiddenEndDate" type="hidden" name="hiddenEndDate" value="<?php echo $endDate ?>" />							
							
							<div class="headerItem">
								<h2 id="connectionName"> <?php echo $connName ?> </h2>
								<?php if($_SESSION['role'] == 2){ ?>
								<input id="settings" type="button" onClick="openConfig($(this.form),event)" class="settings" value="&#xf013;">
								<?php } ?>
								 
							</div>
							<div class="bodyHeader">
								<h3 id="connectionCenters"><?php echo $nameCenter1 . " & " . $nameCenter2 ?></h3>								
							</div>							
					</form>
					<form action="projects.php" id="toProjects" method="GET">
						<input id="toProjectsIdConn" type="hidden" name="toProjectsIdConn" value="<?php echo $connId ?>" />
						<?php 
							
							if(checkOutdated( formatDate('d/m/Y', 'Y-m-d', $endDate) )){
								executeInsertUpdateQuery($conn, "UPDATE connections SET outdated = 1 WHERE name = :connName ", array(':connName'=>$connName) );
							}					
							
							if($outdated == 0){
								echo("<input id=\"outdatedBtn\" type=\"submit\" value=\"Projects\">");
							}else{
								echo("<span id=\"updated\"> </span>");
								echo("<div id=\"outdated\">Caducat</div>");
							}
						?>
					</form>
				</div>			    

		    <?php } ?>			

		</div>
		<?php if($_SESSION['role'] == 2){ ?>
		<div class="itemAdd shadowBox">
			<button onclick="createNew()">Afegir nou<br><i class="fa fa-plus"></i></button>
		</div>
		<?php } ?>
	</div>

	<div class="blackScreen" style="display:none">
		<div class="formBox shadowBox" >
			<form action="">
				<input id="hiddenCenter1Id" type="hidden" name="hiddenCenter1Id" value="<?php echo $userNameCenterId ?>" />			
				<input id="hiddenNameCenter1" type="hidden" name="hiddenNameCenter1" value="<?php echo $nameCenter1 ?>" />

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
	<script src="./includes/js/connections.js"></script>	
</body>
</html>
