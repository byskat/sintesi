<?php
	require('../includes/php/db.inc.php');
	require('./includes/php/userValidation.inc.php');
	require('./includes/php/functions.inc.php');
	if(!isset($_POST["toProjectsIdConn"])){
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

				$idConnection = $_POST["toProjectsIdConn"];				
				
				//Selecciono totes les ID de projecte les cuals la seva IDde projecte sigui la del que s'ha clicat
				$results = executePreparedQuery($conn, "SELECT projects_id FROM connectionsProjects WHERE projects_id = :idConnection", array(':idConnection'=>$idConnection));

			    //Comprovo que la consulta hagi tornat com a minim un resultat
			    if($results != false){
				    foreach ($results as $result) {

				    	//Per cada resultat extrec les dades del projecte
				    	$project = executePreparedQuery($conn, "SELECT * FROM projects WHERE id = :projectId", array(':projectId'=>$result));

				    	$projId = $project->id;
				    	$projName = $project->name;
				    	$startDate = formatDate('Y-m-d', 'd/m/Y', $project->startDate);
				    	$endDate = formatDate('Y-m-d', 'd/m/Y', $project->endDate);
				    	$projDescription = $project->description;
                	
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
								<input id="settings" type="button" onClick="openConfig($(this.form),event)" class="settings" value="&#xf013;">							 
							</div>

						</form>
					</div>			    

		    <?php }} ?>			

		</div>
		<div class="itemAdd shadowBox">
			<button onclick="createNew()">Afegir nou<br><i class="fa fa-plus"></i></button>
		</div>
	</div>

	<div class="blackScreen" style="display:none">
		<div class="formBox shadowBox" >
			<form action="">
				<h3>Edició del projecte <span id="nameHeader" class="important"></span></h3>
				
				<input id="hiddenConnId" type="hidden" name="hiddenConnId" value="<?php echo $idConnection ?>" />

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

			var projName = document.getElementById('nameConfig').value;
			var projEndDate = document.getElementById('endDate').value;
			var projDesc = document.getElementById('projDesc').value;				

			//Definint variables per l'ajax
			var url = "/html/includes/php/projectsAjax.php?&projId=" + clickedId + "&connId" + idConn + "&projName=" + projName + "&projEndDate=" + projEndDate + "&projDesc=" + projDesc + "&action=" + action;
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
						
						var response = JSON.parse(myQuery.responseText);
						
						//Actualitzo els camps de les connexions:
						
						$('form#'+response.projId).find('#projectName').html(response.projName);
						
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


			if(action == "create"){
				location.reload();
			}				

		}

		function createNew(){

			resetNew();
			action = "create";
			$('#center').prop('disabled', false);

			$('.blackScreen').show();
		}

		function resetNew(){
			var projName = document.getElementById('nameConfig').value = "";
			$('#nameHeader').text(projName);
			var projEndDate = document.getElementById('startDate').value = "";
			var projStartDate = document.getElementById('endDate').value = "";
			
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