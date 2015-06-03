<?php
	require('../../../includes/php/db.inc.php');
	require('./functions.inc.php');
	
	$formId = $_GET['formId'];	
	$projId = $_GET['projId'];
	$teamId = $_GET['teamId'];	
	$teamName = $_GET['teamName'];
	$endDate = $_GET['teamEndDate'];	
	$action = $_GET['action'];

	//Processo la base de dades	

	if($action == "update"){		
		
		$sql = "UPDATE teams SET name = :teamName, endDate = :endDate WHERE id = :teamId";
		$arr = array(
	    	':teamName'=>strip_tags(trim($teamName)),
	    	':endDate'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $endDate))),
	    	':teamId'=>strip_tags(trim($teamId))
		);

        executeInsertUpdateQuery($conn, $sql, $arr);

        //Proceso els valors a retornar pel servidor. Aquests valors em permetran actualitzar dinamicament el quadre de la connexio

		$updateValues = array(
			"formId" => $formId,
			"teamId" => $teamId,
	    	"teamName" => $teamName,
	    	"endDate" => $endDate
		);

		echo json_encode($updateValues);       

	}else if($action == "create"){

		$foundInThisProj = false;

		//Comprovo que no es pugui crear un projecte amb el mateix nom
		$results = executePreparedQuery($conn, "SELECT id FROM teams WHERE name = :teamName", array(':teamName'=>$teamName), false);	

		if($results != false){
			//Per tal de que dues conexions diferents puguin tenir un projecte amb el mateix nom. Necesito saver de quina connexio pertany el resultat. Selecciono totes les id_de connexio les cuals continguin aquella id de projecte
			$ids_proj = executePreparedQuery($conn, "SELECT projects_id FROM `teamsProjects` WHERE teams_id = :teamId", array(':teamId'=>$results->id), true);
			//Per cada resultat comprovo si el num de connexio actual es correspon amb algun dels resultats.
			foreach ($ids_proj as $result) {

				if($result->projects_id == $projId){
					$foundInThisProj = true;
				}
			}
		}
				
		if($results == false || $foundInThisProj == false){

			$sql = "INSERT INTO teams (name, startDate, endDate) VALUES (:teamName, :startDate, :endDate)";                
			$arr = array(
	          ':teamName'=>strip_tags(trim($teamName)),
	          ':startDate'=>strip_tags(trim(date('Y-m-d'))),
	          ':endDate'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $endDate)))
	      	);
	      	executeInsertUpdateQuery($conn, $sql, $arr);

	      	//Selecciono la ID de la ultima insercio i l'asigno com a ID actual del projecte, Despres faig la connexio
	      	$teamId = $conn->lastInsertId();

	      	$sql = "INSERT INTO teamsProjects (teams_id, projects_id) VALUES (:teamId, :projId)";                
			$arr = array(
	          ':teamId'=>$teamId,
	          ':projId'=>$projId
	      	);
	      	executeInsertUpdateQuery($conn, $sql, $arr);
      	}else{
	      	echo "Ja existeix un equip amb aquest nom";
      	}
	}   

?>