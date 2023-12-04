<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>

<script type="text/javascript">
	function setfocus() {
		document.getElementById("transferencia").focus();
	}

	function recargarTabla() {
		location.reload(); // Recargar la página para actualizar la tabla
	}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estiloVerificarTrasferencia.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">

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
			//echo "Bodega". $bodega; 
		?>
	</div>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Verificar Transferencias <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">

			<div class=\"table-responsive-xs\">

				<button id="recargarTabla" onclick="recargarTabla()">
					<i class="bi bi-arrow-clockwise"></i> Recargar Transferencias Pendientes 
				</button>

				<form name="formtransferencia2" action="verificartransferencias0.php" method="POST" width="100%">
					<br>
					<table align="center">
						<tr>
							<th colspan="5">Transferencias Pendientes </th>
						</tr>
						<tr>
							<th id="fila4"> B.Origen </th>
							<th id="fila4"> Trans.Agrup. </th>
							<th id="fila4"> Fecha</th>
							<th id="fila4"> Destino </th>
							<th id="fila4"> Transferencias</th>
						</tr>
						<?php

				

						require('../conexion_mssql.php');
						$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						$usuario = $_SESSION['usuario'];
						$bodega = $_SESSION['bodega'];
						//echo "bodega".$bodega.$base.$usuario.$acceso;
						$result = $pdo->prepare("LOG_TRANSFERENCIAS_PENDIENTES_VERIFICAR @BODEGA=:bodega, @acceso=:acceso");
						$result->bindParam(':bodega', $bodega, PDO::PARAM_STR);
						$result->bindParam(':acceso', $acceso, PDO::PARAM_STR);
						$result->execute();
						$arreglo = array();
						$x = 0;
						while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

							$arreglo[$x][1] = $row['Id'];
							$arreglo[$x][3] = $row['Destino'];
							$arreglo[$x][4] = $row['origen'];
							$arreglo[$x][5] = $row['fecha'];
							$arreglo[$x][6] = $row['CodDestino'];
							$x++;
						}
						$count = count($arreglo);
						//echo $count; 
						$y = 0;
						while ($y <= $count - 1) {
							$pdot = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$resultt = $pdo->prepare(" select TransferenciasID from inv_transferencias_agrupadas where agruparid=:agruparid");
							$resultt->bindParam(':agruparid', $arreglo[$y][1], PDO::PARAM_STR);
							$resultt->execute();
							$transferencias = "";
							while ($rowt = $resultt->fetch(PDO::FETCH_ASSOC)) {
								$transferencias = $transferencias . "/" . $rowt['TransferenciasID'];
							}
						?>
							<tr>
								<td id="fila4"> <a href="trakingverificartr.php?numero=<?php echo $arreglo[$y][1] ?>"> <?php echo $arreglo[$y][4] ?> </a></td>
								<td id="label2" align="center"> <input name="transferencia" type="submit" id="transferencia" size="20" value="<?php echo $arreglo[$y][1] ?>"> </td>
								<td id="fila4"> <?php echo $arreglo[$y][5] ?></td>
								<td id="fila4"> <input name="destino" type="hidden" id="destino" value="<?php $arreglo[$y][3] ?>"><?php echo $arreglo[$y][6] . " " . $arreglo[$y][3] ?></td>
								<td id="fila4"> <?php echo $transferencias ?> </td>
								<td id="fila4"> <input name="id" type="hidden" id="id" value="<?php echo $arreglo[$y][2] ?>"></td>
								<td id="box"> <input name="bodega" type="hidden" id="bodega" value="<?php echo $bodega ?>"></td>
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

</html>