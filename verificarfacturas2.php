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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

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
			// echo $bodegaFAC;
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
			<h3 class="mt-4">Mienstras realiza el proceso de series, no recargar la pagina</h3>

		</div>
		<div class="container mt-4">
			<div class="row justify-content-center">
				<div class="col-md-8">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">Detalles de la Factura</h5>
							<table class="table">
								<tr>
									<td><strong>Id:</strong></td>
									<td><?php echo $idfac ?></td>
									<td><strong>Numero:</strong></td>
									<td><?php echo $numfac ?></td>
									<td><strong>Factura #:</strong></td>
									<td><?php echo $secuencia ?></td>
								</tr>
								<tr>
									<td><strong>Cliente:</strong></td>
									<td colspan="2"><?php echo $cliente ?></td>
									<td><strong>Ruc:</strong></td>
									<td colspan="2"><?php echo $Ruc ?></td>
								</tr>
								<tr>
									<td><strong>Vendedor:</strong></td>
									<td colspan="5"><?php echo $Vendedor ?></td>
								</tr>
								<tr>
									<td><strong>Fecha y Hora de Creación:</strong></td>
									<td colspan="5"><?php echo $Fecha ?></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="cuerpo2">
			<p style="font-weight: bold" class="fs-5" align="center">Solo Código :
				<input onkeydown="handleKeyPress2(event)" name="codigo" type="text" id="codigo" size="50" value="<?php $codigoleido ?>">
				<input name="factura" type="hidden" id="factura" value="<?php echo $numerorecibido ?>">
				<button class="btn btn-success" onclick="Ingresar_codigo()">Ingresar</button>
				<!-- <h4 style="text-align:center">
				<input type="button" onclick="window.location.href='verificarfacturas.php'" value="Regresar" style="text-decoration:none; background:none; border:none; color:blue; cursor:pointer; margin-right: 5cm;">
			</h4> -->
			</p>
			<div id="leyenda">

			</div>
			<div id="SECC_SERIES" style="display: none;">
				<h4 id="SER_CODIGO"></h4>
				<h6>serie:</h6>
				<input type="text" class="fs-6" id="SERIE_P" onkeydown="handleKeyPress(event)">
				<button class="btn btn-primary btn-sm" onclick="Ingresar_serie()">
					<i class="bi bi-cloud-upload"></i> >>
				</button>

			</div>
			<div id="leyenda_serie" class="text-danger fs-4 mt-4">

			</div>
		</div>

		<div id="Cuerpo2">
			<div id="label">
				<h3><strong> Unidades por verificar:</strong>
					<span id="UNIDADES_POR_VERIFICAR"></span>
				</h3>
			</div>
		</div>
		<div id="SECC_FINAL" class="mt-5 text-center" style="display: none;">

			<button onclick="GUARDAR_SERIES()" class="btn btn-success fs-3">FACTURA COMPLETA presione aquí para CONTINUAR</button>

		</div>

		<div class="table-responsive p-5">
			<table id="DETALLE" class="table">

			</table>
		</div>



		<div id="Cuerpo2">
			<!-- <p style="font-weight: bold" align="center">
				<?php
				if ($itemsporleer == 0) {
					$_SESSION['datosarreglos'] = $arreglo; // Grabo otra vez el arreglo en memoria
				?> -->
			<!-- <form action="verificarfacturas3.php" method="post">
				<p style="font-weight: bold" align="center">
					<input name="factura" type="hidden" id="factura" value="<?php echo $numerorecibido ?>">
					<input type="submit" name="submit" id="submit" value="FACTURA COMPLETA presione aquí para CONTINUAR">
				</p>
			</form> -->
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
				$_SESSION['arregloseries'] = $arregloseries
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

<link href="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/b-html5-2.4.2/rr-1.4.1/datatables.min.css" rel="stylesheet">

<script src="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/b-html5-2.4.2/rr-1.4.1/datatables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<link href="assets/freeze/freeze-ui.min.css" type="text/css" rel="stylesheet" />
<script src="assets/freeze/freeze-ui.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	var ARRAY_CODIGOS = []
	var ARRAY_DATOS_FACTURA = []
	var ARRAY_SERIES_INSERTADAS = []
	var POR_VERIFICAR = undefined;
	var PRODUCTO_ID = "";

	function Cargar_Detalle_Factura() {
		let param = {
			Cargar_detalle_factura: 1,
			secuencia: '<?php echo $secuencia ?>',
			bodega: '<?php echo $bodegaFAC ?>'
		}


		AjaxSend(param, function(x) {
			console.log('x: ', x);


			POR_VERIFICAR = 0;
			let unique = [...new Set(x.map(item => (item.code).trim()))];
			ARRAY_CODIGOS = unique;
			ARRAY_DATOS_FACTURA = x;

			x.map(function(b) {
				POR_VERIFICAR = POR_VERIFICAR + parseInt(b.cantidad);
				$("#UNIDADES_POR_VERIFICAR").text(POR_VERIFICAR);
			})

			tabla_detalle_factura(x)
		});

	}

	Cargar_Detalle_Factura();

	function tabla_detalle_factura(respuesta) {
		let tabla = $('#DETALLE').DataTable({
			destroy: true,
			data: respuesta,
			dom: "rtip",
			paging: false,
			info: false,
			columns: [{
					data: "code",
					title: "CODIGO",
				},
				{
					data: "nombre",
					title: "DESCRIPCION",
				},
				{
					data: "cantidad",
					title: "CANTIDAD",
					// className: "text-center" // Centrar la columna "Tipo"
				},
				{
					data: "INGRESADO",
					title: "INGRESADO",
					// className: "text-center" // Centrar la columna "Tipo"

				}
			],
			"createdRow": function(row, data, index) {

				$('td', row).eq(0).addClass("text-dark fs-5 fw-bolder text-left ");
				$('td', row).eq(1).addClass("text-dark fs-5 fw-bolder text-left");
				$('td', row).eq(2).addClass("text-dark fw-bolder ");
				$('td', row).eq(3).addClass("text-dark fw-bolder  text-left");
				if (parseInt(data["INGRESADO"]) == parseInt(data["cantidad"])) {
					$('td', row).eq(0).addClass("bg-success bg-opacity-10");
					$('td', row).eq(1).addClass("bg-success bg-opacity-10");
					$('td', row).eq(2).addClass("bg-success bg-opacity-10");
					$('td', row).eq(3).addClass("bg-success bg-opacity-10");
				} else {
					$('td', row).eq(0).addClass("bg-danger bg-opacity-10");
					$('td', row).eq(1).addClass("bg-danger bg-opacity-10");
					$('td', row).eq(2).addClass("bg-danger bg-opacity-10");
					$('td', row).eq(3).addClass("bg-danger bg-opacity-10");
				}
			},
		});

	}

	function Ingresar_codigo() {
		let codigo = $("#codigo").val();
		// let serie = $("#codigo").val();
		let leyenda = $("#leyenda");
		leyenda.text("");

		if (codigo == "") {
			leyenda.text("NO PUEDE INGRESAR VACIO");
		} else {

			if (ARRAY_CODIGOS.includes(codigo.trim())) {

				let VAL = ARRAY_DATOS_FACTURA.filter(item => (item.code).trim() == codigo.trim())[0]
				console.log('VAL: ', VAL);
				let SERIE_ENTRADA = VAL["RSeriesEnt"];
				let REGISTRO_SERIE = VAL["serie"];
				PRODUCTO_ID = VAL["productoid"]

				if (SERIE_ENTRADA == 1 || REGISTRO_SERIE == 1) {
					$("#SER_CODIGO").text(codigo);
					$("#SECC_SERIES").show(100);
					$("#codigo").val("");
					$("#SERIE_P").focus();

				} else {
					Ingresar_por_codigo(VAL)
					$("#SECC_SERIES").hide();

				}

				// leyenda.text("CODIGO LEIDO");

			} else {
				leyenda.text("EL CODIGO INGRESADO NO SE ENCUENTRA EN LA FACTURA");
			}
		}
	}

	function Ingresar_serie() {
		let serie = $("#SERIE_P").val();
		let factura = $("#factura").val();
		let leyenda = $("#leyenda_serie");

		let param = {
			Ingreso_serie: 1,
			serie: serie,
			factura: factura,
			productoid: PRODUCTO_ID,
			creado_por: '<?php echo $usuario ?>'
		}

		if (serie == "") {
			leyenda.text("INGRESE NUMERO DE SERIE");
			$("#SERIE_P").focus();

		} else {
			AjaxSend(param, function(x) {
				console.log('x: ', x);
				leyenda.text("");

				if (x[0] == 0) {
					leyenda.text(x[2]);
					$("#SERIE_P").val("");
					$("#SERIE_P").focus();
				} else {
					if (x[2] == "VENDIDA") {
						leyenda.text("SERIE VENDIDA EN FACT# " + x[1][0]["Secuencia"] + " SI LA FACTURA TIENE NOTA DE CREDITO, DEVOLVERLA PARA CONTINUAR");
						$("#SERIE_P").val("");
						$("#SERIE_P").focus();
					} else {
						if (x[1].length > 0) {
							INSERTAR_SERIE_TABLA(x[1][0])
						} else {
							leyenda.text("ERROR AL BUSCAR LA SERIE");
							$("#SERIE_P").val("");
							$("#SERIE_P").focus();
						}
					}
				}
			});
		}


	}

	function Ingresar_por_codigo(datos) {
		console.log('datos: ', datos);
		let leyenda = $("#leyenda_serie");
		leyenda.text("");
		let CODIGO_FILTRADO = ARRAY_DATOS_FACTURA.filter(item => item.productoid == datos["productoid"])
		let cantidad_por_ingresar = parseInt(CODIGO_FILTRADO[0]["cantidad"])
		let cantidad_series_ingresadas = (ARRAY_SERIES_INSERTADAS.filter(item => item.productoid == datos["productoid"])).length

		if (cantidad_series_ingresadas == cantidad_por_ingresar) {
			leyenda.text("YA SE INGRESO EL TOTAL DE SERIES PARA ESTE CODIGO");
			$("#codigo").val("");
			$("#codigo").focus();

		} else {

			let b = {
				productoid: datos["productoid"],
				serie: "",
				serie_ent: 0
			}
			console.log('param: ', b);

			ARRAY_SERIES_INSERTADAS.push(b);
			SUMAR_INSERTADAS(datos["productoid"])
			$("#codigo").val("");
			$("#codigo").focus();
		}

		if (POR_VERIFICAR == 0) {
			$("#SECC_FINAL").show(100)
		}
	}

	function INSERTAR_SERIE_TABLA(datos) {
		let leyenda = $("#leyenda_serie");

		let CODIGO_FILTRADO = ARRAY_DATOS_FACTURA.filter(item => item.productoid == datos["productoid"])

		let cantidad_por_ingresar = parseInt(CODIGO_FILTRADO[0]["cantidad"])

		let cantidad_series_ingresadas = (ARRAY_SERIES_INSERTADAS.filter(item => item.productoid == datos["productoid"])).length

		if (cantidad_series_ingresadas == cantidad_por_ingresar) {
			leyenda.text("YA SE INGRESO EL TOTAL DE SERIES PARA ESTE CODIGO");
			$("#SECC_SERIES").hide();
			$("#SERIE_P").val("");
			$("#SER_CODIGO").focus();
		} else {

			let b = {
				productoid: datos["productoid"],
				serie: datos["serie"],
				serie_ent: 1
			}

			if (ARRAY_SERIES_INSERTADAS.length == 0) {
				leyenda.text("SERIE INGRESADA CON EXITO")
				ARRAY_SERIES_INSERTADAS.push(b);
				SUMAR_INSERTADAS(datos["productoid"])
				$("#SERIE_P").val("");
				$("#SERIE_P").focus();

			} else {

				let containsValue = ARRAY_SERIES_INSERTADAS.some(obj => obj.productoid == datos["productoid"] && obj.serie == datos["serie"]);
				if (containsValue) {
					leyenda.text("SERIE YA INGRESADA");
					$("#SERIE_P").val("");
					$("#SERIE_P").focus();
				} else {
					ARRAY_SERIES_INSERTADAS.push(b)
					leyenda.text("SERIE INGRESADA CON EXITO");
					SUMAR_INSERTADAS(datos["productoid"])
					$("#SERIE_P").val("");
					$("#SERIE_P").focus();

				}

			}

			let cantidad_series_ = (ARRAY_SERIES_INSERTADAS.filter(item => item.productoid == datos["productoid"])).length
			console.log('cantidad_series_ingresadas: ', cantidad_series_ingresadas);
			if (cantidad_series_ == cantidad_por_ingresar) {
				$("#SERIE_P").val("");
				$("#codigo").focus();
				$("#SECC_SERIES").hide();
			}

		}

		if (POR_VERIFICAR == 0) {
			$("#SECC_FINAL").show(100)
		}
	}

	function SUMAR_INSERTADAS(ID) {
		let INGRESADOS = 0;
		let CANTIDADES = 0;
		ARRAY_DATOS_FACTURA.map(function(x) {
			if (x.productoid == ID) {
				x.INGRESADO = parseInt(x.INGRESADO) + 1
			}
			CANTIDADES = CANTIDADES + parseInt(x.cantidad)
			INGRESADOS = INGRESADOS + parseInt(x.INGRESADO)
		})
		tabla_detalle_factura(ARRAY_DATOS_FACTURA);
		POR_VERIFICAR = CANTIDADES - INGRESADOS
		$("#UNIDADES_POR_VERIFICAR").text(POR_VERIFICAR);

	}

	function handleKeyPress2(event) {
		// Check if the pressed key is Enter (key code 13)
		if (event.key === 'Enter') {
			// Perform your action here
			// alert('Enter key pressed!');
			Ingresar_codigo()
		}
	}

	function handleKeyPress(event) {
		// Check if the pressed key is Enter (key code 13)
		if (event.key === 'Enter') {
			// Perform your action here
			// alert('Enter key pressed!');
			Ingresar_serie()
		}
	}

	function GUARDAR_SERIES() {
		let factura = $("#factura").val();

		if (ARRAY_SERIES_INSERTADAS.length == 0) {

		} else {
			let param = {
				Completar_Factura: 1,
				DATOS: ARRAY_SERIES_INSERTADAS.filter(item => item.serie_ent == 1),
				CLIENTE: '<?php echo $cliente ?>',
				CREADO_POR: '<?php echo $usuario ?>',
				FACTURA: factura,
				BODEGA_FAC: '<?php echo $bodegaFAC ?>',
			}

			console.log('param: ', param);

			AjaxSend(param, function(x) {
				console.log('x: ', x);
				if (x[0] == 1) {
					let cab = x[1][0]
					let det = x[2][0]
					let fac = x[3][0]

					if (cab == 1 && det == 1 && fac == 1) {
						Swal.fire({
							title: "Datos Guardados",
							text: "",
							icon: "success"
						});
						$("#SECC_FINAL").hide()
						setTimeout(() => {
							window.history.back();
						}, 1000);
					} else {
						Swal.fire({
							title: "Error al guardar",
							text: "Intentelo nuevamente",
							icon: "error"
						});
					}

				} else {
					Swal.fire({
						title: "Error al guardar",
						text: "Intentelo nuevamente",
						icon: "error"
					});
				}

			})
		}




	}

	function AjaxSend(param, callback) {
		FreezeUI({
			text: 'Cargando'
		});
		$.ajax({
			data: param,
			datatype: 'json',
			url: 'verificarfacturas2_f.php',
			type: 'POST',
			success: function(x) {
				x = JSON.parse(x)
				UnFreezeUI();
				callback(x)
			}
		})
	}
</script>


</html>