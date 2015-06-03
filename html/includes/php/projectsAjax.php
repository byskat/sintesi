<?php
	require('../../../includes/php/db.inc.php');
	require('./functions.inc.php');
	
	$formId = $_GET['formId'];
	$projId = $_GET['idProj'];
	$connId = $_GET['idConn'];
	$projName = $_GET['projName'];
	$projEndDate = $_GET['projEndDate'];
	$projDesc = $_GET['projDesc'];
	$action = $_GET['action'];

	//Processo la base de dades	

	if($action == "update"){		
		
		$sql = "UPDATE projects SET name = :projName, endDate = :endDate, description = :projDesc  WHERE id = :projId";
		$arr = array(
	    	':projName'=>strip_tags(trim($projName)),
	    	':endDate'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $projEndDate))),
	    	':projDesc'=>strip_tags(trim($projDesc)),
	    	':projId'=>strip_tags(trim($projId))
		);

        executeInsertUpdateQuery($conn, $sql, $arr);

        //Si ha modificat la data i deixa d'estar caducat torna el valor de outdated de la BBDD a 0
		
		if(checkOutdated( formatDate('d/m/Y', 'Y-m-d', $projEndDate) ) == false){
			executeInsertUpdateQuery($conn, "UPDATE projects SET outdated = 0 WHERE id = :projId", array(':projId'=>$projId) );
			$updateOutdated = true;
		}		

        //Proceso els valors a retornar pel servidor. Aquests valors em permetran actualitzar dinamicament el quadre de la connexio

		$updateValues = array(
			"formId" => $formId,
			"projId" => $projId,
	    	"projName" => $projName,
	    	"endDate" => $projEndDate,
	    	"projDesc" => $projDesc,
	    	"outdated" => $updateOutdated
		);

		echo json_encode($updateValues);       

	}else if($action == "create"){

		$foundInThisConn = false;

		//Comprovo que no es pugui crear un projecte amb el mateix nom
		$results = executePreparedQuery($conn, "SELECT id FROM projects WHERE name = :projName", array(':projName'=>$projName), false);	

		if($results != false){
			//Per tal de que dues conexions diferents puguin tenir un projecte amb el mateix nom. Necesito saver de quina connexio pertany el resultat. Selecciono totes les id_de connexio les cuals continguin aquella id de projecte
			$ids_conn = executePreparedQuery($conn, "SELECT connections_id FROM `connectionsProjects` WHERE projects_id = :projId", array(':projId'=>$results->id), true);
			//Per cada resultat comprovo si el num de connexio actual es correspon amb algun dels resultats.
			foreach ($ids_conn as $result) {

				if($result->connections_id == $connId){
					$foundInThisConn = true;
				}
			}
		}		 
		
		if($results == false || $foundInThisConn == false){

			$sql = "INSERT INTO projects (name, startDate, endDate, description) VALUES (:projName, :startDate, :endDate, :projDesc)";                
			$arr = array(
	          ':projName'=>strip_tags(trim($projName)),
	          ':startDate'=>strip_tags(trim(date('Y-m-d'))),
	          ':endDate'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $projEndDate))),
	          ':projDesc'=>strip_tags(trim($projDesc))
	      	);
	      	executeInsertUpdateQuery($conn, $sql, $arr);

	      	//Selecciono la ID de la ultima insercio i l'asigno com a ID actual del projecte, Despres faig la connexio
	      	$projId = $conn->lastInsertId();

	      	$sql = "INSERT INTO connectionsProjects (connections_id, projects_id) VALUES (:connId, :projId)";                
			$arr = array(
	          ':connId'=>$connId,
	          ':projId'=>$projId
	      	);
	      	executeInsertUpdateQuery($conn, $sql, $arr);
      	}else{
	      	echo "Ja existeix un projecte amb aquest nom";
      	}
	}   

?>