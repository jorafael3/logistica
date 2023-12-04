<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script src="https://code.jquery.com/jquery-git.js"></script>


<script type="text/javascript">
	function setfocus() {
		document.getElementById("numero").focus();
	}
</script>
<script language="javascript">
	/*$(document).ready(function () 
{
	$("form :checkbox").click(function () {
	var trans = this.value; 
	window.location.href  = "preparartransferencias.php?trans=" + trans;
	//alert('hi : ' + this.value);
	});
});*/
</script>
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
			$usuario1 = $usuario;
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
					<center> Preparar Transferencias Agrupadas <?php echo substr($nomsuc, 10, 20);  ?> </center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
		</div>
		<hr>
		<div id="cuerpo2" align="center">

			<div class=\"table-responsive-xs\">
				<form name="formtransferencia2" action="preparartransferencias0.php" method="POST" width="100%">
					<table align="center">
						<tr>
							<th colspan="7">Transferencias Agrupadas Pendientes </th>
						</tr>
						<tr>
							<th id="fila4">Grupo</th>
							<th id="fila4"> B.Origen </th>
							<th id="fila4"> Fecha</th>
							<th id="fila4" colspan="2"> Destino</th>
							<th id="fila4">Transferencias</th>
						</tr>
						<?php

						$_SESSION['usuario'] = $usuario;

						require('../conexion_mssql.php');
						$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);

						$usuario = $_SESSION['usuario'];
						$bodega = $_SESSION['bodega'];
						//echo "bodega".$bodega.$base.$usuario.$acceso;
						$result = $pdo->prepare("LOG_TRANSFERENCIAS_PENDIENTES_SELECT_PREPARAR @BODEGA=:bodega, @acceso=:acceso");
						$result->bindParam(':bodega', $bodega, PDO::PARAM_STR);
						$result->bindParam(':acceso', $acceso, PDO::PARAM_STR);
						$result->execute();
						$arreglo = array();
						$x = 0;
						while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
							//$arreglo[$x][2]=$row['Numero'];
							//$arreglo[$x][1]=$row['Id'];
							$arreglo[$x][3] = $row['Destino'];
							$arreglo[$x][4] = $row['origen'];
							$arreglo[$x][5] = $row['fecha'];
							$arreglo[$x][6] = $row['CodDestino'];
							$arreglo[$x][7] = $row['Grupo'];
							$x++;
						}
						$count = count($arreglo);
						$y = 0;
						echo $arreglo[$y][7];
						while ($y <= $count - 1) {
							$pdot = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$resultt = $pdo->prepare(" select TransferenciasID from inv_transferencias_agrupadas where agruparid=:agruparid");
							$resultt->bindParam(':agruparid', $arreglo[$y][7], PDO::PARAM_STR);
							$resultt->execute();
							$transferencias = "";
							while ($rowt = $resultt->fetch(PDO::FETCH_ASSOC)) {
								$transferencias = $transferencias . "/" . $rowt['TransferenciasID'];
							}
						?>
							<tr>
								<td id="fila4" style="text-align: center;">
									<input name="transfer" type="submit" id="transfer" size="20" value="<?php echo $arreglo[$y][7] ?>">
								</td>
								<td id="filax"> <?php echo $arreglo[$y][4] ?></td>
								<td id="fila4"> <?php echo $arreglo[$y][5] ?></td>
								<td id="filax"> <?php echo $arreglo[$y][6] ?></td>
								<td id="fila4"> <?php echo $arreglo[$y][3] ?></td>
								<td id="fila4"> <?php echo $transferencias ?> </td>
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

			$usuario = $usuario1;
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