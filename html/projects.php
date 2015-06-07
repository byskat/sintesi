<?php
	require('../includes/php/db.inc.php');
	require('./includes/php/userValidation.inc.php');
	require('./includes/php/functions.inc.php');

	if(!isset($_GET["toProjectsIdConn"])){	
		header('Location: ./login.php');
	}

	$_SESSION["toProjectsIdConn"]=$_GET["toProjectsIdConn"];
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projectes</title>        
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
				<h1>Projectes</h1>
			</div>
		</div>
		<div class="itemList">		

			<?php				
				
				$idConnection = $_GET['toProjectsIdConn'];				
				
				//Seleccionbo tots els projectes els quals la seva ID de conexio es la que revo per POST.				
            	$sql = "SELECT proj.id, proj.name, proj.startDate, proj.endDate, proj.description, proj.outdated FROM projects proj, connectionsprojects cp WHERE proj.id = cp.projects_id AND cp.connections_id = :idConnection";
            	$arr = array(':idConnection'=>$idConnection);
            	$results = executePreparedQuery($conn, $sql, $arr, true);
				
			    //Comprovo que la consulta hagi tornat com a minim un resultat
			    if($results != false){
			    	//Per cada resultat trobat obtinc totes les seves dades i la genero per mosrar-lo
				    foreach ($results as $result) {
				    	$projId = $result->id;
				    	$projName = $result->name;
				    	$startDate = formatDate('Y-m-d', 'd/m/Y', $result->startDate);
				    	$endDate = formatDate('Y-m-d', 'd/m/Y', $result->endDate);
				    	$projDescription = $result->description;
              			$outdated = $result->outdated;
						?>
			    	<div class="item shadowBox">
				    	
				    	<form id="<?php echo $projId?>" action="">
							<!-- Guardo en camps ocults la informació que vull pasar a Javascript un cop premi el botó de mostrar la configuració d'aquest projecte -->
				    		<input id="hiddenIdProj" type="hidden" name="hiddenIdProj" value="<?php echo $projId ?>" />
							<input id="hiddenName" type="hidden" name="hiddenName" value="<?php echo $projName ?>" />
							<input id="hiddenStartDate" type="hidden" name="hiddenStartDate" value="<?php echo $startDate ?>" />
							<input id="hiddenEndDate" type="hidden" name="hiddenEndDate" value="<?php echo $endDate ?>" />
							<input id="hiddenProjDesc" type="hidden" name="hiddenProjDesc" value="<?php echo $projDescription ?>" />							
							
							<div class="headerItem">
								<h2 id="projectName"> <?php echo $projName?> </h2>								
								<?php if($_SESSION['role'] == 2){ ?>
									<input id="settings" type="button" onClick="openConfig($(this.form),event)" class="settings" value="&#xf013;">
								<?php } ?>							 
							</div>

						</form>
						<form action="teams.php" id="toProjects" method="GET">
							<input id="toTeamsProjId" type="hidden" name="toTeamsProjId" value="<?php echo $projId ?>" />
							<input id="toTeamsDesc" type="hidden" name="toTeamsDesc" value="<?php echo $projDescription ?>" />

							<?php 
								/*							
								 * En aquest bloc comprobo que el projecte no estigui caducat:
								 * mostro l'enllaç a equips si no ho está i si ho está mostro un missatge caducat i deixo un 
								 * span buit on posteriorment, un cop allargada la data, mostraré el botó a equips.
								*/	
								if(checkOutdated( formatDate('d/m/Y', 'Y-m-d', $endDate) )){
									executeInsertUpdateQuery($conn, "UPDATE projects SET outdated = 1 WHERE id = :projId ", array(':projId'=>$projId) );
								}					
								
								if($outdated == 0){
									echo("<input class=\"itemBottom\" id=\"outdatedBtn\" type=\"submit\" value=\"Equips\">");
								}else{
									echo("<span class=\"itemBottom\" id=\"updated\"> </span>");
									echo("<div class=\"itemBottom\" id=\"outdated\">Caducat</div>");
								}
							?>						
						</form>
					</div>			    

		    <?php }} ?>

			<!-- Només mostro l'opció de de crear connexións si és professor -->
		    <?php if($_SESSION['role'] == 2){ ?>
				<div class="itemAdd shadowBox">
					<button onclick="createNew()">Afegir nou<br><i class="fa fa-plus"></i></button>
				</div>
			<?php } ?>
		</div>

		<div class="paddingTop"></div>
		<form action="connections.php" method="GET">
			<button class="backButton"><i class="fa fa-arrow-left"></i></button>
		</form>
	</div>
	
	<!-- En aquest bloc creo el formulari emergent que surt al modificar i crear un projecte -->
	<div class="blackScreen" style="display:none">
		<div class="formBox shadowBox" >
			<form action="">
				<h3>Edició del projecte <span id="nameHeader" class="important"></span></h3>
				
				<input id="hiddenConnId" type="hidden" name="hiddenConnId" value="<?php if(isset($idConnection)){ echo $idConnection; } ?>" />
				<input id="hiddenProjId" type="hidden" name="hiddenProjId" value="<?php if(isset($projId)){ echo $projId; } ?>" />

				<span class="tag">Nom:</span><input id="nameConfig" name="name" class="tag">
				<span class="tag">Data inici:</span><input id="startDate" disabled name="startDate" class="tag">
				<span class="tag">Data fi:</span><input id="endDate" name="endDate" class="tag">
				<span class="tag">Descripció:</span><input id="projDesc" name="projDesc" class="tag">				
				<p class="center"><input type="button" class="redButton" onClick="saveConfig($(this.form),event)" value="enviar"></p>
			</form>
		</div>
	</div>
	
	<script type="text/javascript" src="./includes/js/functions.inc.js"></script>
	<script>
		var clickedId = "";
		var action = "update";

		/*
		 * Aquesta funció obra el formulari de configuració del projecte clickat amb les seves dades. 
		 * És aqui on serialitza el formulari i per tant obté la informació continguda en els hidden. 
		*/
	
		function openConfig(form, event){			
			event.preventDefault();
			var serialized = ($(form).serializeArray());
			
			clickedId = serialized[0]['value'];

			$('#nameHeader').text(serialized[1]['value']);
			$('#nameConfig').val(serialized[1]['value']);			
			$('#startDate').val(serialized[2]['value']);
			$('#endDate').val(serialized[3]['value']);
			$('#projDesc').val(serialized[4]['value']);

			$('.blackScreen').show();
			
		}

		/*
		 * Aquesta funció guarda canvis en el projecte, ja sigui crear un de nou o modificar una existent.
		 * Per evitar recarregar la pàgina sempre que és possible i millorar la fluidesa de l'usuari, fa aquest
		 * procés per AJAX.
		*/

		function saveConfig(form, event){
			event.preventDefault();
			
			//Recupero les dades dels formularis per treballar amb elles.
			var serialized = ($(form).serializeArray());
			var idConn = serialized[0]['value'];
			var idProj = serialized[1]['value'];
			var projName = $('#nameConfig').val();
			var projEndDate = $('#endDate').val();
			var projDesc = $('#projDesc').val();				

			//Defineixo la url i creo el XMLHTTPRequest.
			var url = "includes/php/projectsAjax.php?&idConn=" + idConn + "&idProj=" + clickedId + "&projName=" + projName + "&projEndDate=" + projEndDate + "&projDesc=" + projDesc + "&action=" + action;
			var myQuery = getXMLHTTPRequest();					 		

			//Oculta la finestra de configuració.
			$('.blackScreen').hide();

					
			//Comprobo que els camps que s0han d'omplir estiguin tots plens.
			if(projName.length > 0 && projEndDate.length > 0 && projDesc.length > 0){
				//Comprovo que la dafa de inalitzar no sigui incorrecte.
				if(compareDates(projEndDate)){					
					// Si tot és correcte faig la petició al servidor.
					myQuery.open("GET", url , true);
					myQuery.onreadystatechange = responseAjax;		
					myQuery.send(null);
				}else{
					alert("Data incorrecte o connexio caducada (Renova la data)");
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
							$('form#'+response.projId).find('#projectName').html(response.projName);

							//Si updateOutdated = true actualitzo l'estat de caducitat.
							console.log(response.procesed);
							if(response.procesed == true){	
								$('#outdated').hide();
								document.getElementById("updated").innerHTML = "<input id=\"outdatedBtn\" type=\"submit\" value=\"Teams\">";				
							}
						}

						/*
						 * Si el nom existeix al intentar cerear el projecte, torna missatge d'error.
						 * Si no el tira, recarega la pagina per mostrar el nou projecte.
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

		function resetNew(){

			$('#nameConfig').val("");			
			$('#startDate').val("");
			$('#endDate').val("");
			$('#projDesc').val("");			
		}
	</script>	
</body>
</html>
