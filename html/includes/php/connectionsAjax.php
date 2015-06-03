<?php
	require('../../../includes/php/db.inc.php');
	require('./functions.inc.php');
	
	$formId = $_GET['formId'];	
	$connName = $_GET['connName'];
	$connEndDate = $_GET['connEndDate'];
	$connCenter1Name = $_GET['connCenter1Name'];
	$connCenter2Name = $_GET['connCenter2Name'];
	$idCenter1 = $_GET['userNameCenterId'];
	$updateOutdated = false;

	//A partir del nom del centre 2 (obtingut del select al crear un centre) trec la seva ID
	
	$result = executePreparedQuery($conn, "SELECT id FROM centers WHERE name = :center2Name", array("center2Name" => $connCenter2Name), false);
	$idCenter2 = $result->id;

	//Processo la base de dades	

	if($_GET['action'] == "update"){							
		
		$sql = "UPDATE connections SET name = :connName, endDate = :endDate WHERE id = :formId";
		$arr = array(
          ':connName'=>strip_tags(trim($connName)),
          ':endDate'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $connEndDate))),
          ':formId'=>strip_tags(trim($formId))
     	);
     	executeInsertUpdateQuery($conn, $sql, $arr);

     	//Si ha modificat la data i deixa d'estar caducat torna el valor de outdated de la BBDD a 0
		
		if(checkOutdated( formatDate('d/m/Y', 'Y-m-d', $connEndDate) ) == false){
			executeInsertUpdateQuery($conn, "UPDATE connections SET outdated = 0 WHERE name = :connName ", array(':connName'=>$connName) );
			$updateOutdated = true;
		}		

        //Proceso els valors a retornar pel servidor. Aquests valors em permetran actualitzar dinamicament el quadre de la connexio

		$updateValues = array(
			"connId" => $formId,
	    	"connName" => $connName,
	    	"connCenters"  => $connCenter1Name . " & " . $connCenter2Name,
	    	"endDate" => $connEndDate,
	    	"nameCenter2" => $connCenter2Name,
	    	"outdated" => $updateOutdated
		);

		echo json_encode($updateValues);       

	}else if($_GET['action'] == "create"){

		//Comprovo que no es pugui crear una connexio amb el mateix nom
		$results = executePreparedQuery($conn, "SELECT id FROM connections WHERE name = :connName", array(':connName'=>$connName), false);  
		
		if($results == false){
			
			$sql = "INSERT INTO `connections` (idcenter1, idcenter2, name, startDate, endDate) VALUES (:idcenter1, :idcenter2, :name, :startDate, :endDate)"; 
			$arr= array(
	          ':idcenter1'=>strip_tags(trim($idCenter1)),
	          ':idcenter2'=>strip_tags(trim($idCenter2)),
	          ':name'=>strip_tags(trim($connName)),
	          ':startDate'=>strip_tags(trim(date('Y-m-d'))),
	          ':endDate'=>strip_tags(trim(formatDate('d/m/Y', 'Y-m-d', $connEndDate)))
	      	);
			executeInsertUpdateQuery($conn, $sql, $arr);
		}else{
			echo "Ja existeix un projecte amb aquest nom";
		}

	}

?>