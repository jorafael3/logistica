<?php

$sql_serverName = "tcp:10.5.1.3,1433";
$sql_database = "CARTIMEX";
$sql_user = "jairo";
$sql_pwd = "qwertys3gur0";

// $sql_serverName = "tcp:10.5.1.86,1433";
// $sql_database = "COMPUTRONSA";
// $sql_user = "jalvarado";
// $sql_pwd = "jorge123";


try {
  $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database=$sql_database", $sql_user, $sql_pwd);
} catch (PDOException $e) {
  die('Connected failed:' . $e->getMessage());
}
