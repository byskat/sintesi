<?php
	require('../includes/php/db.inc.php');
	require('./includes/php/userValidation.inc.php');
	require('./includes/php/functions.inc.php');

	if(!isset($_GET["toTeamsProjId"])){	
		header('Location: ./login.php');
	}

	$_SESSION['toTeamsProjId']=$_GET["toTeamsProjId"];
	$_SESSION['toTeamsDesc']=$_GET['toTeamsDesc'];
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equips</title>        
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
				<h1>Equips</h1>
			</div>
		</div>

		<div class="itemList">
			<div class="descriptionProject shadowBox"><b>Descripció del projecte:</b> <?php echo $_GET['toTeamsDesc'] ?></div>		

			<?php				

				$idProj = $_GET['toTeamsProjId'];
				
				//Obtenc la id de la inscripcio de l'usuari a partir de la id d'usuari
				$result = executePreparedQuery($conn, "SELECT * FROM inscriptions WHERE users_id = :userId", array(':userId'=>$_SESSION['userID']), false);				
				$idInscription = $result->id;
				
				//Seleccionbo tots els projectes els quals la seva ID de conexio es la que revo per GET.
            	$sql = "SELECT tms.id, tms.name, tms.startDate, tms.endDate FROM teams tms, teamsprojects tp WHERE tms.id = tp.teams_id AND tp.projects_id = :idProj";
            	$arr = array(':idProj'=>$idProj);
            	$results = executePreparedQuery($conn, $sql, $arr, true);
				
			    //Comprovo que la consulta hagi tornat com a minim un resultat
			    if($results != false){
			    	//Per cada resultat trobat obtinc totes les seves dades i la genero per mosrar-lo.
				    foreach ($results as $result) {

				    	$teamId = $result->id;
				    	$teamName = $result->name;
				    	$startDate = formatDate('Y-m-d', 'd/m/Y', $result->startDate);
				    	$endDate = formatDate('Y-m-d', 'd/m/Y', $result->endDate);

				    	
				    	//Miro si l'usuari loguejat esta instrit en l'equip actual
				    	$userFoundInThisProject = executePreparedQuery($conn, "SELECT * FROM `inscriptionsteams` WHERE teams_id = :teamId AND inscription_id = :inscription_id", array(':teamId'=>$teamId, 'inscription_id'=>$idInscription), false);
			?>
			    	<div class="item shadowBox">		    		
				    	
				    	<form id="<?php echo $teamId?>" action="">
							<!-- Guardo en camps ocults la informació que vull pasar a Javascript un cop premi el botó de mostrar la configuració d'aquest equip -->
				    		<input id="hiddenIdProj" type="hidden" name="hiddenIdProj" value="<?php echo $teamId ?>" />
							<input id="hiddenName" type="hidden" name="hiddenName" value="<?php echo $teamName ?>" />
							<input id="hiddenStartDate" type="hidden" name="hiddenStartDate" value="<?php echo $startDate ?>" />
							<input id="hiddenEndDate" type="hidden" name="hiddenEndDate" value="<?php echo $endDate ?>" />
							<input id="hiddenTeamId" type="hidden" name="hiddenTeamId" value="<?php if(isset($teamId)){ echo $teamId; } ?>" />							
							
							<div class="headerItem">
								<h2 id="teamName"> <?php echo $teamName?> </h2>	
								<!-- Només mostro l'opció de de configurar equips si és professor -->							
								<?php if($_SESSION['role'] == 2){ ?>
									<input id="settings" type="button" onClick="openConfig($(this.form),event)" class="settings" value="&#xf013;">
								<?php } ?>							 
							</div>
						</form>					
						
						<form id="join" action="resources.php" method="GET">   
						    <!--Per si no hi ha cap team llogicament no es pot setejar el valor de teamId per tant ho controlo-->    
						                                
						    <input id="hiddenInscriptionId" type="hidden" name="hiddenInscriptionId" value="<?php echo $idInscription ?>" />
						    <input id="hiddenTeamName" type="hidden" name="hiddenTeamName" value="<?php if(isset($teamName)){ echo $teamName; } ?>" />
						    <input id="hiddenTeamId1" type="hidden" name="hiddenTeamId1" value="<?php if(isset($teamId)){ echo $teamId; } ?>" />
						    <?php 
						        /*                            
						         * En aquest bloc comprobo si l'usuari actual esta inscrit en el projecte:
						         * Si no hi está mostro el botó per unir-se i un span buit per un cop unit mostrar el botó a recursos, si ho está mostro el botó re accedir als recursos.
						        */
						            
						        if($userFoundInThisProject == false){
						            echo("<input id=\"join\" type=\"button\" onClick=\"joinTeam($(this.form),event)\" value=\"Uneix-te\">");
						            echo("<span id =\"goin\"></span>");
						        }else{
						            echo("<input id=\"toResources\" type=\"submit\" value=\"Entra\">");
						        }
						    ?>
						</form>

					</div>			    

		    <?php }} ?>			

			<!-- Només mostro l'opció de crear equips si és professor -->
			<?php if($_SESSION['role'] == 2){ ?>
				<div class="itemAdd shadowBox">
					<button onclick="createNew()">Afegir nou<br><i class="fa fa-plus"></i></button>
				</div>
			<?php } ?>

		</div>	

		<div class="paddingTop"></div>
		<form action="projects.php" method="GET">
			<input name="toProjectsIdConn" id="toProjectsIdConn" type="hidden" value="<?php echo $_SESSION['toProjectsIdConn'] ?>">
			<button class="backButton"><i class="fa fa-arrow-left"></i></button>
		</form>
	</div>
	
	<!-- En aquest bloc creo el formulari emergent que surt al modificar i crear un equip -->
	<div class="blackScreen" style="display:none">
		<div class="formBox shadowBox" >
			<form action="">
				<h3>Edició de l'equip <span id="nameHeader" class="important"></span></h3>
				
				<input id="hiddenProjId" type="hidden" name="hiddenProjId" value="<?php echo $idProj ?>" />
				<input id="hiddenTeamId" type="hidden" name="hiddenTeamId" value="<?php if(isset($teamId)){ echo $teamId; } ?>" />

				<span class="tag">Nom:</span><input id="nameConfig" name="name" class="tag">
				<span class="tag">Data inici:</span><input id="startDate" disabled name="startDate" class="tag">
				<span class="tag">Data fi:</span><input id="endDate" name="endDate" class="tag">		
				<p class="center"><input type="button" class="redButton" onClick="saveConfig($(this.form),event)" value="enviar"></p>
			</form>
		</div>
	</div>

	<script type="text/javascript" src="./includes/js/functions.inc.js"></script>
	<script>
		var clickedId = "";
		var action = "update";
		var teamId = null;

		/*
		 * Aquesta funció  fa una petició a servidor per unir l'usuari connectat al grup solicitat 
		 * i modificar l'opció unir-se per el botó a recursos. Aquest procés el fa per AJAX.
		 */
		
		function joinTeam(form, event){

      var serialized = ($(form).serializeArray());            
      var teamId = serialized[2]['value'];
      var inscriptionId = serialized[0]['value'];

      var url = "/includes/php/userJoinAjax.php?&teamId=" + teamId + "&inscriptionId=" + inscriptionId;
      var myQuery = getXMLHTTPRequest();
      
      myQuery.open("GET", url , true);
      myQuery.onreadystatechange = responseAjax;        
      myQuery.send(null);

      function responseAjax(){
        if(myQuery.readyState == 4){        
            if(myQuery.status == 200){

                location.reload();
                document.getElementById('"hiddenTeamId1"').value = teamId;
                document.getElementById('goin').innerHTML = "<input id=\"toResources\" type=\"submit\" style=\"display:none\" value=\"Entra\">";
                $('#toResources').click();                        
            }else{
                alert("Error " + myQuery.status);
            }
        }
      }
  	}   

		/*
		 * Aquesta funció obra el formulari de configuració de l'equip clickat amb les seves dades. 
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
			teamId = serialized[4]['value'];

			$('.blackScreen').show();
			
		}

		function saveConfig(form, event){
			event.preventDefault();
			
			//Recupero les dades dels formularis per treballar amb elles.
			var serialized = ($(form).serializeArray());

			var projId = serialized[0]['value'];
			var teamName = $('#nameConfig').val();
			var teamEndDate = $('#endDate').val();			

			//Defineixo la url i creo el XMLHTTPRequest.
			var url = "/includes/php/teamsAjax.php?&formId=" + clickedId + "&projId=" + projId + "&teamId=" + teamId + "&teamName=" + teamName + "&teamEndDate=" + teamEndDate + "&action=" + action;
			var myQuery = getXMLHTTPRequest();					 		

			//Oculta la finestra de configuració.
			$('.blackScreen').hide();
					
			//Comprobo que els camps que s'han d'omplir estiguin tots plens.
			if(teamName.length > 0 && teamEndDate.length > 0){
				//Comprovo que la dafa de inalitzar no sigui incorrecte.
				if(compareDates(teamEndDate)){					

					myQuery.open("GET", url , true);
					myQuery.onreadystatechange = responseAjax;		
					myQuery.send(null);
				}else{
					alert("Data incorrecte o projecte caducat (Renova la data)");
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
						
							//Actualitzo els camps dels equips:						
							$('form#'+response.formId).find('#teamName').html(response.teamName);
						}

						/*
						 * Si el nom existeix al intentar cerear la connexió, torna missatge d'error.
						 * Si no el tira recarega la pagina per mostrar el nou projecte.
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

			$('#nameHeader').text("");
			$('#nameConfig').val("");			
			$('#startDate').val("");
			$('#endDate').val("");			
		}

	</script>	
</body>
</html>