<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/tablas.css">
<body > 
<div id= "header" align= "center">
<?php 
		    session_start();	
		    if (isset($_SESSION['loggedin']))
				{
					//echo "Entra aqui"; 
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					if ($base=='CARTIMEX'){
							require 'headcarti.php';  
					}
					else{
							require 'headcompu.php';
					}
					date_default_timezone_set('America/Guayaquil');
					$fecha = date("Y-m-d", time());
					$hora = date("H:i:s", time());
					$fh = $fecha . " " . $hora;
					//echo "BOdega". $bodega; 
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>   Guias Facturas  <?php echo substr($nomsuc,10,20); ?> </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a>  </div>
			 
	</div> 
<hr>	
<div id= "cuerpo2" align= "center" >
		<div>
			<table align ="center" >
				<tr>
					<td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>
				</tr> 
			</table>
		</div>		
		<div class=\"table-responsive-xs\">
			
			<table align ="center" >
				<tr>
					<th colspan="11">Ingresar Guias Facturas </th> 
				</tr> 
				<tr> 
					<th id= "fila4"> SId </th>
					<th id= "fila4"> Factura </th>
					<th id= "fila4"> Cliente </th>
					<th id= "fila4"> Fecha </th>
					<th id= "fila4">  </th>
					<th id= "fila4"> Forma de Pago  </th>
					<th id= "fila4"> Confir.Trans  </th>
					<th id= "fila4"> Estado  </th>
					<th id= "fila4"> V.Factura  </th>
					<th id= "fila4"> Por Cancelar </th>
					<th id= "fila4"> Entrega</th>
					<th id= "fila4"> Bodega Retiro</th>
				</tr>
		<?php

							$_SESSION['usuario']=$usuario;
							$_SESSION['bodega']=$bodega; 
							//echo "bodega".$bodega.$base.$usuario.$acceso;
							include('conexion_mssql.php');
						/*	
							//******Proceso aqui primero todas las facturas pendientes de GUIA para ver cual ha sido Anulada o 
							//Devuelta en su TOTALIDAD Y marcarla como ANULADA tanto en el SISCO como en SGL(facturaslistas) 
							
							$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];
							$result0 = $pdo0->prepare("LOG_FACTURAS_PENDIENTES_GUIAS_SELECT @BODEGA=:bodega , @acceso=:acceso" );		 
							$result0->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							$result0->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							$result0->execute();
							$arreglod = array();
							$xd=0; 
							while ($row0 = $result0->fetch(PDO::FETCH_ASSOC)) 
								{
									$arreglod[$xd][2]=$row0['secuencia'];
									//echo "Secuencia". $arreglod[$xd][2]; 
									$xd++; 
								}
							$countd = count($arreglod); 
							$yd=0;
							while ( $yd <= $countd-1 ) 
								{
									$devo= $arreglod[$yd][2];
									$pdod = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
									$resultd = $pdod->prepare("LOG_FACTURAS_DEVUELTA_UPDATE @Secuencia=:secuencia" );		 
									$resultd->bindParam(':secuencia',$devo,PDO::PARAM_STR);
									$resultd->execute();
									
									$countarr = $resultd->rowcount();
									//echo "Trae registro".$countarr; 
									if ($countarr ==1)
									 
										{
											$rowdd = $resultd->fetch(PDO::FETCH_ASSOC);
											//echo "datos a actualizar en sisco". $usuario.$fh.$devo ;
											include("conexion.php");
											$sqlde = "update covidsales set Anulada= '1' , anuladapor= '$usuario', fechaanulada= '$fh' where factura = '$devo' ";
											//echo $sqlde; 
											$resultde = mysqli_query($con, $sqlde);
										}	
									$yd=$yd+1;
								}	
								//***************************xxxxxxxxxxxxxxxxxxxxxxx********************************
							*/
							
							
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							//new PDO($dsn, $sql_user, $sql_pwd);
							//Select Query
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];
							
							$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_GUIAS_SELECT @BODEGA=:bodega , @acceso=:acceso" );		 
							$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							//Executes the query
							$result->execute();
							$arreglo = array();
							$x=0; 
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
							{
								$arreglo[$x][1]=$row['Sucursal'];
								$arreglo[$x][2]=$row['secuencia'];
								$arreglo[$x][3]=$row['fecha'];
								$arreglo[$x][4]=$row['nombodega'];
								$arreglo[$x][5]=number_format($row['saldo'],2);
								$arreglo[$x][6]=$row['Detalle'];
								$arreglo[$x][7]=$row['bodegsuc'];
								$arreglo[$x][8]=number_format($row['TOTAL'],2);
								$arreglo[$x][9]=number_format($row['rete'],2);
								$x++; 
							}
							
							$count = count($arreglo); 
							$y=0;
							while ( $y <= $count-1 ) 
							{
								$numfac= $arreglo[$y][2];
								include("conexion.php");
								$sql1 = "SELECT a.*, p.bodega as bodegaret, t.numeroconfir as confir,t.doc1 as doc , t.transaccion as transa ,
										c.sucursalid as sucursal  FROM covidsales a
										left outer join covidpickup p on p.orden= a.secuencia
										left outer join covidciudades c on p.bodega= c.almacen
										left outer join covidtransferencias t on t.transaccion= a.secuencia
										where a.factura = trim('$numfac') and a.anulada<> '1'  ";
								$result1 = mysqli_query($con, $sql1);
								$conrow = $result1->num_rows; 
								//echo "Contad". $conrow; 
								
								if ($conrow >0)
									{
										 
										$row1 = mysqli_fetch_array($result1);
										$estado = $row1['estado'];
										$formapago = $row1['formapago'];
										$bodegaretiro = $row1['bodegaret'];
										$sucuret = $row1['sucursal'];
										$confir = $row1['confir'];
										$doc = $row1['doc'];
										
										$transa = $row1['transa'];
										//ESTABLECE SI ES PICKUP O ENVIO DE ACUERDO HABILITA EL CAMPO PARA INGRESAR # GUIA EN DETALLE
										if ($row1['pickup']=='1')
											{ $medio = "Pick-up";}
										else
											{ $medio = "Envio";}
									}
								else
									{
										$estado="NO SISCO";
										
									}	
		?>						
								<tr>
									<td id= "fila4"  > <a  href ="trakingguiavta.php?secu=<?php echo $arreglo[$y][2]?>"> <?php echo $arreglo[$y][1] ?></a> </td>
									<td id= "fila4" align= "center"> <a> <?php echo $arreglo[$y][2] ?></a></td> 
									<td id= "fila4" align= "center"> <a> <?php echo $arreglo[$y][6] ?></a></td> 
									<td id= "fila4"  > <?php echo $arreglo[$y][3] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][4]   ?>  </td>
									<td id= "fila4"  > <?php echo $formapago ?>  </td>
									<td id= "fila4"  > <a href = "verconfirmaciontrans.php?trans=<?php echo $transa ?>"><?php echo $transa ?>  </td>
									<td id= "fila4"  > <?php echo $estado ?>  </td>
									<td id= "label5"  > <?php echo $arreglo[$y][8] ?>  </td>
									<td id= "filax"  > <?php echo $arreglo[$y][5] ?>  </td>
									<td id= "fila4"  > <?php echo $medio ?>  </td>
									<td id= "fila4"  > <?php echo $bodegaretiro   ?>  </td>
								</tr>	
		<?php
							$formapago= ''; 
							$medio= '';
							$bodegaretiro= ''; 
							$y=$y+1;			
							}	
		?>	
		</table>
		</div>				
 </div> 
 
<?php	
			
			$_SESSION['usuario']=$usuario;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['bodega']=$bodega;
			$_SESSION['nomsuc']=$nomsuc;
			
			
			}
			else
			{
				header("location: index.html");
			}	
	
?>	
</div>		
</body>	