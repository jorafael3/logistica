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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

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
			$PMDROP = $_SESSION['PMDROP'];
			$drop = $_SESSION['drop'];

			$drop_gye = $_SESSION['drop_gye'];
			$drop_uio = $_SESSION['drop_uio'];
			if ($base == 'CARTIMEX') {
				require 'headcarti.php';
			} else {
				require 'headcompu.php';
			}

			//echo "Usuario". $usuario; 
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Preparar Facturas <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">

			<div>
				<form name="formfactura" action="prepararfacturas0.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<td id="label">Secuencia</td>
							<td id="box"> <input name="secu" type="text" id="secu" size="30" value=""> </td>
							<td id="box"> <input name="bodega" type="hidden" id="bodega" value="<?php echo trim($bodega) ?>"> </td>
						</tr>
						<tr>
							<td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>
							<td id="label"> Preparar
								<input name="submit" id="submit" value="Grabar" src="assets\img\lupa.png" type="image">
								<a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
							</td>
						</tr>
					</table>
				</form>
			</div>


			<div class=\"table-responsive-xs\">
				<form name="formfactura2" action="prepararfacturas0.php" method="POST" width="100%">
					<table align="center">
						<tr>
							<th colspan="6">Facturas Pendientes </th>
						</tr>
						<tr>
							<th id="fila4"> SId </th>
							<th id="fila4"> Cliente </th>
							<th id="fila4"> Factura </th>
							<th id="fila4"> Fecha </th>
							<th id="fila4"> </th>
							<?php
							if ($PMDROP != 1) {
							?>
								<th id="fila4"> Comentario </th>

							<?php

							}
							?>
							<?php
							if ($PMDROP == 1) {
							?>
								<th id="fila4"> VALOR</th>
								<th id="fila4"> SALDO</th>
							<?php

							}
							?>

						</tr>
						<?php

						$_SESSION['usuario'] = $usuario;
						$_SESSION['bodega'] = $bodega;
						//echo "bodega".$bodega.$base.$usuario.$acceso;
						require('conexion_mssql.php');
						$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						//new PDO($dsn, $sql_user, $sql_pwd);
						//Select Query
						$usuario = $_SESSION['usuario'];
						$bodega = $_SESSION['bodega'];

						$result2 = $pdo->prepare("SELECT distinct
							f.ID,
							f.Secuencia as secuencia, 
							--dt.ProductoID,
							cantidad = sum(dt.Cantidad),
							isnull(dv.devuelto,0) as devuelto, total = sum(dt.Cantidad) - isnull(dv.devuelto,0) 
							from VEN_FACTURAS  f with(NOLOCK)
							inner join VEN_FACTURAS_DT dt with(NOLOCK)
							on f.id = dt.FacturaID 
							left outer join(
								select d.facturaid,devuelto = sum(pr.Cantidad) from CLI_CREDITOS d
								inner join CLI_CREDITOS_PRODUCTOS pr
								on pr.CréditoID = d.ID
								where d.Anulado = 0 and d.Tipo = 'VEN-DE'
								group by d.facturaid
							) dv on dv.facturaid = f.ID 
							where f.Anulado= 0 and f.Fecha >= '20230101' 
							--and f.Secuencia = '022-002-000032572'
							--and f.Sucursalid 
							--and f.id in 
							--(select factura from facturaslistas with (nolock) WHERE anulado= '0' and tipo = 'VEN-FA' AND ESTADO='VERIFICADA')  
							group by f.ID,f.Secuencia,dv.devuelto
							having sum(dt.Cantidad) = isnull(dv.devuelto,0)
						");
						$LISTA = [];
						if ($result2->execute()) {
							$res = $result2->fetchAll(PDO::FETCH_ASSOC);
							foreach ($res as $row) {
								array_push($LISTA, $row["secuencia"]);
							}
							//var_dump($res);
						} else {
							$err = $result2->errorInfo();
							//echo json_encode($err);
						}

						if ($drop == 1 || $PMDROP == 1) {
							$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_SELECT_DROPSHIPING 
							@gye=:bodega , 
							@uio=:acceso");
							$result->bindParam(':bodega', $drop_gye, PDO::PARAM_STR);
							$result->bindParam(':acceso', $drop_uio, PDO::PARAM_STR);
						} else {
							$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_SELECT @BODEGA=:bodega , @acceso=:acceso");
							$result->bindParam(':bodega', $bodega, PDO::PARAM_STR);
							$result->bindParam(':acceso', $acceso, PDO::PARAM_STR);
						}


						//Executes the query
						$result->execute();
						$arreglo = array();
						$x = 0;
						while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
							if (in_array(trim($row['secuencia']), $LISTA)) {
							} else {
								$arreglo[$x][1] = $row['Sucursal'];
								$arreglo[$x][2] = $row['secuencia'];
								$arreglo[$x][3] = $row['fecha'];
								$arreglo[$x][4] = $row['nombodega'];
								$arreglo[$x][5] = $row['Detalle'];
								$arreglo[$x][6] = $row['nota'];
								$arreglo[$x][7] = $row['BodegaFAC'];
								$arreglo[$x][8] = $row['saldo'];
								$arreglo[$x][9] = $row['TOTAL'];
								$x++;
							}
						}
						$count = count($arreglo);
						$y = 0;
						if ($PMDROP == 1) {

							include("conexion.php");


							while ($y <= $count - 1) {
								$secu = $arreglo[$y][2];
								$sql1 = "SELECT a.*, 
								p.bodega as bodegaret, 
								date_format(a.paymentez,'%d/%m/%Y') as fechapay,
								date_format(a.tcfecha,'%d/%m/%Y') as tcfecha,date_format(a.l2pfecha,'%d/%m/%Y') as l2pfecha,
								c.sucursalid as sucursal  FROM covidsales a
								left outer join covidpickup p on p.orden= a.secuencia
								left outer join sisco.covidciudades c on p.bodega= c.almacen
								where a.factura = trim('$secu') and a.anulada<> '1'  ";
								$result1 = mysqli_query($con, $sql1);
								$row1 = mysqli_fetch_array($result1);
								// echo ($row1["saldo"]);
								$FORMA_PAGO = isset($row1["valortotal"]) ? $row1["valortotal"] : "";
								// $FECHA = isset($row1["saldo"]) ? $row1["saldo"] : "";
						?>
								<tr>
									<td id="fila4"> <?php echo $arreglo[$y][1] ?></td>
									<td id="fila4"> <?php echo $arreglo[$y][5] ?></td>
									<td id="label2" align="center"> <input name="secu" type="submit" id="secu" size="20" value="<?php echo $arreglo[$y][2] . " " . $arreglo[$y][7] ?>"> </td>
									<td id="fila4"> <?php echo $arreglo[$y][3] ?> </td>
									<td id="fila4"> <?php echo $arreglo[$y][4] ?> </td>
									<!-- <td id="fila4"> <?php echo $arreglo[$y][6] ?> </td> -->
									<td id="fila4" style="text-align: right; font-weight: bold;font-size: 14px;"> <?php echo round($arreglo[$y][9], 2) ?> </td>
									<td id="fila4" style="text-align: right; font-weight: bold;font-size: 14px;"> <?php echo round(floatval($arreglo[$y][8]), 2) ?> </td>
									<td id="label2"> <input name="bodegaFAC" type="hidden" id="bodegaFAC" size="30" value="<?php echo $bodega ?>"> </td>
								</tr>
							<?php
								$y = $y + 1;
							}
						} else {
							while ($y <= $count - 1) {
							?>

								<tr>
									<td id="fila4"> <?php echo $arreglo[$y][1] ?></td>
									<td id="fila4"> <?php echo $arreglo[$y][5] ?></td>
									<td id="label2" align="center"> <input name="secu" type="submit" id="secu" size="20" value="<?php echo $arreglo[$y][2] . " " . $arreglo[$y][7] ?>"> </td>
									<td id="fila4"> <?php echo $arreglo[$y][3] ?> </td>
									<td id="fila4"> <?php echo $arreglo[$y][4] ?> </td>
									<td id="fila4"> <?php echo $arreglo[$y][6] ?> </td>
									<td id="label2"> <input name="bodegaFAC" type="hidden" id="bodegaFAC" size="30" value="<?php echo $bodega ?>"> </td>
								</tr>
						<?php
								$y = $y + 1;
							}
						}

						?>
					</table>
				</form>
			</div>
			<a align="center"> ** Si la factura SOLO contiene SERVICIOS o GARANTIAS deben seleccionar <strong> FINALIZAR PREPARACION </strong><br>
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