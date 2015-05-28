<?php
	require('../../../includes/php/db.inc.php');
	require('./functions.inc.php');
	
	$connName = $_GET['connName'];
	$connEndDate = $_GET['connEndDate'];
	$connCenter2Name = $_GET['connCenterName'];
	$idCenter1 = $_GET['userNameCenterId'];


	//Obtenc el nom del centre del creador de la conexio (El necessitare per passar dades que em serivran per actualitzar els camps de la connexio).
	
	$sql = "SELECT name FROM centers WHERE id =" . $idCenter1;
	$query = $conn->query($sql);
	$result = $query->fetch(PDO::FETCH_OBJ);
	$nameCenter1 = $result->name;

	//A partir del nom del centre 2 trec la seva ID
	$sql = "SELECT id FROM centers WHERE name ='" . $connCenter2Name . "'";
	$query = $conn->query($sql);
	$result = $query->fetch(PDO::FETCH_OBJ);
	$idcenter2 = $result->id;

	//Processo la base de dades	

	if($_GET['action'] == "update"){

		$formId = $_GET['formId'];		

		$sql = "UPDATE connections SET name = '" . $connName . "', endDate = '" . formatDate('d/m/Y', 'Y-m-d', $connEndDate) . "' WHERE id =" . $formId;
        $conn->exec($sql);

        //Proceso els valors a retornar pel servidor. Aquests valors em permetran actualitzar dinamicament el quadre de la connexio

		$updateValues = array(
			"connId" => $formId,
	    	"connName" => $connName,
	    	"connCenters"  => $nameCenter1 . " & " . $connCenter2Name,
	    	"endDate" => $connEndDate,
	    	"nameCenter2" => $connCenter2Name
		);

		echo json_encode($updateValues);       

	}else if($_GET['action'] == "create"){
		
		$sql = "INSERT INTO `connections` (idcenter1, idcenter2, name, startDate, endDate) VALUES (:idcenter1, :idcenter2, :name, :startDate, :endDate)";            
		$query = $conn->prepare($sql);                  
		$query->execute(array(
          ':idcenter1'=>strip_tags(trim($idCenter1)),
          ':idcenter2'=>strip_tags(trim($idcenter2)),
          ':name'=>strip_tags(trim($connName)),
          ':startDate'=>strip_tags(trim(date('Y-m-d'))),
          ':endDate'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $connEndDate)))
      ));

	}
	
    

?>