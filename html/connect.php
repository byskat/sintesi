<?php
//$connection = mysql_connect('localhost', 'root', '');
$connection = mysqli_connect("localhost","root","123456","creditSintesi");
if (mysqli_connect_errno()){
    die("ERROR: No s'ha pogut accedir a la base de dades." . mysqli_connect_error());
}
/*
$select_db = mysql_select_db('login');
if (!$select_db){
    die("ERROR: No s'ha pogut accedir a la taula." . mysql_error());
}
*/