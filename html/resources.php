<?php
	require('../includes/php/db.inc.php');
	session_start();
	if (isset($_SESSION['userID'])){
		$userID = $_SESSION['userID'];
		$user = $_SESSION['username'];
	} else {
		header('Location: login.php');
	}

	//Generar select de centres
    
    $options = "";
    $options .= "<option value='startOption' selected disabled >Selecciona un centre</option>";
    $sql = "SELECT name FROM centers";
    $query = $conn->query($sql);
    $results = $query->fetchAll(PDO::FETCH_OBJ);                            

    foreach ($results as $result) {                                
        $options .= "<option value='" . $result->name . "'>". $result->name . "</option>";
    } 

?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panell</title>        
    
    <link rel="stylesheet" type="text/css" href="./includes/css/normalize.css">
    <link rel="stylesheet" type="text/css" href="./includes/css/jquery-ui-1.11.4/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="./includes/css/style.css" />
    <link rel="stylesheet" type="text/css" href="./includes/fonts/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="./includes/datetimepicker/jquery.datetimepicker.css"/>

    <script src="./includes/js/jquery-2.1.4/jquery-2.1.4.js"></script>
    <script src="./includes/js/jquery-ui-1.11.4/jquery-ui.js"></script>
    <script src="./includes/datetimepicker/jquery.datetimepicker.js"></script>
</head>
<body>

	<div class="header">
		<div class="login">
			<img src="images/assets/user_placeholder_res.jpeg">
			<p>Benvingut, <span id="userBox" class="important userLink"><?php echo $user ?></span></p>
			<div class="userBox arrowBox" style="display:none"><a href="logout.php"><span class="important">Logout?</span></a></div>
		</div>

	</div>

	<nav class="nav">
		<ul>
			<li>
				<div class="ico"><i class="fa fa-home"></i></div>
				<div class="option"></div>
				<p class="text"><a href="#" class="text">home</a></p>
			</li>
			<li class="search">
				<div class="ico"><i class="fa fa-search"></i></div>
				<input type="hidden" autofocus="true" />
				<input type="text" class="search" placeholder="search...">
			</li>
			
			<li>
				<div class="ico"><i class="fa fa-paper-plane"></i></div>
				<div class="option"></div>
				<p class="text"><a href="#" class="text">missatges</a></p>
			</li>
			<li>
				<div class="ico"><i class="fa fa-cog"></i></div>
				<div class="option"></div>
				<p class="text"><a href="#" class="text">opcións</a></p>
			</li>
			<li>
				<div class="ico"><i class="fa fa-question"></i></div>
				<div class="option"></div>
				<p class="text"><a href="#" class="text">ajuda</a></p>
			</li>

		</ul>
	</nav>

	<div class="panel">
		<div class="itemList">
			

			<?php

				//Obtinc el rol i la id del centre l'usuari loguejat
				
				$sql = "SELECT id, role FROM users WHERE id ='" . $userID . "'";
				$query = $conn->query($sql);
				$result = $query->fetch(PDO::FETCH_OBJ);
				$userNameId = $result->id;
				$userRole = $result->role;

				$sql = "SELECT centers_id FROM inscriptions WHERE users_id ='" . $userNameId . "'";
				$query = $conn->query($sql);
				$result = $query->fetch(PDO::FETCH_OBJ);
				$userNameCenterId = $result->centers_id;
				
				//Ho selecciono tot de conexions per procesa-ho

				$sql = "SELECT * FROM connections";
			    $query = $conn->query($sql);
			    $results = $query->fetchAll(PDO::FETCH_OBJ);			    		                                

			    foreach ($results as $result) {

			    	$connId = $result->id;
			    	$connName = $result->name;

			    	$newDate = DateTime::createFromFormat('Y-m-d', $result->startDate);    
        			$startDate = $newDate->format('d/m/Y');

        			$newDate = DateTime::createFromFormat('Y-m-d', $result->endDate);    
        			$endDate = $newDate->format('d/m/Y');

			    	//A partir de la id de cada centre en trec el seu nom. Per cada cas comprobo tambe la id de la taula connexio per saver les ID dels centres en questio
			    	$sql = "SELECT c.name FROM centers c, connections conn WHERE c.id = conn.idcenter1 AND conn.id =" . $connId;
                	$query = $conn->query($sql);
                	$result = $query->fetch(PDO::FETCH_OBJ);
                	$center1 = $result->name;

                	$sql = "SELECT c.name FROM centers c, connections conn WHERE c.id = conn.idcenter2 AND conn.id =" . $connId;
                	$query = $conn->query($sql);
                	$result = $query->fetch(PDO::FETCH_OBJ);
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
					<?php echo $options ?>
				</select>
				<p class="center"><input type="button" class="redButton" onClick="saveConfig($(this.form),event)" value="enviar"></p>
			</form>
		</div>
	</div>

	<script type="text/javascript">

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
    		$('#endDate').val(serialized[4]['value']);
    		$('#startDate').val(serialized[3]['value']);
    		$('#center').val(serialized[2]['value']);

    		if(action == "update"){
    			$('#center').prop('disabled', 'disabled');
    		}

			$('.blackScreen').show();
			
		}

		function saveConfig(form, event){
			event.preventDefault();
			
			//Recuperant dades dels formularis per treballar
			var serialized = ($(form).serializeArray());

			var userNameCenterId = serialized[0].value;
			var connName = document.getElementById('nameConfig').value;
			var connEndDate = document.getElementById('endDate').value;
			var connCenterName = document.getElementById('center').value;		

			//Definint variables per l'ajax
			var url = "./includes/php/connectionsAjax.php?&formId=" + clickedId + "&connName=" + connName + "&connEndDate=" + connEndDate + "&connCenterName=" + connCenterName + "&userNameCenterId=" + userNameCenterId + "&action=" + action;
			var myQuery = getXMLHTTPRequest();					 		

			$('.blackScreen').hide();

			console.log(compareDates(connEndDate));
			
			//Comprobo que tot estigui ple
			if(connName.length > 0 && connEndDate.length > 0 && connCenterName != "Selecciona un centre"){
				//Comprovo la data
				if(compareDates(connEndDate)){					

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
						
						$('form#'+response.connId).find('#connectionName').html(response.connName);
						
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
			var connName = document.getElementById('nameConfig').value = "";
			$('#nameHeader').text(connName);
			var connEndDate = document.getElementById('startDate').value = "";
			var connEndDate = document.getElementById('endDate').value = "";
			var connCenterName = document.getElementById('center').selectedIndex = 0;
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
