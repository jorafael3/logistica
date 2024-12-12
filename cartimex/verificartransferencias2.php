<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
	function setfocus() {
		document.getElementById("codigo").focus();
	}
	document.getElementById('codigo').addEventListener('click', function() {
		this.select();
	});

	document.getElementById('codigo').addEventListener('keydown', function(event) {
		if (event.key === 'Enter') {
			this.select();
		}
	});


	// funcion de boton bultos 

	// Obtener referencia al botón y al input
</script>


<style>
	#bulto {
		border: 1px solid #ccc;
		border-radius: 4px;
		padding: 8px;
		font-size: 16px;
		width: 10%;
		box-sizing: border-box;
	}
</style>

<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<link href="../estilos/estiloverificartrasferencia2.css" rel="stylesheet" type="text/css">

<body onload="setfocus()">
	<div id="header" align="center">
		<?PHP
		session_start();
		if (isset($_SESSION['loggedin'])) {
			$transfer = $_SESSION['transfer'];
			$bloqueado = $_SESSION['bloqueo'];
			$nota = $_SESSION['nota'];
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$codigo = $_SESSION['codigo'];
			$nomsuc = $_SESSION['nomsuc'];
			$transfer = $_SESSION['transfer'];
			//echo "Esto me llega".$usuario.$transfer.$base.$acceso.$codigo.$nomsuc ;
			//(); 
			if (isset($_POST["transferencia"])) {
				$y = $_SESSION['contadort']; // Cargo el contador en la memoria (ya estaba cargado)
				$numerorecibido = $_POST['transferencia'];
			} else {
				$y = 0; //encero el contador por  primera  vez 
				$readonly = "";
				unset($arregloseriest);
				$arregloseriest = array();
				$numerorecibido = $_SESSION['transfer'];
				$_SESSION['arregloseriest'] = $arregloseriest;
				$arregloseriest = $_SESSION['arregloseriest'];
			}

			if ($base == 'CARTIMEX') {
				require '../headcarti.php';
			} else {
				require '../headcompu.php';
				$_SESSION['base'] = $base;
				$Nota = " ";
			}



			$usuario1 = $usuario;
			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Saca los datos de la cabecera de la TRansferencia
			//Select Query
			$result = $pdo->prepare('LOG_VERIFICAR_TRANSFERENCIA2 @TransferenciaID=:numerorecibido');
			$result->bindParam(':numerorecibido', $numerorecibido, PDO::PARAM_STR);

			//Executes the query
			$result->execute();
			//$count = $result->rowcount();

			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

				$idorigen = $row['idorigen'];
				$iddestino = $row['iddestino'];
				$detalle = $row['detalle'];
				$origen = $row['Origen'];
				$destino = $row['destino'];
				$fecha = $row['fecha'];
				$TransferId = $row['agruparid'];
				$Transnum = $row['Transnum'];
				$_SESSION['Transnum'] = $Transnum;
			}

			//cargo el arreglo de series temporales tiene la misma estructura que arregloseries 

			$arregloseries2 = array();
			$S = 0;
			$pdoser = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$resultser = $pdoser->prepare('select * from SERIESSGLCARTIMEX where facturaid =:facturaid');
			$resultser->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
			$resultser->execute();
			while ($rowser = $resultser->fetch(PDO::FETCH_ASSOC)) {

				$arregloseries2[$S][1] = $rowser['PRODUCTO'];
				$arregloseries2[$S][2] = $rowser['SERIE'];
				$arregloseries2[$S][3] = $rowser['FACTURAID'];
				$arregloseries2[$S][4] = $rowser['grabar'];

				$S++;
			}
			//echo '<pre>', print_r($arregloseries2),'</pre>';		

			$codigoleidoinicial = "";
			$arreglo = $_SESSION['datosarreglos']; // Cargo el arreglo de la memoria
			// var_dump($arreglo);
			$xx = count($arreglo); //Cuento las filas del arreglo
			//echo '<pre>', print_r($arreglo),'</pre>';
			if (isset($_POST["codigo"])) {
				//echo "Viene de leer un codigo <br>";
				$codigoleidoinicial = strtoupper($_POST["codigo"]); //echo "Codigo leido: ".$codigoleidoinicial."<br>";
				$arreglo = $_SESSION['datosarreglos']; // Cargo el arreglo de la memoria
				$x = count($arreglo); //Cuento las filas del arreglo
				$z = 0; // variable para recorrer el arreglo
				$muestraleyenda1 = 0; //Inicializo variables para mostrar mensajes 
				$muestraleyenda2 = "";
				$bodega = 0; //bandera para saber q la serie existe pero en otra bodega no la que se necesita
				$grabar = 0;  //para saber si debo grabar o no en la tabla de RMA_productos	
				// aqui recorro el codigo para saber si tiene + cantidad. De ser asi, hago un nuevo codigo leido
				$cantidad = 1; // por defecto siempre la cantidad es 1
				$serial = "";
				$primer = 0;
				$long = strlen($codigoleidoinicial);
				for ($w = 0; $w < $long; $w++) {
					$parte = substr($codigoleidoinicial, $w, 1);
					if ($parte == "+") {
						$codigoleido = substr($codigoleidoinicial, 0, $w); //echo "cod nuevo:".$codigoleido."<br>";
						$cantidad = substr($codigoleidoinicial, $w + 1, $long);
						$w = 100;
						$entrocantidad = 1;
					} else {
						$codigoleido = $codigoleidoinicial;
						$cantidad = 1;
						$entrocantidad = 0;
					}
				}

				//aqui recorro el codigo para saber si tiene anexada la serie. De ser asi, hago un nuevo codigo leido y asigno la serie a una variable 	
				if ($entrocantidad == 0) {
					for ($w = 0; $w < $long; $w++) {
						$parte = substr($codigoleido, $w, 1);
						if ($parte == "*") {
							$codigoleido = substr($codigoleidoinicial, 0, $w); //echo "cod nuevo:".$codigoleido."<br>";
							$serial = substr($codigoleidoinicial, $w + 1, $long);
							$w = 100;
						} else {
							$codigoleido = $codigoleidoinicial;
							$serial = '';
						}
					}
				}
				// Por cada serie o codigo ingresado consulta la tabla RMA_Productos para buscarla

				$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result1 = $pdo1->prepare('LOG_VERIFICAR_TRANSFERENCIA3 @Codigoleido=:codigoleido, @Transferenciaid=:numerorecibido, @BodegaOrigen=:idorigen');
				$result1->bindParam(':codigoleido', $codigoleido, PDO::PARAM_STR);
				$result1->bindParam(':numerorecibido', $numerorecibido, PDO::PARAM_STR);
				$result1->bindParam(':idorigen', $idorigen, PDO::PARAM_STR);

				//Executes the query
				$result1->execute();
				$count = $result1->rowcount();
				/*Si el contador trae 0 hace proceso con el código sin serie caso contrario busca la serie y el producto al que pertenece*/
				if ($count == 0) {
					$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//Select Query
					$result2 = $pdo2->prepare('LOG_VERIFICAR_TRANSFERENCIA4 @Codigoleido=:codigoleido, @Transferenciaid=:numerorecibido');
					$result2->bindParam(':codigoleido', $codigoleido, PDO::PARAM_STR);
					$result2->bindParam(':numerorecibido', $numerorecibido, PDO::PARAM_STR);
					//Executes the query
					$result2->execute();
					$count2 = $result2->rowcount();

					if ($count2 == 0) {
						$muestraleyenda2 = " Item no pertenece a este documento o la serie está en otra BODEGA****";
						$bodega = 1;
					} else {
						while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
							$registraserie = $row2['registroSeries'];
							$entraserie = $row2['RSeriesEnt'];
							$idproducto = $row2['ID'];
							$cantseries = $row2['cant'];

							//echo "Entra aqui 1".$registraserie. $entraserie ; 
							if ($registraserie == 1 and $entraserie == 1) {

								//echo "Voy x aqui ".$idproducto.$numerorecibido; 
								//busco si ya esta ingresada la totalidad de series 
								$pdosere = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
								$resultsere = $pdosere->prepare('select * from SERIESSGLCARTIMEX where PRODUCTO=:productoid and FACTURAID=:transferenciaid');
								$resultsere->bindParam(':productoid', $idproducto, PDO::PARAM_STR);
								$resultsere->bindParam(':transferenciaid', $numerorecibido, PDO::PARAM_STR);
								$resultsere->execute();
								$countsere = $resultsere->rowcount();
								//echo "Contador series".$countsere; 
								if ($countsere == 0) {
									header("location: ingseriestr.php");
									// Echo "aQUI".$registraserie.$entraserie.$serial;
									$muestraleyenda2 = "Debe registrar SERIE del Producto";
									$bodega = 1;
								} else {
									//echo "Entra aqui" ; 
									$muestraleyenda2 = "Ya estan registradas las series de este producto";
									$bodega = 1;
								}
							} else {
								// Echo "Aca".$registraserie.$entraserie.$serial; 
								if (($registraserie == 1 and $entraserie == 0) and $serial == "") {
									//	Echo "Aca***".$idproducto.$numerorecibido ;	
									$pdosere = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
									$resultsere = $pdosere->prepare('select FACTURAID from SERIESSGLCARTIMEX where PRODUCTO=:productoid and FACTURAID=:transferenciaid');
									$resultsere->bindParam(':productoid', $idproducto, PDO::PARAM_STR);
									$resultsere->bindParam(':transferenciaid', $numerorecibido, PDO::PARAM_STR);
									$resultsere->execute();
									$countsere = $resultsere->rowcount();
									//  echo "Contador series".$countsere;
									if ($countsere == 0) {
										header("location: ingseriestr.php");
										//Echo "aQUI".$registraserie.$entraserie.$serial;
										$muestraleyenda2 = "Debe registrar SERIE del Producto";
										$bodega = 1;
									} else {
										$muestraleyenda2 = "Ya estan registradas las series de este producto";
										$bodega = 1;
									}
								} else {
									$rmacodigo = $codigoleido;
									$grabar = 1;
								}
							}
						}
					}
				}
				/*else
						{
							while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
								{
									
								//	Pregunto si la serie ya está registrada en otra factura
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
						}*/
				if ($bodega == 0) {
					//echo '<pre>', print_r($arregloseriest),'</pre>';
					$muestraleyenda2 = "";
					$arregloseriest = $_SESSION['arregloseriest'];
					$zz = 0;
					$existe = 0;
					while ($zz < count($arregloseriest)) {
						//Mientras haya elementos en el arreglo
						if ($arregloseriest[$zz][2] == $serial) { //Pregunta Si la serie ingresada está duplicada
							//echo "encontró que existe".$serial;
							$existe = 1;
						}
						$zz++;
					}
					if ($existe == 1) // Si existe muestra leyenda
					{
						//echo "aqui deberia entrar".$serial;
						$muestraleyenda2 = "  Ya leyó esa serie xxx ";
						$bodega = 1;
					} else {
						while ($z <= $x - 1) {
							//echo "Entro al while: -".$rmacodigo."--Arreglo:-".$arreglo[$z][4]."--Arreglo:-".$arreglo[$z][6]."--Arreglo:-".$arreglo[$z][7]."--Arreglo:-".$arreglo[$z][8]."<br>";   
							if (
								(rtrim($rmacodigo) == rtrim($arreglo[$z][6])) or (rtrim($rmacodigo) == rtrim($arreglo[$z][4])) or
								(rtrim($rmacodigo) == rtrim($arreglo[$z][7])) or (rtrim($rmacodigo) == rtrim($arreglo[$z][8])) and $rmacodigo <> ""
							) {
								//echo "Debe entrar aqui". $rmacodigo; 
								// Entro en este if si el codigo leido esta en el arreglo.
								//echo "Aqui verifico si".$arreglo[$z][9]."es mayor que".$arreglo[$z][3]."<br>";
								$muestraleyenda1 = 1; // para indicar que si pertenece a esta factura
								if (($arreglo[$z][11] + $cantidad) > $arreglo[$z][3]) // en este if verifico si no me he excedido en cantidades
								{
									$muestraleyenda2 = " Cantidad en exceso ";
								} else {
									if ($serial == "") {
										$muestraleyenda2 = "Item procesado correctamente ..... ";
										$arreglo[$z][11] = $arreglo[$z][11] + $cantidad;
										$_SESSION['datosarreglos'] = $arreglo;  // Grabo otra vez el arreglo en memoria  
										//echo '<pre>', print_r($arregloseriest),'</pre>';
										//echo "Contadorx".$_SESSION['contadort']; 
									} else {
										$muestraleyenda2 = "Item procesado correctamente ";
										$arreglo[$z][11] = $arreglo[$z][11] + $cantidad;
										$_SESSION['datosarreglos'] = $arreglo; // Grabo otra vez el arreglo en memoria
										$arregloseriest = $_SESSION['arregloseriest'];
										$arregloseriest[$y][1] = $idproducto;
										$arregloseriest[$y][2] = $serial;
										$arregloseriest[$y][3] =  $numerorecibido;
										$arregloseriest[$y][4] =  $grabar;
										$rmacodigo = $row['rmacodigo'];
										//echo '<pre>', print_r($arregloseriest),'</pre>';
										$y = $_SESSION['contadort'] = $_SESSION['contadort'] + 1;
										$_SESSION['arregloseriest'] = $arregloseriest;
									}
								}
							}
							$z++;
						}
					}
				}

				if ($muestraleyenda2 == "" and $muestraleyenda1 == 0) {
					//$muestraleyenda2="";
					//$muestraleyenda2="Item no pertenece a esta transferencia"; 	 
					echo $muestraleyenda2;
				}

				if ($muestraleyenda2 <> "") {
		?>
					<div id="leyenda"> <?php echo $muestraleyenda2 ?></div>
			<?php
				}
			}
			//Aqui voy a recorrer el arreglo de series temporales que traigo (arregloseries2) 	
			$t = 0;
			//echo "Mientras haya elementos en el arreglo";
			while ($t < count($arregloseries2)) {
				//echo "Seriestemporal".$arregloseries2[$t][2]. $arregloseries2[$t][1];
				//aqui recorro el arreglo de original (arreglo) 
				$o = 0;
				while ($o < count($arreglo)) {

					if ($arregloseries2[$t][1] == $arreglo[$o][10]) {
						$arreglo[$o][11] = $arreglo[$o][11] + 1;
						// echo "cantidad de series ".$arreglo[$o][11];
					}

					$o++;
				}
				$t++;
			}
			$_SESSION['arregloseriest'] = $arregloseries2;
			// meto un lazo para contar el numero de items pendientes por ingresar
			// variable para recorrer el arreglo
			?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Verificar Transferencias </center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">
			<div align="left">
				<table>
					<tr>
						<td id="label2">
							<strong> Id: </strong> <a> <?php echo $TransferId  ?> </a>
							<strong> Numero: </strong> <a> <?php echo $Transnum ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Origen: </strong> <a> <?php echo $origen ?> </a>
							<strong> Destino: </strong> <a> <?php echo $destino ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Detalle: </strong> <a> <?php echo $detalle ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Fecha y Hora de Creacion: </strong> <a> <?php echo $fecha ?> </a>
							<br>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<hr>

		<div id="cuerpo2" style="<?php echo $readonly ?>">
			<form name="form2" action="verificartransferencias2.php" method="POST">
				<p style="font-weight: bold" align="center">
					<label for="codigo_serie">Código o serie :</label>
					<input name="codigo" type="text" id="codigo" size="30" value="<?php $codigoleido ?>">
					<input name="transferencia" type="hidden" id="transferencia" value="<?php echo $numerorecibido ?>">
					<input type="submit" name="submit" id="submit" value="Ingresar">
				<h4 style="text-align:center">
					<input type="button" onclick="window.location.href='verificartransferencias.php'" value="Regresar" style="text-decoration:none; background:none; border:none; color:blue; cursor:pointer; margin-right: 5cm;">
				</h4>
		</div>
		</p>


		<!-- <div id="boton-div">
			<a href="trakingverificartrDT.php?numero=<?php echo $arreglo[$y][1] ?>" target="_blank">
				<button type="button" class="btn btn-primary">
					<i class="bi bi-file-text"></i> Ver productos
				</button>
			</a>
		</div> -->

		</form>
	</div>


	<div id="Cuerpo2">
		<div>
			<table id="listado2" align="center">
				<tr>
					<th> CODIGO </th>
					<th> ARTICULO </th>
					<th> CANTIDAD TOTAL </th>
					<th> INGRESADAS </th>
					<th> PESO </th>
				</tr>
				<?php
				$totalitems = 0;
				$totalleidos = 0;
				$totalPeso = 0; // Variable para almacenar el peso total
				$zz = 0;

				while ($zz <= $xx - 1) {

					$totalitems = $arreglo[$zz][3] + $totalitems;

					// Obtener cantidad total e ingresadas
					$cantidadTotal = $arreglo[$zz][3];
					$ingresadas = $arreglo[$zz][11];

					$pesoTotalItem = $arreglo[$zz][13]; // Suponiendo que la columna es la 13
					$totalPeso += $pesoTotalItem;



					// Validación y estilo
					if ($cantidadTotal == $ingresadas) {
						$rowStyle = 'background-color: #E2FFEA;'; // Pinta de verde si son iguales
					} else {
						$rowStyle = 'background-color: #FFE3E2;'; // Pinta de rojo si son diferentes
					}

					// Mostrar los valores del arreglo
					echo '<tr style="' . $rowStyle . '">';
					echo '<td id="fila2" align="left">' . $arreglo[$zz][4] . '</td>';
					echo '<td id="fila2" align="left">' . $arreglo[$zz][5] . '</td>';
					echo '<td id="fila" align="left">' . $cantidadTotal . '</td>';
					echo '<td id="fila" align="left">' . $ingresadas . '</td>';
					echo '<td id="fila2" align="left">0' . $arreglo[$zz][13] . '</td>';
					// Columna Peso
					echo '</tr>';

					$totalleidos += $ingresadas; // Actualizar totalleidos
					$zz++;
				}

				?>
				<!-- </table> -->
		</div>
	</div>

	<?php

			$itemsporleer = $totalitems - $totalleidos;
	?>

	<div id="Cuerpo2">
		<div id="label">
			<h3><strong> Items por verificar: <?php echo $itemsporleer ?> </strong></h3>
			<?php
			// Mostrar el valor del peso total de la primera línea del arreglo
			echo '<h3><strong>Peso total: ' . number_format($arreglo[0][14], 2) . '</strong></h3>';
			?>

		</div>
	</div>
	<div id="Cuerpo2">
		<p style="font-weight: bold" align="center">


			<?php

			if ($itemsporleer == 0) {

				$readonly = "display:none;";
				//$readonly = "readonly";
				//echo "Aqui muestro la propiedad ".$readonly; 
				//echo "Aqui se supone  esta el arreglo con la series ingresadas"; 
				//echo '<pre>', print_r($arreglo),'</pre>';
				$_SESSION['datosarreglos'] = $arreglo; // Grabo otra vez el arreglo en memoria
				$_SESSION['IDDESTINO'] = $iddestino;	//Asigno variable de bodega destino
				$_SESSION['IDORIGEN'] = $idorigen; //Asigno variable de bodega Origen 
				$_SESSION['DETALLE'] = $detalle;

				//$_SESSION['arregloseriest']= $arregloseriest;

			?>

		<form action="verificartransferencias3.php" method="post">
			<p style="font-weight: bold" align="center">
				<input name="transferencia" type="hidden" id="transferencia" value="<?php echo $numerorecibido ?>">
				<input class="form-control" name="bulto" type="number" id="bulto" size="30" placeholder="Ingrese los bultos" value="<?php echo $butorecibido ?>" style="width: 100%; max-width: 200px;">
				<br>
				<br>
				<input type="submit" name="submit" id="submit" value="TRANSFERENCIA COMPLETA presione aquí para CONTINUAR">
			</p>
		</form>
	<?php
			}

			$_SESSION['usuario'] = $usuario1;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['nomsuc'] = $nomsuc;
			$_SESSION['transfer'] = $numerorecibido;
			$_SESSION['BULTOS'] = $butorecibido;
			$_SESSION['IDDESTINO'] = $iddestino;	//Asigno variable de bodega destino
			$_SESSION['IDORIGEN'] = $idorigen; //Asigno variable de bodega Origen 
			$_SESSION['DETALLE'] = $detalle;
			$_SESSION['cantseries'] = $cantseries;
			$_SESSION['idproducto'] = $idproducto;
			$arreglo = $_SESSION['datosarreglos']; //arreglo original 
			$arregloseriest = $_SESSION['arregloseriest'];
			//echo '<pre>', print_r($arregloseries2),'</pre>';	
			$_SESSION['arregloseriest'] = $arregloseries2;
			//echo '<pre>', print_r($arregloseriest),'</pre>';	
			//echo '<pre>', print_r($arreglo),'</pre>';	//este es el arreglo original
			//echo $usuario1. $base.$acceso.$nomsuc.$numerorecibido;

	?>

	</p>
	</div>


<?php
		} else {
			header("location: index.html");
		}
?>
</div>
</div>
</body>

<script>
	var usuario = "<?php echo $usuario; ?>";

	function imprimirConexionActiva() {

		console.log("conexión activa: " + usuario);
	}

	setInterval(imprimirConexionActiva, 2000);
</script>

</html>