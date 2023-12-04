<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
	function setfocus() {
		document.getElementById("codigo").focus();
	}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/estiloverificartrasferenciacompu.css" rel="stylesheet" type="text/css">

<body onload="setfocus()">
	<div id="header" align="center">
		<?PHP
		//error_reporting(E_ALL);
		//ini_set('display_errors','On');
		session_start();
		if (isset($_SESSION['loggedin'])) {
			$bloqueado = $_SESSION['bloqueo'];
			$nota = $_SESSION['nota'];
			$usuario = $_SESSION['usuario'];
			$Id = $_SESSION['id'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$codigo = $_SESSION['codigo'];
			$nomsuc = $_SESSION['nomsuc'];
			$factura = $_SESSION['factura'];
			$bodegaFAC = $_SESSION['bodegaFAC'];
			// En este if entro si vengo llamado por la misma pagina
			if (isset($_POST["factura"])) {
				$y = $_SESSION['contador']; // Cargo el contador en la memoria (ya estaba cargado)
				$numerorecibido = $_POST['factura'];
			} else {
				$y = 0; //
				//echo "encero el contador por  primera  vez ";
				unset($arregloseries);
				$arregloseries = array();
				$numerorecibido = $_SESSION['factura'];
				$_SESSION['arregloseries'] = $arregloseries;
				$arregloseries = $_SESSION['arregloseries'];
				//echo '<pre>', print_r($arregloseries),'</pre>';

			}
			if ($base == 'CARTIMEX') {
				require 'headcarti.php';
			} else {
				require 'headcompu.php';
				$_SESSION['base'] = $base;
				$Nota = " ";
			}

			$usuario1 = $usuario;
			require('conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('LOG_VERIFICAR_FACTURA2 @facturaid=:facturaid');
			$result->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
			$result->execute();
			$usuario = $usuario1;
			while ($rowcli = $result->fetch(PDO::FETCH_ASSOC)) {
				$cliente = $rowcli['cliente'];
				$Ruc = $rowcli['ruc'];
				$Fecha = $rowcli['fecha'];
				$Vendedor = $rowcli['vendedor'];
				$secuencia = $rowcli['secuencia'];
				$idfac = $rowcli['id'];
				$numfac = $rowcli['numero'];
				$nota = $rowcli['nota'];
				$bloqueado = $rowcli['bloqueado'];
			}

		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Verificar Facturas <?php echo substr($nomsuc, 10, 20);  ?></center>
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">
			<div align="left">
				<table>
					<tr>
						<td id="label2">
							<strong> Id: </strong> <a> <?php echo $idfac ?> </a>
							<strong> Numero: </strong> <a> <?php echo $numfac ?> </a>
							<strong> Factura # : </strong> <a> <?php echo $secuencia ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Cliente: </strong> <a> <?php echo $cliente ?> </a>
							<strong> Ruc: </strong> <a> <?php echo $Ruc ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Vendedor: </strong> <a> <?php echo $Vendedor ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Fecha y Hora de Creacion: </strong> <a> <?php echo $Fecha ?> </a>
							<br>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<hr>
		<div id="cuerpo2">
			<form name="form2" action="verificarfacturas2.php" method="POST">
				<p style="font-weight: bold" align="center">Código o serie :
					<input name="codigo" type="text" id="codigo" size="30" value="<?php $codigoleido ?>">
					<input name="factura" type="hidden" id="factura" value="<?php echo $numerorecibido ?>">
					<input type="submit" name="submit" id="submit" value="Ingresar">
				<h4 style="text-align:center">
					<input type="button" onclick="window.location.href='verificarfacturas.php'" value="Regresar" style="text-decoration:none; background:none; border:none; color:blue; cursor:pointer; margin-right: 5cm;">
				</h4>
				</p>
			</form>
		</div>
		<?php
			$codigoleidoinicial = "";
			$arreglo = $_SESSION['datosarreglos']; // Cargo el arreglo de la memoria
			$xx = count($arreglo); //Cuento las filas del arreglo que viene de la consulta de la factura
			//echo '<pre>', print_r($arreglo),'</pre>'; //trae los datos de la consulta de los productos en el arreglo original

			if (isset($_POST["codigo"])) {
				//echo "Viene de leer un codigo <br>";
				$codigoleidoinicial = strtoupper($_POST["codigo"]); //echo "Codigo leido: ".$codigoleidoinicial."<br>";
				$arreglo = $_SESSION['datosarreglos']; // Cargo el arreglo de la memoria
				$x = count($arreglo); //Cuento las filas del arreglo original
				//echo "Contador del arreglo original".$x;
				$z = 0; // variable para recorrer el arreglo
				$muestraleyenda1 = 0;
				$muestraleyenda2 = "";
				$bodega = 0;
				$grabar = 0; //para saber si debo grabar o no en la tabla de RMA_productos
				$serial = "";
				// aqui recorro el codigo para saber si tiene +cantidad. De ser asi, hago un nuevo codigo leido
				$cantidad = 1; // por defecto siempre la cantidad es 1
				$codigoleido = $codigoleidoinicial;
				$long = strlen($codigoleidoinicial);
				for ($w = 0; $w < $long; $w++) {
					$parte = substr($codigoleidoinicial, $w, 1);
					if ($parte == "+") {
						$codigoleido = substr($codigoleidoinicial, 0, $w);
						//echo "cod nuevo:".$codigoleido."<br>";
						$cantidad = substr($codigoleidoinicial, $w + 1, $long);
						$w = 100;
						$entrocantidad = 1;
					} else {
						$codigoleido = $codigoleidoinicial;
						$cantidad = 1;
						$entrocantidad = 0;
					}
				}
				if ($entrocantidad == 0)  //Para saber si anexo serie al codigo del producto
				{

					for ($w = 0; $w < $long; $w++) {
						$parte = substr($codigoleidoinicial, $w, 1);

						if ($parte == "*") {
							$codigoleido = substr($codigoleidoinicial, 0, $w); //echo "cod nuevo:".$codigoleido."<br>";
							$serial = substr($codigoleidoinicial, $w + 1, $long);
							$w = 100;
						} else {
							$codigoleido = $codigoleidoinicial;
							$serial = "";
						}
					}
				}
				//echo '<pre>',print_r($_SESSION['arreglo']),'</pre>';
				//echo "Codigo".$codigoleido;
				//echo "serial".$serial;
				// Por cada serie leida consulta la tabla RMA_Productos para buscarla

				$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result1 = $pdo1->prepare('LOG_VERIFICAR_FACTURA3 @facturaid=:facturaid, @codigoleido=:codigoleido');
				$result1->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
				$result1->bindParam(':codigoleido', $codigoleido, PDO::PARAM_STR);
				$result1->execute();
				$count = $result1->rowcount();
				/*Si el contador trae 0 quiere decir que no es serie o ese producto no pertenece a la factura asi que hace proceso con el código sin serie caso contrario busca la serie y el producto al que pertenece*/
				if ($count == 0) {
					//echo "Entra aqui".$codigoleido.$count;
					// echo $numerorecibido;
					// echo $codigoleido;
					// echo $serial;
					$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result2 = $pdo2->prepare('LOG_VERIFICAR_FACTURA4  @codigoleido=:codigoleido, @facturaid=:facturaid');
					$result2->bindParam(':codigoleido', $codigoleido, PDO::PARAM_STR);
					$result2->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
					$result2->execute();
					// var_dump($result2->fetch(PDO::FETCH_ASSOC));
					$count2 = $result2->rowcount();
					if ($count2 == 0) {
						$muestraleyenda2 = "Item no pertenece a esta factura o serie está en otra BODEGA X ";
						$bodega = 1;
					} else {
						// echo("<pre>");
						// var_dump($result2->fetch(PDO::FETCH_ASSOC));
						// echo("</pre>");

						while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
							$registraserie = $row2['registroSeries'];
							$entraserie = $row2['RSeriesEnt'];
							$idproducto = $row2['ID'];

							if ($registraserie == 1 and $entraserie == 1) {
								//Echo "aQUI".$registraserie.$entraserie.$serial;
								$muestraleyenda2 = "Debe registrar SERIE del Producto";
								$bodega = 1;

								// $pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
								// $result3 = $pdo1->prepare('LOG_VERIFICAR_FACTURA3 @facturaid=:facturaid, @codigoleido=:codigoleido');
								// $result3->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
								// $result3->bindParam(':codigoleido', $serial, PDO::PARAM_STR);
								// $result3->execute();
								// echo ("<pre>");
								// var_dump($result3->fetch(PDO::FETCH_ASSOC));
								// echo ("</pre>");
								// while ($row = $result3->fetch(PDO::FETCH_ASSOC)) {
								// 	//echo "Por aqui va".$codigoleido;
								// 	//Pregunto si la serie ya está registrada en otra factura
								// 	if (trim($row['estado']) == 'VENDIDO') {
								// 		$muestraleyenda2 = " Serie ya está registrada en factura # " . $row['numfactura'] . " ";
								// 		$bodega = 1;
								// 	} else {
								// 		//echo "Por aqui va".$codigoleido;
								// 		//Lleno el arreglo donde se guardan las series que estoy registrando
								// 		if ($y == 0) {	//Primera serie la graba sin preguntar porque es el primer elemento del arreglo
								// 			$arregloseries[$y][1] = $row['productoid'];
								// 			$arregloseries[$y][2] = $codigoleido;
								// 			$arregloseries[$y][3] =  $numerorecibido;
								// 			$arregloseries[$y][4] =  $grabar;
								// 			$rmacodigo = $row['rmacodigo'];
								// 			$y = $_SESSION['contador'] = $_SESSION['contador'] + 1;
								// 		} else {
								// 			$muestraleyenda2 = "";
								// 			$arregloseries = $_SESSION['arregloseries'];
								// 			$zz = 0;
								// 			$existe = 0;
								// 			while ($zz < count($arregloseries)) {	//Mientras haya elementos en el arreglo
								// 				if ($arregloseries[$zz][2] == $codigoleido) //Pregunta Si la serie ingresada está duplicada
								// 				{
								// 					$existe = 1;
								// 				}
								// 				$zz++;
								// 			}
								// 			if ($existe == 1) // Si existe muestra leyenda
								// 			{
								// 				$muestraleyenda2 = "  Ya leyó esa serie... ";
								// 				$bodega = 1;
								// 			} else {
								// 				while ($z <= $x - 1) {
								// 					$rmacodigo = $row['rmacodigo'];
								// 					if (
								// 						((rtrim($rmacodigo) == rtrim($arreglo[$z][6])) or
								// 							(rtrim($rmacodigo) == rtrim($arreglo[$z][4])) or
								// 							(rtrim($rmacodigo) == rtrim($arreglo[$z][7])) or
								// 							(rtrim($rmacodigo) == rtrim($arreglo[$z][8])))
								// 						and $rmacodigo <> ""
								// 					) {
								// 						// Entro en este if si el codigo leido esta en el arreglo.
								// 						//echo "Aqui verifico si".$arreglo[$z][9]."es mayor que".$arreglo[$z][3]."<br>";
								// 						// para indicar que si pertenece a esta factura
								// 						if (($arreglo[$z][11] + $cantidad) > $arreglo[$z][3]) // en este if verifico si no me he excedido en cantidades
								// 						{
								// 							$muestraleyenda2 = " Cantidad en exceso  ";
								// 						} else {
								// 							$muestraleyenda2 = "Item procesado correctamente..";
								// 							$bodega = 1;
								// 							$arreglo[$z][11] = $arreglo[$z][11] + $cantidad;
								// 							$_SESSION['datosarreglos'] = $arreglo;  // Grabo otra vez el arreglo en memoria
								// 							$arregloseries = $_SESSION['arregloseries'];
								// 							$arregloseries[$y][1] = $row['productoid'];
								// 							$arregloseries[$y][2] = $codigoleido;
								// 							$arregloseries[$y][3] =  $numerorecibido;
								// 							$arregloseries[$y][4] =  $grabar;
								// 							$rmacodigo = $row['rmacodigo'];
								// 							$y = $_SESSION['contador'] = $_SESSION['contador'] + 1;
								// 							$_SESSION['arregloseries'] = $arregloseries;
								// 						}
								// 					}
								// 					$z++;
								// 				}
								// 			}
								// 		}
								// 		$_SESSION['arregloseries'] = $arregloseries;
								// 		//echo '<pre>',print_r($_SESSION['arregloseries']),'</pre>';
								// 	}
								// }

								// echo "auiii";
							} else {
								//Echo "Aca".$registraserie.$entraserie.$serial;
								if (($registraserie == 1 and $entraserie == 0) and $serial == "") {
									//echo "Entro aqui ";
									$muestraleyenda2 = "Debe registrar SERIE del Producto";
									$bodega = 1;
								} else {

									$rmacodigo = $codigoleido;
									$grabar = 1;
									$bodega = 0;
									//echo "Entro acad".$rmacodigo;
								}
							}
						}
					}
				} else {
					//echo "Entra aqui xq es serie lo que ingrese".$codigoleido;
					while ($row = $result1->fetch(PDO::FETCH_ASSOC)) {
						//echo "Por aqui va".$codigoleido;
						//Pregunto si la serie ya está registrada en otra factura
						if (trim($row['estado']) == 'VENDIDO') {
							$muestraleyenda2 = " Serie ya está registrada en factura # " . $row['numfactura'] . " ";
							$bodega = 1;
						} else {
							//echo "Por aqui va".$codigoleido;
							//Lleno el arreglo donde se guardan las series que estoy registrando
							if ($y == 0) {	//Primera serie la graba sin preguntar porque es el primer elemento del arreglo
								$arregloseries[$y][1] = $row['productoid'];
								$arregloseries[$y][2] = $codigoleido;
								$arregloseries[$y][3] =  $numerorecibido;
								$arregloseries[$y][4] =  $grabar;
								$rmacodigo = $row['rmacodigo'];
								$y = $_SESSION['contador'] = $_SESSION['contador'] + 1;
							} else {
								$muestraleyenda2 = "";
								$arregloseries = $_SESSION['arregloseries'];
								$zz = 0;
								$existe = 0;
								while ($zz < count($arregloseries)) {	//Mientras haya elementos en el arreglo
									if ($arregloseries[$zz][2] == $codigoleido) //Pregunta Si la serie ingresada está duplicada
									{
										$existe = 1;
									}
									$zz++;
								}
								if ($existe == 1) // Si existe muestra leyenda
								{
									$muestraleyenda2 = "  Ya leyó esa serie... ";
									$bodega = 1;
								} else {
									while ($z <= $x - 1) {
										$rmacodigo = $row['rmacodigo'];
										if (
											((rtrim($rmacodigo) == rtrim($arreglo[$z][6])) or
												(rtrim($rmacodigo) == rtrim($arreglo[$z][4])) or
												(rtrim($rmacodigo) == rtrim($arreglo[$z][7])) or
												(rtrim($rmacodigo) == rtrim($arreglo[$z][8])))
											and $rmacodigo <> ""
										) {
											// Entro en este if si el codigo leido esta en el arreglo.
											//echo "Aqui verifico si".$arreglo[$z][9]."es mayor que".$arreglo[$z][3]."<br>";
											// para indicar que si pertenece a esta factura
											if (($arreglo[$z][11] + $cantidad) > $arreglo[$z][3]) // en este if verifico si no me he excedido en cantidades
											{
												$muestraleyenda2 = " Cantidad en exceso  ";
											} else {
												$muestraleyenda2 = "Item procesado correctamente..";
												$bodega = 1;
												$arreglo[$z][11] = $arreglo[$z][11] + $cantidad;
												$_SESSION['datosarreglos'] = $arreglo;  // Grabo otra vez el arreglo en memoria
												$arregloseries = $_SESSION['arregloseries'];
												$arregloseries[$y][1] = $row['productoid'];
												$arregloseries[$y][2] = $codigoleido;
												$arregloseries[$y][3] =  $numerorecibido;
												$arregloseries[$y][4] =  $grabar;
												$rmacodigo = $row['rmacodigo'];
												$y = $_SESSION['contador'] = $_SESSION['contador'] + 1;
												$_SESSION['arregloseries'] = $arregloseries;
											}
										}
										$z++;
									}
								}
							}
							$_SESSION['arregloseries'] = $arregloseries;
							//echo '<pre>',print_r($_SESSION['arregloseries']),'</pre>';
						}
					}
				}
				if ($bodega == 0) {


					$muestraleyenda2 = "";
					$arregloseries = $_SESSION['arregloseries'];
					$zz = 0;
					$existe = 0;
					//echo '<pre>', print_r($arregloseries),'</pre>';
					while ($zz < count($arregloseries)) {
						//Mientras haya elementos en el arreglo
						if (($arregloseries[$zz][2] == $serial) and ($idproducto == $arregloseries[$zz][1])) { //Pregunta Si la serie ingresada está duplicada
							//echo "encontró que existe".$serial;
							$existe = 1;
						}
						$zz++;
					}
					if ($existe == 1) // Si existe muestra leyenda
					{
						//echo "aqui deberia entrar".$serial;
						$muestraleyenda2 = "  Ya leyó esa serie";
						$bodega = 1;
					} else {

						while ($z <= $x - 1) {
							//echo "Entro al while: -".$rmacodigo."--Arreglo:-".$arreglo[$z][4]."--Arreglo:-".$arreglo[$z][6]."--Arreglo:-".$arreglo[$z][7]."--Arreglo:-".$arreglo[$z][8]."<br>";
							if (((rtrim($rmacodigo) == rtrim($arreglo[$z][6])) or  (rtrim($rmacodigo) == rtrim($arreglo[$z][4])) or
								(rtrim($rmacodigo) == rtrim($arreglo[$z][7])) or  (rtrim($rmacodigo) == rtrim($arreglo[$z][8]))) and $rmacodigo <> "") {
								//echo "z=".$z;
								// Entro en este if si el codigo leido esta en el arreglo.
								//echo "Aqui verifico si".$arreglo[$z][9]."es mayor que".$arreglo[$z][3]."<br>";
								$muestraleyenda1 = 1; // para indicar que si pertenece a esta factura
								if (($arreglo[$z][11] + $cantidad) > $arreglo[$z][3]) // en este if verifico si no me he excedido en cantidades
								{
									$muestraleyenda2 = "Cantidad en exceso ";
								} else {
									//echo "Voy x aqui".$rmacodigo.$serial;
									if ($serial == "") {
										$muestraleyenda2 = "Item procesado correctamente ";
										$arreglo[$z][11] = $arreglo[$z][11] + $cantidad;
										$_SESSION['datosarreglos'] = $arreglo;  // Grabo otra vez el arreglo en memoria
										//echo '<pre>', print_r($arregloseries),'</pre>';
										//echo "Contadorx".$_SESSION['contadort'];
									} else {
										$muestraleyenda2 = "Item procesado correctamente...";
										$arreglo[$z][11] = $arreglo[$z][11] + $cantidad;
										$_SESSION['datosarreglos'] = $arreglo;  // Grabo otra vez el arreglo en memoria
										$arregloseries = $_SESSION['arregloseries'];
										$arregloseries[$y][1] = $arreglo[$z][10];
										$arregloseries[$y][2] = $serial;
										$arregloseries[$y][3] =  $numerorecibido;
										$arregloseries[$y][4] =  $grabar;
										$rmacodigo = $row['rmacodigo'];
										//echo '<pre>', print_r($arregloseries),'</pre>'.$rmacodigo;
										//echo "rmacodigodentrodel while".$rmacodigo;
										$y = $_SESSION['contador'] = $_SESSION['contador'] + 1;
										$_SESSION['arregloseries'] = $arregloseries;
									}
								}
							}
							$z++;
						}
					}
				}

				if ($muestraleyenda2 == "" and $muestraleyenda1 == 0) {
					echo "<div align='center'><font color= 'red'>Item no pertenece a esta factura  </font></div>";
					//echo "<center> Item no pertenece a esta factura </center><br>";
				}
				if ($muestraleyenda2 <> "") {
		?>
				<div id="leyenda"> <?php echo $muestraleyenda2 ?></div>
		<?php
				}
			}

			// meto un lazo para contar el numero de items pendientes por ingresar

			$totalitems = 0;
			$totalleidos = 0;
			$zz = 0; // variable para recorrer el arreglo
		?>
		<div id="Cuerpo2">
			<div>
				<table id="listado2" align="center">
					<tr>
						<th> CODIGO </th>
						<th> ARTICULO </th>
						<th> CANTIDAD TOTAL</th>
						<th> INGRESADAS </th>
					</tr>
					<?php
					
					while ($zz <= $xx - 1) {
						$totalitems = $arreglo[$zz][3] + $totalitems;

						// Obtener cantidad total e ingresadas
						$cantidadTotal = $arreglo[$zz][3];
						$ingresadas = $arreglo[$zz][11];

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
						echo '</tr>';

						$totalleidos += $ingresadas; // Actualizar totalleidos
						$zz++;
					}
					// echo "</table>";
					?>
			</div>
		</div>
		<?php

			$itemsporleer =  $totalitems -  $totalleidos;

			if ($bloqueado == 1) {
				echo "Bloqueada!!";
			} else {

				if ($nota <> "") {
					echo "Desbloqueadas!!";
				}
			}
		?>
		<div id="Cuerpo2">
			<div id="label">
				<h3><strong> Unidades por verificar: <?php echo $itemsporleer ?> </strong></h3>
			</div>
		</div>

		<div id="Cuerpo2">
			<p style="font-weight: bold" align="center">
				<?php
				if ($itemsporleer == 0) {
					$_SESSION['datosarreglos'] = $arreglo; // Grabo otra vez el arreglo en memoria

				?>
				<!--  -->
			<form action="verificarfacturas3.php" method="post">
				<p style="font-weight: bold" align="center">
					<input name="factura" type="hidden" id="factura" value="<?php echo $numerorecibido ?>">
					<input type="submit" name="submit" id="submit" value="FACTURA COMPLETA presione aquí para CONTINUAR">
				</p>
			</form>
		<?php
				}

				$_SESSION['cliente'] = $cliente;
				$_SESSION['usuario'] = $usuario;
				$_SESSION['id'] = $Id;
				$_SESSION['base'] = $base;
				$_SESSION['acceso'] = $acceso;
				$_SESSION['codigo'] = $codigo;
				$_SESSION['nomsuc'] = $nomsuc;
				$_SESSION['factura'] = $factura;
				$_SESSION['bodegaFAC'] = $bodegaFAC;
				$arreglo = $_SESSION['datosarreglos']; //Assigns session var to $array
				$arregloseries = $_SESSION['arregloseries'];
				//echo '<pre>', print_r($arregloseries),'</pre>';
				$_SESSION['arregloseries'] = $arregloseries;

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

</html>