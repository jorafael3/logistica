<?php

$DB_HOST = "10.5.1.245";
$DB_USER = "root";
$DB_PASS = "Bruno2001";
$DB_NAME = "computrones";

/// Para MYSQL
$concom = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME); 
if (!$concom) 
{
    die("Connection failed: " . mysqli_connect_error());
}

