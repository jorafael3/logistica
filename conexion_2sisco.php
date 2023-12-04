<?php

$sql_serverName = "tcp:10.5.1.245";
$sql_database = "sisco";
$sql_user = "root";
$sql_pwd = "Bruno2001";

$DB_HOST = "10.5.1.245";
$DB_USER = "root";
$DB_PASS = "Bruno2001";
$DB_NAME = "sisco";

// $sql_serverName = "tcp:10.5.1.86,1433";
// $sql_database = "COMPUTRONSA";
// $sql_user = "jalvarado";
// $sql_pwd = "jorge123";


try {
  $pdo = new PDO("mysql:host=10.5.1.245;dbname=" . $sql_database, $sql_user, $sql_pwd);
} catch (PDOException $e) {
  die('Connected failed:' . $e->getMessage());
}
