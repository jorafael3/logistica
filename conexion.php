<?php

$DB_HOST = "10.5.1.245";
$DB_USER = "root";
$DB_PASS = "Bruno2001";
$DB_NAME = "sisco";


$min_link2pay = 400;
//echo "Aqui estoy".$DB_HOST.$DB_USER.$DB_PASS.$DB_NAME;

/// Para MYSQL
$con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME); 

if (!$con) 
{
    die("Connection failed: " . mysqli_connect_error());
}

//echo "Connected successfully";
//mysqli_close($con);




//$link = mssql_connect("10.5.1.3:1433", "jairo", "qwertys3gur0");
//date_default_timezone_set('America/Guayaquil');

