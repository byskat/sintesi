
//Funcions pel bon funcionament del menú despelgable esquerra
//
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

//Aquesta funció torna un XMLHTTPRequest segons el navegador que s'utilitzi.

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

//Aquesta funció compara dues dates per comprovar si la data actual és mes petita que la data que rep per parametre.
//
function compareDates(endDate){

	var arr_endDate = endDate.split("/");

	var todayDate = new Date()
	var setEndDate = new Date();
	
	setEndDate.setDate(arr_endDate[0]);
	setEndDate.setMonth(arr_endDate[1]);
	setEndDate.setFullYear(arr_endDate[2]);

	return todayDate < setEndDate;
}

//Aquesta funció mostra el formulari de creació/edició d'usuari.
function createNew(){

	resetNew();
	action = "create";
	$('#center').prop('disabled', false);

	$('.blackScreen').show();
}