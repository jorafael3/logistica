<meta name="viewport" content="width=device-width, height=device-height">
<!DOCTYPE html>
<html>
<script type="text/javascript">
	function setfocus() {
		document.getElementById("codigo").focus();
	}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/tablas.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

<body onload="setfocus()">
	<div id="header" align="center">
		<?php
		session_start();
		if (isset($_SESSION['loggedin'])) {
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	= $_SESSION['acceso'];
			$bodega = $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$desde = $_POST['desde'];
			$hasta = $_POST['hasta'];
			if ($_POST['tiporeporte'] == 'd') {
				$tipofecha = "FechaEntregado";
			} else {
				$tipofecha = "Fecha";
			}
			$usuario1 = $usuario;
			$usuario = $usuario1;
			if ($base == 'CARTIMEX') {
				require '../headcarti.php';
			} else {
				require '../headcompu.php';
			}
			$res = [];

			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare("LOG_FACTURAS_DESPACHADAS_SGL @bodega=:bodega , @acceso=:acceso, @desde=:desde, @hasta=:hasta, @tipofecha=:tipofecha");
			$result->bindParam(':bodega', $bodega, PDO::PARAM_STR);
			$result->bindParam(':acceso', $acceso, PDO::PARAM_STR);
			$result->bindParam(':desde', $desde, PDO::PARAM_STR);
			$result->bindParam(':hasta', $hasta, PDO::PARAM_STR);
			$result->bindParam(':tipofecha', $tipofecha, PDO::PARAM_STR);
			$result->execute();
			$arreglodesp = array();
			$x = 0;
			$res =  $result->fetchAll(PDO::FETCH_ASSOC);

			for ($i = 0; $i < count($res); $i++) {
				$res[$i]["Detalle"] = str_replace(["\n", "\r", "\t",'"'], '', $res["Detalle"]);
			}

			// print_r($res);
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">
			<div id="izq"></div>
			<div id="centro"> <a class="titulo">
					<center> Facturas despachadas <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a></div>
		</div>
		<hr>
		<div id="cuerpo2" align="center">

			<div>
				<form name="formfactura" action="trakingfacturas0.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<td id="label">Factura # </td>
							<td id="box"> <input name="secu" type="text" id="secu" size="30" value=""> </td>
							<td id="box"> <input name="bodega" type="hidden" id="bodega" value="<?php echo trim($bodega) ?>"> </td>
						</tr>
						<tr>
							<td id="etiqueta" colspan="2"> Consultar
								<input name="submit" id="submit" value="Grabar" src="..\assets\img\lupa.png" type="image">
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<div id="cuerpo2" align="center">
			<div>
				<form name="formproducto" action="facturasdespachadas.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<td id="label"> Fecha:
								<select name="tiporeporte">
									<option value='d'>Despacho</option>
									<option value='f'>Factura</option>
								</select>
							</td>
							<td id="label"> Desde:
								<input type="date" name="desde" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo date("Y-m-d"); ?>">
							</td>
							<td id="label"> Hasta:
								<input type="date" name="hasta" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo date("Y-m-d"); ?>">
							</td>
							<td> </td>
						</tr>
						<tr>
							<td> </td>
							<td id="etiqueta"> Consultar
								<input name="submit" id="submit" value="Consultar" src="..\assets\img\lupa.png" type="image">
							</td>
							<td id="etiqueta"> <a href="../menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a></td>
						</tr>
					</table>
				</form>


			</div>
			<div class="d-none">

				<form action="facturasdespachadasexcel.php" method="post">
					<button type="submit" id="export_data" name="exportarCSV" value="Export to excel" class="btn btn-info">Exportar a Excel (CSV)</button>
					<td id="box"> <input name="acceso" type="hidden" id="acceso" size="30" value="<?php echo $acceso ?>"> </td>
					<td id="box"> <input name="bodega" type="hidden" id="bodega" size="30" value="<?php echo $bodega ?>"> </td>
					<td id="box"> <input name="desde" type="hidden" id="desde" size="30" value="<?php echo $desde ?>"> </td>
					<td id="box"> <input name="hasta" type="hidden" id="hasta" size="30" value="<?php echo $hasta ?>"> </td>
					<td id="box"> <input name="tipofecha" type="hidden" id="tipofecha" size="30" value="<?php echo $tipofecha ?>"> </td>
				</form>


			</div>

			<div class="table-responsive">
				<table id="TABLA" class="table table-striped nowrap">

				</table>
			</div>
		</div>
	<?php

			$_SESSION['usuario'] = $usuario1;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['nomsuc'] = $nomsuc;
		} else {
			header("location: index.html");
		}

	?>
	</div>
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.8/b-3.0.2/b-html5-3.0.2/datatables.min.css" rel="stylesheet">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.8/b-3.0.2/b-html5-3.0.2/datatables.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>

	<script>
		let data = '<?php echo json_encode($res) ?>';
		data = JSON.parse(data);
		console.log('data: ', data);


		if (data.length > 0) {
			Tabla(data);
		}

		function Tabla(data) {
			let table_Ingresos = $('#TABLA').DataTable({
				destroy: true,
				data: data,
				dom: 'Bfrtip',
				paging: true,
				"pageLength": 100,
				buttons: [
					'excel'
				],

				columns: [{
					data: "codbodega",
					title: "BODEGA",
				}, {
					data: "Detalle",
					title: "CLIENTE",
				}, {
					data: "secuencia",
					title: "SECUENCIA",
				}, {
					data: "fecha",
					title: "FECHA",
					render: function(x) {
						if (x == null) {
							x = '';
						} else {
							x = `
						<span>` + moment(x).format("YYYY-MM-DD") + `</span><br>
						<span>` + moment(x).format("hh:mm") + `</span><br>
						`
						}

						return x;
					}
				}, {
					data: "tporiginal",
					title: "TP ORIGINAL",
				}, {
					data: "tpedido",
					title: "TIPO PEDIDO",
				}, {
					data: "nombodega",
					title: "BODEGA. FACT",
				}, {
					data: "prepapor",
					title: "PREPA. POR",
				}, {
					data: "verifpor",
					title: "VERIF. POR",
				}, {
					data: "fverif",
					title: "FECHA VERIF.",
					render: function(x) {
						if (x == null) {
							x = '';
						} else {
							x = `
						<span>` + moment(x).format("YYYY-MM-DD") + `</span><br>
						<span>` + moment(x).format("hh:mm") + `</span><br>
						`
						}

						return x;
					}
				}, {
					data: "guiapor",
					title: "GUIA POR.",
				}, {
					data: "fguia",
					title: "FECHA GUIA.",
					render: function(x) {
						if (x == null) {
							x = '';
						} else {
							x = `
						<span>` + moment(x).format("YYYY-MM-DD") + `</span><br>
						<span>` + moment(x).format("hh:mm") + `</span><br>
						`
						}

						return x;
					}
				}, {
					data: "guia",
					title: "GUIA",
				}, {
					data: "BULTOS",
					title: "BULTOS",
				}, {
					data: "trans",
					title: "TRANSPORTE",
				}, {
					data: "entrepor",
					title: "EMBAR. POR",
				}, {
					data: "fdesp",
					title: "FECHA DESPACHO",
					render: function(x) {
						if (x == null) {
							x = '';
						} else {
							x = `
						<span>` + moment(x).format("YYYY-MM-DD") + `</span><br>
						<span>` + moment(x).format("hh:mm") + `</span><br>
						`
						}

						return x;
					}
				}, {
					data: "fvehi",
					title: "FECHA E.VEHICULO",
					render: function(x) {
						if (x == null) {
							x = '';
						} else {
							x = `
						<span>` + moment(x).format("YYYY-MM-DD") + `</span><br>
						<span>` + moment(x).format("hh:mm") + `</span><br>
						`
						}

						return x;
					}
				}, {
					data: "ciudad",
					title: "CIUDAD",
				}, {
					data: "ESTADO_DESPACHO",
					title: "ESTADO COURIER",
				}, {
					data: "FECHA_DESPACHO",
					title: "FECHA COURIER",
				}, {
					data: "HORA_DESPACHO",
					title: "HORA COURIER",
				}, {
					data: "PESO",
					title: "PESO",
					render: function(x) {
						x = parseFloat(x);
						return x;
					}
				}],
				"createdRow": function(row, data, index) {
					// $('td', row).eq(4).addClass('text-center');
					// $('td', row).eq(0).addClass('fw-bold fs-6');
					// $('td', row).eq(2).addClass('fw-bold fs-6 ');
					// $('td', row).eq(3).addClass('fw-bold fs-6');
					// $('td', row).eq(4).addClass('fw-bold fs-6 ');
					// $('td', row).eq(5).addClass('fw-bold fs-6 bg-light-info');
					// $('td', row).eq(6).addClass('fw-bold fs-6 ');
					// $('td', row).eq(7).addClass('fw-bold fs-6 ');

					for (let index = 0; index < 30; index++) {
						$('td', row).eq(index).addClass('fw-bold fs-7');
					}

					$('td', row).eq(1).addClass('fw-bold fs-6 bg-light-warning');

				}
			});
		}
	</script>
</body>