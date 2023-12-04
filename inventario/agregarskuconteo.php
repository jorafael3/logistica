<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body> 

<?php 
error_reporting(E_ALL);
ini_set('display_errors','On');
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$bodega = $_SESSION['bodega'];
			$ubi = $_POST["ubi"];
			$prodid = $_POST["prodid"];
			$ConteoID= $_SESSION['ConteoID'];
			$usuario1= $usuario; 
			//echo "Usuario:".$usuario. $base. $codigo. $nombre.$clave . $acceso .$lugartrabajo.$usrid.$id; 
			
			echo "1Usuario:---".$usuario."Datos a grabar ". $base. $ubi. $prodid. $bodega. $ConteoID; 
			//die();
			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			echo "2Usuario:---".$usuario."Datos a grabar ". $base. $ubi. $prodid. $bodega;
			 
			//Select Query
			$result = $pdo->prepare("Insert into Bodega_SKU (ID, percha, bodega) values (:prodid,:ubi,:bodega)" );
			$result->bindParam(':prodid',$prodid,PDO::PARAM_STR);
			$result->bindParam(':ubi',$ubi,PDO::PARAM_STR);	
			$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);				
			//Executes the query
			$result->execute();
			
			$_SESSION['base']= $base;
			$_SESSION['Bodegacont']=$bodega;
			$_SESSION['usuario']=$usuario1;
			$_SESSION['ConteoID'] = $ConteoID;
			$_SESSION['proid'] = $prodid;
			echo "Usuario:---".$usuario1."Datos a grabar ". $base. $ubi. $prodid; 
			//die();
			header("location: ingresasegundoconteo1.php?pid=$prodid");
		}
	else
		{
			header("location: ../index.html");
		}
 ?>
</body>