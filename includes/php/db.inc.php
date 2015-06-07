<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 'On');

    //define('URL', 'http://10.101.0.20:8080');

    try {
        $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $dsn = "mysql:host=localhost;dbname=creditsintesi";
        $conn = new PDO($dsn, "root", "1234", $opc);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    }
    catch (PDOException $e) {
        $error = $e->getCode();
        $missatge = $e->getMessage();
        die("Hi ha hagut un error ". $missatge);
    }
?>