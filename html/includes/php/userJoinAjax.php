<?php

	require('../../../includes/php/db.inc.php');
	require('./functions.inc.php');

	//Utilitzo les dades obtingudes per fer la connexió entre equips i inscriptions.
	$teamId = $_GET['teamId'];
	$inscriptionId = $_GET['inscriptionId'];

	$sql = "INSERT INTO inscriptionsteams (teams_id, inscription_id) VALUES (:teamId, :inscriptionId)";

	$arr = array(
	          ':teamId'=>trim($teamId),
	          ':inscriptionId'=>trim($inscriptionId)	 
      		);			
	executeInsertUpdateQuery($conn, $sql, $arr);
	
?>