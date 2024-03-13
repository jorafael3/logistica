<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <link href="../estilos/estilos2.css" rel="stylesheet" type="text/css"> -->
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<!-- <link href="../estilos/estilopreparartrasferencia1.css" rel="stylesheet" type="text/css"> -->
<link href="../estilos/estilopreparartrasferencia2.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
	function visible() {
		var div1 = document.getElementById('Preparando');
		div1.style.display = 'none';
	}

	$(document).ready(function() {
		// Manejo del clic en las celdas seleccionables
		$("body").on("click", ".fila-check", function() {
			$(this).toggleClass("selected");
		});
	});
</script>


<style>
	#listado2 th {
		background-color: #b1f8b1;
		color: black;
		/* Cambia este color según tu diseño */
	}

	.check-label {
		display: flex;
		align-items: center;
		justify-content: center;
		/* Centra horizontalmente */
	}

	.check-box {
		width: 20px;
		height: 20px;
		border: 2px solid #ccc;
		border-radius: 5px;
		margin-right: 10px;
		cursor: pointer;
	}

	.check-box:checked {
		background-color: #3498db;
		/* Cambia este color según tu diseño */
		border-color: #3498db;
	}
</style>

<body>
	<div id="header" align="center">
		<?php
		//error_reporting(E_ALL);
		//ini_set('display_errors','On');

		session_start();
		if (isset($_SESSION['loggedin'])) {
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	= $_SESSION['acceso'];
			$transfer = $_SESSION['transfer'];
			$bodega	= $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$usuario1 = trim($usuario);
			// $codigo= trim($codigo);
			if ($base == 'CARTIMEX') {
				require '../headcarti.php';
			} else {
				require '../headcompu.php';
				$_SESSION['base'] = $base;
				$Nota = " ";
			}
			if ($_POST['transfer'] <> '') {
				$gretiro = 'text';
				$activargr = 'submit';
			} else {
				$gretiro = 'hidden';
				$activargr = 'hidden';
			}
			require('../conexion_mssql.php');

			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('LOG_BUSQUEDA_TRANSFERENCIA @numero=:transfer');
			$result->bindParam(':transfer', $transfer, PDO::PARAM_STR);
			$result->execute();
			$arreglotra = array();
			$x = 0;
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$arreglotra[$x][1] = $row['Numtransfer'];
				$arreglotra[$x][2] = $row['Idtransfer'];
				$arreglotra[$x][3] = $row['Detalle'];
				$arreglotra[$x][4] = $row['Descodigo'];
				$arreglotra[$x][5] = $row['Destino'];
				$arreglotra[$x][6] = $row['Oricodigo'];
				$arreglotra[$x][7] = $row['Origen'];
				$arreglotra[$x][8] = $row['Fecha'];
				$arreglotra[$x][9] = $row['BodegaId'];
				$arreglotra[$x][10] = $row['Nota'];
				$x++;
			}
			$count = count($arreglotra);
			$y = 0;
			while ($y <= $count - 1) {
				$Idtransfer = $arreglotra[$y][2];
				$Numtransfer = $Numtransfer . "/" . $arreglotra[$y][1];
				$Fecha = $arreglotra[$y][8];
				$Oricodigo = $arreglotra[$y][6];
				$Origen = $arreglotra[$y][7];
				$Descodigo = $arreglotra[$y][4];
				$Destino = $arreglotra[$y][5];
				$Detalle = $arreglotra[$y][3];
				$bodegaid = $arreglotra[$y][9];
				$Nota = $arreglotra[$y][10] . "/" . $arreglotra[$y][10];
				$y = $y + 1;
			}

		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Datos de Transferencia</center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
		</div>
		<hr>
		<div id="cuerpo2" align="center">
			<div align="left">
				<table>
					<tr>
						<td><strong> BODEGA ORIGEN: <a> <?php echo $Oricodigo ?>&nbsp;&nbsp;&nbsp; <?php echo $Origen ?> </strong></td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Transferencia Agrupada: </strong> <a> <?php echo $Idtransfer ?> </a>
							<strong> Numero: </strong> <a> <?php echo $Numtransfer ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Fecha: </strong> <a> <?php echo $Fecha ?> </a>
							<strong> Destino: </strong> <a> <?php echo $Descodigo ?> <?php echo $Destino ?></a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Detalle: </strong> <a> <?php echo $Detalle ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Nota: </strong> <a> <?php echo $Nota ?> </a>
							<br>
						</td>
					</tr>
				</table>
			</div>
			<?php
			$tipo = 'INV-TR';
			$Zona = 'G';
			$TipoP = 'T';
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result1 = $pdo1->prepare('Log_facturaslistas_preparando_insert @id=:transfer , @usuario=:usuario, @tipo=:tipo, @TipoP=:TipoP, @Zona=:Zona');
			$result1->bindParam(':transfer', $transfer, PDO::PARAM_STR);
			$result1->bindParam(':usuario', $usuario1, PDO::PARAM_STR);
			$result1->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result1->bindParam(':TipoP', $TipoP, PDO::PARAM_STR);
			$result1->bindParam(':Zona', $Zona, PDO::PARAM_STR);
			$result1->execute();

			$pdo6 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result6 = $pdo6->prepare('Log_facturaslistas_preparandoTR_select @id=:transfer, @tipo=:tipo, @zona=:zona');
			$result6->bindParam(':transfer', $transfer, PDO::PARAM_STR);
			$result6->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result6->bindParam(':zona', $Zona, PDO::PARAM_STR);
			$result6->execute();

			while ($row6 = $result6->fetch(PDO::FETCH_ASSOC)) {
				$Preparando = $row6['preparando'];
				$Fechapre = $row6['fechaPreparando'];
				$ESTADO = $row6['ESTADO'];
				$zona = $row6['zona'];
			}

			$pdo4 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result4 = $pdo4->prepare('SELECT ESTADO,PREPARADOPOR, FECHAYHORA  FROM  facturaslistas   
									   WHERE TIPO =:tipo AND FACTURA=:transfer');
			$result4->bindParam(':transfer', $transfer, PDO::PARAM_STR);
			$result4->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result4->execute();
			while ($row4 = $result4->fetch(PDO::FETCH_ASSOC)) {
				$PreparadoPor = $row4['PREPARADOPOR'];
				$Fechaprepa = $row4['FECHAYHORA'];
				$ESTADO = $row4['ESTADO'];
			}
			if ($ESTADO == 'PREPARANDO') {
				$lprepa = $Preparando;
				$lfecha = $Fechapre;
				$ltitulo = "Preparando";
				$activar = "submit";
			} else {
				$lprepa = $PreparadoPor;
				$lfecha = $Fechaprepa;
				$ltitulo = "Preparado";
				$activar = "hidden";
			}
			// echo $transfer;
			$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result2 = $pdo2->prepare('LOG_PREPARAR_TRANSFERENCIA @TransferenciaID = :transfer ');
			$result2->bindParam(':transfer', $transfer, PDO::PARAM_STR);
			$result2->execute();
			$arreglo = array();
			$x = 0;
			while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
				$arreglo[$x][1] = $row2['DTID'];
				$arreglo[$x][2] = $row2['ProductoId'];
				$arreglo[$x][3] = $row2['CopProducto'];
				$arreglo[$x][4] = $row2['Cantidad'];
				$arreglo[$x][5] = $row2['Detalle'];
				$arreglo[$x][6] = $row2['RegistaSerie'];
				$x++;
			}
			$count = count($arreglo);

			/*----------Saco  los productos de JAULA si es q hay --------*/
			$pdo4 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result4 = $pdo4->prepare('LOG_PREPARAR_TRANSFERENCIAS_JAULA @TransferenciaID = :transfer ');
			$result4->bindParam(':transfer', $transfer, PDO::PARAM_STR);
			$result4->execute();
			$arreglo4 = array();
			$J = 0;
			while ($row4 = $result4->fetch(PDO::FETCH_ASSOC)) {
				$arreglo4[$J][1] = $row4['DTID'];
				$arreglo4[$J][2] = $row4['ProductoId'];
				$arreglo4[$J][3] = $row4['CopProducto'];
				$arreglo4[$J][4] = $row4['Cantidad'];
				$arreglo4[$J][5] = $row4['Detalle'];
				$arreglo4[$J][6] = $row4['RegistaSerie'];
				$arreglo4[$J][7] = $row4['Clase'];
				$arreglo4[$J][9] = $row4['zonau'];
				if (($arreglo4[$J][7]) == '02') {
					$contaserv++;
				} else {
					$contaserv = 0;
				}
				$J++;
			}
			$count4 = count($arreglo4);
			$finalizar = "hidden";
			$zonaretiro = ' ';
			if ($count4 > 0) {
				$zona = "J";
				$jaula = "visibility";
				$pdoj = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$resultj = $pdoj->prepare('select * from facturaslistas_zonas where factura=:transfer and zona=:zona
												   and zretiro is not null');
				$resultj->bindParam(':transfer', $transfer, PDO::PARAM_STR);
				$resultj->bindParam(':zona', $zona, PDO::PARAM_STR);
				$resultj->execute();
				while ($rowj = $resultj->fetch(PDO::FETCH_ASSOC)) {
					$finalizar = "submit";
					$zonaretiro = $rowj['zretiro'];
				}
				$sgtepagina = 'preparartransferencias1.php';
			} else {
				$jaula = "display:none";
				$sgtepagina = 'preparartransferencias2.php';
				$finalizar = "submit";
			}
			?>
			<div align="left" id="Preparado">
				<table>
					<tr>
						<td id="label3">
							<strong> <?php echo $ltitulo ?> </strong> <a> <?php echo $lprepa ?> </a>
							<strong> Fecha: </strong> <a> <?php echo $lfecha ?> </a>
							<br>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<?php
		?>
		<div id="cuerpo2" align="center">
			<div>
				<table id="listado2" align="center">
					<tr>
						<th> UBICACION1 </th>
						<th> UBICACION2 </th>
						<th> CODIGO </th>
						<th> ARTICULO </th>
						<th> CANT. </th>
						<th>COMPLETADO</th>
					</tr>
					<?php
					$y = 0;
					echo $bodegaid;
					while ($y <= $count - 1) {
						$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						$result3 = $pdo3->prepare('LOG_BUSCAR_MEJOR_UBICACION @ProductoId =:ProdId , @bodega=:bodega');
						$result3->bindParam(':ProdId', $arreglo[$y][2], PDO::PARAM_STR);
						$result3->bindParam(':bodega', $bodegaid, PDO::PARAM_STR);
						$result3->execute();
						while ($row3 = $result3->fetch(PDO::FETCH_ASSOC)) {
							$ubi1 = $row3['ubi1'];
							$ubi2 = $row3['ubi2'];
						}
						$countubi = $result3->rowcount();
						if ($countubi == 0) {
							$ubi1 = '';
							$ubi2 = '';
						}
					?>
						<tr>
							<td id="fila2" align=left> <?php echo $ubi1 ?></td>
							<td id="fila2" align=left> <?php echo $ubi2 ?></td>
							<td id="fila2" align=left> <?php echo $arreglo[$y][3] ?></td>
							<td id="fila2" align=left> <?php echo $arreglo[$y][5] ?></td>
							<td id="fila" align=left> <?php echo $arreglo[$y][4] ?></td>
							<td class="fila-check" align="center"><label class="check-label"><input type="checkbox" class="check-box" /></label>
							</td>
						</tr>
					<?php
						$y = $y + 1;
					}
					?>
				</table>
			</div>
			<br>
			<div style="<?php echo $jaula ?>">
				<table id="listado2" align="center">
					<tr></tr>
					<tr>
						<th colspan="6">Productos de Jaula</th>
					</tr>
					<tr>
						<th> UBICACION1 </th>
						<th> UBICACION2 </th>
						<th> CODIGO </th>
						<th> ARTICULO </th>
						<th> CANT. </th>
						<th>COMPLETADO</th>
					</tr>
					<?php

					$f = 0;
					while ($f <= $count4 - 1) {;
						$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						$result5 = $pdo5->prepare('LOG_BUSCAR_MEJOR_UBICACION @ProductoId =:ProdId , @bodega =:Bodega');
						$result5->bindParam(':ProdId', $arreglo4[$f][2], PDO::PARAM_STR);
						$result5->bindParam(':Bodega', $bodega, PDO::PARAM_STR);
						$result5->execute();

						while ($row5 = $result5->fetch(PDO::FETCH_ASSOC)) {
							$ubij1 = $row5['ubi1'];
							$ubij2 = $row5['ubi2'];
						}
						$countubi = $result5->rowcount();
						if ($countubi == 0) {
							$ubi1 = '';
							$ubi2 = '';
						}
					?>
						<tr>
							<td id="fila2" align=left> <?php echo $ubij1 ?></td>
							<td id="fila2" align=left> <?php echo $ubij2 ?></td>
							<td id="fila2" align=left> <?php echo $arreglo4[$f][3] ?></td>
							<td id="fila2" align=left> <?php echo $arreglo4[$f][5] ?></td>
							<td id="fila" align=left> <?php echo $arreglo4[$f][4] ?></td>
							<td class="fila-check" align="center"><label class="check-label"><input type="checkbox" class="check-box" /></label>
						</tr>
					<?php
						$f = $f + 1;
					}
					?>
				</table>
			</div>
			<div>
				<form name "interrumpir" action="interrumpirprepatr.php" method="POST" width="75%">
					<td id="box"> <input name="tipo" type="hidden" id="tipo" size="30" value="INV-TR"> </td>
					<input name="submit" id="submit" value="Interrumpir Preparacion" type="submit">
				</form>
				<div style="<?php echo $jaula ?>">
					<a> <strong> Retirar en : <?php echo $zonaretiro ?><strong></a>
				</div>
				<form name="formpreparar" action="<?php echo $sgtepagina ?>" method="POST" width="75%">
					<table align="center">
						<tr>
							<td>
							<td id="box"> <input name="transfer" type="hidden" id="transfer" size="30" value="<?php echo $transfer ?>"> </td>
							<td id="box"> <input name="bodega" type="hidden" id="bodega" size="30" value="<?php echo $bodegaid ?>"> </td>
							<input name="submit" id="submit" value="Finalizar Preparacion" type="<?php echo $finalizar ?>">
							<?php
							if ($activar == "hidden") { ?>
							<?php
							} else { ?>

								</td>
						</tr>
					</table>
				</form>
				<form name "grabarretiro" action="preparartransferencias2.php" method="POST" width="75%">
					<td id="box"> <input name="zona" type="<?php echo $gretiro ?>" id="zona" size="20" value="" required> </td>
					<br><input name="submit" id="submit" value="Grabar Retiro Jaula" type="<?php echo $activargr ?>">
				</form>

			<?php				} ?>
			</div>
		</div>
	<?php
			$_SESSION['bodega'] = $bodega;
			$_SESSION['usuario'] = $usuario1;
			$_SESSION['id'] = $Idtransfer;
			$_SESSION['transfer'] = $transfer;
			$_SESSION['base'] = $base;
			$_SESSION['tipo'] = $tipo;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['codigo'] = $codigo;
			$_SESSION['nomsuc'] = $nomsuc;
		} else {
			header("location: index.html");
		}
	?>
	</div>
</body>

</html>