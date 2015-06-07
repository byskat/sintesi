<!-- Include de la barra superior comuna a totes les pàgines -->
<div class="header">
	<div class="login">
		<?php if(file_exists("images/assets/".$user->profileImg)){ ?>
		<img src="images/assets/<?php echo $user->profileImg ?>">
		<?php } else { ?>
		<img src="../images/assets/<?php echo $user->profileImg ?>">
		<?php } ?>
		<p>Benvingut, <span id="userBox" class="important userLink"><?php echo $user->username ?></span></p>
		<?php if(file_exists("logout.php")){ ?>
		<div class="userBox arrowBox" style="display:none"><a href="logout.php"><span class="important">Logout?</span></a></div>
		<?php } else { ?>
		<div class="userBox arrowBox" style="display:none"><a href="../logout.php"><span class="important">Logout?</span></a></div>
		<?php } ?>
	</div>
</div>