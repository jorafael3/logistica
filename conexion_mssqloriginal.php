<?php

$usuario = $_POST['Usr'];
$pass = $_POST['Pwd'];
$base = $_POST['Empresa'];

if ($_POST['Empresa']=='')
	{
		$base = $_SESSION['base'];

	}

else
	{
		$base = $_POST['Empresa'];
	}

$basecon = $base; 
 
$sql_serverName = "tcp:10.5.1.3,1433";
//$sql_user = "userweb";
//$sql_pwd = "CA043714240";
 
$sql_user = "jairo";
$sql_pwd = "qwertys3gur0";

$dsn = 'sqlsrv:server='.$sql_serverName';dbname='.$basecon.'';
//$dsn = 'sqlsrv:server=$sql_serverName ; Database = $basecon';




try
{
	$pdo_test = new PDO($dsn, $sql_user, $sql_pwd);
	//$pdo_test = new PDO($dsn, $sql_user, $sql_pwd);
	echo "Conectado con exito";
	die(); 
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
$pdo_test = null;



/*****************Conexion alterna hasta saber xq no funciona la de arriba ********/
/*
$link = mssql_connect('10.5.1.3:1433', 'jairo', 'qwertys3gur0');

if (!$link)
    die('Unable to connect!');
else
   //     echo mssql_get_last_message();
    //    echo '<br>';

if (!mssql_select_db($basecon, $link))
    die('Unable to select database!');
else
	
 //        echo mssql_get_last_message();
 //       echo '<br>';

echo '<br>';
*/
?>
