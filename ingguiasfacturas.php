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
			$BODEGA = strval($bodega);
			$drop = $_SESSION['drop'];
			$drop_gye = $_SESSION['drop_gye'];
			$drop_uio = $_SESSION['drop_uio'];

			$nomsuc = $_SESSION['nomsuc'];
			if ($base == 'CARTIMEX') {
				require 'headcarti.php';
			} else {
				require 'headcompu.php';
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
					<input hidden id="BODEGA" type="text" value="<?php echo $BODEGA ?>">
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">

			<div style="display: none;">
				<form name="formfactura" action="ingguiasfacturas0.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<td id="label">Secuencia </td>
							<td id="box"> <input name="secu" type="text" id="secu" size="30" value=""> </td>
							<td id="box"> <input name="bodega" type="hidden" id="bodega" value="<?php echo trim($bodega) ?>"> </td>
						</tr>
						<tr>

							<td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>
							<td id="label"> Enviar
								<input name="submit" id="submit" value="Grabar" src="assets\img\lupa.png" type="image">
								<a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
							</td>
						</tr>
					</table>
				</form>
			</div>


			<div class=\"table-responsive-xs\">

				<table id="Tabla_Guias" class="table table-striped">

				</table>

			</div>
			<a align="center"> Se activa para ingresar la guia cuando: <br>
				1.) El saldo de la factura sea <strong>$0.00 </strong>
				y el estado en el SISCO sea <strong>FACTURADO </strong><br>
				2.) La forma de pago sea <strong> Tienda </strong>
				siempre y cuando el <strong> Pick Up </strong> sea en una Sucursal distinta a la de facturacion.<br>
				Ej.: Factura de Kennedy pero <strong> cancela y retira </strong> en Manta <br>
				3.) El estado sea <strong> FACTURADO </strong>
				y el saldo de la factura sea un valor aproximado al de la <strong> Retencion </strong><br></a>

		</div>

	<?php
			// echo $BODEGA;
			// $_SESSION['usuario'] = $usuario;
			// $_SESSION['base'] = $base;
			// $_SESSION['acceso'] = $acceso;
			// $_SESSION['bodega'] = $bodega;
			// $_SESSION['nomsuc'] = $nomsuc;
		} else {
			header("location: index.html");
		}

	?>
	</div>
	<link href="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/cr-1.7.0/r-2.5.0/datatables.min.css" rel="stylesheet">

	<script src="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/cr-1.7.0/r-2.5.0/datatables.min.js"></script>
	<link href="assets/freeze/freeze-ui.min.css" type="text/css" rel="stylesheet" />
	<script src="assets/freeze/freeze-ui.min.js" type="text/javascript"></script>

	<script>



		function Cargar_guias() {
			let BODEGA = $("#BODEGA").val()

			let para = {
				"Cargar_guias": 1,
				bodega: BODEGA,
				acceso: <?php echo $acceso ?>,
				drop: <?php echo $drop ?>,
				drop_gye: <?php echo $drop_gye ?>,
				drop_uio: <?php echo $drop_uio ?>,
			}
			console.log('para: ', para);

			AjaxSend(para, function(x) {

				x.map(function(x) {
					let param = {
						Cargar_guias_sisco: 1,
						secuencia: x.secuencia
					}
					x.COMENTARIO = ""

					let param2 = {
						Cargar_Multi: 1,
						secuencia: x.secuencia,
						bodega: x.BodegaFAC
					}

					AjaxSend(param2,function(ob){
						let d = ob.filter(item => item.Section != "HEADER");
						d = d.filter(item => item.MULTI == "MULTI")
						x.MULTI = d.length;
					})

					AjaxSend(param, function(obj) {
						// console.log('obj: ', obj);
						x.SISCO = obj
						x.FORMA_PAGO = 0
						x.COMENTARIO = ""

						if (obj.length > 0) {
							x.ACTIVAR_LINK = 0
							x.medio = ""
							x.sucufact = ""
							x.FORMA_PAGO = obj[0]["formapago"]
							x.estado = obj[0]["estado"]
							x.BODEGA_RETIRO = obj[0]["bodegaret"]
							x.COMENTARIO = obj[0]["comentarios"]
							if (obj[0]["pickup"] == 1) {
								x.medio = "PICK UP"
							} else {
								x.medio = "ENVIO"
							}

							if ((obj[0]["estado"] == "Facturado" || obj[0]["estado"] == "Entrega en " + obj[0]["bodegaret"]) &&
								(parseFloat(x.saldo) == 0)) {
								x.ACTIVAR_LINK = 1
								x.sucufact = x.bodegsuc
							}
							if (x.Sucursal == "72") {
								x.ACTIVAR_LINK = 1
							}
							if ((obj[0]["formapago"] == "Tienda" && x.bodegsuc != obj[0]["sucursal"]) &&
								obj[0]["sucursal"] != "") {
								x.ACTIVAR_LINK = 1
							}

							if (((x.saldo) <= (x.rete)) && (obj[0]["estado"] == "Facturado" ||
									obj[0]["estado"] == "Entrega en " + obj[0]["bodegaret"])) {
								x.ACTIVAR_LINK = 1
							}
						} else {
							x.ACTIVAR_LINK = 0
							x.medio = ""
							x.sucufact = ""
							x.FORMA_PAGO = ""
							x.BODEGA_RETIRO = ""
							x.COMENTARIO = ""
							if (x.Sucursal == "72") {
								x.ACTIVAR_LINK = 1
							}
							if (x.hasOwnProperty("ESTADO_DROP")) {
								if (x.TIENE_DROP != 0) {
									x.ACTIVAR_LINK = 1
									x.estado = "DROPSHIPPING"
								} else {
									x.ACTIVAR_LINK = 0
									x.estado = 'FALTAN DATOS'
								}
								x.COMENTARIO = x.DROP_DIRECCION + " - " + x.DROP_REFERENCIA
								if (x.DROP_PEDIDO == 1 || x.DROP_PEDIDO == 2) {
									x.medio = 'PICK UP'
									x.BODEGA_RETIRO = x.tienda_retiro_nombre
								} else {
									x.medio = 'ENVIO'
								}
							} else {
								x.estado = "NO SISCO"
							}
						}
					})
				})
				console.log('x: ', x);
				setTimeout(() => {
					UnFreezeUI();

					Tabla(x)
				}, 20000);

			})
		}
		Cargar_guias()

		function Tabla(data) {
			var table = $('#Tabla_Guias').DataTable({
				destroy: true,
				data: data,
				dom: 'frtip',
				// responsive: true,
				deferRender: true,
				buttons: [{
					text: `<span class"fw-bolder">Refrescar </span> <i class="bi bi-arrow-clockwise"></i>`,
					className: 'btn btn-success',
					action: function(e, dt, node, config) {}
				}],
				// scrollY: '30vh',
				// scrollCollapse: true,
				// paging: false,
				// info: false,
				// "order": [
				// 	[7, "asc"]
				// ],
				columns: [{
						data: "Sucursal",
						title: "SID",
						render: function(x, y, r) {

							x = `
								<a href="trakingguia.php?secu=` + r.secuencia + `&bodegaFAC=` + r.BodegaFAC + `">` + r.Sucursal + `</a>

							`
							return x
						}
					},
					{
						data: "secuencia",
						title: "FACTURA",
						render: function(x, y, r) {

							if (r.ACTIVAR_LINK == 1) {
								x = `
								<a href="ingguiasfacturas0.php?secu=` + r.secuencia + `&bodegaFAC=` + r.BodegaFAC + `">` + r.secuencia + `</a> 
								`
							}
							return x
						}
					},

					{
						data: "Detalle",
						title: "CLIENTE",
						// render: $.fn.dataTable.render.number(',', '.', 2, "$"),
					},
					{
						data: "fecha",
						title: "FECHA",

					}, {
						data: "nombodega",
						title: "BODEGA",
					},
					{
						data: "FORMA_PAGO",
						title: "FORMA DE PAGO",

					},
					{
						data: "fecha",
						title: "F. APROBACION",

					},
					{
						data: "estado",
						title: "ESTADO",

					},
					{
						data: "TOTAL",
						title: "V. FACTURADO",
						render: $.fn.dataTable.render.number(',', '.', 2, "$"),

					},
					{
						data: "saldo",
						title: "POR CANCELAR",
						render: $.fn.dataTable.render.number(',', '.', 2, "$"),

					}, {
						data: "MULTI",
						title: "MULTIBODEGA",
					}, 
					{
						data: "medio",
						title: "ENTREGA",

					}, 
					{
						data: "BODEGA_RETIRO",
						title: "BODEGA RETIRO",

					},
					{
						data: null,
						title: "COMENTARIO",
						render: function(x) {
							return x.COMENTARIO == null ? "" : x.COMENTARIO
						}
					},
					{
						data: null,
						title: "HACER PEDIDO",
						render: function(x, y, r) {

							if ((r.BodegaFAC == "0000000072" || r.BodegaFAC == "0500000008" ||
									r.BodegaFAC == "0500000002" || r.BodegaFAC == "0500000004") &&
								parseFloat(r.saldo) == 0 && r.pedido == null) {
								x = `
									<a href="mailproveedor.php?numfac=` + r.secuencia + `&bodegaFAC=` + r.BodegaFAC + `">Pedido</a> 
									`
							} else {
								x = ''
							}
							return x
						},
						visible:false
					}
				],
				"createdRow": function(row, data, index) {

					if (data["estado"] == "DROPSHIPPING") {
						$(row).addClass('bg-success');
					}

					$('td', row).eq(0).addClass("fs-6 fw-bolder");
					$('td', row).eq(5).addClass("fs-6 fw-bolder bg-light-primary");
					$('td', row).eq(1).addClass("fw-bolder bg-light-info");
					$('td', row).eq(2).addClass("fw-bolder bg-light-warning");
					$('td', row).eq(3).addClass("fw-bolder bg-light-success");
					$('td', row).eq(4).addClass("fw-bolder bg-light-primary");
					$('td', row).eq(6).addClass("fw-bolder");
					$('td', row).eq(10).addClass("fw-bolder bg-light-primary");

					if (data["estado"] == "Facturado") {
						$('td', row).eq(7).addClass("fw-bolder text-success");
					} else if (data["estado"] == "NO SISCO") {
						$('td', row).eq(7).addClass("fw-bolder text-danger");
					} else {
						$('td', row).eq(7).addClass("fw-bolder text-dark");
					}
					if (data["TOTAL"] == data["saldo"]) {
						$('td', row).eq(8).addClass("text-danger");
						$('td', row).eq(9).addClass("text-danger");
					}

					if(data["MULTI"] > 0){
						$('td', row).eq(10).html("SI");
						$('td', row).eq(10).addClass("text-info");
					}else{
						$('td', row).eq(10).html("NO");
					}

				},

			});
			setTimeout(function() {
				$($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
			}, 500);
		}

		function AjaxSend(param, callback) {
			FreezeUI({
				text: 'Cargando'
			});
			$.ajax({
				data: param,
				datatype: 'json',
				url: 'ingguiasfacturas_f.php',
				type: 'POST',
				success: function(x) {
					x = JSON.parse(x)
					// UnFreezeUI();
					callback(x)
				}
			})
		}
	</script>
</body>