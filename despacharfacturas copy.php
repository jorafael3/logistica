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
			$fecha = date("yy-m-d", time());
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;
			//echo "BOdega". $bodega; 
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Despachar Facturas <?php echo substr($nomsuc, 10, 20); ?> </center>
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">

			<div>
				<form name="formfactura" action="despacharfacturas0.php" method="POST" width="75%">
					<table align="center">

						<tr>

							<td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>

							<a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
							</td>
						</tr>
					</table>
				</form>
			</div>


			<div class=\"table-responsive-xs\">
				<form name="formfactura2" action="despacharfacturas0.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<th colspan="10">Facturas Por Despachar </th>
						</tr>
						<tr>
							<th id="fila4"> SId </th>
							<th id="fila4"> Ruc </th>
							<th id="fila4"> Cliente </th>
							<th id="fila4"> Factura </th>
							<th id="fila4"> Fecha </th>
							<th id="fila4"> </th>
							<th id="fila4"> Saldo </th>
							<th id="fila4"> Estado </th>
							<th id="fila4"> Transporte </th>
							<th id="fila4"> </th>
						</tr>
						<?php

						$_SESSION['usuario'] = $usuario;
						$_SESSION['bodega'] = $bodega;
						//echo "bodega".$bodega.$base.$usuario.$acceso;
						include('conexion_mssql.php');
						//******Proceso aqui primero todas las facturas pendientes de GUIA para ver cual ha sido Anulada o 
						//Devuelta en su TOTALIDAD Y marcarla como ANULADA tanto en el SISCO como en SGL(facturaslistas) 

						$paso = 'INGRESADAGUIA';
						// $pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						// $usuario = $_SESSION['usuario'];
						// $bodega = $_SESSION['bodega'];
						// $result0 = $pdo0->prepare("LOG_FACTURAS_PENDIENTES_DEVUELTAS @BODEGA=:bodega , @acceso=:acceso, @Estado=:estado");
						// $result0->bindParam(':bodega', $bodega, PDO::PARAM_STR);
						// $result0->bindParam(':acceso', $acceso, PDO::PARAM_STR);
						// $result0->bindParam(':estado', $paso, PDO::PARAM_STR);
						// $result0->execute();
						// $arreglod = array();
						// $xd = 0;
						// while ($row0 = $result0->fetch(PDO::FETCH_ASSOC)) {
						// 	$arreglod[$xd][2] = $row0['secuencia'];
						// 	//echo "Secuencia". $arreglod[$xd][2]; 
						// 	$xd++;
						// }
						// $countd = count($arreglod);
						// $yd = 0;
						// while ($yd <= $countd - 1) {
						// 	$devo = $arreglod[$yd][2];
						// 	$pdod = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						// 	$resultd = $pdod->prepare("LOG_FACTURAS_DEVUELTA_UPDATE @Secuencia=:secuencia");
						// 	$resultd->bindParam(':secuencia', $devo, PDO::PARAM_STR);
						// 	$resultd->execute();

						// 	$countarr = $resultd->rowcount();
						// 	//echo "Trae registro".$countarr; 
						// 	if ($countarr == 1) {
						// 		$rowdd = $resultd->fetch(PDO::FETCH_ASSOC);
						// 		//echo "datos a actualizar en sisco". $usuario.$fh.$devo ;
						// 		include("conexion.php");
						// 		$sqlde = "update covidsales set Anulada= '1' , anuladapor= '$usuario', fechaanulada= '$fh' where factura = '$devo' ";
						// 		//echo $sqlde; 
						// 		$resultde = mysqli_query($con, $sqlde);
						// 	}
						// 	$yd = $yd + 1;
						// }

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
								select d.facturaid,devuelto = sum(pr.Cantidad) from CLI_CREDITOS d with(NOLOCK)
								inner join CLI_CREDITOS_PRODUCTOS pr with(NOLOCK)
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

						//***************************xxxxxxxxxxxxxxxxxxxxxxx********************************
						$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						//new PDO($dsn, $sql_user, $sql_pwd);
						//Select Query
						$usuario = $_SESSION['usuario'];
						$bodega = $_SESSION['bodega'];

						$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_DESPACHO_SELECT @BODEGA=:bodega , @acceso=:acceso");
						$result->bindParam(':bodega', $bodega, PDO::PARAM_STR);
						$result->bindParam(':acceso', $acceso, PDO::PARAM_STR);
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
								$arreglo[$x][6] = $row['Ruc'];
								$arreglo[$x][7] = number_format($row['saldo'], 2);
								$arreglo[$x][8] = number_format($row['rete'], 2);
								$arreglo[$x][9] = $row['id'];
								$arreglo[$x][10] = $row['tipo'];
								$idfactura = $row['id'];
								$numfac = $row['secuencia'];
								$arreglo[$x][11] = $row['BodegaFAC'];
								$arreglo[$x][12] = $row['estado2'];
								$arreglo[$x][13] = $row['TRANSPORTE'];
								$x++;
							}
						}

						$count = count($arreglo);
						$y = 0;
						while ($y <= $count - 1) {
							$numfac = $arreglo[$y][2];
							include("conexion.php");
							//$sql1 = "SELECT * FROM covidsales where factura = '$numfac'";
							$sql1 = "SELECT a.*, p.bodega as bodegaret, c.sucursalid as sucursalret , d.sucursalid as sucursalfact  FROM covidsales a
										inner join sisco.covidciudades d on a.bodega= d.almacen
										left outer join covidpickup p on p.orden= a.secuencia
										left outer join sisco.covidciudades c on p.bodega= c.almacen
										where a.factura = '$numfac' ";
							$result1 = mysqli_query($con, $sql1);
							$conrow = $result1->num_rows;
							//echo "Contad". $conrow; 
							if ($conrow > 0) {
								$row1 = mysqli_fetch_array($result1);
								$estado = $row1['estado'];
								$formapago = $row1['formapago'];
								$transporte = $row1['despachofinal'];
								$sucuret =  $row1['sucursalret'];
								$sucurfact =  $row1['sucursalfact'];
								$bodegaretiro = $row1['bodegaret'];
								//ACTUALIZA EL ESTADO EN SISCO PERMITE HACER despacho  
								if (($formapago == 'Tienda') and ($arreglo[$y][7] == 0)) {
									$bodegaretiro = "Entrega en " . $bodegaretiro;
									$sql2 = "update covidsales set estado= '$bodegaretiro' where factura = '$numfac'";
									//$sql2 = "update covidsales set estado= 'Facturado' where factura = '$numfac'";
									$result2 = mysqli_query($con, $sql2);
								}
								if (($estado == 'Despachado') and ($transporte == 'Casillero')) {
									//$fec= getdate();
									$entregado = "Casillero";
									//	echo $entregado , $numfac; 
									$pdo7 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
									$result7 = $pdo7->prepare("Log_facturaslistas_despachar_update @numfac=:numfac ,@usuario=:entregado");
									$result7->bindParam(':numfac', $numfac, PDO::PARAM_STR);
									$result7->bindParam(':entregado', $entregado, PDO::PARAM_STR);
									//Executes the query
									$result7->execute();
								}
							}
						?>
							<tr>
								<td id="fila4"> <a href="trakingdesp.php?secu=<?php echo $arreglo[$y][2] ?>&bodegaFAC=<?php echo $arreglo[$y][11] ?>"><?php echo $arreglo[$y][1] ?> </a></td>
								<td id="fila4"> <?php echo $arreglo[$y][6] ?> </td>
								<td id="fila4"> <?php echo $arreglo[$y][5] ?> </td>
								<?php
								$posicion = "";
								if ($transporte == 'Entrega en tienda' or $transporte == 'Casillero') {
									include("conexioncas.php");
									$sqlcas = "SELECT a.posicion, a.ocupado FROM `lockers` as a where a.factura='$numfac' and a.ocupado=1  ";
									$resultcas = mysqli_query($concom, $sqlcas);
									$rowcas = mysqli_fetch_array($resultcas);
									$posicion = $rowcas['posicion'];
									if (($transporte == 'Entrega en tienda') and ($sucuret <> $sucurfact)) {
								?>
										<td id="fila4"><a href="mod1.php?sec=<?php echo $arreglo[$y][2] ?>"><?php echo $arreglo[$y][2] ?></td>
									<?php								} else {
									?>
										<td id="fila4"> <?php echo $arreglo[$y][2] ?></td>
									<?php								}
								} else {
									?>
									<td id="fila4"><a href="mod1.php?sec=<?php echo $arreglo[$y][2] ?>"><?php echo $arreglo[$y][2] ?></td>
								<?php								}

								?>
								<td id="fila4"> <?php echo $arreglo[$y][3] ?> </td>
								<td id="fila4"> <?php echo $arreglo[$y][4] ?> </td>
								<td id="filax"> <?php echo $arreglo[$y][7] ?> </td>
								<td id="fila4"> <?php echo $arreglo[$y][12] . $estado . " " . $posicion ?> </td>
								<td id="fila4"> <?php echo $transporte . " " . $posicion ?> </td>
								<?php
								if (($transporte <> 'Casillero') and ($arreglo[$y][7] <= $arreglo[$y][8])) {
								?>
									<td id="box"> <input name="checkbox[]" type="checkbox" value="<?php echo $arreglo[$y][2] . $arreglo[$y][11] ?>"> </td>
								<?php
								} else if ($arreglo[$y][1] == 72) {
								?>
									<td id="box"> <input name="checkbox[]" type="checkbox" value="<?php echo $arreglo[$y][2] . $arreglo[$y][11] ?>"> </td>
								<?php
								} else {	?>
									<td id="box"> <input name="checkbox[]" type="checkbox" disabled> </td>
								<?php
								}
								?>
							</tr>

						<?php
							$y = $y + 1;
						}
						?>
					</table>
					<input id="submit" value=" Despachar Facturas Marcadas " type="submit">
				</form>
			</div>
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
<script>
	function Cargar_Despachos() {

		let para = {
			"Cargar_guias": 1,
			bodega: BODEGA,
			acceso: <?php echo $acceso ?>,
			drop: <?php echo $drop ?>,
			drop_gye: <?php echo $drop_gye ?>,
			drop_uio: <?php echo $drop_uio ?>,
		}
		console.log('para: ', para);

		
	}

	Cargar_Despachos();

	function AjaxSend(param, callback) {
		FreezeUI({
			text: 'Cargando'
		});
		$.ajax({
			data: param,
			datatype: 'json',
			url: 'ingguiasfacturas_f.php',
			type: 'POST',
			success: function(x) {
				x = JSON.parse(x)
				// UnFreezeUI();
				callback(x)
			}
		})
	}
</script>

</html>