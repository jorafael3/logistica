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
//ini_set('display_errors','On');
		session_start();	
		if (isset($_SESSION['loggedin']))
			{	
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega	=$_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					$usuario1 = $usuario;  
					
					date_default_timezone_set('America/Guayaquil');
					$year = date("Y");
					
					//probar si se graba bien el dia y el mes 
					$fecha = date("Y-d-m", time());
					$hora = date("H:i:s", time());
					$fh = $fecha . " " . $hora;
					$idorden = $_POST['idorden'];
					
					if ($base=='CARTIMEX'){require '../../headcarti.php';  }else{require '../../headcompu.php';	$_SESSION['base']= $base; }
					echo  $idorden ;
					
					require('../../conexion_mssql.php');
					$usuario = $_SESSION['usuario'];
					$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result0 = $pdo0->prepare("UPDATE COM_ORDENES SET ESTADO = 'RECIBIDA' , FRECIBIDA=:fecha, RecibidoPor=:RecibidoPor from COM_ORDENES where  ID=:idorden " );	
					$result0->bindParam(':fecha',$fh,PDO::PARAM_STR);							
					$result0->bindParam(':RecibidoPor',$usuario1,PDO::PARAM_STR);	
					$result0->bindParam(':idorden',$idorden,PDO::PARAM_STR);							
					$result0->execute();
					
					
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare("UPDATE INV_PRODUCTOS_SERIES_COMPRAS SET FRecibidoCompra=:fh , RecibidoCompraPor=:usuario from INV_PRODUCTOS_SERIES_COMPRAS where  OCompraID=:idorden " );	
					$result->bindParam(':fh',$fh,PDO::PARAM_STR);
					$result->bindParam(':usuario',$usuario1,PDO::PARAM_STR);
					$result->bindParam(':idorden',$idorden,PDO::PARAM_STR);							
					$result->execute();
					
					
						 	
					$_SESSION['usuario']=$usuario1;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo;
					$_SESSION['idorden']=$idorden;
					
					header("location: mailnovedadescompras.php");
					//header("location: recepcioncompras.php");
			}
		else
			{
				header("location: index.html");
			}			
?>		 		
			</table>
			<br>
			</form> 
	</div>
	<br>	
</div>
</div>		
</body>	
</html>	