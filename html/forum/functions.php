<?php

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

	function mainForum($conn){
		?>
		<table>
			<tr>
				<th class="center">#</th>
				<th>Topic</th>
				<th class="center">Views</th>
				<th class="center">Replies</th>
				<th>Date/Time</th>
				<th colspan="2" class="add"><a class="addTopic" href="create_topic.php"><i class="fa fa-plus"></i></a></th>
			</tr>
		<?php
		$sql="SELECT * FROM forum_question ORDER BY id DESC";

		$result=$conn->query($sql);
		 
		$rows=$result->fetchAll(PDO::FETCH_OBJ);
		foreach ($rows as $row){
		?>
			<tr>
				<form action="view_topic.php" method="POST">
				<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
				<input type="hidden" name="visit" value="true" />
				<td class="center"><?php echo $row->id; ?></td>
				<td><?php echo $row->topic; ?><br></td>
				<td class="center"><?php echo $row->view; ?></td>
				<td class="center"><?php echo $row->reply; ?></td>
				<td class="center"><?php echo $row->datetime; ?></td>
				<td class="center options"><button class="enter" type="submit"><i class="fa fa-chevron-right"></i></button></td>
				</form>
				<?php if($row->open){ ?>
				<form action="close_topic.php" method="POST">
				<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
				<td class="center options"><button class="visible" type="submit"><i class="fa fa-comments"></i></button></td>
				</form>
				<?php } else { ?>
				<form action="open_topic.php" method="POST">
				<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
				<td class="center options"><button class="delete" type="submit"><i class="fa fa-comments"></i></button></td>
				</form>
				<?php } ?>
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
		$sql="UPDATE forum_question SET view = view + 1 WHERE id = '$id'";
		$conn->query($sql);
	}

	function loadTopic($conn,$topicId){
		$sql="SELECT * FROM forum_question WHERE id='$topicId'";
		$result=$conn->query($sql);
		$rows=$result->fetch(PDO::FETCH_OBJ);

		if(isset($_POST['editTopic'])){
			$editTopic = filter_var($_POST['editTopic'],FILTER_VALIDATE_BOOLEAN);
		} else {
			$editTopic = false;
		}

		?>
		<div class="topic">
			<!--Vista normal-->
			<?php if(!$editTopic){ ?>
			<div class="topicHeader">
				<div class="topicTitle">
					<h1><?php echo $rows->topic; ?></h1>
				</div>
				<div class="topicSubtitle">
					<form action="" method="POST">
						<?php echo $rows->datetime; 
						if($rows->open){
						?>
						<input type="hidden" id="id" name="id" value="<?php echo $topicId; ?>">
						<input type="hidden" id="editTopic" name="editTopic" value="true">
						<button action="submit"><i class="fa fa-pencil-square-o"></i></button>
						<?php } ?>
					</form>
				</div>
			</div>
			<div class="topicBody">

				<div class="topicUser">
					<img src="../images/assets/user_placeholder_res.jpeg">
					<span>
						<?php echo $rows->name; ?><br>
						<?php echo $rows->email;?>
					</span>
				</div>
				<div class="topicMessage question">
						<p><?php echo $rows->detail; ?><p>
				</div>

				<!--Vista d'edició-->
				<?php	} else { ?>
				<form action="update_topic.php" method="POST">
				<div class="topicHeader">
					<div class="topicTitle">
						<h1><input name="updateName" type="text" id="updateName" value="<?php echo $rows->topic; ?>"/></h1>
					</div>
					<div class="topicSubtitle">
							<?php echo $rows->datetime; 
							session_start();
							$_SESSION['id']=$topicId;
							?>
							<a href="view_topic.php"><button type="button"><i class="fa fa-times"></i></button></a>
					</div>
				</div>
				<div class="topicBody">

					<div class="topicUser">
					<img src="../images/assets/user_placeholder_res.jpeg">
					<span>
						<?php echo $rows->name; ?><br>
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

	function loadComments($conn, $topicId, $open){
		$sql="SELECT * FROM forum_answer WHERE question_id='$topicId'";
		$result=$conn->query($sql);
		$rows=$result->fetchAll(PDO::FETCH_OBJ);

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
					<img src="../images/assets/user_placeholder_res.jpeg">
					<span>
						<?php echo $row->a_name; ?><br>
						<?php echo $row->a_email;?>
					</span>
				</div>

			<?php if($editComment!=$row->a_id){ ?>
			<!--Vista normal-->
				<div class="topicMessage">
					<div class="topicSubtitle">
						<form action="" method="POST">
							<?php echo "# ".$row->a_id; echo " | ".$row->a_datetime; 
							if($open){
							?>
							<input type="hidden" id="id" name="id" value="<?php echo $topicId; ?>">
							<input type="hidden" id="editComment" name="editComment" value="<?php echo $row->a_id; ?>">
							<button action="submit"><i class="fa fa-pencil-square-o"></i></button>
							<?php } ?>
						</form>
					</div>
					<p><?php echo $row->a_answer; ?></p>
				</div>

				<?php } else { ?>
				<!--Vista d'edició-->
				<div class="topicMessage">
					<div class="topicSubtitle">
						<?php 
						echo "# ".$row->a_id; echo " | ".$row->a_datetime; 

						session_start();
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

	function commentSection($id){ ?>
		<div class="topicBody">
				<div class="topicUser">
				</div>

		<div class="commentSection">
			<form name="form1" method="post" action="add_answer.php" method="POST">
				<input name="id" type="hidden" id="id" value="<?php echo $id; ?>"></td>
				<input name="a_name" type="hidden" id="a_name" value="<?php echo "test"; ?>"></td>
				<input name="a_email" type="hidden" id="a_email" value="<?php echo "test"; ?>"></td>
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

	function newTopic(){ ?>
		<div class="topic">
			<div class="topicHeader">
				<div class="topicTitle">
					<h1>Create New Topic</h1>
				</div>
			</div>
		</div>
		<div class="questionSection">
			<form id="form1" name="form1" method="POST" action="add_topic.php">
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