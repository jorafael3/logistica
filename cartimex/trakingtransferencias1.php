<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script>
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/tablas.css">

<body>
	<div id="header" align="center">
		<?php
		//error_reporting(E_ALL);
		//ini_set('display_errors','On');
		session_start();
		if (isset($_SESSION['loggedin'])) {
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	= $_SESSION['acceso'];
			$transfer = $_SESSION['transfer'];
			$bodega	= $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$vdesp = $_SESSION['vdesp'];
			$usuario = trim($usuario);
			//echo $usuario; 
			$usuario1 = $usuario;
			if ($base == 'CARTIMEX') {
				require '../headcarti.php';
			} else {
				require '../headcompu.php';
				$_SESSION['base'] = $base;
				$Nota = " ";
			}
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Datos de Transferencia </center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>

		</div>
		<hr>

		<?php
			// $codigo= trim($codigo);
			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare('LOG_Detalle_Transferencias2_individual @numero=:transfer');
			$result->bindParam(':transfer', $transfer, PDO::PARAM_STR);
			$result->execute();
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				if ($row['Section'] == 'HEADER') {
		?>
				<div id="cuerpo2" class=\"table-responsive-xs\">
					<div align="center" width=100%>
						<table border=2 width=100% id="traking">
							<tr>
								<td id="td1"> <strong> Transferencia ID:</strong> </td>
								<td id="label4" colspan="5"> <a> <?php echo $row['Idtransfer']; ?> </a></td>
							</tr>
							<tr>
								<td id="td1"> <strong> B.Origen :</strong> </td>
								<td id="label4"> <a> <?php echo $row['BOrigen'] . "   " . $row['NomOrigen']; ?> </a></td>
								<td id="td1"> <strong> B.Origen : </strong> </td>
								<td id="label4" width="20%"> <a> <?php echo $row['CodDestino'] . "   " . $row['BDestino']; ?> </a></td>
								<td id="label5" colspan="2" align="right"> <a href="consultarseriestr.php"> CONSULTAR SERIES </a></td>
							</tr>
							<tr>
								<td id="td1"> <strong> Fecha :</strong> </td>
								<td id="label4"> <a> <?php echo $row['Fecha']; ?> </a></td>
								<td id="td1"> <strong> Detalle: </strong> </td>
								<td id="label4" colspan="3"> <a> <?php echo $row['Detalle']; ?> </a> </td>
							</tr>
							<tr>
								<td id="td1"> <strong> Prep.Jaula.Por: </strong> </td>
								<td id="label4"> <a> <?php echo $row['PREPARAJAULA']; ?> </a> </td>
								<td id="td1"> <strong> Fecha Jaula: </strong> </td>
								<td id="label4" colspan="3"> <a> <?php echo $row['FYHJAULA']; ?> </a> </td>
							</tr>
							<tr>
								<td id="td1"> <strong> Preparado.Por:</strong> </td>
								<td id="label4"> <a> <?php echo $row['PREPARADOPOR']; ?> </a> </td>
								<td id="td1"> <strong> Fecha Preparado: </strong> </td>
								<td id="label4" colspan="3"> <a> <?php echo $row['FECHAYHORA']; ?> </a> </td>
							</tr>
							<tr>
								<td id="td1"> <strong> Verificado Por: </strong> </td>
								<td id="label4"> <a> <?php echo $row['VERIFICADO']; ?> </a> </td>
								<td id="td1"> <strong> Fecha Verificado: </strong> </td>
								<td id="label4" colspan="3"> <a> <?php echo $row['FECHAVERIFICADO']; ?> </a> </td>
							</tr>
							<tr>
								<td id="td1"> <strong> Ing.Guia Por: </strong> </td>
								<td id="label4"> <a> <?php echo $row['GUIAPOR']; ?> </a> </td>
								<td id="td1"> <strong> Fecha Guia: </strong> </td>
								<td colspan="3" id="label4"> <a> <?php echo $row['FECHAGUIA']; ?> </a> </td>
							</tr>
							<tr>
								<td id="td1"> <strong> Transporte: </strong> </td>
								<td id="label4"> <a> <?php echo $row['TRANSPORTE']; ?> </a> </td>
								<td id="td1"> <strong> Guia #: </strong> </td>
								<td id="label4" colspan="3"> <a> <?php echo $row['GUIA']; ?> </a> </td>
							</tr>
							<tr>
								<td id="td1"> <strong> Despachado Por: </strong> </td>
								<td id="label4"> <a> <?php echo $row['DESPACHADO']; ?> </a> </td>
								<td id="td1"> <strong> Fecha Despachado: </strong> </td>
								<td id="label4" colspan="3"> <a> <?php echo $row['FECHADESPACHADO']; ?> </a> </td>

							</tr>
							<tr>
								<td id="td1"> <strong> Peso: </strong> </td>
								<td id="label4" colspan="1"> <a> <?php echo $row['Comentario']; ?> </a> </td>
								<td id="td1"> <strong> Bultos: </strong> </td>
								<td id="label4" colspan="2"> <a> <?php echo $row['Bultos']; ?> </a> </td>
							</tr>
							<tr>
								<td id="td1"> <strong></strong> </td>
								<td id="label4" colspan="1"> </td>
								<td id="td1"> <strong> ESTADO COURIER: </strong> </td>
								<td id="label4" colspan="2"> <a> <?php echo $row['ESTADO_DESPACHO']; ?> </a> </td>
							</tr>
							<tr>
								<td id="td1"> <strong></strong> </td>
								<td id="label4" colspan="1"> </td>
								<td id="td1"> <strong> FECHA COURIER: </strong> </td>
								<td id="label4" colspan="2"> <a> <?php echo $row['FECHA_DESPACHO'] . " - " . $row['HORA_DESPACHO']; ?> </a> </td>
							</tr>
						</table>
						<table border=2 width=100% id="traking" align="center">
							<tr>
								<th colspan=11><a>Detalle de Productos </a></th>
							</tr>
							<th bgcolor='$color1' align=center colspan=1><B>Código</B></th>
							<th bgcolor='$color1' align=center colspan=8><B>Descripción</B></th>
							<th bgcolor='$color1' align=center colspan=1><B>Cant.</B></th>
							<?php
						} else {
							if ($row['Section'] == 'DETALLE') { ?>
								<tr>
									<td align='left' colspan=1><?php echo utf8_encode($row['AgruparID']) ?></td>
									<td align='center' colspan=8> <?php echo $row[utf8_decode('Codigo')] ?></td>
									<td align='right' colspan=1><?php echo number_format($row['Cantidad'])  ?></td>
								</tr>
							<?php
							} else {
								$Totalt = 0;
								$Totalt =  $row['Cantidad'];
							?>
								<tr>
									<td colspan="7"> </td>
									<th bgcolor='$color1' align=center height=0><B>Total Unidades </B></th>
									<td align='right' colspan=3><?php echo number_format($Totalt) ?></td>
								</tr>
				<?php
							}
						}
					}
					//echo "User ".$usuario1; 
					$_SESSION['usuario'] = $usuario1;
					$_SESSION['id'] = $Id;
					$_SESSION['base'] = $base;
					$_SESSION['acceso'] = $acceso;
					$_SESSION['codigo'] = $codigo;
					$_SESSION['bodega'] = $bodega;
					$_SESSION['nomsuc'] = $nomsuc;
					$_SESSION['transfer'] = $transfer;
				} else {
					header("location: index.html");
				}

				?>
						</table>
						<form name="formpreparar" action="#" method="POST" width="75%">
							<table align="center">
								<tr>
									<a href="transferenciasdespachadas.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a>
									</td>
								</tr>
							</table>
						</form>

					</div>
				</div>

</body>

</html>