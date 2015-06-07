<?php

    try {
        $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $dsn = "mysql:host=localhost;dbname=creditsintesi";
        $conn = new PDO($dsn, "root", "1234", $opc);
    }
    catch (PDOException $e) {
        $error = $e->getCode();
        $missatge = $e->getMessage();
        die("Hi ha hagut un error ". $missatge);
    }

	$txt = file_get_contents("source.txt");
	$matches = [];
	$num = preg_match_all("/^([^\(].*),\s(.*)\ \d\ (.*)$/m", $txt,
			$matches, 
			PREG_SET_ORDER);

	$arr_nums = [];	
	
	foreach($matches as $match) {
		$number = str_replace('.', '', $match[3]);
		$name = str_replace('(*)', '', $match[2]);
		$name = trim($name);
		$lastName = $match[1];

		if(array_search("(" . $number . ",", $arr_nums) === false){
			array_push($arr_nums, "(" . $number . ",");

			$sql = "INSERT INTO teachersvalidations (orderNum, name, lastName) VALUES(:num, :name, :lastName)";
			
			$query = $conn->prepare($sql);
			$query->execute(array(':num'=>$number,
								  ':name'=>$name,
								  ':lastName'=>$lastName));
		}
	}

	echo("Done");
?>
