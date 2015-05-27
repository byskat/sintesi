<?php

//Cadascun dels regexp a utilitzar
define('RGXP_EMAIL', "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/");
define('RGXP_ORDERNUMBER', '/^[0-9]*$/');
define('RGXP_USERNAME', '/^[a-z0-9_-]{3,15}$/');

//Inclou l'array amb tots els missatges d'error per idioma

/*
	if ($_SESSION['lang'] == ca){
		include './languages/catala.php';
	}
	if ($_SESSION['lang'] == es){
		include './languages/castella.php';
	}
	if ($_SESSION['lang'] == en){
		include './languages/engish.php';
	}
 */

include './languages/catala.php';

//Se li pasa un error per marametre (ex: invalid text) i torna segons l'idioma
function translate($name) {
	global $strings;
	return empty($strings[$name]) ? $name : $strings[$name];
}

//getter el valor $_GET['cosa'];
function getParam($name, $regexp = '/(.*)/') {
	return parseParam(INPUT_GET, $name, $regexp);
}

//getter el valor $_POST['cosa'];
function postParam($name, $regexp = '/(.*)/') {
	return parseParam(INPUT_POST, $name, $regexp);
}

//Funcio que retorna el valor $_POST[] o $_GET[] validat per un regexp]
function parseParam($type, $name, $regexp) {
	$value = filter_input($type, $name, FILTER_VALIDATE_REGEXP, [
			'options' => [
			'regexp' => $regexp
			]]);
	//var_dump($name, $regexp, $value);
	//Si ergexp falla o no existeix dona false o la llegada de valoe es 0 torna null si no torna el valor de l'input
	return ($value === false || strlen(trim($value)) === 0) ? null : $value;
}

function comprovarCamps($camps, $method = 'get') {
	$errors = [];
	$method = strtolower($method . 'Param');
	foreach($camps as $nom => $camp) {
		$valid = true;
	
		$validation = empty($camp['validation']) ? '/(.*)/' : $camp['validation'];

		if(is_callable($validation)) {
			$valor = call_user_func($method, $nom);
			$valid = call_user_func($validation, $valor);	
	    } else {
			$valor = call_user_func($method, $nom, $validation);
			if(($valor === null && empty($camp['opcional']))
					|| (!empty(call_user_func($method, $nom)) && !empty($camp['opcional']))) {
				$valid = false;
			}
		}

		if(!$valid) $errors[] = translate('invalid_' . $nom);
		
	}
	return $errors;
}


function obtenirCamps($camps, $method = 'get') {
	$resultat = new stdClass;
	$method = strtolower($method . 'Param');
	foreach($camps as $nom => $camp) {
		$validation = (empty($camp['validation']) || is_callable($camp['validation'])) ? '/(.*)/' : $camp['validation'];
		$resultat->{$nom} = call_user_func($method, $nom, $validation);
	}
	return $resultat;
}

?>
