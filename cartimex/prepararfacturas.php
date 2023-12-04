<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
	function setfocus() {
		document.getElementById("secu").focus();
	}
</script>
<link href="../estilos/estiloprepararFactura.css" rel="stylesheet" type="text/css">
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
			//	echo "Usuario".$usuario; 
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
					<center> Preparar Facturas <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">
			<br>
			<div>
				<form name="formfactura" action="prepararfacturas0.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<td id="label">Secuencia</td>
							<td id="box"> <input name="secu" type="text" id="secu" size="30" value=""> </td>
							<td id="box"> <input name="bodega" type="hidden" id="bodega" value="<?php echo trim($bodega) ?>"> </td>
						</tr>
						<tr>
							<td colspan="3" id="label">
								<a href="javascript:window.location.href=window.location.href" style="text-decoration:none">
									<img src="..\assets\img\refresh.png"></img>
								</a>
								Preparar
								<input name="submit" id="submit" value="Grabar" src="..\assets\img\lupa.png" type="image">
								<a href="../menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
							</td>
						</tr>
					</table>
				</form>
			</div>

			<br>
			<div class="table-responsive-xs">
				<form name="formfactura2" action="prepararfacturas0.php" method="POST" style="width: 100%;">
					<table align="center" class="table table-bordered">
						<tr>
							<th colspan="6" style="text-align: center;">Facturas Pendientes</th>
						</tr>
						<tr>
							<th id="fila4" style="text-align: center;">Bodega</th>
							<th id="fila4" style="text-align: center;">Cliente</th>
							<th id="fila4" style="text-align: center;">Factura</th>
							<th id="fila4" style="text-align: center;">Fecha</th>
							<!-- <th id="fila4"></th> -->
							<th id="fila4" style="text-align: center;">Tipo Pedido</th>
							<th id="fila4" style="text-align: center;">Estado</th>
						</tr>

						<?php

						$_SESSION['usuario'] = $usuario;
						$_SESSION['bodega'] = $bodega;
						//echo "Esto envio".$usuario. $base. $acceso. $bodega;
						require('../conexion_mssql.php');
						$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						$usuario = $_SESSION['usuario'];
						$bodega = $_SESSION['bodega'];
						$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_SELECT @BODEGA=:bodega , @acceso=:acceso");
						$result->bindParam(':bodega', $bodega, PDO::PARAM_STR);
						$result->bindParam(':acceso', $acceso, PDO::PARAM_STR);
						//Executes the query
						$result->execute();
						$arreglo = array();
						$x = 0;
						while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
							$arreglo[$x][1] = $row['bodegsuc'];
							$arreglo[$x][2] = $row['secuencia'];
							$arreglo[$x][3] = $row['fecha'];
							$arreglo[$x][4] = $row['Detalle'];
							$arreglo[$x][5] = $row['TipoP'];
							$arreglo[$x][6] = $row['Bloqueado'];
							/*$arreglo[$x][4]=$row['nombodega'];
								
								*/
							$x++;
						}
						$count = count($arreglo);
						$y = 0;
						while ($y <= $count - 1) {
						?>
							<tr>
								<td id="fila4" style="text-align: center;"><?php echo $arreglo[$y][1] ?></td>
								<td id="fila4" style="text-align: left;"><?php echo $arreglo[$y][4] ?></td>
								<td id="label2" align="center" style="text-align: center;">
									<input name="secu" type="submit" id="secu" size="20" value="<?php echo $arreglo[$y][2] ?>" onclick="return checkEstadoBloqueada('<?php echo $arreglo[$y][6] ?>');">
								</td>

								<!-- <script>
									function checkEstadoBloqueada(estado) {
										if (estado === 'BLOQUEADA') {
											alert('¡La factura está bloqueada!');
											// window.location.href = 'prepararfacturas.php'; // Redirige a prepararfacturas.php
											return false; // Evita que se avance a la siguiente página
										}
										return true; // Permite avanzar a la siguiente página si no está bloqueada
									}
								</script> -->
								<td id="fila4" style="text-align: center;"><?php echo $arreglo[$y][3] ?></td>
								<!-- <td id="fila4" style="text-align: center;"><?php   ?></td> -->
								<td id="fila4" style="text-align: center;"><?php echo $arreglo[$y][5] ?></td>
								<td id="fila4" style="text-align: center;<?php if ($arreglo[$y][6] == 'BLOQUEADA') echo ' color: red;'; ?>"><?php echo $arreglo[$y][6] ?></td>

								<td id="box"><input name="bodega" type="hidden" id="bodega" size="30" value="<?php echo $bodega ?>"></td>
							</tr>
						<?php
							$y = $y + 1;
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