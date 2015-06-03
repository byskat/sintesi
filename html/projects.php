<?php
	require('../includes/php/db.inc.php');
	require('./includes/php/userValidation.inc.php');
	require('./includes/php/functions.inc.php');

	if(!isset($_GET["toProjectsIdConn"])){	
		header('Location: ./login.php');
	}
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
		<div class="itemList">		

			<?php				
				
				$idConnection = $_GET['toProjectsIdConn'];				
				
				//Seleccionbo tots els projectes els quals la seva ID de conexio es la que revo per POST.
				
            	$sql = "SELECT proj.id, proj.name, proj.startDate, proj.endDate, proj.description, proj.outdated FROM projects proj, connectionsProjects cp WHERE proj.id = cp.projects_id AND cp.connections_id = :idConnection";
            	$arr = array(':idConnection'=>$idConnection);
            	$results = executePreparedQuery($conn, $sql, $arr, true);
				
			    //Comprovo que la consulta hagi tornat com a minim un resultat
			    if($results != false){
			    	
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
							
							if(checkOutdated( formatDate('d/m/Y', 'Y-m-d', $endDate) )){
								executeInsertUpdateQuery($conn, "UPDATE projects SET outdated = 1 WHERE id = :projId ", array(':projId'=>$projId) );
							}					
							
							if($outdated == 0){
								echo("<input id=\"outdatedBtn\" type=\"submit\" value=\"Teams\">");
							}else{
								echo("<span id=\"updated\"> </span>");
								echo("<div id=\"outdated\">Caducat</div>");
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
				<h3>Edició del projecte <span id="nameHeader" class="important"></span></h3>
				
				<input id="hiddenConnId" type="hidden" name="hiddenConnId" value="<?php echo $idConnection ?>" />
				<input id="hiddenProjId" type="hidden" name="hiddenProjId" value="<?php echo $projId ?>" />

				<span class="tag">Nom:</span><input id="nameConfig" name="name" class="tag">
				<span class="tag">Data inici:</span><input id="startDate" disabled name="startDate" class="tag">
				<span class="tag">Data fi:</span><input id="endDate" name="endDate" class="tag">
				<span class="tag">Descripció:</span><input id="projDesc" name="projDesc" class="tag">				
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

		function saveConfig(form, event){
			event.preventDefault();
			
			//Recuperant dades dels formularis per treballar
			var serialized = ($(form).serializeArray());
			var idConn = serialized[0]['value'];
			var idProj = serialized[1]['value'];
			var projName = $('#nameConfig').val();
			var projEndDate = $('#endDate').val();
			var projDesc = $('#projDesc').val();				

			//Definint variables per l'ajax
			var url = "/html/includes/php/projectsAjax.php?&idConn=" + idConn + "&idProj=" + clickedId + "&projName=" + projName + "&projEndDate=" + projEndDate + "&projDesc=" + projDesc + "&action=" + action;
			var myQuery = getXMLHTTPRequest();					 		

			$('.blackScreen').hide();

					
			//Comprobo que tot estigui ple
			if(projName.length > 0 && projEndDate.length > 0 && projDesc.length > 0){
				//Comprovo la data
				if(compareDates(projEndDate)){					

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
							$('form#'+response.projId).find('#projectName').html(response.projName);

							//Si updateOutdated = true actualitzo l'estat de caducitat.
							console.log(response.procesed);
							if(response.procesed == true){	
								$('#outdated').hide();
								document.getElementById("updated").innerHTML = "<input id=\"outdatedBtn\" type=\"submit\" value=\"Teams\">";				
							}
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

			$('#nameConfig').val("");			
			$('#startDate').val("");
			$('#endDate').val("");
			$('#projDesc').val("");			
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
