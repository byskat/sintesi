<?php
	require('../../../includes/php/db.inc.php');
	require('./functions.inc.php');
	
	$projId = $_GET['projId'];
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

        //Proceso els valors a retornar pel servidor. Aquests valors em permetran actualitzar dinamicament el quadre de la connexio

		$updateValues = array(
			"projId" => $projId,
	    	"projName" => $projName,
	    	"endDate" => $projEndDate,
	    	"projDesc" => $projDesc
		);

		echo json_encode($updateValues);       

	}else if($action == "create"){		
		
		//Comprovo que no es pugui crear un projecte amb el mateix nom
		$results = executePreparedQuery($conn, "SELECT id FROM projects WHERE name = :projName", array(':projName'=>$projName), false);
		
		if($results == false){

			$sql = "INSERT INTO projects (name, startDate, endDate, description) VALUES (:projName, :startDate, :endDate, :projDesc)";                
			$arr = array(
	          ':projName'=>strip_tags(trim($projName)),
	          ':startDate'=>strip_tags(trim(date('Y-m-d'))),
	          ':endDate'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $projEndDate))),
	          ':projDesc'=>strip_tags(trim($projDesc))
	      	);
	      	executeInsertUpdateQuery($conn, $sql, $arr);

	      	/*$sql = "INSERT INTO connectionsProjects (connections_id, projects_id) VALUES (:connId, :projId)";                
			$arr = array(
	          ':connId'=>$connId,
	          ':projId'=>$projId
	      	);
	      	executeInsertUpdateQuery($conn, $sql, $arr);*/
      	}else{
	      	echo "Ja existeix un projecte amb aquest nom";
      	}


	}
	
    

?>