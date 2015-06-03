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
	var connCenter1Name = serialized[1].value;
	var connName = $('#nameConfig').val();
	var connEndDate = $('#endDate').val();
	var connCenter2Name = $('#center').val();		

	//Definint variables per l'ajax
	var url = "/html/includes/php/connectionsAjax.php?&formId=" + clickedId + "&connName=" + connName + "&connEndDate=" + connEndDate + "&connCenter1Name=" + connCenter1Name + "&connCenter2Name=" + connCenter2Name + "&userNameCenterId=" + userNameCenterId + "&action=" + action;
	var myQuery = getXMLHTTPRequest();					 		

	$('.blackScreen').hide();

			
	//Comprobo que tot estigui ple
	if(connName.length > 0 && connEndDate.length > 0 && connCenter2Name != "Selecciona un centre"){
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

				var response = myQuery.responseText;	
				
				if(action == "update"){
					response = JSON.parse(response);
				
					//Actualitzo els camps de les connexions:						
					$('form#'+response.connId).find('#connectionName').html(response.connName);

					//Si updateOutdated = true actualitzo l'estat de caducitat.
					if(response.outdated == true){
						$('#outdated').hide();						
						$('#outdatedBtn').show();						
					}
				}

				//Si el nom existeix al intentar cerear la connexio, torna missatge d'error.
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
	$('#endDate').val("");
	$('#startDate').val("");
	$('#center')[0].selectedIndex = 0;
		
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
