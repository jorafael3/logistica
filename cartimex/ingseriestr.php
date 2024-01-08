<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
	function setfocus() {
		document.getElementById("serie").focus();
	}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">

<body onload="setfocus()">
	<div id="header" align="center">
		<?php
		session_start();
		if (isset($_SESSION['loggedin'])) {
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	= $_SESSION['acceso'];
			$secu	= $_SESSION['secu'];
			$bodega	= $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$transfer = $_SESSION['transfer'];
			$cantseries = $_SESSION['cantseries'];
			$idproducto = $_SESSION['idproducto'];
			$arreglo = $_SESSION['datosarreglos'];
			$arregloseries = $_SESSION['arregloseries'];
			$serieleida = $_POST['serie'];
			$usuario1 = $usuario;
			$bloq = '';
			//echo "Facturaid" .$transfer. "Productoid".$idproducto."Series".$cantseries; 
			//die();
			if ($base == 'CARTIMEX') {
				require '../headcarti.php';
			} else {
				require '../headcompu.php';
				$_SESSION['base'] = $base;
				$Nota = " ";
			}

			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('select codigo= código, detalle= nombre from inv_productos where id=:id');
			$result->bindParam('id', $idproducto, PDO::PARAM_STR);
			$result->execute();
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$codigo = $row['codigo'];
				$detalle = $row['detalle'];
			}

			if (isset($_POST['submit'])) {
				$x = $_SESSION['Contador'];
				$series = $_SESSION['series']; // Cargo el arreglo en memoria 
				$serieleida = isset($_POST['serie']) ? $_POST['serie'] : '';
				$producto_id = isset($_POST['productoid']) ? $_POST['productoid'] : '';

				// Validación de la serie y el ProductoID en la base de datos
				$query = "SELECT COUNT(*) AS serie_count FROM INV_PRODUCTOS_SERIES_COMPRAS WHERE Serie = :serie AND ProductoID = :producto_id";
				$stmt = $pdo->prepare($query);
				$stmt->bindParam(':serie', $serieleida, PDO::PARAM_STR);
				$stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
				$stmt->execute();
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				$serieCount = $result['serie_count'];

				$serieExiste = ($serieCount > 0);

				if (!$serieExiste) {

					echo json_encode(['SERIE' => $serieExiste]);
					$muestraleyenda2 = 'La serie no pertenece al producto especificado o no está registrada en la base de datos.';
				} else {
					// Validación de serie en rma_productos
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('select estado , facturaid from rma_productos where serie=:serie and productoid=:productoid ');
					$result1->bindParam('serie', $serieleida, PDO::PARAM_STR);
					$result1->bindParam('productoid', $idproducto, PDO::PARAM_STR);
					$result1->execute();
					$count1 = $result1->rowCount();
					if ($count1 == 0) {
						$yy = 0;
						$existe = 0;

						while ($yy < $_SESSION['Contador']) {    // Mientras haya elementos en el arreglo
							if ($series[1][$yy] == $serieleida) // Pregunta Si la serie ingresada está duplicada
							{
								$existe = 1;
							}
							$yy++;
						}
						if ($existe == 1) // Si existe muestra leyenda
						{
							$muestraleyenda2 = "Ya leyó esa serie... ";
							$xx = $x - 1;
						} else {
							$series[1][$x] = $_POST['serie']; // Agrego la serie al arreglo 
							$_SESSION['series'] = $series; // Grabo el arreglo en memoria
							$xx = $x;
							if ($cantseries - 1 == $x) {
								$bloq = 'readonly';
							}
							$x++;
						}
					} else {
						$row = $result1->fetch(PDO::FETCH_ASSOC);
						if (trim($row['estado']) == 'VENDIDO') {
							$muestraleyenda2 = " Serie ya está registrada en factura # " . $row['facturaid'] . " ";
						} else {
							$yy = 0;
							$existe = 0;
							while ($yy < $_SESSION['Contador']) {    // Mientras haya elementos en el arreglo
								if ($series[1][$yy] == $serieleida) // Pregunta Si la serie ingresada está duplicada
								{
									$existe = 1;
								}
								$yy++;
							}
							if ($existe == 1) // Si existe muestra leyenda
							{
								$muestraleyenda2 = "Ya leyó esa serie... ";
								$xx = $x - 1;
							} else {
								$series[1][$x] = $_POST['serie']; // Agrego la serie al arreglo 
								$_SESSION['series'] = $series; // Grabo el arreglo en memoria
								$xx = $x;
								if ($cantseries - 1 == $x) {
									$bloq = 'readonly';
								}
								$x++;
							}
						}
					}
				}
			} else {
				$conta = 0;
				$x = 0;
				unset($series);
				$series = array();
				$_SESSION['series'] = $series;
				$series = $_SESSION['series'];
				$_SESSION['Contador'] = $x;
			}

		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Ingresar Series </center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
		</div>
		<hr>
		<div id="cuerpo2" align="center">
			<table>
				<tr>
					<td id="label2">
						<strong> Codigo: </strong> <a> <?php echo $codigo ?> </a>
						<strong> Detalle: </strong> <a> <?php echo $detalle ?> </a>
						<strong> Cant.: </strong> <a> <?php echo $cantseries ?> </a>
					</td>
				</tr>
			</table>
		</div>
		<hr>
		<div id="cuerpo2">
			<form name="form2" action="ingseriestr.php" method="POST" style="text-align: center;">
				<p style="font-weight: bold;">
					Serie:
					<input name="serie" type="text" <?php echo $bloq ?> id="serie" size="30" value="<?php echo trim($serieleida); ?>" style="padding: 5px; margin: 5px; border: 1px solid #ccc;">
					<input name="productoid" type="hidden" id="productoid" value="<?php echo $idproducto; ?>">
					<input type="submit" name="submit" id="submit" value="-->" style="padding: 5px 10px; margin: 5px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
				</p>
			</form>
		</div>
		<div id="leyenda"> <?php echo $muestraleyenda2 ?></div>
		<div id="cuerpo2" align="center">
			<table id="series" align="center">
				<form name="form2" action="ingseriestr1.php" method="POST">
					<?php
					$zz = 0;
					$label = 1;
					while ($zz <= $xx) {
						// aqui aprovecho y voy a mostrar las series que ya han sido leidos		
					?>
						<p style="font-weight: bold" align="center"> <?php echo $label ?>:
							<input name="serie" type="text" id="serie" size="30" value="<?php echo $series[1][$zz] ?>">

							<?php
							if ($cantseries - 1 == $zz) {
							?>
						<p>
							<input type="submit" name="submit" id="submit" value="Grabar series ">
					<?php
							}
							$label++;
							$zz++;
						}

					?>
						</p>
				</form>
			</table>
		</div>
	<?php
			$_SESSION['series'] = $series;
			$_SESSION['Contador'] = $x;
			//echo $usuario.$base.$acceso.$secu.$bodega.$nomsuc.$cantseries.$idproducto; 
			//echo '<pre>', print_r($arreglo),'</pre>'; //arreglo original de datos de factura
			//echo '<pre>', print_r($arregloseries),'</pre>'; //arreglo de series ingresadas 
			//	echo '<pre>', print_r($series),'</pre>'; //arreglo de series ingresadas 
			//echo "Aqui se supone q se muestra el contador aumentado ".$x; 
			$_SESSION['series'] = $series;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['usuario'] = $usuario1;
			$_SESSION['productoid'] = $idproducto;
			$_SESSION['transfer'] = $transfer;
			$_SESSION['cantseries'] = $cantseries;
		}
	?>
	</div>
</body>