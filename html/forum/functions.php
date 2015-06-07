<?php
	//Funció unica de forum, semblant al include que col·loca totes les dependències de les pàgines anteiors.
	//Amb la diferencia de que rep el titol de la pàgina.
	function head($title){?>
		<head>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <title><?php echo $title; ?></title>        
	    
	    <link rel="stylesheet" type="text/css" href="../includes/css/normalize.css">
	    <link rel="stylesheet" type="text/css" href="../includes/css/jquery-ui-1.11.4/jquery-ui.css">
	    <link rel="stylesheet" type="text/css" href="forum.css" />
	    <link rel="stylesheet" type="text/css" href="../includes/css/style.css" />
	    <link rel="stylesheet" type="text/css" href="../includes/fonts/font-awesome/css/font-awesome.min.css">
	    <link rel="stylesheet" type="text/css" href="../includes/datetimepicker/jquery.datetimepicker.css"/>

	    <script src="../includes/js/jquery-2.1.4/jquery-2.1.4.js"></script>
	    <script src="../includes/js/jquery-ui-1.11.4/jquery-ui.js"></script>
	    <script src="../includes/datetimepicker/jquery.datetimepicker.js"></script>
		</head><?php
	}
	//Funció que genera tot l'apartat principal del forum, es tracta d'una funció modular. Rep la conexió de la
	//base de dades, la id de l'equip que instancia el forum, també el nom d'aquest, i finalment l'usuari que 
	//està accedint en aquell moment.
	function mainForum($conn, $teamId, $teamName, $user){
		?>
		<table>
			<!-- Header de la taula -->
			<tr>
				<th class="center">#</th>
				<th>Tema</th>
				<th width="15%">Usuari</th>
				<th class="center">Visites</th>
				<th class="center">Respostes</th>
				<th>Data/Hora</th>
				<form action="create_topic.php" method="POST">
				<input type="hidden" name="teamId" value="<?php echo $teamId; ?>" />
				<th colspan="2" class="add"><button class="addTopic"><i class="fa fa-plus"></i></button></th>
				</form>
			</tr>
		<?php 
		//Es seleccionen els temes relacionats amb l'equip accedit, es reben les dades tant de la pregunta com del 
		//seu autor.
		$sql="SELECT f.*, u.username, u.profileImg FROM forumquestion f, users u WHERE u.id = f.id_user AND f.id_team = '$teamId' ORDER BY f.id DESC;";
		$result=$conn->query($sql);
		$rows=$result->fetchAll(PDO::FETCH_OBJ);

		//Es fa un fetch per generar les dades de tots ells.
		foreach ($rows as $row){
		?>
			<tr>
				<form action="view_topic.php" method="POST">
				<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
				<input type="hidden" name="teamId" value="<?php echo $teamId; ?>" />
				<input type="hidden" name="visit" value="true" />
				<td class="center"><?php echo $row->id; ?></td>
				<td class="centerMedia"><?php echo $row->topic; ?></td>
				<td class="centerMedia"><img class="userTopicImg" src="../images/assets/<?php echo $row->profileImg; ?>"><?php echo $row->username; ?></td>
				<td class="center"><?php echo $row->view; ?></td>
				<td class="center"><?php echo $row->reply; ?></td>
				<td class="center"><?php echo $row->datetime; ?></td>
				<td class="center options"><button class="enter" type="submit"><i class="fa fa-chevron-right"></i></button></td>
				</form>
				<?php if($user->id == $row->id_user){
					//Si l'usuari logejat es el propietari del tema...
					?>
					<form action="switch_topic.php" method="POST">
						<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
						<input type="hidden" name="teamId" value="<?php echo $teamId; ?>" />
						<input type="hidden" name="teamName" value="<?php echo $teamName; ?>" />
						<input type="hidden" name="option" value="<?php echo $row->open; ?>" />
						<?php if($row->open) $optClass="visible"; else $optClass="delete"; ?>
						<td class="center options"><button class="<?php echo $optClass; ?>" type="submit"><i class="fa fa-comments"></i></button></td>
					</form>
					<?php
					//D'altra banda, si ell no es el propietari...
					} else {
						//Podrà veure si es obert.
						if($row->open){
						?>
						<td class="center options"><button class="visible noClick" type="submit"><i class="fa fa-comments"></i></button></td>
						<?php 
						//O tancat.
						} else { ?>
						<td class="center options"><button class="delete noClick" type="submit"><i class="fa fa-comments"></i></button></td>
				  <?php } 
				  }?>
			</tr>
		<?php
		}
		$result=null;
		?>
		</table>
		<?php
	}

	//Funció que afegeix un nou visitant al comptador de visites. Se li passa la id del "topic" al qual
	//se li vol afegir el visitant i la conexio de la base de dades.
	function addViewer($conn,$id){
		$sql="UPDATE forumquestion SET view = view + 1 WHERE id = '$id'";
		$conn->query($sql);
	}

	//Funció modular que carrega la vista del tema elegit. Rep la conexió, la id del tema i l'usuari que hi accedeix.
	//Retorna open, es la variable que comprova si el tema permet, o no, comentaris.
	function loadTopic($conn,$topicId,$user){
		//Es fa una selecció de la pregunta i el seu autor.
		$sql="SELECT f.*, u.username, u.profileImg, u.email FROM forumquestion f, users u WHERE u.id = f.id_user AND f.id = '$topicId' ORDER BY f.id DESC;";
		$result=$conn->query($sql);
		$rows=$result->fetch(PDO::FETCH_OBJ);

		//Condició que comprova que s'hagi o no, accedit en mode d'edició, variable que es passa per post.
		if(isset($_POST['editTopic'])){
			$editTopic = filter_var($_POST['editTopic'],FILTER_VALIDATE_BOOLEAN);
		} else {
			$editTopic = false;
		}

		?>
		<div class="topic">
			<!--Vista normal-->
			<!--En cas de no estar en mode d'edició... -->
			<?php if(!$editTopic){ ?>
			<div class="topicHeader">
				<div class="topicTitle">
					<h1><?php echo $rows->topic; ?></h1>
				</div>
				<div class="topicSubtitle">
					<p style="float: left"><?php echo $rows->datetime; ?> </p> <?php
					//En cas d'estar en vista normal, comprovem si s'ha de mostrar la possibilitat d'editar el tema.
					//Depèn de si l'usuari que ho intenta sigui el creador del tema.
					if($user->id==$rows->id_user) { ?>
					<form action="" method="POST">
						<?php if($rows->open){
						?>
						<input type="hidden" id="id" name="id" value="<?php echo $topicId; ?>">
						<input type="hidden" id="editTopic" name="editTopic" value="true">
						<button action="submit"><i class="fa fa-pencil-square-o"></i></button>
						<?php } ?>
					</form>
					<?php } ?>
				</div>
			</div>
			<div class="topicBody">

				<div class="topicUser">
					<img src="../images/assets/<?php echo $rows->profileImg; ?>">
					<span>
						<?php echo $rows->username; ?><br>
						<?php echo $rows->email;?>
					</span>
				</div>
				<div class="topicMessage question">
						<p><?php echo $rows->detail; ?><p>
				</div>

				<!--Vista d'edició-->
				<!--En cas d'estar en vista d'edició apareixerà un formulari en el que podrem editar les diferents dades
				del tema-->
				<?php	} else { ?>
				<form action="update_topic.php" method="POST">
				<div class="topicHeader">
					<div class="topicTitle">
						<h1><input name="updateName" type="text" id="updateName" value="<?php echo $rows->topic; ?>"/></h1>
					</div>
					<div class="topicSubtitle">
							<p style="float: left"><?php echo $rows->datetime; ?> </p> <?php
							$_SESSION['id']=$topicId;
							?>
							<a href="view_topic.php"><button type="button"><i class="fa fa-times"></i></button></a>
					</div>
				</div>
				<div class="topicBody">

					<div class="topicUser">
					<img src="../images/assets/<?php echo $rows->profileImg; ?>">
					<span>
						<?php echo $rows->username; ?><br>
						<?php echo $rows->email;?>
					</span>
				</div>
					<div class="topicMessage question">
						
							<input type="hidden" id="id" name="id" value="<?php echo $topicId; ?>">
							<div class="commentArea">
								<textarea name="updateText" id="updateText" required><?php echo $rows->detail ?></textarea>
							</div>
							<div class="commentOptions">
								<button type="submit" name="Submit" class="bgGreen allHeight"><i class="fa fa-arrow-up"></i></button>
							</div>
					</div>
					</form>
				<?php } ?>

			</div>
			<div style="clear:both"></div>
		</div>
		<?php

		return $rows->open;
	}

	//Funció que carrega els comentaris per el tema sel·leccionat, rep també les dades de conexió de usuari, i la
	//de comprovació de disponibilitat de comentaris.
	function loadComments($conn, $topicId, $open, $user){
		//Es seleccionen totes les respostes i les dades dels seus respectius creadors.
		$sql="SELECT a.*, u.username, u.profileImg, u.email FROM forumanswer a, users u WHERE u.id = a.id_user AND a.question_id='$topicId'";
		$result=$conn->query($sql);
		$rows=$result->fetchAll(PDO::FETCH_OBJ);

		//Es comprova, la vista d'edició, tal i com passa en l'anterior funció.
		if(isset($_POST['editComment'])){
			$editComment = intval($_POST['editComment']);
		} else {
			$editComment = -1;
		}

		foreach ($rows as $row){
		?>

		<div class="topic">
			<div class="topicBody">
				<div class="topicUser">
					<img src="../images/assets/<?php echo $row->profileImg; ?>">
					<span>
						<?php echo $row->username; ?><br>
						<?php echo $row->email;?>
					</span>
				</div>

			<?php if($editComment!=$row->a_id){ ?>
			<!--Vista normal-->
				<div class="topicMessage">
					<div class="topicSubtitle">
					<p style="float: left"><?php echo "# ".$row->a_id; echo " | ".$row->a_datetime; ?> </p> <?php
					if($user->id==$row->id_user) { ?>
						<form action="" method="POST">
							<?php if($open){
							?>
							<input type="hidden" id="id" name="id" value="<?php echo $topicId; ?>">
							<input type="hidden" id="editComment" name="editComment" value="<?php echo $row->a_id; ?>">
							<button action="submit"><i class="fa fa-pencil-square-o"></i></button>
							<?php } ?>
						</form>
					<?php } ?>
					</div>
					<p><?php echo $row->a_answer; ?></p>
				</div>

				<?php } else { ?>
				<!--Vista d'edició-->
				<div class="topicMessage">
					<div class="topicSubtitle">
						<p style="float: left"><?php echo "# ".$row->a_id; echo " | ".$row->a_datetime; ?> </p> <?php
						$_SESSION['id']=$topicId;
						?>
						<a href="view_topic.php"><button type="button"><i class="fa fa-times"></i></button></a>
					</div>
					<form name="formComment"action="update_answer.php" method="POST">
					<input type="hidden" id="question_id" name="question_id" value="<?php echo $row->question_id; ?>">
					<input type="hidden" id="a_id" name="a_id" value="<?php echo $row->a_id; ?>">
					<div class="commentArea">
						<textarea name="a_answer" id="a_answer" required><?php echo $row->a_answer ?></textarea>
					</div>
					<div class="commentOptions">
						<button type="submit" name="Submit" class="bgGreen allHeight"><i class="fa fa-arrow-up"></i></button>
					</div>
					</form>
				</div>

				<?php } ?>

			</div>
			<div style="clear:both"></div>
		</div>
		<?php
		}
	}

	//Secció de comentaris, pràcticament només genera html, si no fós per la id del tema que está comentant.
	function commentSection($id){ ?>
		<div class="topicBody">
				<div class="topicUser">
				</div>

		<div class="commentSection">
			<form name="form1" method="post" action="add_answer.php" method="POST">
				<input name="id" type="hidden" id="id" value="<?php echo $id; ?>"></td>
				<div class="commentArea">
					<textarea name="a_answer" id="a_answer" placeholder="comentari" class="paddingBottom" required></textarea>
				</div>
				<div class="commentOptions">
					<button type="submit" name="Submit" class="bgGreen"><i class="fa fa-arrow-up"></i></button>
					<button type="reset" name="Submit2" class="bgRed"><i class="fa fa-trash"></i></button>
				</div>
			</form>
		</div>
		</div>
		<?php
	}

	//Funció de creació de nou tema, només genera un formulari.
	function newTopic(){ ?>
		<div class="topic">
			<div class="topicHeader">
				<div class="topicTitle">
					<h1>Crear nou tema</h1>
				</div>
			</div>
		</div>
		<div class="questionSection">
			<form id="form1" name="form1" method="POST" action="add_topic.php">
				<input name="teamId" type="hidden" id="teamId" value="<?php echo $_POST['teamId']; ?>"/>
				<input name="name" type="hidden" id="name" value="test"/>
				<input name="email" type="hidden" id="email" value="test" />
				
				<input name="topic" type="text" id="topic" placeholder="titol de la pregunta" required />
				
				<div style="clear:both"></div>
				<div class="commentArea">	
					<textarea name="detail" id="detail" placeholder="descripció de la pregunta" required></textarea>
				</div>
				<div class="commentOptions">
					<button type="submit" name="Submit" class="bgGreen"><i class="fa fa-arrow-up"></i></button>
					<button type="reset" name="Submit2" class="bgRed"><i class="fa fa-trash"></i></button>
				</div>
			</form>
		</div>
		<?php
	}