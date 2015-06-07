<?php
	require('../includes/php/db.inc.php');
	require('./includes/php/userValidation.inc.php');
	require('./includes/php/functions.inc.php');

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexions</title>        
    <?php require('./includes/php/header.inc.php'); ?>
</head>
<body>

	<?php
		require('./includes/php/topBar.inc.php');
		require('./includes/php/leftMenu.inc.php');
	?>	

	<div class="panel">
		<div class="itemHeader">
			<div class="itemTitle">
				<h1>Connexions</h1>
			</div>
		</div>
		<div class="itemList">			

			<?php
				//Obtinc la ID del centre el cual pertany l'usuari conectat.
				$result = executePreparedQuery($conn, "SELECT centers_id FROM inscriptions WHERE users_id = :userId", array(':userId'=>$_SESSION['userID']), false);
				$userNameCenterId = $result->centers_id;

			    //Selecciono tots els resultats de la taula connections per mostar-los.
			    $results = executeQuery($conn, "SELECT * FROM connections", true);

			    //Miro si hi ha alguna connexió i la mostro.
			    if($results != false){

				    //Per cada resultat trobat obtinc totes les seves dades i la genero per mosrar-lo.
				    foreach ($results as $result) {

				    	$connId = $result->id;		    	
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
							
				    		<!-- Guardo en camps ocults la informació que vull pasar a Javascript un cop premi el botó de mostrar la configuració d'aquesta connexió -->
				    		<input id="hiddenIdConn" type="hidden" name="hiddenIdConn" value="<?php echo $connId ?>" />
							<input id="hiddenName" type="hidden" name="hiddenName" value="<?php echo $connName ?>" />							
							<input id="hiddenCenter2" type="hidden" name="hiddenCentre2" value="<?php echo $nameCenter2 ?>" />
							<input id="hiddenStartDate" type="hidden" name="hiddenStartDate" value="<?php echo $startDate ?>" />
							<input id="hiddenEndDate" type="hidden" name="hiddenEndDate" value="<?php echo $endDate ?>" />							
							
							<div class="headerItem">
								<h2 id="connectionName"> <?php echo $connName ?> </h2>
								<!-- Només mostro l'opció de de configurar connexións si és professor -->
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
							/*							
							 * En aquest bloc comprobo que la connexió no estigui caducada:
							 * mostro l'enllaç a projectes si no ho está i si ho está mostro un missatge caducat i deixo un 
							 * span buit on posteriorment, un cop allargada la data, mostraré el botó a Projectes
							*/							
							if(checkOutdated( formatDate('d/m/Y', 'Y-m-d', $endDate) )){
								executeInsertUpdateQuery($conn, "UPDATE connections SET outdated = 1 WHERE name = :connName ", array(':connName'=>$connName) );
							}					
							
							if($outdated == 0){
								echo("<input id=\"outdatedBtn\" class=\"itemBottom\" type=\"submit\" value=\"Projectes\">");
							}else{
								echo("<span class=\"itemBottom\" id=\"updated\"> </span>");
								echo("<div class=\"itemBottom\" id=\"outdated\">Caducat</div>");
							}
						?>
					</form>
				</div>			    

		    <?php }} ?>			

			<!-- Només mostro l'opció de crear connexións si és professor -->
			<?php if($_SESSION['role'] == 2){ ?>
				<div class="itemAdd shadowBox">
					<button onclick="createNew()">Afegir nou<br><i class="fa fa-plus"></i></button>
				</div>
			<?php } ?>

		</div>
	</div>

	<!-- En aquest bloc creo el formulari emergent que surt al modificar i crear una connexió -->
	<div class="blackScreen" style="display:none">
		<div class="formBox shadowBox" >
			<form action="">
				<input id="hiddenCenter1Id" type="hidden" name="hiddenCenter1Id" value="<?php if(isset($userNameCenterId)){ echo $userNameCenterId; } ?>" />			
				<input id="hiddenNameCenter1" type="hidden" name="hiddenNameCenter1" value="<?php if(isset($nameCenter1)){ echo $nameCenter1; }  ?>" />

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
	<script type="text/javascript" src="./includes/js/functions.inc.js"></script>
	<script>
		var clickedId = "";
		var action = "update";

		/*
		 * Aquesta funció obra el formulari de configuració de la connexió clickada amb les seves dades. 
		 * És aqui on serialitza el formulari i per tant obté la informació continguda en els hidden. 
		*/
	
		function openConfig(form, event){			
			event.preventDefault();
			var serialized = ($(form).serializeArray());
			
			clickedId = serialized[0]['value'];

			$('#nameHeader').text(serialized[1]['value']);
			$('#nameConfig').val(serialized[1]['value']);
			$('#endDate').val(serialized[4]['value']);
			$('#startDate').val(serialized[3]['value']);
			$('#center').val(serialized[2]['value']);    	

			if(action == "update"){
				$('#center').prop('disabled', 'disabled');
			}

			$('.blackScreen').show();			
		}

		/*
		 * Aquesta funció guarda canvis en la connexió, ja sigui crear una de nova o modificar una existent.
		 * Per evitar recarregar la pàgina sempre que és possible i millorar la fluidesa de l'usuari, fa aquest
		 * procés per AJAX.
		*/
	
		function saveConfig(form, event){
			event.preventDefault();
			
			//Recupero les dades dels formularis per treballar amb elles.
			var serialized = ($(form).serializeArray());

			var userNameCenterId = serialized[0].value;
			var connCenter1Name = serialized[1].value;
			var connName = $('#nameConfig').val();
			var connEndDate = $('#endDate').val();
			var connCenter2Name = $('#center').val();		

			//Defineixo la url i creo el XMLHTTPRequest.
			var url = "includes/php/connectionsAjax.php?&formId=" + clickedId + "&connName=" + connName + "&connEndDate=" + connEndDate + "&connCenter1Name=" + connCenter1Name + "&connCenter2Name=" + connCenter2Name + "&userNameCenterId=" + userNameCenterId + "&action=" + action;
			var myQuery = getXMLHTTPRequest();					 		

			//Oculta la finestra de configuració.
			$('.blackScreen').hide();

					
			//Comprobo que els camps que s'han d'omplir estiguin tots plens.
			if(connName.length > 0 && connEndDate.length > 0 && connCenter2Name != "Selecciona un centre"){
				//Comprovo que la dafa de inalitzar no sigui incorrecte.
				if(compareDates(connEndDate)){					
					// Si tot és correcte faig la petició al servidor.
					myQuery.open("GET", url , true);
					myQuery.onreadystatechange = responseAjax;		
					myQuery.send(null);
				}else{
					alert("Data incorrecte o connexió caducada (Renova la data)");
				}				
			}else{
				alert("Falta algun camp");
			}	

			/*
			 * Aquesta funció processa els resultat obtinguts pel servidor.
			 * En aquest cas faig distincions per si s'actualitza un element o es crea un de nou ja que les respostes seràn diferents.
			 */
			function responseAjax(){

				if(myQuery.readyState == 4){		
					if(myQuery.status == 200){

						var response = myQuery.responseText;	
						
						if(action == "update"){
							response = JSON.parse(response);
						
							//Actualitzo els camps de les connexions:						
							$('form#'+response.connId).find('#connectionName').html(response.connName);

							//Si s'ha actualitzat la data, actualitzo l'estat de caducitat.
							if(response.outdated == true){
								$('#outdated').hide();
								document.getElementById('updated').innerHTML = "<input id=\"outdatedBtn\" type=\"submit\" value=\"Projects\">";				
							}
						}

						/*
						 * Si el nom existeix al intentar cerear la connexió, torna missatge d'error.
						 * Si no el tira recarega la pagina per mostrar la nova connexió.
						*/
						
						if(action == "create" && response.length > 0){
							alert(myQuery.responseText);
						}else if(action == "create" && response.length == 0){
							location.reload();
						}										
						
					}else{
						alert("Error " + myQuery.status);
					}
				}
			}
		}		

		//Al crear una nova connexió s'ha de buidar el formulari d'inserció
		function resetNew(){

			$('#nameHeader').text("");
			$('#nameConfig').val("");
			$('#endDate').val("");
			$('#startDate').val("");
			$('#center')[0].selectedIndex = 0;				
		}

	</script>	
</body>
</html>
