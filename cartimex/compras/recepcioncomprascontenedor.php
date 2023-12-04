<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../css/tablas.css">
<body> 
<div id= "header" align= "center">
<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','1');
session_start();	
if (isset($_SESSION['loggedin']))
	{	
		$usuario = $_SESSION['usuario'];
		$base = $_SESSION['base'];
		$acceso	=$_SESSION['acceso'];
		$bodega	=$_SESSION['bodega'];
				
		$idorden = $_POST['idorden'];
		$Placa = $_POST['Placa'];
		$Nota = $_POST['Nota'];
		//if ($base=='CARTIMEX'){require '../../headcarti.php';  }else{require '../../headcompu.php';	$_SESSION['base']= $base; }
		$_SESSION['usuario']= $usuario; 
		
		//echo $idorden. $Placa.$Nota;
		require('../../conexion_mssql.php');
		$usuario = $_SESSION['usuario'];
		$TipoIngreso= 'COM-OR'; 
		$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$result = $pdo->prepare("Insert into IMP_RECEPCION_LIQ_CONTENEDOR (LiquidacionID , Placa, Nota, TipoIngreso, creadopor ) values (:idorden, :Placa , :Nota, :TipoIngreso, :creadopor ) " );	
		$result->bindParam(':idorden',$idorden,PDO::PARAM_STR);
		$result->bindParam(':Placa',$Placa,PDO::PARAM_STR);
		$result->bindParam(':Nota',$Nota,PDO::PARAM_STR);							
		$result->bindParam(':TipoIngreso',$TipoIngreso,PDO::PARAM_STR);	
		$result->bindParam(':creadopor',$usuario,PDO::PARAM_STR);
		$result->execute();
		
			
		$_SESSION['usuario']=$usuario;
		$_SESSION['base']= $base ;
		$_SESSION['acceso']=$acceso;
		$_SESSION['codigo']=$codigo;
		$_SESSION['idorden']=$idorden;
		
		header("location: recepcioncompras1.php");
	}
else
	{
		header("location: index.html");
	}			
?>		 		
</div>		
</body>	
</html>	