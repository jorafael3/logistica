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
			$transfer = $_SESSION['transfer']; 
			$base = $_SESSION['base'];
			$bodega= $_SESSION['bodega'];
			$acceso	=$_SESSION['acceso'];
			$nomsuc = $_SESSION['nomsuc'];	
			$_SESSION['usuario']= $usuario; 
			$_SESSION['bodega']= $bodega; 
			$tipo = 'INV-TR'; 
			$zretiro = $_POST['zona'];
			echo "Datos a grabar".$transfer. $usuario. $acceso. $zretiro; 
			//die(); 
			require('../conexion_mssql.php');
			$usuario = $_SESSION['usuario'];
			$bodega = $_SESSION['bodega'];
			
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('Log_facturaslistas_preparar_zonas_update 
									@id=:transfer , @usuario=:usuario , @acceso =:acceso , @zretiro=:zretiro , @tipo=:tipo' );
			$result->bindParam(':transfer',$transfer,PDO::PARAM_STR);
			$result->bindParam(':usuario',$usuario,PDO::PARAM_STR);			
			$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
			$result->bindParam(':zretiro',$zretiro,PDO::PARAM_STR);
			$result->bindParam(':tipo',$tipo,PDO::PARAM_STR);
			$result->execute();	
			//echo $base;
			
			$_SESSION['base']= $base;
			$_SESSION['transfer']= $transfer;
			$_SESSION['usuario']= $usuario;
			$_SESSION['bodega']= $bodega;
			$_SESSION['nomsuc']=$nomsuc; 
			echo "2Usuario:---".$usuario."Datos a grabar ". $base. $bodega; 
			//die(); 
			header("location: preparartransferencias.php");
		}
	else
		{
			header("location: index.html");
		}
 ?>
</body>