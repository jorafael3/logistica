<html>
<script type="text/javascript">
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">

<body> 
<div id= "header" align= "center">
<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','On');
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	=$_SESSION['acceso'];
			$bodega = $_SESSION['bodega'];
			$LiqID = $_POST['LiqID'];
			
			$_SESSION['usuario']= $usuario ;
			if ($base=='CARTIMEX'){	require '../headcarti.php'; }else{require '../headcompu.php';}
					
			require('../../conexion_mssql.php');
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result1 = $pdo1->prepare('DELETE FROM IMP_RECEPCION_LIQ_CONTENEDOR where LiquidacionID=:LiqID');		 
			$result1->bindParam(':LiqID',$LiqID,PDO::PARAM_STR);
			$result1->execute();
			$usuario= $_SESSION['usuario'];
			 
			$_SESSION['usuario']= $usuario;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;	
			header("Refresh: 0 ; recepcionimportaciones.php");    
		}
	else
		{		
			header("location: index.html");
		}		
?>
</div>					
 </div>
</body>