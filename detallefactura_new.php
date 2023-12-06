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
		<div id="cuerpo2" align="center" width="100%">

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
			// echo $numfac;

			include("conexion_mssql.php");
			//echo "Factura: ".$numfac;
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('PER_Detalle_Facturas2 @secuencia=:secu , @bodegaFAC=:bodegaFAC');
			$result->bindParam(':secu', $numfac, PDO::PARAM_STR);
			$result->bindParam(':bodegaFAC', $bodegaFAC, PDO::PARAM_STR);
			if ($result->execute()) {
				$res = $result->fetchAll(PDO::FETCH_ASSOC);
				$CABECERA = $res[0];
				// var_dump($res[0]);
			} else {
				echo "<h3> ERROR AL CARGAR </h3>";
				die();
			}
			?>

			<div class='container mt-4'>
				<div class='row'>
					<div class='col'>
						<h3>Información de despacho de orden</h3>
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
									<td class="bg-warning bg-opacity-50 fw-bold"><?php echo number_format($CABECERA['SubTotal'], 2, ",", ".") ?></td>
									<th class="bg-warning">Descuento</th>
									<td class="bg-warning bg-opacity-50 fw-bold"><?php echo number_format($CABECERA['Descuento'], 2, ",", ".") ?></td>
									<th class="bg-warning">Finan.</th>
									<td class="bg-warning bg-opacity-50 fw-bold"><?php echo number_format($CABECERA['Financiamiento'], 2, ",", ".") ?></td>
									<th class="bg-warning">Impuesto</th>
									<td class="bg-warning bg-opacity-50 fw-bold"><?php echo number_format($CABECERA['Impuesto'], 2, ",", ".") ?></td>
									<th class="bg-warning">Total</th>
									<td class="bg-warning bg-opacity-50 fw-bold"><?php echo number_format($CABECERA['Total'], 2, ",", ".") ?></td>
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
								<tr>
									<td>TNQGAS</td>
									<td>TANQUE CILINDRO PARA GAS 15KG - AMARILLO</td>
									<td>1,00</td>
									<td>$125,89</td>
									<td>$125,89</td>
									<td>$0,00</td>
									<td>$15,11</td>
									<td>$141,00</td>
								</tr>
								<tr>
									<td>FRW-2011GY</td>
									<td>FREIDORA DE AIRE SANKEY 2 LITROS - SILVER</td>
									<td>1,00</td>
									<td>$91,07</td>
									<td>$91,07</td>
									<td>$91,07</td>
									<td>$21,86</td>
									<td>$204,00</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class='row'>
					<div class='col-md-6'>
						<label for='direccion'>Dirección</label>
						<input type='text' class='form-control' id='direccion' name='direccion' value='PARAISO DE LA FLOR BLOQUE 3 MZ 232 SOLAR 20' readonly>
					</div>
					<div class='col-md-6'>
						<label for='referencia'>Referencia</label>
						<input type='text' class='form-control' id='referencia' name='referencia' value='ATRAS DE LA ESCUELA HUMBERTO MORE' readonly>
					</div>
				</div>

				<div class='row'>
					<div class='col'>
						<label for='comentario'>Comentario</label>
						<textarea class='form-control' id='comentario' name='comentario' readonly>CLIENTE RETIRA ENTIENDA</textarea>
					</div>
				</div>

				<div class='row'>
					<div class='col-md-4'>
						<label for='ciudad'>Ciudad</label>
						<input type='text' class='form-control' id='ciudad' name='ciudad' value='GUAYAQUIL' readonly>
					</div>
					<div class='col-md-4'>
						<label for='celular'>Celular</label>
						<input type='text' class='form-control' id='celular' name='celular' value='0939744938' readonly>
					</div>
					<div class='col-md-4'>
						<label for='email'>Email</label>
						<input type='text' class='form-control' id='email' name='email' value='johnbryanramirezsalas@gmail.com' readonly>
					</div>
				</div>
			</div>



		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>