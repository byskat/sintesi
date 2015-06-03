<?php
	require('../includes/php/db.inc.php');
	require('./includes/php/userValidation.inc.php');
	require('./includes/php/functions.inc.php');

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

			<?php
				
			?>

		</div>
	</div>
	
	<script>
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
