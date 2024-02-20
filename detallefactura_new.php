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
				if ($(this).val() == "Entrega en tienda") {
					$("#dvdespacho").show();
				} else {
					$("#dvdespacho").hide();
				}
			});
		});
	</script>

	<script>
		function getState(val) {
			$.ajax({
				type: "POST",
				url: "get_state.php",
				data: 'idgrupo=' + val,
				success: function(data) {
					$("#state-list").html(data);
				}
			});
		}
	</script>

	<div id="header" align="center">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript">
			$(function() {
				$("#ddlpickup").change(function() {
					if ($(this).val() == "Casillero") {
						$("#casilleros").show();
					} else {
						$("#casilleros").hide();
					}
				});
			});
		</script>


		<?PHP
		session_start();
		//Variables de sesion de SGL
		$usuario = $_SESSION['usuario'];
		$base = $_SESSION['base'];
		$acceso	= $_SESSION['acceso'];
		$secu = TRIM($_POST["secu"]);
		$bodegaf = $_SESSION['bodega'];
		$nomsuc = $_SESSION['nomsuc'];
		$bodegaFAC = $_SESSION['bodegaFAC'];
		$_SESSION['usuario'] = $usuario;
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
		<div id="cuerpo2" width="100%">

			<?php
			if (!isset($_SESSION["usuario"])) {
				header("Location: index.php");
			}
			//include("barramenu.html");
			$usuariof = $usuario;
			include("conexion.php");
			date_default_timezone_set('America/Guayaquil');

			if (!empty($_POST['numfac'])) {
				$numfac = $_POST['numfac'];
			} else {
				$numfac = $_GET['numfac'];
			}
			$sec = $_GET['sec'];
			$TIPO_ENVIO = $_GET['medio'];
			// echo $numfac;

			include("conexion.php");


			$sqlcas = "SELECT a.*, p.bodega as bodegaret, c.sucursalid as sucursalret , d.sucursalid as sucursalfact , cr.doc1, cr.doc2, cr.doc3, cr.doc4, cr.doc5
			   FROM covidsales a 
			   inner join sisco.covidciudades d on a.bodega= d.almacen 
			   left outer join covidpickup p on p.orden= a.secuencia 
               left outer join covidcredito cr on cr.transaccion= a.secuencia
			   left outer join sisco.covidciudades c on p.bodega= c.almacen where a.factura = '$numfac'  and a.anulada<> '1'";

			$resultcas = mysqli_query($con, $sqlcas);
			while ($rowcas = mysqli_fetch_array($resultcas)) {
				$casillero = $rowcas['casillero'];
				$bodega = $rowcas['bodega'];
				$bodegaret = $rowcas['bodegaret'];
				$pickup = $rowcas['pickup'];
				$direccion = $rowcas['direccion'];
				$referencia = $rowcas['referencias'];
				$comentario = $rowcas['comentarios'];
				$ciudad = $rowcas['ciudad'];
				$celular = $rowcas['celular'];
				$mail = $rowcas['mail'];
				$sucuret = $rowcas['sucursalret'];
				$sucufact  = $rowcas['sucursalfact'];
				$doc1  = $rowcas['doc1'];
				$doc2  = $rowcas['doc2'];
				$doc3  = $rowcas['doc3'];
				$doc4  = $rowcas['doc4'];
				$doc5  = $rowcas['doc5'];
			}

			include("conexion_mssql.php");
			// echo "Factura: ".$bodegaFAC;
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('PER_Detalle_Facturas2 @secuencia=:secu , @bodegaFAC=:bodegaFAC');
			$result->bindParam(':secu', $numfac, PDO::PARAM_STR);
			$result->bindParam(':bodegaFAC', $bodegaFAC, PDO::PARAM_STR);
			if ($result->execute()) {
				$res = $result->fetchAll(PDO::FETCH_ASSOC);
				$CABECERA = $res[0];
				echo "<pre>";
				var_dump($res[0]);
				echo "</pre>";
			} else {
				echo "<h3> ERROR AL CARGAR </h3>";
				die();
			}


			$result2 = $pdo->prepare("SELECT ID,Nombre,Código as codigo from SIS_SUCURSALES
			where Región != 'OMNICANAL'
			and Anulado = 0
			and TipoNegocio = 'COMPUTRON'
			and ID not in ('0000000067','0000000066')

			UNION ALL

			SELECT ID,Nombre,Código as codigo from SIS_SUCURSALES
			where ID in ('0000000001')
			");
			$result2->execute();
			$SUCURSALES = $result2->fetchAll(PDO::FETCH_ASSOC);


			$DISPLAY = 'none';
			if (trim($TIPO_ENVIO) == "ENVIO") {
				$DISPLAY = 'show';
			}

			?>




			<div class='container mt-4'>
				<div class='row'>
					<div class='col'>
						<h3>Información de despacho de orden</h3>
					</div>
				</div>

				<div class='row'>
					<div class='col-6'>
						<table class='table table-bordered'>
							<thead class='thead-dark'>
								<tr>
									<th class="bg-light">Fecha :</th>
									<td colspan='5'><?php echo substr($CABECERA['Fecha'], 0, -13) ?></td>
								</tr>
								<tr>
									<th class="bg-light">Secuencia:</th>
									<td colspan='5'><?php echo $CABECERA['Secuencia'] ?></td>

								</tr>
								<tr>
									<th class="bg-light">Vendedor:</th>
									<td colspan='5'><?php echo $CABECERA['Vendedor'] ?></td>
								</tr>
								<tr>
									<th class="bg-light">Nombre</th>
									<td colspan='5'><?php echo $CABECERA['Nombre'] ?></td>
								</tr>
								<tr>
									<th class="bg-light">Ruc:</th>
									<td colspan='5'><?php echo $CABECERA['Cedula'] ?></td>
								</tr>
								<tr>
									<th class="bg-light">Sucursal</th>
									<td colspan='5'><?php echo $CABECERA['Sucursal'] ?></td>
								</tr>

							</thead>
						</table>
					</div>

					<div class='col-6'>
						<table class='table table-bordered'>
							<thead class=''>
								<tr>
									<th class="bg-light">Entrega</th>
									<td class="fw-bold bg-info bg-opacity-50"><?php echo $TIPO_ENVIO ?></td>
								</tr>
								<tr>
									<th class="bg-light">Bodega de retiro</th>
									<td class="fw-bold bg-info bg-opacity-50"><?php echo isset($bodegaret) ?></td>
								</tr>
								<tr>
									<th class="bg-dark text-light">SubTotal</th>
									<td class="bg-warning bg-opacity-10 fw-bold text-end"><?php echo number_format($CABECERA['SubTotal'], 2, ",", ".") ?></td>
								</tr>
								<tr>
									<th class="bg-dark text-light">Descuento</th>
									<td class="bg-warning bg-opacity-10 fw-bold text-end"><?php echo number_format($CABECERA['Descuento'], 2, ",", ".") ?></td>

								</tr>
								<tr>
									<th class="bg-dark text-light">Financieamiento</th>
									<td class="bg-warning bg-opacity-10 fw-bold text-end"><?php echo number_format($CABECERA['Financiamiento'], 2, ",", ".") ?></td>

								</tr>
								<tr>
									<th class="bg-dark text-light">Impuesto</th>
									<td class="bg-warning bg-opacity-10 fw-bold text-end"><?php echo number_format($CABECERA['Impuesto'], 2, ",", ".") ?></td>

								</tr>
								<tr>
									<th class="bg-dark text-light">Total</th>
									<td class="bg-dark bg-opacity-10 fs-4 fw-bold text-success text-end"><?php echo number_format($CABECERA['Total'], 2, ",", ".") ?></td>
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
								foreach ($res as $row) {
									if ($row["Section"] != "HEADER") {
										if ($row['MULTI'] != 'MULTI') {

								?>
											<tr>
												<td><?php echo $row[('Codigo')] ?></td>
												<td><?php echo $row[('Nombre')] ?></td>
												<td><?php echo $row[('Cantidad')] ?></td>
												<td>$<?php echo number_format($row['Precio'], 2, ",", ".")  ?></td>
												<td>$<?php echo number_format($row['SubTotal'], 2, ",", ".")  ?></td>
												<td>$<?php echo number_format($row['Descuento'], 2, ",", ".")  ?></td>
												<td>$<?php echo number_format($row['Impuesto'], 2, ",", ".")  ?></td>
												<td>$<?php echo number_format($row['Total'], 2, ",", ".")  ?></td>
											</tr>
								<?php
										}
									}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="row mt-3">
					<h3 class="fw-bold text-muted bg-success bg-opacity-10">Datos del cliente</h3>
					<div class="col-6">
						<table class='table table-bordered'>
							<thead class='thead-dark'>
								<tr>
									<th class="bg-light">Dirección :</th>
									<td>
										<?php echo $CABECERA['DIRECCION'] == null || $CABECERA['DIRECCION'] == "" ? $direccion : $CABECERA['DIRECCION'] ?>
									</td>
								</tr>
								<tr>
									<th class="bg-light">Referencia :</th>
									<td>
										<?php echo $CABECERA['REFERENCIA'] == null || $CABECERA['REFERENCIA'] == "" ? $referencia : $CABECERA['REFERENCIA'] ?>
									</td>
								</tr>
								<tr>
									<th class="bg-light">Ciudad :</th>
									<td>
										<?php echo $ciudad ?>
									</td>
								</tr>
								<tr>
									<th class="bg-light">Celular :</th>
									<td>
										<?php echo $celular ?>
									</td>
								</tr>
								<tr>
									<th class="bg-light">Email :</th>
									<td>
										<?php echo $mail ?>
									</td>
								</tr>
								<tr>
									<th class="bg-light">Comentario :</th>
									<td>
										<textarea class='form-control' id='comentario' name='comentario' readonly>
									<?php echo $comentario ?>

									</textarea>

									</td>
								</tr>
							</thead>

						</table>

					</div>

					<div class="col-6">
						<form action='detallefactura2.php' method='post' id="FORM_G">
							<table class='table table-bordered'>
								<input type="hidden" value="<?php echo $TIPO_ENVIO ?>" id="TIPO_ENVIO">
								<Input Type="hidden" Name='numfac' value='<?php echo $numfac ?>'>
								<Input Type="hidden" Name='sec' value='<?php echo $sec ?>'>
								<Input Type="hidden" Name='bodegaFAC' value='<?php echo $bodegaFAC ?>'>
								<thead class='thead-dark'>
									<tr>
										<th class="bg-light">FORMA DE DESPACHO :</th>
										<td>
											<select name="medio" id="ddlpickup" class="form-select">
												<option value=''>Seleccione</option>
												<option value='Urbano'>Urbano</option>
												<option value='Tramaco'>Tramaco</option>
												<option value='Servientrega'>Servientrega</option>
												<option value='Vehiculo Computron'>Vehiculo Computron</option>
												<option value='Entrega en tienda'>Entrega en tienda</option>
												<option value='Casillero'>Casillero</option>

											</select>
										</td>

									</tr>
									<tr style="display:show" id="SECC_SUC">

										<th class="bg-light">TIENDA DE RETIRO :</th>
										<td>
											<select name="nbodega" id="sucursales" class="form-select">
												<option value="">Seleccione tienda de retiro</option>
												<?php
												foreach ($SUCURSALES as $row) {
												?>
													<option value="<?php echo $row["ID"] ?>"><?php echo $row["codigo"] . " - " . $row["Nombre"] ?></option>

												<?php

												}
												?>

											</select>
										</td>
									</tr>
									<tr>
										<th class="bg-light">GUIA #</th>
										<td>
											<Input Type=Text Size=10 Maxlenght=95 Name='despacho' id='despacho' class="form-control">
										</td>
									</tr>
									<tr>
										<th class="bg-light">BULTOS #</th>
										<td>
											<Input Type="number" Size=10 Maxlenght=5 Name='bultos' id='bultos' min='1' max='10' required class="form-control">
										</td>
									</tr>
									<tr>
										<th class="bg-light">COMENTARIO DESPACHO</th>
										<td>
											<textarea class="form-control" Size=20 rows=4 cols=120 Name='comentariodesp' id='comentariodesp'></textarea>
									</tr>
								</thead>
							</table>
						</form>

					</div>

					<div class="col-12 text-center mb-5">
						<button onclick="Guardar_Form()" class="btn btn-success fw-bold">GUARDAR</button>

					</div>


				</div>


			</div>



		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		var TIENDA_RETIRO = 1;

		$("#ddlpickup").on("change", function(x) {
			let val = $(this).val();
			// console.log('val: ', val);
			// if (val == "Entrega en tienda") {
			// 	$("#SECC_SUC").show(100);
			// 	TIENDA_RETIRO = 1;
			// } else {
			// 	$("#SECC_SUC").hide(0);
			// 	TIENDA_RETIRO = 0
			// }
		})


		function Guardar_Form() {
			var formulario = document.getElementById("FORM_G");
			let guia = $("#despacho").val();
			let sucursal = $("#sucursales").val();
			let tipo = $("#TIPO_ENVIO").val();
			let forma_despa = $("#ddlpickup").val();
			// let guia = $("#despacho").val();


			if (guia == "") {
				Swal.fire({
					title: "Debe Ingresar un numero de guia",
					text: "",
					icon: "info"
				});
			} else if (forma_despa == "") {
				Swal.fire({
					title: "Debe Ingresar una forma de despacho",
					text: "",
					icon: "info"
				});

			} else {

				if (TIENDA_RETIRO == 1) {
					if (sucursal == "") {
						Swal.fire({
							title: "Debe Ingresar una tienda de retiro",
							text: "",
							icon: "info"
						});
					} else {
						formulario.submit();
					}
				} else {
					formulario.submit();
				}

			}
			console.log('guia: ', guia);

		}
	</script>

</body>