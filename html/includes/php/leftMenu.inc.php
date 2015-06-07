<!-- Include el menu lateral esquerra comú a totes les pàgines -->
<nav class="nav">
	<ul>
		<li>
			<div class="ico"><i class="fa fa-home"></i></div>
			<div class="option"></div>
			<?php if(file_exists("connections.php")){ ?>
			<p class="text"><a href="connections.php" class="text">inici</a></p>
			<?php } else { ?>
			<p class="text"><a href="../connections.php" class="text">inici</a></p>
			<?php } ?>
		</li>
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
		<li>
			<div class="ico"><i class="fa fa-cog"></i></div>
			<div class="option"></div>
			<?php if(file_exists("userOption.php")){ ?>
			<p class="text"><a href="userOptions.php" class="text">opcións</a></p>
			<?php } else { ?>
			<p class="text"><a href="../userOptions.php" class="text">opcións</a></p>
			<?php } ?>
		</li>
		<li>
			<div class="ico"><i class="fa fa-info"></i></div>
			<div class="option"></div>
			<?php if(file_exists("userOption.php")){ ?>
			<p class="text"><a href="about.php" class="text">quant a</a></p>
			<?php } else { ?>
			<p class="text"><a href="../about.php" class="text">quant a</a></p>
			<?php } ?>
		</li>

	</ul>
</nav>