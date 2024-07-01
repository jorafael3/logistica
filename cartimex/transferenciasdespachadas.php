<meta name="viewport" content="width=device-width, height=device-height">
<!DOCTYPE html>
<html>

<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/tablas.css">

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
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">
			<div id="izq"></div>
			<div id="centro"> <a class="titulo">
					<center> Transferencias despachadas </center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a></div>
		</div>
		<hr>
		<div id="cuerpo2" align="center">

			<div>
				<form name="formfactura" action="trakingtransferencias0.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<td id="label">Transferencia # </td>
							<td id="box"> <input name="transfer" type="text" id="transfer" size="30" value=""> </td>
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
				<form name="formproducto" action="transferenciasdespachadas.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<td id="label"> Fecha:
								<select name="tiporeporte">
									<option value='d'>Despacho</option>
									<option value='f'>Transferencias</option>
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
			<div class=\"table-responsive-xl\">
				<button id="btnExport" onclick="htmlTableToExcel('xlsx')">Exportar a excel</button>
				<form name="formfactura2">
					<table id="despacho" align="center">
						<tr>
							<th colspan="6"> </th>
						</tr>
						<tr>
							<th id="fila4" width="5%"> AgruparID </th>
							<th id="fila4" width="5%"> Transferencia </th>
							<th id="fila4" width="5%"> Fecha </th>
							<th id="fila4" width="8%"> Bodega Origen</th>
							<th id="fila4" width="8%"> Bodega Destino </th>
							<th id="fila4" width="20%"> Detalle </th>
							<th id="fila4" width="8%"> Prep. Jaula </th>
							<th id="fila4" width="8%"> Preparacion</th>
							<th id="fila4" width="8%"> Verificacion </th>
							<th id="fila4" width="8%"> Ing.Guia</th>
							<th id="fila4" width="8%"> Transporte </th>
							<th id="fila4" width="7%"> Guia# </th>
							<th id="fila4" width="8%"> Despachado </th>
							<th id="fila4" width="8%"> ESTADO COURIER </th>
							<th id="fila4" width="8%"> FECHA COURIER </th>
							<th id="fila4" width="8%"> HORA COURIER </th>
						</tr>
						<?php
						if ($desde <> '') {
							//echo "Bodega".$bodega;
							require('../conexion_mssql.php');
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result = $pdo->prepare("LOG_TRANSFERENCIAS_DESPACHADAS_SGL @bodega=:bodega , @acceso=:acceso, @desde=:desde, @hasta=:hasta, @tipofecha=:tipofecha");
							$result->bindParam(':bodega', $bodega, PDO::PARAM_STR);
							$result->bindParam(':acceso', $acceso, PDO::PARAM_STR);
							$result->bindParam(':desde', $desde, PDO::PARAM_STR);
							$result->bindParam(':hasta', $hasta, PDO::PARAM_STR);
							$result->bindParam(':tipofecha', $tipofecha, PDO::PARAM_STR);
							$result->execute();
							$arreglodesp = array();
							$x = 0;
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
								$arreglodesp[$x][1] = $row['GrupoID'];
								$arreglodesp[$x][2] = $row['Transferencia'];
								$arreglodesp[$x][3] = $row['Origen'];
								$arreglodesp[$x][4] = $row['Destino'];
								$arreglodesp[$x][5] = $row['Detalle'];
								$arreglodesp[$x][6] = $row['prepajaula'];
								$arreglodesp[$x][7] = $row['fprepa'];
								$arreglodesp[$x][8] = $row['fverif'];
								$arreglodesp[$x][9] = $row['fguia'];
								$arreglodesp[$x][10] = $row['transporte'];
								$arreglodesp[$x][11] = $row['guia'];
								$arreglodesp[$x][12] = $row['fdesp'];
								$arreglodesp[$x][13] = $row['Fecha'];
								$arreglodesp[$x][14] = $row['ESTADO_DESPACHO'];
								$arreglodesp[$x][15] = $row['FECHA_DESPACHO'];
								$arreglodesp[$x][16] = $row['HORA_DESPACHO'];
								$x++;
							}
							//echo '<pre>', print_r($arreglodesp),'</pre>';	
							$count = count($arreglodesp);
							$y = 0;
							while ($y <= $count - 1) {
								$numfac = $arreglodesp[$y][2];

						?>
								<tr>
									<td id="fila4"> <?php echo $arreglodesp[$y][1] ?></a></td>
									<td id="fila4"> <a href="trakingtransferencias0.php?transfer=<?php echo $arreglodesp[$y][2] ?>"> <?php echo $arreglodesp[$y][2] ?></td>
									<td id="fila4"> <?php echo $arreglodesp[$y][13] ?></td>
									<td id="fila4"> <?php echo $arreglodesp[$y][3] ?></td>
									<td id="fila4"> <?php echo $arreglodesp[$y][4] ?> </td>
									<td id="fila4"> <?php echo $arreglodesp[$y][5] ?> </td>
									<td id="fila4"> <?php echo $arreglodesp[$y][6] ?> </td>
									<td id="fila4"> <?php echo $arreglodesp[$y][7] ?> </td>
									<td id="fila4"> <?php echo $arreglodesp[$y][8] ?> </td>
									<td id="fila4"> <?php echo $arreglodesp[$y][9] ?> </td>
									<td id="fila4"> <?php echo $arreglodesp[$y][10] ?> </td>
									<td id="fila4"> <?php echo $arreglodesp[$y][11] ?> </td>
									<td id="fila4"> <?php echo $arreglodesp[$y][12] ?> </td>
									<td id="fila4"> <?php echo $arreglodesp[$y][14] ?> </td>
									<td id="fila4"> <?php echo $arreglodesp[$y][15] ?> </td>
									<td id="fila4"> <?php echo $arreglodesp[$y][16] ?> </td>
								</tr>
						<?php
								$y = $y + 1;
							}
						}
						?>
					</table>
				</form>
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


</body>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script>
	console.log("asdasdasd");
	function setfocus() {
		//document.getElementById("codigo").focus();
	}
	function htmlTableToExcel(type) {
		var data = document.getElementById('despacho');
		var excelFile = XLSX.utils.table_to_book(data, {
			sheet: "sheet1"
		});
		XLSX.write(excelFile, {
			bookType: type,
			bookSST: true,
			type: 'base64'
		});
		XLSX.writeFile(excelFile, 'ExportedFile:HTMLTableToExcel.' + type);
	}
</script>


</html>