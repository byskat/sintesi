<?php
	require('../includes/php/db.inc.php');
	require('./includes/php/userValidation.inc.php');
	require('./includes/php/functions.inc.php');

	if(!isset($_GET["toTeamsProjId"])){	
		header('Location: ./login.php');
	}
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
		<div class="itemList">
			<div style="margin-bottom: 20px; padding:10px; width:100%; background-color:#999; color:white; font-weight:bold;">Descripció del projecte: <?php echo $_GET['toTeamsDesc'] ?></div>		

			<?php				
				
				$idProj = $_GET['toTeamsProjId'];
				
				//Obtenc la id de la inscripcio de l'usuari a partir de la id d'usuari
				$result = executePreparedQuery($conn, "SELECT * FROM inscriptions WHERE users_id = :userId", array(':userId'=>$_SESSION['userID']), false);
				
				$idInscription = $result->id;
				
				//Seleccionbo tots els projectes els quals la seva ID de conexio es la que revo per POST.
            	$sql = "SELECT tms.id, tms.name, tms.startDate, tms.endDate FROM teams tms, teamsProjects tp WHERE tms.id = tp.teams_id AND tp.projects_id = :idProj";
            	$arr = array(':idProj'=>$idProj);
            	$results = executePreparedQuery($conn, $sql, $arr, true);
				
			    //Comprovo que la consulta hagi tornat com a minim un resultat
			    if($results != false){
			    	
				    foreach ($results as $result) {

				    	$teamId = $result->id;
				    	$teamName = $result->name;
				    	$startDate = formatDate('Y-m-d', 'd/m/Y', $result->startDate);
				    	$endDate = formatDate('Y-m-d', 'd/m/Y', $result->endDate);

				    	
				    	//Miro si l'usuari loguejat esta instrit en l'equip actual
				    	$userFoundInThisProject = executePreparedQuery($conn, "SELECT * FROM `inscriptionsTeams` WHERE teams_id = :teamId AND inscription_id = :inscription_id", array(':teamId'=>$teamId, 'inscription_id'=>$idInscription), false);
				    	
                	
			?>
			    	<div class="item shadowBox">		    		
				    	
				    	<form id="<?php echo $teamId?>" action="">

				    		<input id="hiddenIdProj" type="hidden" name="hiddenIdProj" value="<?php echo $teamId ?>" />
							<input id="hiddenName" type="hidden" name="hiddenName" value="<?php echo $teamName ?>" />
							<input id="hiddenStartDate" type="hidden" name="hiddenStartDate" value="<?php echo $startDate ?>" />
							<input id="hiddenEndDate" type="hidden" name="hiddenEndDate" value="<?php echo $endDate ?>" />							
							
							<div class="headerItem">
								<h2 id="teamName"> <?php echo $teamName?> </h2>								
								<?php if($_SESSION['role'] == 2){ ?>
								<input id="settings" type="button" onClick="openConfig($(this.form),event)" class="settings" value="&#xf013;">
								<?php } ?>							 
							</div>
						</form>					
						
						<form id="join" action="resources.php" method="GET">

							<input id="hiddenTeamId" type="hidden" name="hiddenTeamId" value="<?php echo $teamId ?>" />
							<input id="hiddenInscriptionId" type="hidden" name="hiddenInscriptionId" value="<?php echo $idInscription ?>" />
							
							<?php 
								if($userFoundInThisProject == false){
									echo("<input id=\"join\" type=\"button\" onClick=\"joinTeam($(this.form),event)\" value=\"Uneix-te\">");
									echo("<span id =\"goin\"></span>");
<<<<<<< HEAD
=======
									//echo("<input id=\"toResources\" type=\"submit\" style=\"display:none\" value=\"Entra\">");
>>>>>>> oscar
								}else{
									echo("<input id=\"toResources\" type=\"submit\" value=\"Entra\">");
								}
							?>
							
						</form>
						
					</div>			    

		    <?php }} ?>			

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
				<h3>Edició de l'equip <span id="nameHeader" class="important"></span></h3>
				
				<input id="hiddenProjId" type="hidden" name="hiddenProjId" value="<?php echo $idProj ?>" />
				<input id="hiddenTeamId" type="hidden" name="hiddenTeamId" value="<?php echo $teamId ?>" />

				<span class="tag">Nom:</span><input id="nameConfig" name="name" class="tag">
				<span class="tag">Data inici:</span><input id="startDate" disabled name="startDate" class="tag">
				<span class="tag">Data fi:</span><input id="endDate" name="endDate" class="tag">		
				<p class="center"><input type="button" class="redButton" onClick="saveConfig($(this.form),event)" value="enviar"></p>
			</form>
		</div>
	</div>-->
	
	<script>
		var clickedId = "";
		var action = "update";

		function getXMLHTTPRequest(){
			var req = false;

			try	{
				req = new XMLHttpRequest();
			}catch(err1){
				try{
					req = new ActiveXObject("Msxm12.XMLHTTP");
				}catch(err2){
					try{
						req = new ActiveXObject("Microsoft.XMLHTTP"); 
					}catch(err3){
						req = false;
					}
				}
			}
			return req;
		}

		function joinTeam(form, event){

			var serialized = ($(form).serializeArray());			
			var teamId = serialized[0]['value'];
			var inscriptionId = serialized[1]['value'];			

			var url = "/html/includes/php/userJoinAjax.php?&teamId=" + teamId + "&inscriptionId=" + inscriptionId;
			var myQuery = getXMLHTTPRequest();
			
			myQuery.open("GET", url , true);
			myQuery.onreadystatechange = responseAjax;		
			myQuery.send(null);

			function responseAjax(){
				if(myQuery.readyState == 4){		
					if(myQuery.status == 200){
						document.getElementById('goin').innerHTML = "<input id=\"toResources\" type=\"submit\" style=\"display:none\" value=\"Entra\">";
						$('#toResources').click();						
					}else{
						alert("Error " + myQuery.status);
					}
				}
			}
		}	


		function openConfig(form, event){			
			event.preventDefault();
			var serialized = ($(form).serializeArray());
			
			clickedId = serialized[0]['value'];

			$('#nameHeader').text(serialized[1]['value']);
			$('#nameConfig').val(serialized[1]['value']);			
			$('#startDate').val(serialized[2]['value']);
			$('#endDate').val(serialized[3]['value']);

			$('.blackScreen').show();
			
		}

		function saveConfig(form, event){
			event.preventDefault();
			
			//Recuperant dades dels formularis per treballar
			var serialized = ($(form).serializeArray());

			var projId = serialized[0]['value'];
			var teamId = serialized[1]['value']
			var teamName = $('#nameConfig').val();
			var teamEndDate = $('#endDate').val();			

			//Definint variables per l'ajax
			var url = "/html/includes/php/teamsAjax.php?&formId=" + clickedId + "&projId=" + projId + "&teamId=" + teamId + "&teamName=" + teamName + "&teamEndDate=" + teamEndDate + "&action=" + action;
			var myQuery = getXMLHTTPRequest();					 		

			$('.blackScreen').hide();

					
			//Comprobo que tot estigui ple
			if(teamName.length > 0 && teamEndDate.length > 0){
				//Comprovo la data
				if(compareDates(teamEndDate)){					

					myQuery.open("GET", url , true);
					myQuery.onreadystatechange = responseAjax;		
					myQuery.send(null);
				}else{
					alert("Data incorrecte o connexio caducada (Renova la data)");
				}				
			}else{
				alert("Falta algun camp");
			}	
				
			function responseAjax(){
				if(myQuery.readyState == 4){		
					if(myQuery.status == 200){

						var response = myQuery.responseText;	
						
						if(action == "update"){
							response = JSON.parse(response);
						
							//Actualitzo els camps de les connexions:						
							$('form#'+response.formId).find('#teamName').html(response.teamName);
						}

						//Si el nom existeix al intentar cerear el projecte, torna missatge d'error.
						//Si no el tira recarega la pagina per mostrar el nou projecte
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

			function compareDates(endDate){

				var arr_endDate = endDate.split("/");

				var todayDate = new Date()
				var setEndDate = new Date();
				
				setEndDate.setDate(arr_endDate[0]);
				setEndDate.setMonth(arr_endDate[1]);
				setEndDate.setFullYear(arr_endDate[2]);

				return todayDate < setEndDate;
			}		

		}

		function createNew(){

			resetNew();
			action = "create";
			$('#center').prop('disabled', false);

			$('.blackScreen').show();
		}

		function resetNew(){

			$('#nameHeader').text("");
			$('#nameConfig').val("");			
			$('#startDate').val("");
			$('#endDate').val("");			
		}

		function resizeMenu(){
			var topMenu = $(window).height()/2-nav.height()/2;
			$('.panel').width($(window).width()-75);
			if(topMenu>68){
				nav.css({top:(topMenu+"px")});
			} else nav.css({top:(68+"px")});	
		}

		//Codi perque el menu aparegui plegat directament sense que es tingui que passar per sobre.
		//I es salti la transició.
		function navReset(){
			nav.addClass('notransition'); 
			nav.width(62);
			nav[0].offsetHeight;
			nav.removeClass('notransition');
		}

		function navAction(){
			flag=true;
			nav = $('.nav');
			search = $('.nav ul li');

			//Regles per l'entrada i sortida del menu.
			search.focusout(function(){
				$(nav).width(62);
				flag=true;	
			});

			search.focusin(function(){
				$(nav).width(200);
				flag=false;
			});

			nav.mouseenter(function(){
				$(this).width(200);		
			});

			nav.mouseleave(function(){
				if(flag) $(this).width(62);		
			});
		}

		$(document).ready(function(){
			var flag = true;
			$('#userBox').click(function(){
				if(flag){
					$('.userBox').show();
					flag=false;
				} else {
					$('.userBox').hide();
					flag=true;
				}
			});


			$('.blackScreen').click(function(){
			$(this).hide();
			}).children().click(function(e) {
				return false;
			});


			navAction();

			resizeMenu();
			$(window).resize(function(){
				resizeMenu();
			});

			navReset();
		});
	</script>
	
</body>
</html>
