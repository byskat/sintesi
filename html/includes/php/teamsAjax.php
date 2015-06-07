<?php
	require('../../../includes/php/db.inc.php');
	require('./functions.inc.php');
	
	$formId = $_GET['formId'];	
	$projId = $_GET['projId'];
	$teamId = $_GET['teamId'];	
	$teamName = $_GET['teamName'];
	$endDate = $_GET['teamEndDate'];	
	$action = $_GET['action'];

	//Segons la opció que revo per get update o create faig una cosa o una altre.
	if($action == "update"){
		
		$sql = "UPDATE teams SET name = :teamName, endDate = :endDate WHERE id = :teamId";
		$arr = array(
	    	':teamName'=>strip_tags(trim($teamName)),
	    	':endDate'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $endDate))),
	    	':teamId'=>strip_tags(trim($teamId))
		);

        executeInsertUpdateQuery($conn, $sql, $arr);

        //Proceso els valors a retornar pel servidor. Aquests valors em permetran actualitzar dinamicament el quadre d'equip

		$updateValues = array(
			"formId" => $formId,
			"teamId" => $teamId,
	    	"teamName" => $teamName,
	    	"endDate" => $endDate
		);

		echo json_encode($updateValues);       

	}else if($action == "create"){

		$foundInThisProj = false;

		//Comprovo que no es pugui crear un equip amb el mateix nom.
		$results = executePreparedQuery($conn, "SELECT id FROM teams WHERE name = :teamName", array(':teamName'=>$teamName), false);
		if($results != false){
			//Per tal de que dos projectes diferents puguin tenir un equip amb el mateix nom. Necesito saver de quin projecte pertany el resultat. Selecciono totes les id de proejecte les cuals continguin aquella id d¡erquip
			$ids_proj = executePreparedQuery($conn, "SELECT projects_id FROM teamsprojects WHERE teams_id = :teamId", array(':teamId'=>$results->id), true);
			//Per cada resultat comprovo si el num de connexio actual es correspon amb algun dels resultats.
			foreach ($ids_proj as $result) {				

				if($result->projects_id == $projId){
					$foundInThisProj = true;
				}
			}
		}
		//Si no es troba cap projecte amb el mateix nom ni s'ha trobat a id d'equip al projecte insereixo l'equip i la connexió del projecte amb equip.		
		if($results == false || $foundInThisProj == false){

			$sql = "INSERT INTO teams (name, startDate, endDate) VALUES (:teamName, :startDate, :endDate)";                
			$arr = array(
	          ':teamName'=>strip_tags(trim($teamName)),
	          ':startDate'=>strip_tags(trim(date('Y-m-d'))),
	          ':endDate'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $endDate)))
	      	);
	      	executeInsertUpdateQuery($conn, $sql, $arr);

	      	//Selecciono la ID de l'úlitma insercio i l'asigno com a ID actual del projecte, Despres faig la connexió
	      	$teamId = $conn->lastInsertId();

	      	$sql = "INSERT INTO teamsprojects (teams_id, projects_id) VALUES (:teamId, :projId)";                
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