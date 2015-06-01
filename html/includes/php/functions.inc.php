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

		if($count == 0){
			$result = false;
		}else if($count == 1){
			$result = $query->fetch(PDO::FETCH_OBJ);
		}else{
			$result = $query->fetchAll(PDO::FETCH_OBJ);
		}
		
		return $result;
	}

	//Aquesta funcio mira si donada una consulta, aquesta torna algun resultat
	function executePreparedQuery($conn, $sql, $arr){		

            $query = $conn->prepare($sql);                  
            $query->execute($arr);
            $count = $query->rowCount();

            if($count == 0){
				$result = false;
			}else if($count == 1){
				$result = $query->fetch(PDO::FETCH_OBJ);
			}else{
				$result = $query->fetchAll(PDO::FETCH_OBJ);
			}

			return $result;        
	}

	function executeUpdateQuery($conn, $sql){
		$conn->exec($sql);
	}

	function executeInsertQuery($conn, $sql, $arr){		            
        $query = $conn->prepare($sql);                  
        $query->execute($arr);
	} 

	function formatDate($formatIn, $formatOut, $date){
		$date = DateTime::createFromFormat($formatIn, $date);    
        return $date->format($formatOut);
	}	

?>