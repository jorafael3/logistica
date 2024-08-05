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
			$secu = $_SESSION['secu'];
			$bodega	= $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$vdesp = $_SESSION['vdesp'];
			$usuario = trim($usuario);
			$usuario1 = $usuario;
			$usuario = $usuario1;

			if ($base == 'CARTIMEX') {
				require '../headcarti.php';
			} else {
				require '../headcompu.php';
				$_SESSION['base'] = $base;
				$Nota = " ";
			}

			// $codigo= trim($codigo);
			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare('LOG_BUSQUEDA_FACTURA @secuencia=:secu');
			$result->bindParam(':secu', $secu, PDO::PARAM_STR);
			$result->execute();
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$Codbodega = $row['Codbodega'];
				$Secuencia = $row['Secuencia'];
				$Numero = $row['Numero'];
				$Id = $row['Id'];
				$ClienteId = $row['ClienteId'];
				$Ruc = $row['Ruc'];
				$Nombre = $row['Nombre'];
				$TipoCLi = $row['TipoCLi'];
				$Fecha = $row['Fecha'];
				$Direccion = $row['Direccion'];
				$Ciudad = $row['Ciudad'];
				$Telefono = $row['Telefono'];
				$Mail = $row['Email'];
				$Contacto = $row['Contacto'];
				$Bloqueado = $row['Bloqueado'];
				$Nota = $row['Nota'];
				$BodegaId = $row['BodegaId'];
				$Bodeganame = $row['Bodeganame'];
				$Observacion = $row['Observacion'];
				$Medio = $row['Medio'];
				$Empmail = $row['Empmail'];
				$Fpago = $row['FPago'];
				$vendedor = $row['Vendedor'];
				$FPago = $row['FPago'];
				$lcomentario = $row['Notad'];
				$link = $row['SRI_LNK'];
			}

			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result1 = $pdo1->prepare('LOG_Detalle_Facturas_Traking @secu=:secu, @bodega=:bodega, @acceso=:acceso');
			$result1->bindParam(':secu', $secu, PDO::PARAM_STR);
			$result1->bindParam(':bodega', $bodega, PDO::PARAM_STR);
			$result1->bindParam(':acceso', $acceso, PDO::PARAM_STR);
			$result1->execute();
			$usuario = $_SESSION['usuario'];
			$arreglo  = array();
			$x = 0;
			while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) {
				$PREPARADOPOR = $row1['PREPARADOPOR'];
				$FECHAYHORA = $row1['FECHAYHORA'];
				$VERIFICADO = $row1['VERIFICADO'];
				$FECHAVERIFICADO = $row1['FECHAVERIFICADO'];
				$GUIAPOR = $row1['GUIAPOR'];
				$FECHAGUIA = $row1['FECHAGUIA'];
				$GUIA = $row1['GUIA'];
				$TRANSPORTE = $row1['TRANSPORTE'];
				$BULTOS = $row1['BULTOS'];
				$DESPACHADO = $row1['DESPACHADO'];
				$FECHADESPACHADO = $row1['FECHADESPACHADO'];
				$ruta = $row1['rutaFactura'];
				$Subtotal = number_format($row1['TSUBTOTAL'], 2);
				$Descuento = number_format($row1['TDESCUENTO'], 2);
				$Finan = number_format($row1['TFINAN'], 2);
				$Impuesto = number_format($row1['TIMPUESTOS'], 2);
				$Total = number_format($row1['TTOTAL'], 2);
				$vehiculoPor = $row1['vehiculoPor'];
				$placa = $row1['placa'];
				$FECHA_DESPACHO = $row1['FECHA_DESPACHO'];
				$HORA_DESPACHO = $row1['HORA_DESPACHO'];
				$ESTADO_DESPACHO = $row1['ESTADO_DESPACHO'];
				$Chofer = $row1['chofer'];
				$FechaVehiculo = $row1['FechaVehiculo'];
				$ComentarioVeh = $row1['ComentarioVeh'];
				$arreglo[$x][1] = $row1['PRODUCTO'];
				$arreglo[$x][2] = $row1['PNOMBRE'];
				$arreglo[$x][3] = number_format($row1['SubTotal'], 2);
				$arreglo[$x][4] = number_format($row1['Descuento'], 2);
				$arreglo[$x][5] = number_format($row1['Financiamiento'], 2);
				$arreglo[$x][6] = number_format($row1['Impuesto'], 2);
				$arreglo[$x][7] = number_format($row1['Total'], 2);
				$arreglo[$x][8] = number_format($row1['Cantidad'], 0);
				$x++;
			}

			$FIRMA = "";
			$result1_f = $pdo1->prepare('SELECT FIRMA from FACTURASLISTAS
					where Factura = :Factura');
			$result1_f->bindParam(':Factura', $Id, PDO::PARAM_STR);
			if ($result1_f->execute()) {
				$res = $result1_f->fetchAll(PDO::FETCH_ASSOC);
				if ($res[0]["FIRMA"] == "" || $res[0]["FIRMA"] == null) {
					$result1_ff = $pdo1->prepare('SELECT FRIMA from FACTURASLISTAS_FIRMAS
					where FACTURA = :Factura');
					$result1_ff->bindParam(':Factura', $Id, PDO::PARAM_STR);
					if ($result1_ff->execute()) {
						$res2 = $result1_ff->fetchAll(PDO::FETCH_ASSOC);
					} else {
						$FIRMA = $res2[0]["FIRMA"];
					}
				} else {
					$FIRMA = $res[0]["FIRMA"];
				}
				// echo "asdasd";
			} else {
				$err = $result1_f->errorInfo();
				// var_dump($err);
			}

			// echo $FIRMA;



		?>

	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Datos de Factura </center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>

		</div>
		<hr>

		<div id="cuerpo2" class=\"table-responsive-xs\">
			<div align="center" width=100%>
				<table border=2 width=100% id="traking">
					<tr>
						<td id="td1" width="15%"> <strong> BODEGA :</strong> </td>
						<td id="label4"> <a> <?php echo $Codbodega ?> </a></td>
						<td id="td1" width="10%"> <strong> Fact.#: </strong> </td>
						<td id="label4" width="20%"> <a> <?php echo $Secuencia ?> </a></td>
						<td id="label5" colspan="2" align="right"> <a href="consultarseries.php?factid=<?php echo $Id ?>"> CONSULTAR SERIES </a></td>
					</tr>
					<tr>
						<td id="td1"> <strong> Ruc: </strong> </td>
						<td id="label4"> <a> <?php echo $Ruc ?> </a> </td>
						<td id="td1"> <strong> Id: </strong> </td>
						<td id="label4"> <a> <?php echo $Id ?> </a> </td>
						<td id="td1"> <strong> Numero: </strong> </td>
						<td id="label4"> <a> <?php echo $Numero ?> </a></td>

					</tr>
					<tr>
						<td id="td1"> <strong> Cliente: </strong> </td>
						<td id="label4"> <a> <?php echo $Nombre ?> </a> </td>
						<td id="td1"> <strong> Tipo: </strong> </td>
						<td id="label4"> <a> <?php echo $TipoCLi ?> </a> </td>
						<td id="td1"> <strong> Ciudad: </strong> </td>
						<td id="label4"> <a> <?php echo $Ciudad ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> Direccion: </strong> </td>
						<td id="label4"> <a> <?php echo $Direccion ?> </a> </td>
						<td id="td1"> <strong> Telefono: </strong> </td>
						<td id="label4"> <a> <?php echo $Telefono ?> </a> </td>
						<td id="td1"> <strong> Email: </strong> </td>
						<td id="label4"> <a> <?php echo $Mail ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> Vendedor: </strong> </td>
						<td id="label4"> <a> <?php echo $vendedor ?> </a> </td>
						<td id="td1"> <strong> F.Pago: </strong> </td>
						<td id="label4" colspan="3"> <a> <?php echo $FPago ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> Fecha Factura: </strong> </td>
						<td id="label4"> <a> <?php echo $Fecha ?> </a> </td>
						<td id="td1"> <strong> Nota: </strong> </td>
						<td colspan="3" id="label4"> <a> <?php echo $Observacion ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> Preparado: </strong> </td>
						<td id="label4"> <a> <?php echo $PREPARADOPOR ?> </a> </td>
						<td id="td1"> <strong> Fecha Preparado: </strong> </td>
						<td id="label4" colspan="3"> <a> <?php echo $FECHAYHORA ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> Verificado: </strong> </td>
						<td id="label4"> <a> <?php echo $VERIFICADO ?> </a> </td>
						<td id="td1"> <strong> Fecha Verificado: </strong> </td>
						<td id="label4" colspan="3"> <a> <?php echo $FECHAVERIFICADO ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> Ingreso Guia: </strong> </td>
						<td id="label4"> <a> <?php echo $GUIAPOR ?> </a> </td>
						<td id="td1"> <strong> Fecha Guia : </strong> </td>
						<td id="label4" colspan="3"> <a> <?php echo $FECHAGUIA ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> Transporte: </strong> </td>
						<td id="label4"> <a> <?php echo $TRANSPORTE ?> </a> </td>
						<td id="td1"> <strong> Guia#: </strong> </td>
						<td id="label4"> <a> <?php echo $GUIA ?> </a> </td>
						<td id="td1"> <strong> Bultos#: </strong> </td>
						<td id="label4"> <a> <?php echo $BULTOS ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> Despachado: </strong> </td>
						<td id="label4"> <a> <?php echo $DESPACHADO ?> </a> </td>
						<td id="td1"> <strong> Fecha Desp.: </strong> </td>
						<td id="label4" colspan="3"> <a> <?php echo $FECHADESPACHADO ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> Comentario Despacho: </strong> </td>
						<td id="label4" colspan="5"> <a> <?php echo $lcomentario ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> Entregado Vehiculo: </strong> </td>
						<td id="label4"> <a> <?php echo $vehiculoPor   ?> </a> </td>
						<td id="td1"> <strong> Chofer: </strong> </td>
						<td id="label4"> <a> <?php echo $Chofer ?> </a> </td>
						<td id="td1"> <strong> Placa: </strong> </td>
						<td id="label4"> <a> <?php echo $placa ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> Fecha Des. Vehiculo: </strong> </td>
						<td id="label4"> <a> <?php echo $FechaVehiculo ?> </a> </td>
						<td id="td1"> <strong> Comentario Vehiculo: </strong> </td>
						<td id="label4" colspan=3> <a> <?php echo $ComentarioVeh ?> </a> </td>
					</tr>
					<tr>
						<td id="td1"> <strong> ESTADO DESPACHO: </strong> </td>
						<td id="label4"> <a> <?php echo $ESTADO_DESPACHO ?> </a> </td>
						<td id="td1"> <strong> FECHA GUIA: : </strong> </td>
						<td id="label4"> <a> <?php echo $FECHA_DESPACHO ?> </a> </td>
						<td id="td1"> <strong> HORA GUIA: </strong> </td>
						<td id="label4"> <a> <?php echo $HORA_DESPACHO ?> </a></td>
					</tr>
				</table>
				<table border=2 width=100% id="traking" align="center">
					<th> Codigo</th>
					<th> Producto </th>
					<th> Cant. </th>
					<th> Subtotal </th>
					<th> Dscto </th>
					<th> Iva </th>
					<th> Total </th>
					<?php
					$count = count($arreglo);
					$y = 0;
					while ($y <= $count - 1) {
					?>
						<tr>
							<td id="label4"> <?php echo $arreglo[$y][1] ?> </td>
							<td id="label4"> <?php echo $arreglo[$y][2] ?> </td>
							<td id="label5"> <?php echo $arreglo[$y][8] ?> </td>
							<td id="label5"> <?php echo $arreglo[$y][3] ?> </td>
							<td id="label5"> <?php echo $arreglo[$y][4] ?> </td>
							<td id="label5"> <?php echo $arreglo[$y][6] ?> </td>
							<td id="label5"> <?php echo $arreglo[$y][7] ?> </td>
						</tr>
				<?php
						$y = $y + 1;
					}

					$_SESSION['usuario'] = $usuario;
					$_SESSION['id'] = $Id;
					$_SESSION['base'] = $base;
					$_SESSION['acceso'] = $acceso;
					$_SESSION['codigo'] = $codigo;
					$_SESSION['bodega'] = $bodega;
					$_SESSION['nomsuc'] = $nomsuc;
					$_SESSION['secu'] = $Secuencia;
				} else {
					header("location: index.html");
				}

				?>
				</table>
				<table border=2 width=100% id="traking">
					<tr>
						<td id="ruta" width="50%"><a href="<?php echo $link ?>" target="_blank"> PDF_Factura </a> </td>
						<td id="td1"> <strong> Subtotal: </strong> </td>
						<td id="label5"> <a> <?php echo $Subtotal ?> </a> </td>
					</tr>
					<tr>
						<td id="label2" width="50%"> </td>
						<td id="td1"> <strong> Descuento: </strong> </td>
						<td id="label5"> <a> <?php echo $Descuento ?> </a></td>
					</tr>
					<tr>
						<td id="label2" width="50%"> </td>
						<td id="td1"> <strong> Financiamiento: </strong> </td>
						<td id="label5"> <a> <?php echo $Finan ?> </a></td>
					</tr>
					<tr>
						<td id="label2" width="50%"> </td>
						<td id="td1"> <strong> Impuesto 12%: </strong> </td>
						<td id="label5"> <a> <?php echo $Impuesto ?> </a></td>
					</tr>
					<tr>
						<td id="label2" width="50%"> </td>
						<td id="td1"> <strong> Total: </strong> </td>
						<td id="label5"> <a> <?php echo $Total ?> </a></td>
					</tr>
				</table>

				<div>

					<?php
					if ($FIRMA != "") {
					?>
						<img style="width: 300px;" src="<?php echo $FIRMA ?>" alt="">
					<?php
		
					}
					?>


				</div>

				<form name="formpreparar" action="#" method="POST" width="75%">
					<table align="center">
						<tr>

							<a href="facturasdespachadas.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a>

							</td>
						</tr>
					</table>
				</form>

			</div>
		</div>

</body>

</html>