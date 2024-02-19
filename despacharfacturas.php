<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
	function setfocus() {
		document.getElementById("secu").focus();
	}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/tablas.css">
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

<body onload="setfocus()">
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
			echo $nomsuc;
			if ($base == 'CARTIMEX') {
				require 'headcarti.php';
			} else {
				require 'headcompu.php';
			}
			date_default_timezone_set('America/Guayaquil');
			$fecha = date("yy-m-d", time());
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;
			//echo "BOdega". $bodega; 
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Despachar Facturas <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">

			<div>
				<form name="formfactura" action="despacharfacturas0.php" method="POST" width="75%">
					<table align="center">

						<tr>

							<td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>

							<a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
							</td>
						</tr>
					</table>
				</form>
			</div>


			<div class=\"table-responsive-xs\">

			</div>
		</div>

		<div class="col-12">
			<div class="col-4">
				<h4>MEDIO</h4>
				<select onchange="filtrar()" name="" id="filtro" class="form-select">
					<option value="1">TODO</option>
					<option value="2">PICK UP</option>
					<option value="3">ENVIO</option>
				</select>
			</div>
			<div class="table-responsive mt-3">
				<table id="Tabla_Guias" class="table ">

				</table>
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
<link href="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/b-html5-2.4.2/rr-1.4.1/datatables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">

<script src="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/b-html5-2.4.2/rr-1.4.1/datatables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

<link href="assets/freeze/freeze-ui.min.css" type="text/css" rel="stylesheet" />
<script src="assets/freeze/freeze-ui.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	var TABLA_DES;
	var ARREGLO_DATOS = [];

	function asd() {
		console.log("aaaaaaaaaaaaaaaaaaaaaaaa");
		FreezeUI({
			text: 'Cargando'
		});
		Cargar_Despachos();

	}

	function Cargar_Despachos() {
		let acceso = '<?php echo $acceso ?>';
		let sucursal = '<?php echo $nomsuc ?>';
		// sucursal = "COMPUTRON QUICENTRO 2"
		if (sucursal == "COMPUTRON EL DORADO") {
			sucursal = "RIOCENTRO EL DORADO"
		}
		//if(sucursal == "COMPUTRON EL DORADO")
		sucursal = sucursal.toUpperCase();
		sucursal = sucursal.replace("COMPUTRON", "")

		sucursal = sucursal.trim()
		let para = {
			"Cargar_guias": 1,
			acceso: '<?php echo $acceso ?>',
		}
		FreezeUI({
			text: 'Cargando'
		});

		AjaxSend(para, function(x) {
			console.log('x: ', x);

			// x.map(function(x) {
			// 	let SISCO = x.SISCO;
			// 	if (SISCO.length > 0) {
			// 		x.BODEGA_RETIRO = SISCO[0]["bodegaret"]
			// 		x.MEDIO = SISCO[0]["pickup"]
			// 	} else {
			// 		// if(x.TIPO_DATOS == 'DROP'){

			// 		// }else{

			// 		// }

			// 	}
			// })
			let data_filtrada;
			if (acceso == 1) {
				data_filtrada = x;
			} else {
				data_filtrada = x.filter(function(x) {
					let b = (x.BODEGA_RETIRO)
					if (b != null) {
						if (b.trim() != "") {
							b = b.toUpperCase()
							b = b.trim()
							if (sucursal == b) {

								return x;
							}
						}
					}
				});
			}

			let ARRAY_ = []
			let unique = [...new Set(data_filtrada.map(item => item.id))];
			unique.map(function(ob) {
				let fil = data_filtrada.filter(i => i.id == ob);
				let nombodegas = [];
				let TRANSPORTE = [];
				let ESTADO_FACTURAS_LISTAS = 0;
				fil.map(function(y) {
					TRANSPORTE.push(y.TRANSPORTE)
					let multi = y.MULTI_DATA
					let f = multi.filter(i => i.Section != 'HEADER')

					f.map(function(m) {
						if (m.ESTADO_FACTURAS_LISTAS == "INGRESADAGUIA" ||
							m.ESTADO_FACTURAS_LISTAS == "RECIBIDAOTRATIENDA" ||
							m.ESTADO_FACTURAS_LISTAS == "DESPACHADA") {

						} else {
							ESTADO_FACTURAS_LISTAS = ESTADO_FACTURAS_LISTAS + 1;
						}

						nombodegas.push(m.bonom);
					})
					// multi.map(function(a) {
					// 	let f = multi.filter(i => i.Section != 'HEADER')


					// })
				})
				let b = {
					BODEGA_RETIRO: fil[0]["BODEGA_RETIRO"],
					BodegaFAC: fil[0]["BodegaFAC"],
					Detalle: fil[0]["Detalle"],
					MEDIO: fil[0]["MEDIO"],
					MULTI: fil[0]["MULTI"],
					MULTI_DATA: fil[0]["MULTI_DATA"],
					Ruc: fil[0]["Ruc"],
					SRI_EM1: fil[0]["SRI_EM1"],
					Sucursal: fil[0]["Sucursal"],
					TIPO_DATOS: fil[0]["TIPO_DATOS"],
					TOTAL: fil[0]["TOTAL"],
					TRANSPORTE: TRANSPORTE,
					TT1: fil[0]["TT1"],
					bodegsuc: fil[0]["bodegsuc"],
					estado: fil[0]["estado"],
					estado2: fil[0]["estado2"],
					fecha: fil[0]["fecha"],
					id: fil[0]["id"],
					localdireccion: fil[0]["localdireccion"],
					nombodega: nombodegas,
					rete: fil[0]["rete"],
					saldo: fil[0]["saldo"],
					secuencia: fil[0]["secuencia"],
					tipo: fil[0]["tipo"],
					ESTADO_FACTURAS_LISTAS: ESTADO_FACTURAS_LISTAS
				}

				ARRAY_.push(b);

			})




			// 
			Tabla_Despachos(ARRAY_);


			ARREGLO_DATOS = ARRAY_;
		});
	}

	Cargar_Despachos();

	function Tabla_Despachos(data) {

		$('#Tabla_Guias').empty()
		TABLA_DES = $('#Tabla_Guias').DataTable({
			destroy: true,
			data: data,
			dom: 'Bfrtip',
			// responsive: true,
			deferRender: true,
			buttons: [{
				extend: 'excelHtml5',
				title: "Excel",
			}, ],
			columnDefs: [{
				orderable: false,
				className: 'select-checkbox',
				targets: 12
			}],
			select: {
				"style": "multi",
				// selector: 'td:first-child'
			},
			buttons: [{
				text: `<span class"fw-bold">Despachar Marcados</span>`,
				className: 'btn bg-primary fw-bold text-light fs-5',
				action: function(e, dt, node, config) {
					Despachar();
				}
			}, {
				text: 'Seleccionar todos',
				className: 'btn fw-bold',
				action: function() {
					TABLA_DES.rows({
						page: 'current'
					}).select();
				}
			}, {
				text: 'cancelar seleccionados',
				className: 'btn fw-bold',
				action: function() {
					TABLA_DES.rows({
						page: 'current'
					}).deselect();
				}
			}, {
				text: 'Refrescar',
				className: 'btn fw-bold btn_refresh',
				action: function() {
					Cargar_Despachos();
				}
			}, {
				"extend": 'excelHtml5',
				"text": '<button class="btn btn-success fs-">EXCEL</button>',
				"footer": true,
			}],
			// scrollY: '30vh',
			// scrollCollapse: true,
			// paging: false,
			// info: false,
			"order": [
				[3, "asc"]
			],
			columns: [{
					data: "Sucursal",
					title: "SID",
					render: function(x, y, r) {
						x = `
								<a target="_blank"  href="trakingdesp.php?secu=` + r.secuencia + `&bodegaFAC=` + r.BodegaFAC + `">` + r.Sucursal + `</a>
							`
						return x;
					}
				},


				{
					data: "Ruc",
					title: "CLIENTE",
					visible: false
					// render: $.fn.dataTable.render.number(',', '.', 2, "$"),
				},
				{
					data: "Detalle",
					title: "CLIENTE",
					// render: $.fn.dataTable.render.number(',', '.', 2, "$"),
				},
				{
					data: "secuencia",
					title: "FACTURA",
					render: function(x, y, r) {
						x = `
								<a target="_blank" href="mod1.php?sec=` + r.secuencia + `">` + r.secuencia + `</a> 
								`
						return x
					}
				},
				{
					data: "fecha",
					title: "FECHA",
				},
				{
					data: "saldo",
					title: "SALDO",
					render: $.fn.dataTable.render.number(',', '.', 2, "$"),
				},
				{
					data: "nombodega",
					title: "BODEGA DESPACHO",
					render: function(x, t) {

						x = [...new Set(x)];

						let a = "";

						x.map(function(y) {
							if (t == "display") {
								a = a + "<span>" + y + "</span><br>"
							} else {
								a = a + y + ","
							}
						})
						return a
					}
				},
				{
					data: "estado",
					title: "ESTADO",
					visible: false
				},
				//355204450563746
				{
					data: "BODEGA_RETIRO",
					title: "BODEGA RETIRO",
				},
				{
					data: "MULTI",
					title: "MULTIBODEGA",
					render: function(x) {
						if (parseInt(x) > 0) {
							x = "SI"
						} else {
							x = "NO"
						}
						return x;
					}
				},
				{
					data: "MEDIO",
					title: "MEDIO",
					render: function(x, y, r) {
						if (r.TIPO_DATOS == "DROP") {
							if (x == 1 || x == 2 || x == null) {
								x = "PICK UP"
							} else {
								x = "ENVIO"
							}
						} else {
							if (x == 1) {
								x = "PICK UP"
							} else {
								x = "ENVIO"
							}
						}
						return x;
					}
				},
				{
					data: "TRANSPORTE",
					title: "TRANSPORTE",
					render: function(x, t) {

						let a = "";

						x.map(function(y) {
							if (t == "display") {
								a = a + "<span>" + y + "</span><br>"
							} else {
								a = a + y + ","
							}
						})
						return a
					}
				},
				{
					data: null,
					title: "",
					render: function(x) {
						return x = ""
					}
				}

			],
			"createdRow": function(row, data, index) {



				for (let i = 0; i < 10; i++) {
					if (data["ESTADO_FACTURAS_LISTAS"] > 0) {
						$('td', row).eq(i).addClass("fs-6 fw-bolder bg-danger bg-opacity-10");
					} else {
						$('td', row).eq(i).addClass("fs-6 fw-bolder");

					}
				}
				$('td', row).eq(6).addClass("bg-warning bg-opacity-10");
				if (parseInt(data["MULTI"]) > 0) {
					$('td', row).eq(7).addClass("text-primary");
				}

				let col1 = `
						<span class="text-muted">cedula: ` + data["Ruc"] + `</span><br>
						<span>` + data["Detalle"] + `</span>
					`;
				$('td', row).eq(1).html(col1);
				//$('td', row).eq(6).html(data["SISCO"][0]["bodegaret"]);




			},

		});
		setTimeout(function() {
			$($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
		}, 500);
	}

	function Despachar() {
		var rows_selected = TABLA_DES.rows('.selected').data().toArray();


		if (rows_selected.length > 0) {

			let filter = rows_selected.filter(i => i.ESTADO_FACTURAS_LISTAS == 0)


			// if (rows_selected.length > filter.length) {
			// 	Swal.fire({
			// 		title: "Hay facturas que no estan completas",
			// 		text: "Solo se guardaran las que esten completas",
			// 		icon: "info"
			// 	});
			// }

			let param = {
				Despachar: 1,
				DATOS: filter,
				acceso: '<?php echo $acceso ?>',
				usuario: '<?php echo $usuario ?>',
			}

			console.log('param: ', param);

			Swal.fire({
				title: "Se guardaran lo cambios?",
				showDenyButton: true,
				showCancelButton: false,
				confirmButtonText: "Continuar",
				denyButtonText: `Cancelar`
			}).then((result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					AjaxSend2(param, function(x) {
						console.log('x: ', x);
						if (x[0] == 1) {
							asd();
							// $(".btn_refresh").click();
							Swal.fire({
								title: "Los datos se marcaron correctamente",
								text: "",
								icon: "success"
							});
						} else {
							Swal.fire({
								title: "Error, al guardar",
								text: "Intentelo nuevamente",
								icon: "danger"
							});
							UnFreezeUI();
						}
					});
				} else if (result.isDenied) {
					// Swal.fire("Changes are not saved", "", "info");
				}
			});


		}
	}

	function filtrar() {
		let filtro = $("#filtro").val();
		let datafiltrada = []
		if (filtro == 1) {
			datafiltrada = ARREGLO_DATOS
		} else if (filtro == 2) {
			datafiltrada = ARREGLO_DATOS.filter(i => i.MEDIO == 1)
		} else if (filtro == 3) {
			datafiltrada = ARREGLO_DATOS.filter(i => i.MEDIO == 0)
		}
		Tabla_Despachos(datafiltrada)
	}

	function AjaxSend(param, callback) {
		FreezeUI({
			text: 'Cargando'
		});
		$.ajax({
			data: param,
			datatype: 'json',
			url: 'despacharfacturas_f.php',
			type: 'POST',
			success: function(x) {
				x = JSON.parse(x)
				UnFreezeUI();
				callback(x)
			}
		})
	}

	function AjaxSend2(param, callback) {
		FreezeUI({
			text: 'Cargando'
		});
		$.ajax({
			data: param,
			datatype: 'json',
			url: 'despacharfacturas_f.php',
			type: 'POST',
			success: function(x) {
				x = JSON.parse(x)
				// UnFreezeUI();
				callback(x)
			}
		})
	}
</script>

</html>