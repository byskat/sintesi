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
				<td class="center"><?php echo $row->id; ?></td>
				<td><?php echo $row->topic; ?><br></td>
				<td class="center"><?php echo $row->view; ?></td>
				<td class="center"><?php echo $row->reply; ?></td>
				<td class="center"><?php echo $row->datetime; ?></td>
				<td class="center options"><button class="enter" type="submit"><i class="fa fa-chevron-right"></i></button></td>
				</form>
				<td class="center options"><button class="delete" type="submit" onclick="<?php deleteTopic($conn,$row->id); ?>" ><i class="fa fa-minus"></i></button></td>
			</tr>
		<?php
		}
		$result=null;
		?>
		</table>
		<?php
	}


	function deleteTopic($conn,$topicId){
		$sql="DELETE FROM forum_question WHERE id='$topicId'";
		$result=$conn->query($sql);
	}


	//Funció que afegeix un nou visitant al comptador de visites. Se li passa la id del "topic" al qual
	//se li vol afegir el visitant i la conexio de la base de dades.
	function addView($conn,$id){
		$sql="SELECT view FROM forum_question WHERE id='$id'";
		$result=$conn->query($sql);
		$rows=$result->fetch(PDO::FETCH_OBJ);
		$view=$rows->view;
		 
		// if have no counter value set counter = 1
		if(empty($view)){
			$view=1;
			$sql="INSERT INTO forum_question(view) VALUES('$view') WHERE id='$id'";
			$result=$conn->query($sql);
		}
		 
		// count more value
		$addview=$view;
		$sql="UPDATE forum_question set view='$addview' WHERE id='$id'";
		$result=$conn->query($sql);
	}