<?php
# Readme: Database Connect 1.3 by @RomelSan (November 22 2019)
# Usage: 
//       require('dbcore.php');
 

$sql_serverName = "tcp:10.5.1.205,1433";
$sql_database = "XTRATECH";
$sql_user = "mike";
$sql_pwd = "Princes@";
 
try
{
	$pdo_test = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
}

catch (PDOException $e)
{
	/* If there is an error an exception is thrown */
	// $pdo_exp = $e;
	echo "
	<script>
	console.log(\"No se puede conectar a la base de datos\");
	</script>
	";
	die("El sitio no se encuentra disponible por el momento, disculpe las molestias.");
}
//$pdo_test = null;