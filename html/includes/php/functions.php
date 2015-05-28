<?php
	//Aquesta funcio ompla els valors del select de centres
	function fillDropDownCenters($conn){

		$options = "";
	    $options .= "<option value='startOption' selected disabled >Selecciona un centre</option>";
	    $sql = "SELECT name FROM centers";
	    $query = $conn->query($sql);
	    $results = $query->fetchAll(PDO::FETCH_OBJ);                            

	    foreach ($results as $result) {                                
	        $options .= "<option value='" . $result->name . "'>". $result->name . "</option>";
	    }

	    return $options;
	}

	//Aquesta funcio executa un codi SQL i retorna el resultat	
	
	function executeQuery($conn, $query){
		$sql = $query;
		$query = $conn->query($sql);
		$count = $query->rowCount();

		if($count == 1){
			$result = $query->fetch(PDO::FETCH_OBJ);
		}else{
			$result = $query->fetchAll(PDO::FETCH_OBJ);
		}

		return $result;
	} 

	function formatDate($formatIn, $formatOut, $date){
		$date = DateTime::createFromFormat($formatIn, $date);    
        return $date->format($formatOut);
	}

?>