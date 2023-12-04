<html>
<script type="text/javascript">
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">

<body> 

<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','On');
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$bodega= $_SESSION['bodega'];
			$acceso	=$_SESSION['acceso'];
			$_SESSION['usuario']= $usuario; 
			$_SESSION['bodega']= $bodega; 
			$factuid= $_POST ['secu']; 
			echo $factuid; 
			 //die(); 
			require('../conexion_mssql.php');
			$usuario = $_SESSION['usuario'];
			$bodega = $_SESSION['bodega'];
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare("delete from FACTURASLISTAS_zonas where PREPARADOPOR= '.' and tipo = 'INV-TR' AND anulado= 0 
									and  ESTADO = 'PREPARANDO' and fechaPreparando > '20210318' and Factura=:factuid" );
			$result->bindParam(':factuid',$factuid,PDO::PARAM_STR);
			$result->execute();	
			header("location: revertirpreparacion.php");
			//echo $base;
			$_SESSION['base']= $base;
			$_SESSION['usuario']= $usuario;
			$_SESSION['bodega']= $bodega;
			$_SESSION['nomsuc']=$nomsuc; 
		}
	else
		{
			header("location: index.html");
		}
 ?>
</body>