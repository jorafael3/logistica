<html>
<script type="text/javascript">
</script>
<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../css/tablas.css">
<body> 
<div id= "header" align= "center">
<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$bodega = $_SESSION['bodega'];
			$nomsuc =$_SESSION['nomsuc'];
			$checkbox = $_POST['checkbox'] ;
			$secu= $_SESSION['secu'];
			$n= $_SESSION['productos'];
			$FacturaId= $_SESSION['FacturaId'] ;
			$fin = count($checkbox);
			$cant= $_POST['cant'];
			$_SESSION['usuario']= $usuario;
			
			
			//die(); 
			date_default_timezone_set('America/Guayaquil');
			$fecha = date("Y-m-d", time());
			$fecha= date("Ymd");
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;
			if ($base=='CARTIMEX'){
					require '../../headcarti.php';  	
			}
			else{
					require '../../headcompu.php';
			}
			
			require('../../conexion_mssql.php');
			$usuario = $_SESSION['usuario'];
?>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Recepcion Ensambles </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
	</div>
</div>
<hr>
<?php
			if ($fin== $n )
				{
					
					$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result2 = $pdo2->prepare('INV_PRODUCCION_ENSAMBLE_SELECTID');	
					$result2->execute();
					
					$codigo= 'INV_PRODUCCION_ENSAMBLE-ID%';
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('INV_PRODUCCION_ENSAMBLE_SELECT @CODIGO=:CODIGO');	
					$result1->bindParam(':CODIGO',$codigo,PDO::PARAM_STR);		
					$result1->execute();				 
					$row1 = $result1->fetch(PDO::FETCH_ASSOC);
					$EnsambleId = $row1['IDEnsamble'];
?>
					<div id="cuerpo2" align= "center">
						<p style="font-weight: bold" align="center" > <font size= "6" > Recepcion Completa! Ensamble # <?php  echo $EnsambleId ?> </font><br> </p>
					</div>
<?php	
					/*******Genera y graba la cabecera del ensamble con el Id correspondiente ******/
					//echo $EnsambleId, $FacturaId, $usuario , $fh; 
					$Tipo = 'VEN-FA';
					$Estado = 'RECIBIDO'; 
					$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result3 = $pdo3->prepare('insert into INV_PRODUCCION_ENSAMBLE (EnsambleId, FacturaId, RecibidoPor, FechaRecibido, Tipo, Estado, Cant) 
											   values (:EnsambleId,:FacturaId,:RecibidoPor,:FechaRecibido, :Tipo, :Estado, :Cant)');	
					$result3->bindParam(':EnsambleId',$EnsambleId,PDO::PARAM_STR);
					$result3->bindParam(':FacturaId',$FacturaId,PDO::PARAM_STR);							
					$result3->bindParam(':RecibidoPor',$usuario,PDO::PARAM_STR);
					$result3->bindParam(':FechaRecibido',$fh,PDO::PARAM_STR);
					$result3->bindParam(':Tipo',$Tipo,PDO::PARAM_STR);
					$result3->bindParam(':Estado',$Estado,PDO::PARAM_STR);
					$result3->bindParam(':Cant',$cant,PDO::PARAM_STR);
					$result3->execute();					

					/*******Genera y graba todos las lineas del detalle del ensamble con el Id correspondiente de acuerdo a la cantidad de maquinas a ensamblar ******/
					//echo "Maquinas a ensamblar ".$cant;
					$y=0;
					while ( $y < $cant ) 
						{
							$pdo4 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result4 = $pdo4->prepare('INV_PRODUCCION_ENSAMBLE_DT_SELECTID');	
							$result4->execute();
							
							$codigodt= 'INV_PRODUCCION_ENSAMBLE_DT-ID%';
							$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result5 = $pdo5->prepare('INV_PRODUCCION_ENSAMBLE_DT_SELECT @CODIGO=:CODIGO');	
							$result5->bindParam(':CODIGO',$codigodt,PDO::PARAM_STR);		
							$result5->execute();				 
							$row5 = $result5->fetch(PDO::FETCH_ASSOC);
							$EnsambleDtId = $row5['ID']; 
							
						//	echo "DTID ".$EnsambleDtId;
							//die(); 
							$pdo6 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result6 = $pdo6->prepare('insert into INV_PRODUCCION_ENSAMBLE_DT (EnsambleId,Id) 
													   values (:EnsambleId, :Id)');	
							$result6->bindParam(':EnsambleId',$EnsambleId,PDO::PARAM_STR);
							$result6->bindParam(':Id',$EnsambleDtId,PDO::PARAM_STR);							
							$result6->execute();					
						$y=$y+1;	
						}	
					header("Refresh: 1 ; ensamblesporrecibir.php");	
				}				
			else
				{
?>
					<div id="cuerpo2" align= "center">
								
										<p style="font-weight: bold" align="center" > <font size= "6" > Faltan productos </font><br> </p>
					</div>
<?php					
					header("Refresh: 1 ; recibirensamble.php");					
					 
				}	
				
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			$_SESSION['secu']=$secu;
			$_SESSION['bodega']= $bodega;
				 
			//header("location: despacharfacturas.php");		
		}

?>

		
</div>
</body>

</html>