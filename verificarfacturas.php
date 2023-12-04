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

<body onload="setfocus()">
	<div id="header" align="center">
		<!-- <?php
		session_start();
		if (isset($_SESSION['loggedin'])) {
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	= $_SESSION['acceso'];
			$bodega = $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			if ($base == 'CARTIMEX') {
				require 'headcarti.php';
			} else {
				require 'headcompu.php';
			}
			//echo "Bodega". $bodega; 
		?> -->
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Verificar Facturas <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">
			<div>
				<form name="formproducto" action="verificarfacturas0.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<td id="label">Factura # : </td>
							<td id="box"> <input name="secu" type="text" id="secu" size="30" value=" " autofocus></td>
							<td id="box"> <input name="bodega" type="hidden" id="bodega" size="30" value="<?php echo trim($bodega) ?>"> </td>
						</tr>
						<tr>
							<td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>
							<td id="label"> Consultar
								<input name="submit" id="submit" value="Grabar" src="assets\img\lupa.png" type="image">
								<a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
							</td>
						</tr>
					</table>
				</form>
			</div>
			<div class=\"table-responsive-xs\">
				<form name="formfactura2" action="verificarfacturas0.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<th colspan="5">Facturas Pendientes </th>
						</tr>
						<tr>
							<th id="fila4"> SId </th>
							<th id="fila4"> Cliente </th>
							<th id="fila4"> Factura </th>
							<th id="fila4"> Fecha </th>
							<th id="fila4"> </th>
						</tr>
						<?php

						$_SESSION['usuario'] = $usuario;
						$_SESSION['bodega'] = $bodega;
						//echo "bodega".$bodega.$base.$usuario.$acceso;
						include('conexion_mssql.php');

						//******Proceso aqui primero todas las facturas pendientes de GUIA para ver cual ha sido Anulada o 
						//Devuelta en su TOTALIDAD Y marcarla como ANULADA tanto en el SISCO como en SGL(facturaslistas) 
						$paso = 'PREPARADA';
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
							if ($countarr == 1) {
								$rowdd = $resultd->fetch(PDO::FETCH_ASSOC);
								//echo "datos a actualizar en sisco". $usuario.$fh.$devo ;
								include("conexion.php");
								$sqlde = "update covidsales set Anulada= '1' , anuladapor= '$usuario', fechaanulada= '$fh' where factura = '$devo' ";
								//echo $sqlde; 
								$resultde = mysqli_query($con, $sqlde);
							}
							$yd = $yd + 1;
						}
						//***************************xxxxxxxxxxxxxxxxxxxxxxx********************************


						$usuario = $_SESSION['usuario'];
						$bodega = $_SESSION['bodega'];
						$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_VERIFICAR @BODEGA=:bodega , @acceso=:acceso");
						$result->bindParam(':bodega', $bodega, PDO::PARAM_STR);
						$result->bindParam(':acceso', $acceso, PDO::PARAM_STR);
						//Executes the query
						$result->execute();
						$arreglo = array();
						$x = 0;
						while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
							$arreglo[$x][1] = $row['Sucursal'];
							$arreglo[$x][2] = $row['secuencia'];
							$arreglo[$x][3] = $row['fecha'];
							$arreglo[$x][4] = $row['nombodega'];
							$arreglo[$x][5] = $row['cliente'];
							$arreglo[$x][6] = $row['BodegaFAC'];
							$x++;
						}
						$count = count($arreglo);
						$y = 0;
						while ($y <= $count - 1) {
						?>
							<tr>
								<td id="fila4"> <a href="trakingverificar.php?secu=<?php echo $arreglo[$y][2] ?>&bodegaFAC=<?php echo $arreglo[$y][6] ?> "> <?php echo $arreglo[$y][1] ?> </a> </td>
								<td id="fila4"> <?php echo $arreglo[$y][5] ?></td>
								<td id="label2" align="center"> <input name="secu" type="submit" id="secu" size="20" value="<?php echo $arreglo[$y][2] . " " . $arreglo[$y][6] ?>"> </td>
								<td id="fila4"> <?php echo $arreglo[$y][3] ?> </td>
								<td id="fila4"> <?php echo $arreglo[$y][4] ?> </td>
								<td id="box"> <input name="bodega" type="hidden" id="bodega" size="30" value="<?php echo $bodega ?>"> </td>
							</tr>
						<?php
							$y = $y + 1;
						}
						?>
					</table>
				</form>
			</div>
		</div>
	<?php
			$_SESSION['usuario'] = $usuario;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['bodega'] = $bodega;
		} else {
			header("location: index.html");
		}
	?>
	</div>
</body>