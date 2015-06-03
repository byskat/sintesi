<?php
    try {
        $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $dsn = "mysql:host=localhost;dbname=creditSintesi";
        $conn = new PDO($dsn, "root", "virasengam", $opc);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    }
    catch (PDOException $e) {
        $error = $e->getCode();
        $missatge = $e->getMessage();
        die("Hi ha hagut un error ". $missatge);
    }
?>