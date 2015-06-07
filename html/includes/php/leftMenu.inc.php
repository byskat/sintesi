<!-- Include el menu lateral esquerra comú a totes les pàgines -->
<nav class="nav">
	<ul>
		<?php if(file_exists("connections.php")){ ?>
		<a href="connections.php" class="text">
		<?php } else { ?>
		<a href="../connections.php" class="text">
		<?php } ?>
			<li>
				<div class="ico"><i class="fa fa-home"></i></div>
				<div class="option"></div>
				<p class="text">inici</p>				
			</li>
		</a>

		<li class="search">
			<div class="ico"><i class="fa fa-search"></i></div>
			<input type="hidden" autofocus="true" />
			<input type="text" class="search" placeholder="buscar...">
		</li>
		
		<li>
			<div class="ico"><i class="fa fa-paper-plane"></i></div>
			<div class="option"></div>
			<p class="text"><a href="#" class="text">missatges</a></p>
		</li>

		<?php if(file_exists("userOption.php")){ ?>
		<a href="userOptions.php" class="text">
		<?php } else { ?>
		<a href="../userOptions.php" class="text">
		<?php } ?>
			<li>
				<div class="ico"><i class="fa fa-cog"></i></div>
				<div class="option"></div>
				<p class="text">opcions</p>				
			</li>
		</a>

		<?php if(file_exists("about.php")){ ?>
		<a href="about.php" class="text">
		<?php } else { ?>
		<a href="../about.php" class="text">
		<?php } ?>
			<li>
				<div class="ico"><i class="fa fa-info"></i></div>
				<div class="option"></div>
				<p class="text">quant a</p>				
			</li>
		</a>
	</ul>
</nav>