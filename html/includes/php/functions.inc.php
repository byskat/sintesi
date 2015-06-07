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

	//Aquesta funcio executa una consulta SQL i retorna el resultat	
	
	function executeQuery($conn, $sql, $alwaysFetchAll){		

			$query = $conn->query($sql);
			$count = $query->rowCount();
       		
       		//En algun cas m'interesa que tot i retornar un sol resultat em fasi un fetch all
			if($alwaysFetchAll == true && $count > 0){
				$result = $query->fetchAll(PDO::FETCH_OBJ);
			}else if ($alwaysFetchAll == false){
				if($count == 0){
					$result = false;
				}else if($count == 1){
					$result = $query->fetch(PDO::FETCH_OBJ);
				}else{
					$result = $query->fetchAll(PDO::FETCH_OBJ);
				}
			}else{
				$result = false;
			}			

			return $result;        
	}



	//Aquesta funcio mira si donada una consulta, aquesta torna algun resultat
	function executePreparedQuery($conn, $sql, $arr, $alwaysFetchAll){		

            $query = $conn->prepare($sql);                  
            $query->execute($arr);
            $count = $query->rowCount();
       		
       		//En algun cas m'interesa que tot i retornar un sol resultat em fasi un fetch all
			if($alwaysFetchAll == true && $count > 0){
				$result = $query->fetchAll(PDO::FETCH_OBJ);
			}else if ($alwaysFetchAll == false){
				if($count == 0){
					$result = false;
				}else if($count == 1){
					$result = $query->fetch(PDO::FETCH_OBJ);
				}else{
					$result = $query->fetchAll(PDO::FETCH_OBJ);
				}
			}else{
				$result = false;
			}			

			return $result;        
	}

	//Aquesta funció executa querys d'inserció i actualització
	function executeInsertUpdateQuery($conn, $sql, $arr){		            
        $query = $conn->prepare($sql);                  
        $query->execute($arr);
	} 

	//Aquesta funció formata la data per canvair el format en que es mostra i el que s'insereix a la base de dades.
	function formatDate($formatIn, $formatOut, $date){
		$date = DateTime::createFromFormat($formatIn, $date);    
        return $date->format($formatOut);
	}

	//Aquesta funció conprova si la data actual es mes petita que la donada.
	function checkOutdated($endDate){

		$today = date("Y-m-d");
		$expire = $endDate;	

		$todayDate = strtotime($today);
		$expireDate = strtotime($expire);

		if($expireDate < $todayDate){
			return true;
		}else{
			return false;
		}
	}

	//Aquesta funció es similar a la anterior pero comprova també que la data actual no sigui la mateixa que la introduida.
	function checkBirthday($birthday){

		$todayDate = strtotime(date("Y-m-d"));
		$birthdayDate = strtotime($birthday);

		if($birthdayDate < $todayDate || $birthdayDate == $todayDate){
			return true;
		}else{
			return false;
		}
	}

	//Funció per carregar una imarge al servidor
	function uploadImage($pathtoUpload){		

		$target_dir = $pathtoUpload;
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

		$message = "";
		// Comprova si la imatge es realment una imatge o es un fake.

	    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	    if($check !== false) {
	        $uploadOk = 1;
	    } else {
	        $uploadOk = 0;
	    }

		// Comprova si existeix un arxiu amb el mateix nom
		if (file_exists($target_file)) {
		    $message = "Ja existeix una imatge amb aquest nom";
		    $uploadOk = 0;
		}
		// Filtre de tamany de la imatge
		if ($_FILES["fileToUpload"]["size"] > 500000) {
		    $message = "Sorry, your file is too large.";
		    $uploadOk = 0;
		}
		// Filtre de formats acceptats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    $message = "Nomes estan permesos JPG, JPEG, PNG & GIF.";
		    $uploadOk = 0;
		}
		// Comprova si uploadOk està a 1 i del contrari mostra l'error.
		if ($uploadOk == 1) {

		    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		        $message = "L'arxiu ". basename( $_FILES["fileToUpload"]["name"]). " ha set pujat.";
		    } else {
		        $message = "Hi ha hagut un error al pujar l'arxiu.";
		    }
		}
		
		//Retorna un array amb el missatgem la variable uploadOk (si s'ha pujat o no) i l'arxiu amb el nom.
		return array($message, $uploadOk, $_FILES["fileToUpload"]["name"]);
	}
?>