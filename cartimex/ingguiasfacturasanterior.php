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
<link rel="stylesheet" type="text/css" href="../css/tablas.css">

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

			<div>
				<form name="formfactura" action="ingguiasfacturas0.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<td id="label">Secuencia </td>
							<td id="box"> <input name="secu" type="text" id="secu" size="30" value=""> </td>
							<td id="box"> <input name="bodega" type="hidden" id="bodega" value="<?php echo trim($bodega) ?>"> </td>
						</tr>
						<tr>

							<td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="..\assets\img\refresh.png"></img></a></td>
							<td id="label"> Enviar
								<input name="submit" id="submit" value="Grabar" src="..\assets\img\lupa.png" type="image">
								<a href="../menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
							</td>
						</tr>
					</table>
				</form>
			</div>

			<div class=\"table-responsive-xs\">
				<form name="formfactura2" action="ingguiasfacturas0.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<th colspan="12">Ingresar Guias Facturas </th>
						</tr>
						<tr>
							<th id="fila4"> Bodega </th>
							<th id="fila4"> Factura </th>
							<th id="fila4"> Cliente </th>
							<th id="fila4"> Fecha </th>
							<th id="fila4"> </th>
							<th id="fila4"> Vendedor</th>
							<th id="fila4"> Estado </th>
							<th id="fila4"> V.Factura </th>
							<th id="fila4"> Tipo Pedido </th>
							<th id="fila4"> T.Transporte</th>
						</tr>
						<?php

						$_SESSION['usuario'] = $usuario;
						$_SESSION['bodega'] = $bodega;
						//echo "bodega".$bodega.$base.$usuario.$acceso;
						include('../conexion_mssql.php');
						$usuario = $_SESSION['usuario'];
						$bodega = $_SESSION['bodega'];
						$paso = 'VERIFICADA';
						$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						$usuario = $_SESSION['usuario'];
						$bodega = $_SESSION['bodega'];
						$result0 = $pdo0->prepare("LOG_FACTURAS_PENDIENTES_DEVUELTAS @BODEGA=:bodega , @acceso=:acceso, @Estado=:estado");
						$result0->bindParam(':bodega', $bodega, PDO::PARAM_STR);
						$result0->bindParam(':acceso', $acceso, PDO::PARAM_STR);
						$result0->bindParam(':estado', $paso, PDO::PARAM_STR);
						$result0->execute();
						$arreglod = array();
						$xd = 0;
						while ($row0 = $result0->fetch(PDO::FETCH_ASSOC)) {
							$arreglod[$xd][2] = $row0['secuencia'];
							//echo "Secuencia". $arreglod[$xd][2]; 
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

							$countarr = $resultd->rowcount();
							//echo "Trae registro".$countarr; 
							//die();
							$yd = $yd + 1;
						}
						$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						//new PDO($dsn, $sql_user, $sql_pwd);
						//Select Query


						$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_GUIAS_SELECT @BODEGA=:bodega , @acceso=:acceso");
						$result->bindParam(':bodega', $bodega, PDO::PARAM_STR);
						$result->bindParam(':acceso', $acceso, PDO::PARAM_STR);
						//Executes the query
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
								<td id="fila4"> <a href="trakingguia.php?secu=<?php echo $arreglo[$y][2] ?>"> <?php echo $arreglo[$y][1] ?></a> </td>

								<?php
								if ($activar == "label") { ?>
									<td id="fila4" align="center"> <a> <?php echo $arreglo[$y][2] ?></a></td>

								<?php
								} else { ?>
									<td id="fila4" align="center"> <a href="ingguiasfacturas0.php?secu=<?php echo $arreglo[$y][2] ?>"> <?php echo $arreglo[$y][2] ?></a> </td>
								<?php
								} ?>
								<td id="fila4" align="center"> <a> <?php echo $arreglo[$y][6] ?></a></td>
								<td id="fila4"> <?php echo $arreglo[$y][3] ?> </td>
								<td id="fila4"> <?php echo $arreglo[$y][4] ?> </td>
								<td id="fila4"> <?php echo $arreglo[$y][5] ?> </td>
								<td id="fila4"> <?php echo $arreglo[$y][8] ?> </td>
								<td id="filax"> <?php echo $arreglo[$y][9] ?> </td>
								<td id="fila4"> <?php echo $arreglo[$y][10] ?> </td>
								<td id="fila4"> <?php echo $arreglo[$y][11] ?> </td>
								<td id="box"> <input name="bodega" type="hidden" id="bodega" size="30" value="<?php echo $bodega ?>"> </td>
							</tr>
						<?php
							$activar = '';
							$y = $y + 1;
						}
						?>

					</table>
				</form>
			</div>
			<a align="center"> Se activa para ingresar la guia cuando: <br>
				* La factura NO ESTA BLOQUEADA </a>

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