<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("codigo").focus();
}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload="setfocus()">
<div id= "header" align= "center">
<?PHP
session_start();
	if (isset($_SESSION['loggedin']))
		{
			$transfer = $_SESSION['transfer'];
			$bloqueado = $_SESSION['bloqueo'];
			$nota = $_SESSION['nota'];
			$usuario= $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso= $_SESSION['acceso'];
			$codigo= $_SESSION['codigo'];
			$nomsuc=$_SESSION['nomsuc'];
			//echo "Esto me llega".$usuario.$transfer.$base.$acceso.$codigo.$nomsuc ;
			//(); 
			if (isset($_POST["transferencia"])) 
				{ 
					$y = $_SESSION['contadort']	; // Cargo el contador en la memoria (ya estaba cargado)
					$numerorecibido= $_POST['transferencia'];
				}	
			else
				{	
					unset($arregloseriest );
					$arregloseriest = array();
					$y = 0; //encero el contador por  primera  vez 
					$numerorecibido= $transfer;
					$_SESSION['arregloseriest']= $arregloseriest; 
				}
			if ($base=='CARTIMEX')
				{require 'headcarti.php';  	}
			else{
					require 'headcompu.php';
					$_SESSION['base']= $base; 
					$Nota = " "; 	
				}
			$usuario1= $usuario; 			
			require('conexion_mssql.php');	
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Saca los datos de la cabecera de la TRansferencia
			//Select Query
			$result = $pdo->prepare('LOG_VERIFICAR_TRANSFERENCIA2 @TransferenciaID=:numerorecibido');
			$result->bindParam(':numerorecibido',$numerorecibido,PDO::PARAM_STR);
			
			//Executes the query
			$result->execute();
			//$count = $result->rowcount();
			
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
				{
					$idorigen = $row['idorigen'];
					$iddestino= $row['iddestino'];
					$detalle = $row['detalle'];
					$origen= $row['Origen'];
					$destino= $row['destino'];
					$fecha = $row['fecha'];
					$TransferId = $row['TransferId'];
					$Transnum = $row['Transnum'];
				}
			
?>
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>  Verificar Transferencias  <?php echo substr($nomsuc,10,20); ?></center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
				 
	</div>
<hr>	
	<div id="cuerpo2" align= "center">
		<div align= "left">
			<table>
				<tr><td id="label2">
				<strong> Id: </strong> <a> <?php echo $TransferId  ?> </a> 
				<strong> Numero: </strong> <a> <?php echo $Transnum ?> </a>
				<br></td></tr>
				<tr><td id="label2">
				<strong> Origen: </strong> <a> <?php echo $origen ?> </a>
				<strong> Destino: </strong> <a> <?php echo $destino ?> </a>
				<br></td></tr>
				<tr><td id="label2">
				<strong> Detalle: </strong> <a> <?php echo $detalle ?> </a>
				<br></td></tr>
				<tr><td id="label2">
				<strong> Fecha y Hora de Creacion: </strong> <a> <?php echo $fecha ?> </a>  
				<br></td></tr>			
			</table>	
		</div>
	</div>
<hr>
	<div id="cuerpo2">
		<form name = "form2" action="verificartransferencias2.php" method="POST" >
							<p style="font-weight: bold" align="center">Código o serieEEE :
							<input  name="codigo" type="text" id="codigo" size = "30" value= "<?php $codigoleido ?>">
							<input  name="transferencia" type="hidden" id="transferencia" value="<?php echo $numerorecibido ?>"  >
							<input type="submit" name="submit" id="submit" value="Ingresar">
							</p>
		</form>
</div>		
<?php			
			$codigoleidoinicial="";
			$arreglo = $_SESSION['datosarreglos']; // Cargo el arreglo de la memoria
			$xx = count($arreglo); //Cuento las filas del arreglo
			// echo '<pre>', print_r($arreglo),'</pre>';
			if (isset($_POST["codigo"])) 
				{ 
					 //echo "Viene de leer un codigo <br>";
					$codigoleidoinicial = strtoupper($_POST["codigo"]); //echo "Codigo leido: ".$codigoleidoinicial."<br>";
					$arreglo = $_SESSION['datosarreglos']; // Cargo el arreglo de la memoria
					$x = count($arreglo); //Cuento las filas del arreglo
					$z = 0 ; // variable para recorrer el arreglo
					$muestraleyenda1=0; //Inicializo variables para mostrar mensajes 
					$muestraleyenda2="";
					$bodega= 0; //bandera para saber q la serie existe pero en otra bodega no la que se necesita
					$grabar = 0; //para saber si debo grabar o no en la tabla de RMA_productos	
					// aqui recorro el codigo para saber si tiene + cantidad. De ser asi, hago un nuevo codigo leido
					$cantidad= 1; // por defecto siempre la cantidad es 1
					$serial = "";
					$primer = 0; 
					$long = strlen($codigoleidoinicial);
					for ( $w = 0 ; $w < $long ; $w++)
						{
							$parte = substr($codigoleidoinicial,$w,1);
							if ($parte =="+") 
								{
								   $codigoleido = substr($codigoleidoinicial,0,$w); //echo "cod nuevo:".$codigoleido."<br>";
								   $cantidad = substr($codigoleidoinicial,$w+1,$long);
								   $w=100;
								   $entrocantidad= 1; 
								} 
							else 
								{
								   $codigoleido = $codigoleidoinicial;
								   $cantidad = 1;
								   $entrocantidad= 0;
								}
						}
							//aqui recorro el codigo para saber si tiene anexada la serie. De ser asi, hago un nuevo codigo leido y asigno la serie a una variable 	
					if ($entrocantidad==0)
						{
							for ( $w = 0 ; $w < $long ; $w++)
								{ 
									$parte = substr($codigoleido,$w,1);
									if ($parte =="*") 
										{
										   $codigoleido = substr($codigoleidoinicial,0,$w); //echo "cod nuevo:".$codigoleido."<br>";
										   $serial = substr($codigoleidoinicial,$w+1,$long);
										   $w=100;
										} 
									else 
										{
										   $codigoleido = $codigoleidoinicial;
										   $serial = '';
										}
								}	
						}
					// Por cada serie leida consulta la tabla RMA_Productos para buscarla
		
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('LOG_VERIFICAR_TRANSFERENCIA3 @Codigoleido=:codigoleido, @Transferenciaid=:numerorecibido, @BodegaOrigen=:idorigen');
					$result1->bindParam(':codigoleido',$codigoleido,PDO::PARAM_STR);
					$result1->bindParam(':numerorecibido',$numerorecibido,PDO::PARAM_STR);
					$result1->bindParam(':idorigen',$idorigen,PDO::PARAM_STR);
					
					//Executes the query
					$result1->execute();
					$count = $result1->rowcount();				
					/*Si el contador trae 0 hace proceso con el código sin serie caso contrario busca la serie y el producto al que pertenece*/
					if ($count == 0 ) 
						{
							$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);	
							//Select Query
							$result2 = $pdo2->prepare('LOG_VERIFICAR_TRANSFERENCIA4 @Codigoleido=:codigoleido');
							$result2->bindParam(':codigoleido',$codigoleido,PDO::PARAM_STR);
							//Executes the query
							$result2->execute();					
							$count2 = $result2->rowcount();
							
							if ($count2 == 0 ) 
								{
									$muestraleyenda2= " Item no pertenece a este documento o la serie está en otra BODEGA";
									$bodega=1;
								}
							else 
								{
									while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) 
										{
											$registraserie = $row2['registroSeries'];
											$entraserie = $row2['RSeriesEnt'];
											$idproducto = $row2['Productoid'];
																					
											if ($registraserie == 1 and $entraserie==1) 
												{
												 // Echo "aQUI".$registraserie.$entraserie.$serial;
												  $muestraleyenda2="Debe registrar SERIE del Producto";
												  $bodega=1; 
												}
											else
												{
												//  Echo "Aca".$registraserie.$entraserie.$serial; 
												  if (($registraserie == 1 and $entraserie==0) and $serial == "")
													{
														//echo "Entro aqui ";
														$muestraleyenda2= "Debe registrar SERIE del Producto";
														$bodega= 1; 
													}
												  else
													{
														$rmacodigo= $codigoleido;
														$grabar= 1; 
													}
												}
										}
								} 
						}
					else
						{
							while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
								{
									
									//Pregunto si la serie ya está registrada en otra factura
									if ( trim($row1['estado'])=='VENDIDO') 
										{
											$muestraleyenda2= "Serie ya está registrada en documento # ".$row['numfactura'];
											$bodega= 1;
										}
									else
										{
										   //echo "aqui deberia entrar".$y;
										   //Lleno el arreglo donde se guardan las series que estoy registrando 
											if ($y == 0)  
												{    
													 
													//Primera serie la graba sin preguntar porque es el primer elemento del arreglo 
													$arregloseriest[$y][1] = $row1['productoid'];
													$arregloseriest[$y][2] = $codigoleido;
													$arregloseriest[$y][3]=  $numerorecibido;
													$arregloseriest[$y][4]=  $grabar;
													$rmacodigo = $row1['rmacodigo'];	
													$y = $_SESSION['contadort']= $_SESSION['contadort']+1;									
												}
											  else
												{
													$muestraleyenda2=""; 
													$arregloseriest = $_SESSION['arregloseriest'];
													$zz = 0; 
													$existe = 0 ; 	
													while ($zz < count($arregloseriest))
														{
															//Mientras haya elementos en el arreglo
															if ($arregloseriest[$zz][2]== $codigoleido or $arregloseriest[$zz][2]==$serial) 
																{//Pregunta Si la serie ingresada está duplicada
																	$existe= 1 ;
																}
															$zz++;
														}	
													if ($existe== 1) // Si existe muestra leyenda
														{
															//echo "aqui deberia entrar".$serial;
															$muestraleyenda2= "Ya leyó esa serie";
															$bodega= 1;	
														}
													else
														{ 
															$rmacodigo = $row1['rmacodigo'];
															while ( $z <= $x-1 )
																{
																	if ( 
																		 (rtrim($rmacodigo) == rtrim($arreglo[$z][6]) ) or (rtrim($rmacodigo) == rtrim($arreglo[$z][4]) ) or 
																		 (rtrim($rmacodigo) == rtrim($arreglo[$z][7]) ) or (rtrim($rmacodigo) == rtrim($arreglo[$z][8]) ) 
																		 and $rmacodigo<>"" )
																		{  
																			// Entro en este if si el codigo leido esta en el arreglo.
																			//echo "Aqui verifico si".$arreglo[$z][9]."es mayor que".$arreglo[$z][3]."<br>";
																			$muestraleyenda1=1; // para indicar que si pertenece a esta TRANSFERENCIA
																			if ( ($arreglo[$z][12]+$cantidad)> $arreglo[$z][3] )
																				// en este if verifico si no me he excedido en cantidades
																				{ 
																					$muestraleyenda2 = " Cantidad en exceso ";
																				} 
																			else 
																				{
																					$muestraleyenda2 = " Item procesado correctamente ";
																					$bodega= 1;
																					$arreglo[$z][12] = $arreglo[$z][12] +$cantidad;
																					$_SESSION['datosarreglos'] = $arreglo; // Grabo otra vez el arreglo en memoria
																					$arregloseriest = $_SESSION['arregloseriest'];
																					$arregloseriest[$y][1] = $row1['productoid'];
																					$arregloseriest[$y][2] = $codigoleido;
																					$arregloseriest[$y][3]=  $numerorecibido;
																					$arregloseriest[$y][4]=  $grabar;
																					$rmacodigo = $row['rmacodigo'];
																					$y = $_SESSION['contadort']= $_SESSION['contadort']+1;
																					$_SESSION['arregloseriest'] = $arregloseriest;	
																					//echo '<pre>', print_r($arregloseriest),'</pre>';
																				}
																		}
																	$z++;
																}	
														} 
												}
											$_SESSION['arregloseriest'] = $arregloseriest; 
										} 
								}
						}
					if ($bodega==0  )
						{		
							//echo '<pre>', print_r($arregloseriest),'</pre>';
							$muestraleyenda2=""; 
							$arregloseriest = $_SESSION['arregloseriest'];
							$zz = 0; 
							$existe = 0 ; 	
							while ($zz < count($arregloseriest))
								{
									//Mientras haya elementos en el arreglo
									if ( $arregloseriest[$zz][2]==$serial) 
										{//Pregunta Si la serie ingresada está duplicada
											//echo "encontró que existe".$serial;
											$existe= 1 ;
										}
								$zz++;
								}	
							if ($existe== 1) // Si existe muestra leyenda
								{
									//echo "aqui deberia entrar".$serial;
									$muestraleyenda2= "  Ya leyó esa serie";
									$bodega= 1;	
								}
							else
								{ 
									while ( $z <= $x-1 )
									{
										//echo "Entro al while: -".$rmacodigo."--Arreglo:-".$arreglo[$z][4]."--Arreglo:-".$arreglo[$z][6]."--Arreglo:-".$arreglo[$z][7]."--Arreglo:-".$arreglo[$z][8]."<br>";   
										if ( 
											 (rtrim($rmacodigo) == rtrim($arreglo[$z][6]) ) or (rtrim($rmacodigo) == rtrim($arreglo[$z][4]) ) or 
											 (rtrim($rmacodigo) == rtrim($arreglo[$z][7]) ) or (rtrim($rmacodigo) == rtrim($arreglo[$z][8]) )and $rmacodigo<>"" )
											{  
												//echo "Debe entrar aqui". $rmacodigo; 
												// Entro en este if si el codigo leido esta en el arreglo.
												//echo "Aqui verifico si".$arreglo[$z][9]."es mayor que".$arreglo[$z][3]."<br>";
												$muestraleyenda1=1; // para indicar que si pertenece a esta factura
												if ( ($arreglo[$z][12]+$cantidad)> $arreglo[$z][3] )// en este if verifico si no me he excedido en cantidades
													{ 
														$muestraleyenda2 = " Cantidad en exceso ";
													} 
												else 
													{
														if($serial==""  ) 
															{
																$muestraleyenda2 = "Item procesado correctamente ..... ";
																$arreglo[$z][12] = $arreglo[$z][12] +$cantidad;
																$_SESSION['datosarreglos'] = $arreglo;  // Grabo otra vez el arreglo en memoria  
																//echo '<pre>', print_r($arregloseriest),'</pre>';
																//echo "Contadorx".$_SESSION['contadort']; 
															} 
														else
															{
																$muestraleyenda2 ="Item procesado correctamente ";
																$arreglo[$z][12] = $arreglo[$z][12] +$cantidad;
																$_SESSION['datosarreglos'] = $arreglo; // Grabo otra vez el arreglo en memoria
																$arregloseriest = $_SESSION['arregloseriest'];
																$arregloseriest[$y][1] = $idproducto;
																$arregloseriest[$y][2] = $serial;
																$arregloseriest[$y][3]=  $numerorecibido;
																$arregloseriest[$y][4]=  $grabar;
																$rmacodigo = $row['rmacodigo'];
																//echo '<pre>', print_r($arregloseriest),'</pre>';
																$y = $_SESSION['contadort']= $_SESSION['contadort']+1;
																$_SESSION['arregloseriest'] = $arregloseriest;	
										
															}			
														
													}
											}
										$z++;
										   
									} 
							}		
						} 
							if ($muestraleyenda2=="" and $muestraleyenda1==0) 
								{
								//$muestraleyenda2="";
								//$muestraleyenda2="Item no pertenece a esta transferencia"; 	 
								echo $muestraleyenda2 ;  
								}
								
							if ($muestraleyenda2<>"")
								{	
?>						
					<div id = "leyenda" >  <?php echo $muestraleyenda2 ?></div>
<?php
								}	
				}
			// meto un lazo para contar el numero de items pendientes por ingresar
			$totalitems = 0;
			$totalleidos = 0;
			$zz = 0 ; // variable para recorrer el arreglo
?> 	
<div id = "Cuerpo2">
	<div>
		<table id= "listado2" align ="center" > 
				<tr> 
					<th> CODIGO </th>
					<th> ARTICULO </th>
					<th> CANTIDAD </th>
				</tr>
<?php	
			while ( $zz <= $xx-1 )
				{
					$totalitems = $arreglo[$zz][3] + $totalitems;
					$totalleidos = $arreglo[$zz][12] + $totalleidos;
					// aqui aprovecho y voy a mostrar los items que ya han sido leidos
					if ( $arreglo[$zz][12]<>0 )
						{
?>							<tr> 
								<td id= "fila2" align=left> <?php echo $arreglo[$zz][4] ?></td> 
								<td id= "fila2" align=left> <?php echo $arreglo[$zz][5] ?></td> 
								<td id= "fila" align=left> <?php echo $arreglo[$zz][12] ?></td> 
							</tr>
<?php							
						}
					$zz++;
				}	
			echo "</table>";
?>
	</div>
	</div>							
<?php			
			
			$itemsporleer =  $totalitems -  $totalleidos;
			 
			if ( $bloqueado == 1)
				{
					echo "Bloqueada!!";
				}	
				else
				{	
						
					if ($nota <> "") 
					{
						echo "Desbloqueadas!!" ;
					}
					
				}	 
?>
<div id="Cuerpo2">
<div id = "label" > <h3><strong> Items por verificar:  <?php echo $itemsporleer ?> </strong></h3></div>
</div>
		<div id="Cuerpo2">
				<p style="font-weight: bold" align="center" > 	
					<?php		   
						if ($itemsporleer == 0)
							{
								$_SESSION['datosarreglos'] = $arreglo; // Grabo otra vez el arreglo en memoria
								$_SESSION['IDDESTINO']= $iddestino;	//Asigno variable de bodega destino
								$_SESSION['IDORIGEN'] = $idorigen; //Asigno variable de bodega Origen 
								$_SESSION['DETALLE'] = $detalle; 
								//$_SESSION['arregloseriest']= $arregloseriest;

					?>
								<form action="verificartransferencias3.php" method="post" >
									  <p style="font-weight: bold" align="center">
										<input  name="transferencia" type="hidden" id="transferencia" value="<?php echo $numerorecibido ?>"  >
										<input type="submit" name="submit" id="submit" value=" TRANSFERENCIA COMPLETA presione aquí para CONTINUAR">
									  </p>
								</form>	 					
					<?php	     
							} 

								$_SESSION['usuario']=$usuario1;
								$_SESSION['base']= $base ;
								$_SESSION['acceso']=$acceso;
								$_SESSION['nomsuc']=$nomsuc; 	
								$_SESSION['transfer']=$numerorecibido; 
								$_SESSION['IDDESTINO']= $iddestino;	//Asigno variable de bodega destino
								$_SESSION['IDORIGEN'] = $idorigen; //Asigno variable de bodega Origen 
								$_SESSION['DETALLE'] = $detalle;
								$arreglo = $_SESSION['datosarreglos']; //Assigns session var to $array
								$arregloseriest = $_SESSION['arregloseriest']; 
								$_SESSION['arregloseriest']= $arregloseriest;
								//echo '<pre>', print_r($arregloseriest),'</pre>';	
								//echo '<pre>', print_r($arreglo),'</pre>';	//este es el arreglo original
								//echo $usuario1. $base.$acceso.$nomsuc.$numerorecibido;
								 
						
					?>
							  
				</p>
		</div>		
<?php				
		}
	else
		{
			header("location: index.html");
		}		
?>		
</div>
</div>
</body>
</html>
