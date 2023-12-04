<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<body>
<div id= "header" align= "center">
<?PHP
	session_start();
	if (isset($_SESSION['loggedin']))
		{
	 
			$usuario = $_SESSION['usuario'];
			$base= $_SESSION['base'];
			$acceso=$_SESSION['acceso'];
			$nomsuc=$_SESSION['nomsuc']; 	
			$iddestino = $_SESSION['IDDESTINO'];	
			$idorigen = $_SESSION['IDORIGEN'];
			$detalle = $_SESSION['DETALLE'];
			$tipo = 'INV-TR'; 
			$numerorecibido= $_SESSION['transfer'];
			$usuario1= $usuario; 
			//echo "Transferencia # ".$numerorecibido.$usuario.$base.$acceso.$nomsuc.$iddestino.$idorigen.$detalle;
			date_default_timezone_set('America/Guayaquil');
			$fecha = date("Y-m-d", time());
			$fecha= date("Ymd");
			if ($base=='CARTIMEX')
						{require 'headcarti.php';  	}
			else
				{
					require 'headcompu.php';
					$_SESSION['base']= $base; 
					$Nota = " "; 	
				}
			$arreglo2 = $_SESSION['arregloseriest']; // Cargo el arreglo de la memoria
			$xx = count($arreglo2);
			$x= 0 ; 
			
			//echo '<pre>', print_r($arreglo2),'</pre>';
			//die(); 
			while ($x < count($arreglo2))
				{
					require('conexion_mssql.php');	
					//echo "Transferencia # ".$numerorecibido.$usuario1.$base.$acceso.$nomsuc.$iddestino.$idorigen.$detalle;
					//die(); 					
					if (($arreglo2[$x][4])== 1) 
						{
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result = $pdo->prepare('rma_productos_insert_series_nuevas @serie=:serie, @productoid=:productoid, @BodegaActual=:iddestino, @transferenciaID=:transferenciaid, @CreadoPor=:usuario');
							$result->bindParam(':serie',$arreglo2[$x][2],PDO::PARAM_STR);
							$result->bindParam(':productoid',$arreglo2[$x][1],PDO::PARAM_STR);
							$result->bindParam(':iddestino',$iddestino,PDO::PARAM_STR);
							$result->bindParam(':transferenciaid',$numerorecibido,PDO::PARAM_STR);
							$result->bindParam(':usuario',$usuario1,PDO::PARAM_STR);
							$result->execute();
						}
					else
						{
							
							$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							//Update Bodega Actual de RMA_Productos tanto en CARTIMEX Y COMPUTRONSA
							$result1 = $pdo1->prepare('rma_productos_update_series @iddestino=:iddestino, @productoid=:productoid, @serie=:serie, @transferenciaid=:transferenciaid');
							$result1->bindParam(':iddestino',$iddestino,PDO::PARAM_STR);
							$result1->bindParam(':productoid',$arreglo2[$x][1],PDO::PARAM_STR);
							$result1->bindParam(':serie',$arreglo2[$x][2],PDO::PARAM_STR);
							$result1->bindParam(':transferenciaid',$numerorecibido,PDO::PARAM_STR);
							$result1->bindParam(':usuario',$usuario1,PDO::PARAM_STR);
							//Ejecutar Store 
							$result1->execute();
						}						
					
					$productoid = ($arreglo2[$x][1]);
					$serie = ($arreglo2[$x][2]);
					$anulado = 0 ;
					$egreso= 0; 
					//Inserta el movimiento de ingreso del cardex de series *********************************
					$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result2 = $pdo2->prepare('rma_productos_cardex_series_insert @serie=:serie, @ProductoID=:productoid, @BodegaID=:iddestino, @DocumentoID=:numerorecibido,
										    	@Fecha=:fecha, @Tipo=:tipo, @Detalle=:detalle, @Egreso=:egreso, @Anulado=:anulado, @CreadoPor=:usuario');
					$result2->bindParam(':serie',$serie,PDO::PARAM_STR);
					$result2->bindParam(':productoid',$productoid,PDO::PARAM_STR);
					$result2->bindParam(':iddestino',$iddestino,PDO::PARAM_STR);
					$result2->bindParam(':numerorecibido',$numerorecibido,PDO::PARAM_STR);
					$result2->bindParam(':fecha',$fecha,PDO::PARAM_STR);
					$result2->bindParam(':tipo',$tipo,PDO::PARAM_STR);
					$result2->bindParam(':detalle',$detalle,PDO::PARAM_STR);
					$result2->bindParam(':egreso',$egreso,PDO::PARAM_STR);
					$result2->bindParam(':anulado',$anulado,PDO::PARAM_STR);
					$result2->bindParam(':usuario',$usuario1,PDO::PARAM_STR);
					//Ejecutar Store 
					$result2->execute();
					
					$egreso= 1;
					$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result3 = $pdo3->prepare('rma_productos_cardex_series_insert @serie=:serie, @ProductoID=:productoid, @BodegaID=:idorigen, @DocumentoID=:numerorecibido,
										    	@Fecha=:fecha, @Tipo=:tipo, @Detalle=:detalle, @Egreso=:egreso, @Anulado=:anulado, @CreadoPor=:usuario');
					$result3->bindParam(':serie',$serie,PDO::PARAM_STR);
					$result3->bindParam(':productoid',$productoid,PDO::PARAM_STR);
					$result3->bindParam(':idorigen',$idorigen,PDO::PARAM_STR);
					$result3->bindParam(':numerorecibido',$numerorecibido,PDO::PARAM_STR);
					$result3->bindParam(':fecha',$fecha,PDO::PARAM_STR);
					$result3->bindParam(':tipo',$tipo,PDO::PARAM_STR);
					$result3->bindParam(':detalle',$detalle,PDO::PARAM_STR);
					$result3->bindParam(':egreso',$egreso,PDO::PARAM_STR);
					$result3->bindParam(':anulado',$anulado,PDO::PARAM_STR);
					$result3->bindParam(':usuario',$usuario1,PDO::PARAM_STR);	
					//Ejecutar Store 
					$result3->execute();
					$x++;
				}	
				$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result5 = $pdo5->prepare('UPDATE facturaslistas SET verificado =:usuario , fechaVerificado=:fecha WHERE factura=:facturaid AND Tipo=:tipo');			
				$result5->bindParam(':facturaid',$numerorecibido,PDO::PARAM_STR);
				$result5->bindParam(':usuario',$usuario1,PDO::PARAM_STR);
				$result5->bindParam(':fecha',$fecha,PDO::PARAM_STR);	
				$result5->bindParam(':tipo',$tipo,PDO::PARAM_STR);
				$result5->execute();
				
			
			?>	
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Verificar Transferencias <?php echo $nomsuc  ?></center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a>  </div>
				 
	</div>
<hr>
<div id="cuerpo2" align= "center">
					<p style="font-weight: bold" align="center" > <font size= "6" > Transferencia # <?php echo $numerorecibido ?>  completa! </font><br> </p>
</div>						
			<?php
			$_SESSION['usuario']=$usuario1;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['nomsuc']=$nomsuc; 	
			header("Refresh: 1 ; verificartransferencias.php");
		
		}
		else
		{
			header("location: index.html");
		}
?>
</div>
</body>
</html>