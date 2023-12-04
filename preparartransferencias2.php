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
			$id = $_SESSION['id']; 
			$base = $_SESSION['base'];
			$bodega= $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			//echo "1Usuario:---".$usuario."Datos a grabar ". $base. $id . $bodega;
			  
			$_SESSION['usuario']= $usuario; 
			$_SESSION['bodega']= $bodega; 
			require('conexion_mssql.php');
			$usuario = $_SESSION['usuario'];
			$bodega = $_SESSION['bodega'];
			
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare('Log_facturaslistas_preparartr_update @id=:id , @usuario=:usuario' );
			$result->bindParam(':id',$id,PDO::PARAM_STR);
			$result->bindParam(':usuario',$usuario,PDO::PARAM_STR);		
			//Executes the query
			$result->execute();	
			
			$_SESSION['base']= $base;
			$_SESSION['id']= $id;
			$_SESSION['usuario']= $usuario;
			$_SESSION['bodega']= $bodega;
			$_SESSION['nomsuc']=$nomsuc; 	
			//echo "Usuario:---".$usuario."Datos a grabar ". $base. $ubi. $prodid. $bodega; 
			 
			header("location: preparartransferencias.php");
		}
	else
		{
			header("location: index.html");
		}
 ?>
</body>