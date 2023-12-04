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
				require 'headcarti.php';
			} else {
				require 'headcompu.php';
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
					<center> Guias Facturass <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>

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

							<td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>
							<td id="label"> Enviar
								<input name="submit" id="submit" value="Grabar" src="assets\img\lupa.png" type="image">
								<a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
							</td>
						</tr>
					</table>
				</form>
			</div>


			<div class=\"table-responsive-xs\">
				<form name="formfactura2" action="ingguiasfacturas0.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<th colspan="13">Ingresar Guias Facturas </th>
						</tr>
						<tr>
							<th id="fila4"> SId </th>
							<th id="fila4"> Factura </th>
							<th id="fila4"> Cliente </th>
							<th id="fila4"> Fecha </th>
							<th id="fila4"> </th>
							<th id="fila4"> Forma de Pago </th>
							<th id="fila4"> F.Aprobacion</th>
							<th id="fila4"> Estado </th>
							<th id="fila4"> V.Factura </th>
							<th id="fila4"> Por Cancelar </th>
							<th id="fila4"> Entrega</th>
							<th id="fila4"> Bodega Retiro</th>
							<th id="fila4"> Hacer Pedido </th>
						</tr>
						<?php

						$_SESSION['usuario'] = $usuario;
						$_SESSION['bodega'] = $bodega;
						//echo "bodega".$bodega.$base.$usuario.$acceso;
						include('conexion_mssql.php');

						//******Proceso aqui primero todas las facturas pendientes de GUIA para ver cual ha sido Anulada o
						//Devuelta en su TOTALIDAD Y marcarla como ANULADA tanto en el SISCO como en SGL(facturaslistas)
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



						$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						//new PDO($dsn, $sql_user, $sql_pwd);
						//Select Query
						$usuario = $_SESSION['usuario'];
						$bodega = $_SESSION['bodega'];
						// echo $bodega;
						// echo $bodega;
						$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_GUIAS_SELECT @BODEGA=:bodega , @acceso=:acceso");
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
							$arreglo[$x][5] = number_format($row['saldo'], 2);
							$arreglo[$x][6] = $row['Detalle'];
							$arreglo[$x][7] = $row['bodegsuc'];
							$arreglo[$x][8] = number_format($row['TOTAL'], 2);
							$arreglo[$x][9] = number_format($row['rete'], 2);
							$arreglo[$x][10] = $row['saldo'];
							$arreglo[$x][11] = $row['rete'];
							$arreglo[$x][12] = $row['BodegaFAC'];
							$arreglo[$x][13] = $row['pedido'];
							$arreglo[$x][14] = $row['estado2'];
							$x++;
						}
						$count = count($arreglo);
						$y = 0;
						while ($y <= $count - 1) {
							$numfac = $arreglo[$y][2];
							// echo $numfac;
							include("conexion.php");
							$sql1 = "SELECT a.*, p.bodega as bodegaret, date_format(a.paymentez,'%d/%m/%Y') as fechapay,
										date_format(a.tcfecha,'%d/%m/%Y') as tcfecha,date_format(a.l2pfecha,'%d/%m/%Y') as l2pfecha,
										c.sucursalid as sucursal  FROM covidsales a
										left outer join covidpickup p on p.orden= a.secuencia
										left outer join sisco.covidciudades c on p.bodega= c.almacen
										where a.factura = trim('$numfac') and a.anulada<> '1'  ";
							$result1 = mysqli_query($con, $sql1);
							$conrow = $result1->num_rows;
							//echo "Contad". $conrow;
							if ($conrow > 0) {
								$row1 = mysqli_fetch_array($result1);
								$estado = $row1['estado'];
								$formapago = $row1['formapago'];
								$bodegaretiro = $row1['bodegaret'];
								$sucuret = $row1['sucursal'];
								$tcfecha = $row1['tcfecha'];
								$fechapay = $row1['fechapay'];
								$l2pfecha = $row1['l2pfecha'];
								if ($tcfecha == '00/00/0000') {
									if ($fechapay == '00/00/0000') {
										if ($l2pfecha == '00/00/0000') {
											$faproba = '';
										} else {
											$faproba = $l2pfecha;
										}
									} else {
										$faproba = $fechapay;
									}
								} else {
									$faproba = $tcfecha;
								}
								//ACTUALIZA EL ESTADO EN SISCO PERMITE HACER GUIA
								//si la forma de pago es Tienda y el saldo es 0 o es el valor de la retencion se activa para hacer la guia.
								if (($formapago == 'Tienda') and ($arreglo[$y][5] == 0) or ($formapago == 'Tienda') and ($arreglo[$y][10]) <= ($arreglo[$y][11])) //saldo
								{
									$sql2 = "update covidsales set estado= 'Facturado'  where factura = '$numfac'";
									$result2 = mysqli_query($con, $sql2);
								}
								//ESTABLECE SI ES PICKUP O ENVIO DE ACUERDO HABILITA EL CAMPO PARA INGRESAR # GUIA EN DETALLE
								if ($row1['pickup'] == '1') {
									$medio = "Pick-up";
								} else {
									$medio = "Envio";
								}
								//si el saldo es $0 y estado es facturado o entrega entienda x se activa para ingresar guia
								if (($estado == "Facturado" or $estado == "Entrega en " . $bodegaretiro) and ($arreglo[$y][5] == 0)) {
									$activar = "submit";
									$sucufact = $arreglo[$y][7];
								} else {
									$activar = "label";
									$sucufact = "";
								}
								if ($arreglo[$y][1] == 72) {
									$activar = "submit";
									$sucufact = $arreglo[$y][7];
								}
								//si el cliente paga y retira en otra tienda tambien se activa para hacer guia
								if (($formapago == 'Tienda') and ($arreglo[$y][7] <> $sucuret) and ($sucuret <> '')) {
									$activar = "submit";
								}
								//si el saldo a cancelar es menor q el 10% del total de la factura y el estado es facturado se activa para hacer guia
								if ((($arreglo[$y][10]) <= ($arreglo[$y][11])) and ($estado == "Facturado" or $estado == "Entrega en " . $bodegaretiro)) {
									$activar = "submit";
								}
							} else {
								if ($arreglo[$y][14] == 'DROPSHIPPING') {
									$estado = 'DROPSHIPPING';
									$activar = "submit";
								} else {
									$estado = "NO SISCO";
									$activar = "label";
								}
								if (trim($arreglo[$y][1]) == '72') {
									$activar = "submit";
									$sucufact = $arreglo[$y][7];
								}
							}
						?>
							<tr>

								<td id="fila4"> <a href="trakingguia.php?secu=<?php echo $arreglo[$y][2] ?>&bodegaFAC=<?php echo $arreglo[$y][12] ?>"> <?php echo $arreglo[$y][1] ?></a> </td>

								<?php
								if ($activar == "label") { ?>
									<td id="fila4" align="center"> <a> <?php echo $arreglo[$y][2] ?></a></td>

								<?php
								} else { ?>
									<td id="fila4" align="center"> <a href="ingguiasfacturas0.php?secu=<?php echo $arreglo[$y][2] ?>&bodegaFAC=<?php echo $arreglo[$y][12] ?>"> <?php echo $arreglo[$y][2] ?></a> </td>
								<?php
								} ?>
								<td id="fila4" align="center"> <a> <?php echo $arreglo[$y][6] ?></a></td>
								<td id="fila4"> <?php echo $arreglo[$y][3] ?> </td>
								<td id="fila4"> <?php echo $arreglo[$y][4]   ?> </td>
								<td id="fila4"> <?php echo $formapago ?> </td>
								<td id="fila4"> <?php echo $faproba ?> </td>
								<td id="fila4"> <?php echo $estado ?> </td>
								<td id="label5"> <?php echo $arreglo[$y][8] ?> </td>
								<td id="filax"> <?php echo $arreglo[$y][5] ?> </td>
								<td id="fila4"> <?php echo $medio ?> </td>
								<td id="fila4"> <?php echo $bodegaretiro   ?> </td>

								<?php
								if (($arreglo[$y][12] == "0000000072"  or $arreglo[$y][12] == '0500000008' or $arreglo[$y][12] == '0500000002' or $arreglo[$y][12] == '0500000004')  and $arreglo[$y][5] == 0 and $arreglo[$y][13] == null) { ?>
									<td id="fila4"> <a href="mailproveedor.php?numfac=<?php echo $arreglo[$y][2] ?>&bodegaFAC=<?php echo $arreglo[$y][12] ?>"> Pedido</a> </td>

								<?php
								}
								?> <td id="box"> <input name="bodega" type="hidden" id="bodega" size="30" value="<?php echo $bodega ?>"> </td>
							</tr>
						<?php
							$formapago = '';
							$medio = '';
							$bodegaretiro = '';
							$faproba = '';
							$y = $y + 1;
						}
						?>
					</table>
				</form>
			</div>
			<a align="center"> Se activa para ingresar la guia cuando: <br>
				1.) El saldo de la factura sea <strong>$0.00 </strong>
				y el estado en el SISCO sea <strong>FACTURADO </strong><br>
				2.) La forma de pago sea <strong> Tienda </strong>
				siempre y cuando el <strong> Pick Up </strong> sea en una Sucursal distinta a la de facturacion.<br>
				Ej.: Factura de Kennedy pero <strong> cancela y retira </strong> en Manta <br>
				3.) El estado sea <strong> FACTURADO </strong>
				y el saldo de la factura sea un valor aproximado al de la <strong> Retencion </strong><br></a>

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