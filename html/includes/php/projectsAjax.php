<?php
	require('../../../includes/php/db.inc.php');
	require('./functions.inc.php');
	
	$projId = $_GET['projId'];
	//$connId = $_GET['connId'];
	$projName = $_GET['projName'];
	$projEndDate = $_GET['projEndDate'];
	$projDesc = $_GET['projDesc'];
	$action = $_GET['action'];


	//Processo la base de dades	

	if($action == "update"){				

		$sql = "UPDATE projects SET name = '" . $projName . "', endDate = '" . formatDate('d/m/Y', 'Y-m-d', $projEndDate) . "' WHERE id =" . $projId;
        
        $conn->exec($sql);

        //Proceso els valors a retornar pel servidor. Aquests valors em permetran actualitzar dinamicament el quadre de la connexio

		$updateValues = array(
			"projId" => $projId,
	    	"projName" => $projName,
	    	"endDate" => $projEndDate,
	    	"projDesc" => $projDesc
		);

		echo json_encode($updateValues);       

	}/*else if($action == "create"){		
		
		$sql = "INSERT INTO projects (name, startDate, endDate, description) VALUES (:projName, :startDate, :endDate, :projDesc)";
		$query = $conn->prepare($sql);                  
		$query->execute(array(
          ':projName'=>strip_tags(trim($idcenter2)),
          ':startDate'=>strip_tags(trim(date('Y-m-d'))),
          ':endDate'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $connEndDate))),
          ':projDesc'=>strip_tags(trim($connName))
      	));

      	$sql = "INSERT INTO connectionsProjects (connections_id, projects_id) VALUES (:connId, :projId)";
		$query = $conn->prepare($sql);                  
		$query->execute(array(
          ':connId'=>$connId,
          ':projId'=>$projId
      	));


	}*/
	
    

?>