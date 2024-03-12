<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
	function setfocus() {
		document.getElementById("secu").focus();
	}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>

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
			if ($base == 'CARTIMEX') {
				require '../headcarti.php';
			} else {
				require '../headcompu.php';
			}


		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> INGRESAR SERIES PARCIAL <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="../assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">

			<div>
				<table align="center">
					<tr>
						<td id="label">Factura # </td>
						<td id="box"> <input name="secu" type="text" id="secu" size="30" value=""> </td>
						<td id="box"> <input name="bodega" type="hidden" id="bodega" value="<?php echo trim($bodega) ?>"> </td>
					</tr>
					<tr>
						<td>

							<button onclick="Consultar()">Consultar</button>
						</td>
						<!-- <td> </td>
						<td id="label"> Consultar
							<input class="" id="submit" value="Grabar" src="../assets\img\lupa.png" type="image">
							<a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
						</td> -->
					</tr>
				</table>
			</div>
		</div>
		<div id="SECC_CABECERA" style="display: none;">
			<div align="left">
				<table>
					<tr>
						<td><strong> BODEGA :
								<span id="bodega_c"></span>
							</strong>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Id: </strong> <a id="ID_C"> </a>
							<strong> Numero: </strong> <a id="NUMERO_c"></a>
							<strong> Factura # : </strong> <a id="FACTURA_c"> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Cliente: </strong> <a id="CLIENTE_C"> </a>
							<strong> Tipo: </strong> <a id="TIPO_C"> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Direccion: </strong> <a id="DIRECCION"></a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Ciudad: </strong> <a id="CIUDAD"></a>
							<strong> Telefono: </strong> <a id="TELEFONO"> </a>
							<strong> Mail: </strong> <a id="MAIL"> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Contacto: </strong> <a id="CONTACTO"> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Vendedor: </strong> <a id="VENDEDOR"> </a>
							<strong> Email: </strong> <a id="EMAIL"> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Fecha y Hora de Creacion: </strong> <a id="FECHA_CREACION"> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Observaciones: </strong> <a id="OBSE"> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Medio Transporte: </strong> <a id="MEDIOT"> </a>
							<br>
						</td>
					</tr>
				</table>
			</div>
		</div>


		<div id="SECCION_TABLE" style="display: none;">
			<div class="table-responsive" align="center">
				<table id="TABLA_SERIES" class="table">

				</table>
			</div>
		</div>
		<div id="NO_SECCION_TABLE" style="display: none;">
			<h3 style="color:red">FACTURA NO TIENE SERIES INGRESADAS, HACERLO DESDE DOBRA</h3>
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

	<link href="https://cdn.datatables.net/v/dt/dt-1.13.4/b-2.3.6/b-html5-2.3.6/datatables.min.css" rel="stylesheet" />
	<script src="https://cdn.datatables.net/v/dt/dt-1.13.4/b-2.3.6/b-html5-2.3.6/datatables.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		function Mensaje(t1, t2, ic) {
			Swal.fire(
				t1,
				t2,
				ic
			)
		}
		var SECUENCIA;
		// 024-002-000047982 
		// 026-002-000029522
		// 051-002-000001260

		function Consultar() {
			let secu = $("#secu").val();
			SECUENCIA = secu;
			let param = {
				"consultarFactura": 1,
				secu: SECUENCIA,
				bodega: '<?php echo $bodega ?>',
				acceso: '<?php echo $acceso ?>',
				base: '<?php echo $_SESSION['base'] ?>'

			}
			console.log('param: ', param);
			Ajax_Send(param, function(x) {
				console.log('x: ', x);
				let CABECERa = x[0];
				let detalle_datos = x[1][0];
				let detalle_val = x[1][1];
				if (CABECERa.length == 0) {
					Mensaje("Factura no existe", "", "error");
					$("#NO_SECCION_TABLE").hide();
					$("#SECCION_TABLE").hide();
					$("#SECC_CABECERA").hide();

				} else {
					LLENAR_CABECERA(CABECERa);
					if (detalle_val == "RMA-NO") {
						$("#NO_SECCION_TABLE").show();
						$("#SECCION_TABLE").hide();
					} else {
						$("#NO_SECCION_TABLE").hide();
						$("#SECCION_TABLE").show();
						let newarray = JSON.parse(JSON.stringify(detalle_datos));

						let datos_filtrados = newarray.filter(function(x) {
							if (x.RegistaSerie == 1) {
								return x;
							}
						});

						let ARRAY_ = [];

						let CODIGOS = [...new Set(datos_filtrados.map(item => item.CopProducto))];
						console.log("CODIGOS", CODIGOS);
						CODIGOS.map(function(x) {
							let cods = datos_filtrados.filter(cat => cat.CopProducto == x);
							console.log("cods", cods);
							let cantidad_productos = [...new Set(cods.map(item => item.Cantidad))][0];
							let cantidad_codigos = cods.length;
							if (parseFloat(cantidad_codigos) == parseFloat(cantidad_productos)) {
								// ARRAY_.push(cods[0]);
								console.log("1111", cods[0]);
								cods.map(function(x) {
									ARRAY_.push(x);
								});
								// for (var i = 0; i < cantidad_productos - cantidad_codigos; i++) {
								// 	let ne = JSON.parse(JSON.stringify(cods[0]));
								// 	ne.serie = '';
								// 	ne.estado = 1;
								// 	ARRAY_.push(ne);
								// }
							} else {

								cods.map(function(x) {
									ARRAY_.push(x);
								});
								for (var i = 0; i < cantidad_productos - cantidad_codigos; i++) {
									let ne = JSON.parse(JSON.stringify(cods[0]));
									ne.serie = '';
									ne.estado = 0;
									ARRAY_.push(ne);
								}

							}

						});


						console.log('ARRAY_: ', ARRAY_);

						Tabla_Series(ARRAY_);
					}
				}


			});
		}

		function LLENAR_CABECERA(x) {

			$("#SECC_CABECERA").show();
			if (x[0]["Bloqueado"] == 1) {
				$("#bodega_c").text("Bloqueada!!");

			} else if (x[0]["Nota"] != "" || x[0]["Nota"] != null) {
				$("#bodega_c").text("Desbloqueada!!");
			} else {
				$("#bodega_c").text("");

			}
			$("#ID_C").text(x[0]["Id"]);
			$("#NUMERO_c").text(x[0]["Numero"]);
			$("#FACTURA_c").text(x[0]["Secuencia"]);
			$("#CLIENTE_C").text(x[0]["Nombre"]);
			$("#TIPO_C").text(x[0]["TipoCLi"]);
			$("#DIRECCION").text(x[0]["Direccion"]);
			$("#CIUDAD").text(x[0]["Ciudad"]);
			$("#TELEFONO").text(x[0]["Telefono"]);
			$("#MAIL").text(x[0]["Email"]);
			$("#VENDEDOR").text(x[0]["Vendedor"]);
			$("#EMAIL").text(x[0]["Empmail"]);
			$("#FECHA_CREACION").text(x[0]["Fecha"]);
			$("#OBSE").text(x[0]["Observacion"]);
			$("#MEDIOT").text(x[0]["Medio"]);

		}

		function Tabla_Series(data) {
			$('#TABLA_SERIES').empty();
			var table = $('#TABLA_SERIES').DataTable({
				destroy: true,
				data: data,
				dom: 'frtip',
				// responsive: true,
				deferRender: true,
				// scrollY: '40vh',
				// scrollCollapse: true,

				columns: [

					{
						data: "CopProducto",
						title: "Codigo",

					},
					{
						data: "Detalle",
						title: "Detalle",

					},
					{
						data: "serie",
						title: "serie",
						render: function(data) {
							if (data == null) {
								data = "";
							}
							return data = '<input type="text" value="' + data + '" class="form-control input-sas">'
						}
					},
					{
						data: null,
						title: "Ingresar",
						className: "btn_confirmar",
						defaultContent: `
								<button class="btn btn-success btn-sm btn_confirmar"> + 
							</button>`,
						// orderable: false,
						width: 20
					}
				],
				"createdRow": function(row, data, index) {
					$('td', row).eq(5).addClass("fs-4 fw-bolder bg-light-primary");
					$('td', row).eq(1).addClass("fw-bolder bg-light-info");
					$('td', row).eq(2).addClass("fw-bolder");
					$('td', row).eq(3).addClass("fw-bolder");
				}
			});

			setTimeout(function() {
				$($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
			}, 500);

			$('#TABLA_SERIES tbody').on('click', 'td.btn_confirmar', function(e) {
				var data = table.row(this).data();
				console.log('data: ', data);
				var columns = $(this).closest("tr").children();
				var serie = columns.eq(2).children().val();
				let parametros = {
					serie: serie.trim(),
					ProductoId: data.ProductoId,
					RMAID: data.RMAID,
					Creado_por: '<?php echo $usuario ?>',
					estado: data.estado,
					Factura_id: data.Factura_id,
					RMADT_ID: data.RMADT_ID,
					serie_anterior: data.serie

				}
				console.log('parametros: ', parametros);

				if (serie == "") {
					Mensaje("Debe ingresar un numero de serie", "", "error");
				} else {
					//026-002-000029522
					// if(data.)
					Validar_Serie_Existe(parametros, function(x) {
						console.log('x: ', x);
						let newarray = JSON.parse(JSON.stringify(parametros));
						let es = (x[0]["Estado"]).trim();
						console.log('es: ', es);

						if (es == "NO-ENCONTRADA") {
							Mensaje("La serie no existe en la base de datos, o no corresponde al producto", "Verifique que este bien escrita", "error");
							// columns.eq(2).children().val("");
						} else if (es == "VENDIDO") {
							Mensaje("La Serie Ya esta en otra factura", "", "error");
							// columns.eq(2).children().val("");
						} else {
							newarray.serie = serie;
							Guardar_Serie(newarray, x);
							// if (parseFloat(data.estado) == 1) {
							// } else if (parseFloat(data.estado) == 0) {
							// }
						}
					});

				}
			});
		}

		function Guardar_Serie(data, x) {

			let p = {
				"guardar_serie": 1,
				ProductoId: data.ProductoId,
				RMAID: data.RMAID,
				Creado_por: '<?php echo $usuario ?>',
				estado: data.estado,
				Factura_id: data.Factura_id,
				RMADT_ID: data.RMADT_ID,
				base: x[0]["base"],
				serie_anterior: data.serie_anterior,
				serie: data.serie,
				RMA: (x[0]["Estado"]).trim()
			}
			console.log('p: ', p);
			Ajax_Send(p, function(res) {
				console.log('res: ', res);
				// let dt = res.GUARDAR_NUEVO_RMA_FACTURA_DT;
				// let rma = res["Factura_id"]["GUARDADO_RMA_PROD"];
				// if (dt == "OK") {
				Mensaje("Serie Guardada", "", "success");
				Consultar();
				// }
			});
		}

		function Validar_Serie_Existe(parametros, callback) {
			parametros.validar_serie = 1
			Ajax_Send(parametros, function(x) {
				callback(x)
			});
		}



		function Ajax_Send(parametros, callback) {
			$.ajax({
				data: parametros,
				datatype: 'json',
				url: 'modificarfunciones.php',
				type: 'POST',
				success: function(series) {
					series = JSON.parse(series);
					callback(series);
				}
			})
		}
	</script>

</body>