<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/tablas.css">
	<link rel="stylesheet" type="text/css" href="css/boton.css">
	<html>
	<!-- <head> -->
	<title>SGL</title>
	<!-- <link rel="stylesheet" type="text/css" href="css/menus.css"> -->
	<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
	<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<html>

<body>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#ddlpickup").change(function() {
				if ($(this).val() == "Envio") {
					$("#dvdespacho").show();
				} else {
					$("#dvdespacho").hide();
				}
			});
		});
	</script>

	<div id="header" align="center">


		<?PHP
		session_start();
		//Variables de sesion de SGL
		$usuario = $_SESSION['usuario'];
		$base = $_SESSION['base'];
		$acceso	= $_SESSION['acceso'];
		$secu =  substr($_POST["secu"], 0, 17);
		$bodegaf = substr($_POST["secu"], 18, 10);
		$nomsuc = $_SESSION['nomsuc'];
		$_SESSION['usuario'] = $usuario;
		$puerta_p = $_SESSION['puerta_p'];

		if ($base == 'CARTIMEX') {
			require 'headcarti.php';
		} else {
			require 'headcompu.php';
		}
		// aqui ingreso numero de guia y bultos
		//echo "bodega facturacion".$bodegaf;
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Guias Facturas <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center" width="100%">
			<?php

			if (!isset($_SESSION["usuario"])) {
				header("Location: index.php");
			}
			//include("barramenu.html");

			$usuariof = $usuario;
			include("conexion.php");
			date_default_timezone_set('America/Guayaquil');

			$sec = $_POST['sec'];
			//***************************************************************************************
			echo "<div id='principal1'>";
			include("conexion_mssql.php");




			//echo "Factura: ".$secu. $bodegaf;
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('PER_Detalle_Facturas2 @secuencia=:secu , 
			@bodegaFAC=:bodegaf');
			$result->bindParam(':secu', $secu, PDO::PARAM_STR);
			$result->bindParam(':bodegaf', $bodegaf, PDO::PARAM_STR);
			$result->execute();
			echo $bodegaf;
			/*$sql = "PER_Detalle_Facturas'" . $numfac . "' ";
	$result = mssql_query(utf8_decode($sql));*/
			$count = $result->rowcount();
			$CABECERA = [];
			$DETALLE = [];

			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

				if ($row['msn'] <> "La Factura esta Anulada") {

					if ($row['Section'] == 'HEADER') {

						array_push($CABECERA, $row);

						// echo  "<left><table border=2 cellspacing=0 width=70%  ></left>";
						// echo "<tr>";
						// echo  "<th colspan = 12><h3>Información de Factura </h3></th></tr>";
						// echo  "<tr><th bgcolor='$color1' align=center height=0><B>Fecha :</B></th>";
						// echo "<td align='left' colspan =2 > "  . substr($row['Fecha'], 0, -13) .  "</td>";
						// echo  "<th bgcolor='$color1' align=center height=0><B>Secuencia:</B></th>";
						// echo "<td align='left' colspan =4>"  . $row['Secuencia'] .  "</td>";
						// echo  "<th bgcolor='$color1' align=center height=0><B>Vendedor:</B></th>";
						// echo  "<td align='left' colspan =3>"  . $row['Vendedor'] .  "</td> </tr>";
						// echo "<tr><th bgcolor='$color1' align=center height=0><B>Ruc:</B></th>";
						// echo  "<td align='left' colspan =2>"  . $row['Cedula'] .  "</td>";
						// echo "<th bgcolor='$color1' align=center height=0><B>Nombre</B></th>";
						// echo  "<td align='left' colspan =5>"  . $row['Nombre'] .  "</td>";
						// echo "<th bgcolor='$color1' align=center height=0><B>Sucursal</B></th>";
						// echo  "<td align='left' colspan =3>" . $row['Sucursal'] .  "</td><tr>";

						$SubTotal = $row['SubTotal'];
						$Descuento = $row['Descuento'];
						$Financiamiento = $row['Financiamiento'];
						$Impuesto = $row['Impuesto'];
						$Total = $row['Total'];
						$RentUSD = $row['RentUSD'];
						$Rent = $row['Rent'];
						$RentUSD2 = $row['RentUSD2'];
						$Rent2 = $row['Rent2'];
						$RetEsperada = $row['RetEsperada'];
						$Sucursal = $row['Sucursal'];
						$RecargoTC = $row['RecargoTC'];
						$ID = $row['ID'];

						// echo "<th bgcolor='$color1' align=center height=0><B>SubTotal</B></th>";
						// echo "<td align='right' colspan =2>$"  . number_format($SubTotal, 2, ",", ".") .  "</td>";
						// echo "<th bgcolor='$color1' align=center height=0><B>Descuento</B></th>";
						// echo "<td align='right'>$"  . number_format($Descuento, 2, ",", ".") .  "</td>";
						// echo "<th bgcolor='$color1' align=center height=0><B>Finan.</B></th>";
						// echo "<td align='right'>$"  . number_format($Financiamiento, 2, ",", ".") .  "</td>";
						// echo  "<th bgcolor='$color1' align=center height=0><B>Impuesto</B></th>";
						// echo "<td align='right'>$"  . number_format($Impuesto, 2, ",", ".") .  "</td>";
						// echo "<th bgcolor='$color1' align=center height=0><B>Total</B></th>";
						// echo "<td align='right' colspan =2>$"  . number_format($Total, 2, ",", ".") .  "</td><tr>";



						$SubTotalt = 0;
						$Impuestot = 0;
						$Totalt = 0;

						// echo  "<tr>";
						// echo "<th bgcolor='$color1' align=center  colspan =2><B>Código</B></th>";
						// echo "<th bgcolor='$color1' align=center colspan =4><B>Descripción</B></th>";
						// echo "<th bgcolor='$color1' align=center ><B>Cant.</B></th>";
						// echo "<th bgcolor='$color1' align=center  ><B>Precio</B></th>";
						// echo  "<th bgcolor='$color1' align=center ><B>SubTotal </B></th>";
						// echo  "<th bgcolor='$color1' align=center ><B>Descuento </B></th>";
						// echo  "<th bgcolor='$color1' align=center <B>Impuesto </B></th>";
						// echo "<th bgcolor='$color1' align=center ><B>Total </B></th>";
						// echo "<tr>";
						$SubTotalt = $row['SubTotal'];
						$Impuestot =  $row['Impuesto'];
						$Totalt =  $row['Total'];
						$SubTotalt2 =  $row['SubTotal'];
						$TotFin =  $row['Financiamiento'];
						$Impuestot2 =  $row['Impuesto'];
						$Totalt2 = $row['Total'];
					} else  // del if ($row['Section']=='HEADER')
					{
						array_push($DETALLE, $row);

						// echo  "<td align='left' colspan =2>"  . $row[utf8_decode('Codigo')] .  "</td>";
						// echo  "<td align='left' colspan =4>"  . utf8_encode($row['Nombre']) .  "</td>";
						// echo "<td align='right'>"  . number_format($row['Cantidad'], 2, ",", ".")  .  "</td>";
						// echo  "<td align='right'>$"  . number_format($row['Precio'], 2, ",", ".")  .  "</td>";
						// echo  "<td align='right'>$"  . number_format($row['SubTotal'], 2, ",", ".")   .  "</td>";
						// echo "<td align='right'>$"  . number_format($row['Descuento'], 2, ",", ".")   .  "</td>";
						// echo "<td align='right'>$"  . number_format($row['Impuesto'], 2, ",", ".")   .  "</td>";
						// echo  "<td align='right'>$"  . number_format($row['Total'], 2, ",", ".")   .  "</td>";
						// echo "<tr>";
					} // del if ($row['Section']=='HEADER')	
				} else {
					echo "<h3> Factura ANULADA </h3>";
					//header('ingguiasfacturas.php');
					//header("Refresh: 3 ; preparartransferencias.php");
					header("Refresh:5; ingguiasfacturas.php");
				}
			}
			// echo  "<tr><th colspan = 12><h3>Información de despacho de Factura </h3></th></tr>";
			// echo  "<Form Action='dropshipping1.php' Method='post'>";
			// echo "<Input Type=hidden Name='numfac' value='$ID'>";
			// echo "<Input Type=hidden Name='bodegaf' value='$bodegaf'>";
			// echo "<th colspan =2>Forma de Despacho:</th>";
			// echo "<td><select name='medio' required id = 'ddlpickup' onchange = 'ShowHideDiv2()'>";
			// echo "  <option value='Ninguno'>(Ninguno)</option>";
			// echo "  <option value='1'>Entrega en Tienda</option>";
			// echo "  <option value='2'>Entrega en otra Tienda</option>";
			// echo "  <option value='3'>Envio</option>";
			// echo "</select></td> ";
			// echo "<td colspan = 10 >";
			// echo "<div id='dvdespacho' style='display: none'>";
			// echo "<strong>Direccion :<strong> <br><textarea  Size = 20 rows= 4 cols=120 Name='direccion' id='direccion'></textarea>";
			// echo "<strong>Referencia :<strong> <br> <textarea  Size = 20 rows= 4 cols=120 Name='referencia' id='referencia'></textarea> <br>";
			// echo "<strong>Telefono :<strong> <input  name='telefono' type='text' id='telefono' size = '30' value= '' > ";
			// echo "<strong>Email :<strong> <input  name='Email' type='text' id='Email' size = '50' value= '' > <br>";
			// echo "</td></tr>";
			// echo "</td></tr></div></td>";
			// echo "</table>";

			// echo "<table border=0 cellspacing=0 width=100%>";
			// echo "<td colspan =14><br><center><input type=\"submit\" name=\"Submit\" value=\"Guardar Información\" class=\"btn btn-sm btn-primary\"></form><br></td>";
			// echo "</table>";


			//echo "fin". $usuariof.$base.$acceso.$bodegaf.$nomsuc;
			//Variables de sesion de SGL 
			$_SESSION['usuario'] = $usuariof;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			//$_SESSION['bodega']=$bodegaf;
			$_SESSION['nomsuc'] = $nomsuc;
			$_SESSION['bodegaFAC'] = $bodegaFAC;
			//echo $datamail;
			//echo $datamail2;
			// var_dump($CABECERA);
			$CABECERA = $CABECERA[0];
			// echo "<pre>";
			// var_dump($CABECERA);
			// echo "</pre>";
			$result2 = $pdo->prepare("SELECT ID,Nombre,Código as codigo from SIS_SUCURSALES
			where Región != 'OMNICANAL'
			and Anulado = 0
			and TipoNegocio = 'COMPUTRON'
			and ID not in ('0000000067','0000000066')
			");
			$result2->execute();
			$SUCURSALES = $result2->fetchAll(PDO::FETCH_ASSOC);


			?>

			<div class='container mt-4'>
				<div class='row'>
					<div class='col'>
						<h3>Información de factura</h3>
					</div>
				</div>

				<div class='row'>
					<div class='col'>
						<table class='table table-bordered'>
							<thead class='thead-dark'>
								<tr>
									<th class="bg-light">Fecha :</th>
									<td><?php echo substr($CABECERA['Fecha'], 0, -13) ?></td>
									<th class="bg-light">Secuencia:</th>
									<td style="width: 250px;"><?php echo $CABECERA['Secuencia'] ?></td>
									<th class="bg-light">Fac_Id:</th>
									<td style="width: 250px;" id="IDFACT"><?php echo $CABECERA['ID'] ?></td>
									<th class="bg-light">Vendedor:</th>
									<td style="width: 250px;"><?php echo $CABECERA['Vendedor'] ?></td>
								</tr>
								<tr>
									<th class="bg-light">Ruc:</th>
									<td><?php echo $CABECERA['Cedula'] ?></td>
									<th class="bg-light">Nombre</th>
									<td colspan='5'><?php echo $CABECERA['Nombre'] ?></td>
									<th class="bg-light">Sucursal</th>
									<td colspan='3'><?php echo $CABECERA['Sucursal'] ?></td>
								</tr>
								<tr>
									<th class="bg-warning">SubTotal</th>
									<td class="bg-warning bg-opacity-50 fw-bold">$<?php echo number_format($CABECERA['SubTotal'], 2, ",", ".") ?></td>
									<th class="bg-warning">Descuento</th>
									<td class="bg-warning bg-opacity-50 fw-bold">$<?php echo number_format($CABECERA['Descuento'], 2, ",", ".") ?></td>
									<th class="bg-warning">Finan.</th>
									<td class="bg-warning bg-opacity-50 fw-bold">$<?php echo number_format($CABECERA['Financiamiento'], 2, ",", ".") ?></td>
									<th class="bg-warning">Impuesto</th>
									<td class="bg-warning bg-opacity-50 fw-bold">$<?php echo number_format($CABECERA['Impuesto'], 2, ",", ".") ?></td>
									<th class="bg-warning">Total</th>
									<td class="bg-warning bg-opacity-50 fw-bold">$<?php echo number_format($CABECERA['Total'], 2, ",", ".") ?></td>
								</tr>
							</thead>
						</table>
					</div>
				</div>

				<div class='row'>
					<div class='col'>
						<table class='table table-bordered table-striped'>
							<thead class='bg-danger'>
								<tr>
									<th class="bg-dark text-light">Código</th>
									<th class="bg-dark text-light">Descripción</th>
									<th class="bg-dark text-light">BODEGA</th>
									<th class="bg-dark text-light">BODEGA N</th>
									<th class="bg-dark text-light">Cant.</th>
									<th class="bg-dark text-light">Precio</th>
									<th class="bg-dark text-light">SubTotal</th>
									<th class="bg-dark text-light">Descuento</th>
									<th class="bg-dark text-light">Impuesto</th>
									<th class="bg-dark text-light">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($DETALLE as $row) {
								?>
									<tr>
										<td><?php echo $row[('Codigo')] ?></td>
										<td><?php echo $row[('Nombre')] ?></td>
										<td><?php echo $row[('bocod')] ?></td>
										<td><?php echo $row[('bonom')] ?></td>
										<td><?php echo $row[('Cantidad')] ?></td>
										<td>$<?php echo number_format($row['Precio'], 2, ",", ".")  ?></td>
										<td>$<?php echo number_format($row['SubTotal'], 2, ",", ".")  ?></td>
										<td>$<?php echo number_format($row['Descuento'], 2, ",", ".")  ?></td>
										<td>$<?php echo number_format($row['Impuesto'], 2, ",", ".")  ?></td>
										<td>$<?php echo number_format($row['Total'], 2, ",", ".")  ?></td>
									</tr>
								<?php
								}
								?>


							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-8">
						<div class='row mt-5'>
							<div class='col'>
								<h3>Información de despacho</h3>
							</div>
						</div>
						<div class='row'>
							<form id="Drop_Form" Action='dropshipping1.php' Method='post'>
								<Input Type=hidden Name='numfac' value='<?php echo $ID ?>'>
								<Input Type=hidden Name='bodegaf' value=<?php echo $bodegaf ?>'>
								<Input Type=hidden Name='Sucursal_ID' value='<?php echo $CABECERA["Sucursal_ID"] ?>'>
								<div class='col'>
									<table class='table table-bordered'>
										<thead class='thead-dark'>
											<tr>
												<th class="bg-light">FORMA DE DESPACHO :</th>
												<td>
													<select name="medio" id="medio" class="form-select" onchange="CambiaMedio()">
														<option value="">Ninguno</option>
														<option value="1">Entrega en Tienda</option>
														<option value="2">Entrega en Otra Tienda</option>
														<option value="3">Envio</option>
													</select>
												</td>
												<th class="bg-light">

													<div style="display: none;" id="SECC_SUC">
														<select name="sucursales" id="sucursales" class="form-select">
															<option value="">Seleccione tienda de retiro</option>
															<?php
															foreach ($SUCURSALES as $row) {
															?>
																<option value="<?php echo $row["ID"] ?>"><?php echo $row["codigo"] . " - " . $row["Nombre"] ?></option>

															<?php

															}
															?>

														</select>
													</div>
													<div id="SECC_REF" style="display: none;">
														<span>Direccion</span>
														<textarea Size=20 rows=4 cols=40 Name='direccion' id='direccion'></textarea><br>
														<span>referencia</span>
														<textarea Size=20 rows=4 cols=40 Name='referencia' id='referencia'></textarea><br>
														<span>Telefono</span>
														<input name='telefono' type='text' id='telefono' size='30' value=''> <br>
														<span>Email </span>
														<input name='Email' type='text' id='Email' size='50' value=''>
													</div>
												</th>
											</tr>
										</thead>
									</table>
								</div>
							</form>
							<div align="center">
								<button onclick="Guardar_Informacion()" class="btn btn-primary mt-5">Guardar informacion</button>
							</div>
						</div>
					</div>

					<?php
					if ($puerta_p == 1) {
					?>
						<div class="col-4">
							<div class='row mt-5'>
								<div class='col'>
									<h3>Documentos</h3>
								</div>
							</div>
							<div class='row'>
								<div class="col-12">
									<input type="file" id="DOCUMENTO">
									<button onclick="Subir_Doc()" class="btn btn-success">Subir</button>
								</div>
								<div class="col-12 mt-4">
									<table id="TABLA_DOCS">

									</table>
								</div>
							</div>
						</div>
					<?php

					}
					?>


				</div>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<link href="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/b-html5-2.4.2/rr-1.4.1/datatables.min.css" rel="stylesheet">

	<script src="https://cdn.datatables.net/v/dt/dt-1.13.8/b-2.4.2/b-html5-2.4.2/rr-1.4.1/datatables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

	<?php

	$SO = PHP_OS;
	if ($SO  == "Linux") {
		$destination_folder = '../sgo_docs/SGL/dropshiping/puerta_puerta/';
	} else {
		$destination_folder = 'puerta/';
	}

	?>


	<script>
		function CambiaMedio() {
			let medio = $("#medio").val();
			console.log('medio: ', medio);
			if (medio == 2) {
				$("#SECC_SUC").show();
				$("#SECC_REF").hide();
				$("#direccion").val("");
				$("#referencia").val("");
				$("#telefono").val("");
				$("#Email").val("");

			} else {
				if (medio == 3) {

					$("#SECC_REF").show();

				} else {
					$("#SECC_SUC").hide();
					$("#SECC_REF").hide();
					$("#sucursales").val("").change()
					$("#direccion").val("");
					$("#referencia").val("");
					$("#telefono").val("");
					$("#Email").val("");
				}
			}
		}

		function Guardar_Informacion() {
			let medio = $("#medio").val();
			let sucursales = $("#sucursales").val();
			var formulario = document.getElementById("Drop_Form");
			if (medio == "") {
				Swal.fire("Seleccione una forma de despacho");
			} else {
				if (medio == 2) {
					if (sucursales == "") {
						Swal.fire("Seleccione una tienda de retiro");
					} else {
						formulario.submit();
					}
				} else {
					formulario.submit();
				}
			}
		}

		function Subir_Doc() {
			// let file = $()
			var archivoInput = document.getElementById('DOCUMENTO');
			var archivo = archivoInput.files[0];
			let IDFACT = $("#IDFACT").text()
			console.log('IDFACT: ', IDFACT);
			console.log('archivo: ', archivo);

			if (archivo == undefined) {
				Swal.fire({
					title: "Seleccione un documento",
					text: "",
					icon: "info"
				});
			} else {
				let hora = moment().format("hhmmss")
				var extension = archivo.type.split("/")[1];
				var nombreArchivo = IDFACT + "_" + hora + "." + extension;
				archivo = renameFile(archivo, nombreArchivo);
				console.log('archivo: ', archivo);
				var formData = new FormData();
				formData.append('Archivo', archivo);

				guardarImgpdf(archivo);


			}
		}

		function renameFile(originalFile, newName) {
			return new File([originalFile], newName, {
				type: originalFile.type,
			});
		}

		function guardarImgpdf(data) {

			var formData = new FormData();
			formData.append('file', data);
			let param = {
				archivo: 1,
				formData: formData
			}
			$.ajax({
				url: "dropshipping_f.php/Archivo",
				type: 'post',
				data: formData,
				contentType: false,
				processData: false,
				success: function(response) {
					console.log('response: ', response);
					Cargar_Documentos();

				}
			});
		}

		function Cargar_Documentos() {
			let IDFACT = $("#IDFACT").text()

			let param = {
				Cargar_Documentos: 1,
				ID: IDFACT
			}
			AjaxSend(param, function(x) {
				console.log('x: ', x);
				Tabla(x)
			})
		}

		Cargar_Documentos();

		function Tabla(data) {
			$('#TABLA_DOCS').empty();
			var table = $('#TABLA_DOCS').DataTable({
				destroy: true,
				data: data,
				dom: 'rtip',
				columns: [{
						data: "archivo",
						title: "archivo",
						render: function(x) {
							let link = '<?php echo $destination_folder ?>' + x
							let a = `
								<a href="` + link + `" target="_blank">` + x + `</a>
							`
							return a;

						}
					},
					{
						data: null,
						title: "Borrar",
						className: "btn_girar",
						defaultContent: `
							<button class="btn btn-danger btn-sm btn_girar">
									X
							</button>
                    `,
						orderable: false,
						width: 100
					}
				],
				"createdRow": function(row, data, index) {


					$('td', row).eq(0).addClass("fs-6 fw-bolder");
					$('td', row).eq(5).addClass("fs-6 fw-bolder bg-light-primary");
					$('td', row).eq(1).addClass("fw-bolder bg-light-info");
					$('td', row).eq(2).addClass("fw-bolder bg-light-warning");
					$('td', row).eq(3).addClass("fw-bolder bg-light-success");
					$('td', row).eq(4).addClass("fw-bolder bg-light-primary");
					$('td', row).eq(6).addClass("fw-bolder");
					$('td', row).eq(10).addClass("fw-bolder bg-light-primary");
				}

			});
			setTimeout(function() {
				$($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
			}, 500);

			$('#TABLA_DOCS tbody').on('click', 'td.btn_girar', function(e) {
				var data = table.row(this).data();
				console.log('data: ', data);

				let param = {
					Borrar: 1,
					ID: data["factura_id"],
					archivo: data["archivo"],
				}

				AjaxSend(param, function(x) {
					console.log('x: ', x);
					Cargar_Documentos();

				})

			});
		}

		function AjaxSend(param, callback) {
			// FreezeUI({
			// 	text: 'Cargando'
			// });
			$.ajax({
				data: param,
				datatype: 'json',
				url: 'dropshipping_f2.php',
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