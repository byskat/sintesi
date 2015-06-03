<?php

	require('../../../includes/php/db.inc.php');
	require('./functions.inc.php');

	$teamId = $_GET['teamId'];
	$inscriptionId = $_GET['inscriptionId'];

	$sql = "INSERT INTO inscriptionsTeams (teams_id, inscription_id) VALUES (:teamId, :inscriptionId)";

	$arr = array(
	          ':teamId'=>trim($teamId),
	          ':inscriptionId'=>trim($inscriptionId)	 
      		);			
	executeInsertUpdateQuery($conn, $sql, $arr);
	
?>