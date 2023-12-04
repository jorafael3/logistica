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
					$fileName= $_SESSION['fileName']; 
					date_default_timezone_set('America/Guayaquil');
					$year = date("Y");
					//probar si se graba bien el dia y el mes 
					$fecha = date("Y-d-m", time());
					$hora = date("H:i:s", time());
					$fh = $fecha . " " . $hora;
					$idorden = $_POST['idorden'];
					$codprod = $_POST['codprod'];
					$serie = $_POST['serie'];
					$fin = count($serie);
					
					if ($base=='CARTIMEX'){require '../../headcarti.php';  }else{require '../../headcompu.php';	$_SESSION['base']= $base; }
					$_SESSION['usuario']= $usuario; 
					//echo  $idorden . "FILAS" . $fin;
					require('../../conexion_mssql.php');
					$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result0 = $pdo0->prepare("delete from inv_productos_series_compras where OCompraID=:idorden and FCompraID is null " );	
					$result0->bindParam(':idorden',$idorden,PDO::PARAM_STR);							
					$result0->execute();
					
					$usuario = $_SESSION['usuario'];
					$i=0;
					for ($i = 0; $i <= $fin-1; $i++) 
						{ 
							$codprodval = $codprod[$i];
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result = $pdo->prepare("SELECT  productoid = P.id 
													from inv_productos p with (nolock) 
													inner join COM_ORDENES_DT ODT ON ODT.ProductoID= P.ID
													where p.registroseries= 1 and ODT.OrdenID=:idorden AND  código=:codprod" );	
							$result->bindParam(':idorden',$idorden,PDO::PARAM_STR);	
							$result->bindParam(':codprod',$codprodval,PDO::PARAM_STR);							
							$result->execute();
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
								{
									$productoid=$row['productoid'];	 
									$serieval= $serie[$i] ;
									//echo $productoid. "---".$idorden . "----SERIE".$serieval . "<br>";								
									$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
									$result1 = $pdo1->prepare('insert into inv_productos_series_compras (OCompraID, ProductoID, Serie, FRecibido, RecibidoPor) 
															 values (:OCompraID, :ProductoID, :Serie, :FRecibido, :RecibidoPor ) ');		 
									$result1->bindParam(':OCompraID',$idorden,PDO::PARAM_STR);						 
									$result1->bindParam(':ProductoID',$productoid,PDO::PARAM_STR);
									$result1->bindParam(':Serie',$serieval,PDO::PARAM_STR);
									$result1->bindParam(':FRecibido',$fh,PDO::PARAM_STR);
									$result1->bindParam(':RecibidoPor',$usuario,PDO::PARAM_STR);
									$result1->execute(); 
								}
						}	 	
					$_SESSION['usuario']=$usuario;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo;
					$_SESSION['idorden']=$idorden;
					
					unlink('/var/www/html/logistica/cartimex/temp/'.$fileName);
					header("location: recepcioncompras1.php");
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