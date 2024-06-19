<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/tablas.css">

<body onload="setfocus()">

	<style>
		.table thead th {
			background-color: #86F9B3;
			color: black;
			border: 1px solid #dee2e6;
		}

		.table tbody tr:nth-child(even) {

			background-color: #f2f2f2;
		}

		.table tbody tr:hover {

			background-color: #ddd;
		}

		.table td,
		.table th {
			border: 1px solid #dee2e6;
			text-align: center;
			vertical-align: middle;
		}

		.editable {
			cursor: pointer;
		}


		body {
			font-family: Arial, sans-serif;
			background-color: #f4f4f4;
		}

		.container1 {
			width: 50%;
			margin: 50px auto;
			background-color: #fff;
			padding: 20px;
			border-radius: 10px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
		}

		.container {
			width: 100%;
			margin: 50px auto;
			background-color: #fff;
			padding: 20px;
			border-radius: 10px;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
		}

		.form-table {
			width: 100%;
		}

		.form-table td {
			padding: 10px;
		}

		.form-table input[type="text"] {
			width: calc(100% - 20px);
			/* Para ajustar el ancho según el padding */
			padding: 8px;
			border: 1px solid #ccc;
			border-radius: 5px;
			transition: border-color 0.3s ease;
		}

		.form-table input[type="text"]:focus {
			outline: none;
			border-color: #66afe9;
		}

		.form-table input[type="image"] {
			border: none;
			cursor: pointer;
			vertical-align: middle;
		}

		.form-table a {
			text-decoration: none;
			color: #333;
			transition: color 0.3s ease;
		}

		.form-table a:hover {
			color: #66afe9;
		}


		.tipopedido {
			width: 100%;
			padding: 8px;
			border: 1px solid #ccc;
			border-radius: 5px;
			background-color: #fff;
			color: #333;
			font-size: 14px;
			box-sizing: border-box;
			/* Para incluir el padding y border en el ancho total */
			transition: border-color 0.3s ease;
		}

		.tipopedido:focus {
			outline: none;
			border-color: #66afe9;
		}
	</style>

	<div id="header" align="center">
		<?php
		session_start();
		if (isset($_SESSION['loggedin'])) {
			//echo "Entra aqui"; 
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	= $_SESSION['acceso'];
			$bodega = $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			if ($base == 'CARTIMEX') {
				require '../headcarti.php';
			} else {
				require '../headcompu.php';
			}
			date_default_timezone_set('America/Guayaquil');
			$fecha = date("y-m-d", time());
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;
			//echo "BOdega". $bodega; 
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Guias Facturas <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">

			<div class="container1">
				<form name="formfactura" action="ingguiasfacturas0.php" method="POST">
					<table class="form-table">
						<tr>
							<td id="label">Secuencia</td>
							<td id="box"> <input name="secu" type="text" id="secu" size="30" value=""> </td>
							<td id="box"> <input name="bodega" type="hidden" id="bodega" value="<?php echo trim($bodega) ?>"> </td>
						</tr>
						<tr>
							<td id="etiqueta"> <a href="javascript:window.location.href=window.location.href"> <img src="..\assets\img\refresh.png"></a></td>
							<td id="label"> Enviar
								<input name="submit" id="submit" value="Grabar" src="..\assets\img\lupa.png" type="image">
								<a href="../menu.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
							</td>
						</tr>
					</table>
				</form>
			</div>

			<div class="container mt-5">
				<div class="table-responsive">
					<form name="formfactura2" action="ingguiasfacturas0.php" method="POST">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th colspan="12" class="text-center">Ingresar Guias Facturas</th>
								</tr>
								<tr>
									<th>Bodega</th>
									<th>Secuencia</th>
									<th>Cliente</th>
									<th>Fecha Creacion</th>
									<th>Bodega</th>
									<th>Vendedor</th>
									<th>Estado</th>
									<th>V.Factura</th>
									<th>Tipo Pedido</th>
									<th>T.Transporte</th>
									<th>Fecha de Solicitada</th>
								</tr>
							</thead>
							<tbody>
								<?php
								session_start();
								$_SESSION['usuario'] = $usuario;
								$_SESSION['bodega'] = $bodega;
								include('../conexion_mssql.php');
								$usuario = $_SESSION['usuario'];
								$bodega = $_SESSION['bodega'];
								$paso = 'VERIFICADA';
								$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
								$result0 = $pdo0->prepare("LOG_FACTURAS_PENDIENTES_DEVUELTAS @BODEGA=:bodega , @acceso=:acceso, @Estado=:estado");
								$result0->bindParam(':bodega', $bodega, PDO::PARAM_STR);
								$result0->bindParam(':acceso', $acceso, PDO::PARAM_STR);
								$result0->bindParam(':estado', $paso, PDO::PARAM_STR);
								$result0->execute();
								$arreglod = array();
								$xd = 0;
								while ($row0 = $result0->fetch(PDO::FETCH_ASSOC)) {
									$arreglod[$xd][2] = $row0['secuencia'];
									$xd++;
								}
								$countd = count($arreglod);
								$yd = 0;
								while ($yd <= $countd - 1) {
									$devo = $arreglod[$yd][2];
									$pdod = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
									$resultd = $pdod->prepare("LOG_FACTURAS_DEVUELTA_UPDATE @Secuencia=:secuencia");
									$resultd->bindParam(':secuencia', $devo, PDO::PARAM_STR);
									$resultd->execute();
									$yd++;
								}
								$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
								$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_GUIAS_SELECT @BODEGA=:bodega , @acceso=:acceso");
								$result->bindParam(':bodega', $bodega, PDO::PARAM_STR);
								$result->bindParam(':acceso', $acceso, PDO::PARAM_STR);
								$result->execute();
								$arreglo = array();
								$x = 0;
								while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
									$arreglo[$x][1] = $row['Bodega'];
									$arreglo[$x][2] = $row['secuencia'];
									$arreglo[$x][3] = $row['fecha'];
									$arreglo[$x][4] = $row['nombodega'];
									$arreglo[$x][5] = $row['vendedor'];
									$arreglo[$x][6] = $row['Detalle'];
									$arreglo[$x][7] = $row['fpago'];
									$arreglo[$x][8] = $row['bloqueo'];
									$arreglo[$x][9] = number_format($row['TOTAL'], 2);
									$arreglo[$x][10] = $row['tpedido'];
									$arreglo[$x][11] = $row['transp'];
									$arreglo[$x][12] = $row['transpid'];
									$arreglo[$x][13] = $row['Fechaenviar'];
									$arreglo[$x][14] = $row['FacturaID'];
									$x++;
								}
								$count = count($arreglo);
								$y = 0;
								while ($y <= $count - 1) {
									if ($arreglo[$y][8] == 'BLOQUEADA') {
										$activar = 'label';
									} else {
										$activar = '';
									}
								?>
									<tr>
										<td><a href="trakingguia.php?secu=<?php echo $arreglo[$y][2] ?>"><?php echo $arreglo[$y][1] ?></a></td>
										<?php if ($activar == "label") { ?>
											<td align="center"><a><?php echo $arreglo[$y][2] ?></a></td>
										<?php } else { ?>
											<td align="center"><a href="ingguiasfacturas0.php?secu=<?php echo $arreglo[$y][2] ?>"><?php echo $arreglo[$y][2] ?></a></td>
										<?php } ?>
										<td align="center"><a><?php echo $arreglo[$y][6] ?></a></td>
										<td><?php echo $arreglo[$y][3] ?></td>
										<td><?php echo $arreglo[$y][4] ?></td>
										<td><?php echo $arreglo[$y][5] ?></td>
										<td><?php echo $arreglo[$y][8] ?></td>
										<td><?php echo $arreglo[$y][9] ?></td>
										
										<td>
											<select class="tipopedido" data-factura-id="<?php echo $arreglo[$y][14] ?>">
												<?php
												$valorActual = $arreglo[$y][10]; // Valor actual del arreglo
												echo "<option value=\"$valorActual\" selected>$valorActual</option>"; // Opción seleccionada por defecto

												// Verificar la bodega y agregar opciones según corresponda
												if ($bodega == '0000000006') {
												
													$opcionesAdicionales = ["MOSTRADOR-GYE", "PROVINCIA" , "MOSTRADOR-UIO", "CUIDAD-UIO"  ,  "CUIDAD-GYE" ];
												
												} else {
													
													$opcionesAdicionales = ["MOSTRADOR-UIO", "PROVINCIA" , "MOSTRADOR-GYE",  "CUIDAD-GYE" , "CUIDAD-UIO" ,];
												}

												foreach ($opcionesAdicionales as $opcion) {
													
													echo "<option value=\"$opcion\">$opcion</option>";
												}
												?>
											</select>
										</td>


										<td><?php echo $arreglo[$y][11] ?></td>
										<td class="editable" contenteditable="true" data-factura-id="<?php echo $arreglo[$y][14] ?>"><?php echo $arreglo[$y][13] ?></td>
										<td>
											<input name="bodega" type="hidden" size="30" value="<?php echo $bodega ?>">
										</td>
									</tr>
								<?php
									$activar = '';
									$y++;
								}
								?>
							</tbody>
						</table>
					</form>
					<div class="text-center mt-4">
						<h3>Se activa para ingresar la guia cuando: <br>*** La factura NO ESTA BLOQUEADA</h3>
					</div>
				</div>
			</div>

		<?php

			$_SESSION['usuario'] = $usuario;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['bodega'] = $bodega;
			$_SESSION['nomsuc'] = $nomsuc;
		} else {
			header("location: index.html");
		}

		?>
		</div>
</body>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script type="text/javascript">
	function setfocus() {

		document.getElementById("secu").focus();
	}



	$(document).ready(function() {
		$('.editable').on('keydown', function(event) {
			if (event.key === 'Enter') {
				event.preventDefault();

				var fechaRetiro = $(this).text();
				var facturaId = $(this).data('factura-id');

				// Formatear la fecha a AAMMDD usando moment.js
				var fechaFormateada = moment(fechaRetiro, "DD/MM/YYYY").format("YYMMDD");

				let param = {
					fechaRetiro: fechaFormateada,
					facturaId: facturaId
				};

				console.log('param: ', param);

				$.ajax({
					url: 'ingguiasactualizarfehca.php',
					method: 'POST',
					data: param,
					success: function(response) {
						Swal.fire(
							'Actualizado',
							'Tipo de pedido actualizado exitosamente.',
							'success'
						);
					},
					error: function() {
						Swal.fire(
							'Error',
							'Error al actualizar la fecha de retiro.',
							'error'
						);
					}
				});
			}
		});
	});


	$(document).ready(function() {
		$('.tipopedido').on('change', function() {

			var tipoPedido = $(this).val();
			var facturaId = $(this).data('factura-id');

			let param = {

				TipoPedido: tipoPedido,
				facturaId: facturaId
			};

			console.log('param: ', param);

			$.ajax({
				url: 'ingguiasactualizarfehca.php',
				method: 'POST',
				data: param,
				success: function(response) {
					Swal.fire(
						'Actualizado',
						'Tipo de pedido actualizado exitosamente.',
						'success'
					);
				},
				error: function() {
					Swal.fire(
						'Error',
						'Error al actualizar la fecha de retiro.',
						'error'
					);
				}
			});
		});
	});
</script>