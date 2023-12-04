<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body>

	<?php
	session_start();
	if (isset($_SESSION['loggedin'])) {
		$usuario = $_SESSION['usuario'];
		$base = $_SESSION['base'];
		$acceso = $_SESSION['acceso'];
		$bodega = $_SESSION['bodega'];
		$nomsuc = $_SESSION['nomsuc'];
		$checkbox = $_POST['checkbox'];

		$fin = count($checkbox);

		if ($base == 'CARTIMEX') {
			require 'headcarti.php';
		} else {
			require 'headcompu.php';
		}
		include("conexion.php");
		date_default_timezone_set('America/Guayaquil');
		$year = date("Y");
		$fecha = date("Y-m-d", time());
		$hora = date("H:i:s", time());
		$fh = $fecha . " " . $hora;

		$i = 0;
		for ($i = 0; $i <= $fin - 1; $i++) {
			$numfac = substr($checkbox[$i], 0, 17);
			$bodegaFAC = substr($checkbox[$i], 17, 10);
			//echo $numfac . " ". $bodegaFAC; die(); 
			//echo "Esto voy a actualizar en sisco".$usuario. $fh;			
			$sql1 = "UPDATE `covidsales` SET   `estado`='Despachado', `cierreusuario`='$usuario', `cierrefecha`='$fh' where factura = '$numfac' ";
			mysqli_query($con, $sql1);

			/*****************		Aca actualizo en el tabla de SQL Facturaslistas***************/


			$usuario1 = $usuario;
			require('conexion_mssql.php');
			echo "Esto actualizo en facturas listas" . $numfac . $usuario1;
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);

			//Select Query
			$result = $pdo->prepare("Log_facturaslistas_despachar_update @numfac=:numfac ,@usuario=:usuario, @bodegaFAC=:bodegaFAC");
			$result->bindParam(':numfac', $numfac, PDO::PARAM_STR);
			$result->bindParam(':usuario', $usuario1, PDO::PARAM_STR);
			$result->bindParam(':bodegaFAC', $bodegaFAC, PDO::PARAM_STR);
			//Executes the query
			$result->execute();
			$usuario = $usuario1;

			/*****************		Aca Envio correo de despacho ***************/

			$tipo = 'VEN-FA';
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare("select top (1) a.id as id, a.secuencia as secu , bo.Sucursal as Sucurfact ,a.Detalle as nomcli, s.nombre as nomsuc , s.dirección as localdireccion , d.saldo as saldo , b.SRI_EM1 as sri_em1
											from ven_facturas a with(nolock) 
											inner join VEN_FACTURAS_DT dt with(nolock) on dt.FacturaID = a.id
											inner join cli_clientes b with(nolock) on a.clienteid = b.id  
											inner join INV_BODEGAS bo with(nolock) on bo.id= dt.BodegaID 
											inner join SIS_SUCURSALES s with (nolock)  on s.Código = bo.Sucursal
											inner join CLI_CLIENTES_DEUDAS d with (nolock) on d.DocumentoID= a.id 
											where a.secuencia =:numfac and d.tipo =:tipo ");
			$result->bindParam(':numfac', $numfac, PDO::PARAM_STR);
			$result->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result->execute();

			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$direccionmail = $row['sri_em1'];
				//$direccionmail = 'pchavez@cartimex.com';
				$secuencia = $row['secu'];
				$nombrecli = $row['nomcli'];
				$sucurfact =  $row['Sucurfact'];
				$nomsuc =  $row['nomsuc'];
				$id =  $row['id'];
				$localdireccion =  $row['localdireccion'];
				$saldo =  number_format($row['saldo'], 2);
				include("conexion.php");
				$sql0 = "SELECT a.* , p.bodega as bodegaret,c.sucursalid as sucursal FROM covidsales a
								left outer join covidpickup p on  a.secuencia = p.orden
								left outer join sisco.covidciudades c on p.bodega= c.almacen
								where a.factura = trim('$secuencia') and a.anulada<> '1' ";
				$result0 = mysqli_query($con, $sql0);
				$conrow = $result0->num_rows;

				if ($conrow > 0) {
					while ($row0 = mysqli_fetch_array($result0)) {
						$rastreo = '';
						$Transporte = '';
						$Transporte = $row0['despachofinal'];
						$guia = $row0['despacho'];
						$sucursal = $row0['sucursal'];
						if ($Transporte == 'Urbano') {
							$rastreo = 'www.urbano.com.ec/';
						}
						if ($Transporte == 'Tramaco') {
							$rastreo = 'www.tramaco.com.ec/';
						}
						if ($Transporte == 'Servientrega') {
							$rastreo = 'www.servientrega.com.ec/rastreo/multiple';
						}
					}

					if (($Transporte == 'Urbano') or ($Transporte == 'Tramaco') or ($Transporte == 'Servientrega')) {
						if ($sucursal == '') {
							$msg = " <img src='http://app.compu-tron.net/logos/Computron2.png' width='300' height='100'> <br><br>";
							$msg = $msg . "<strong> Gracias por su compra !!  </strong><br><br>";
							$msg = $msg . "Estimado/a &nbsp; &nbsp; <strong> " . $nombrecli . "</strong> <br> La factura # <strong>" . $secuencia . "</strong> fue embarcada en el Courier:<br><br>";
							$msg = $msg . "<strong>Transporte:  </strong>" . $Transporte . "<br>";
							$msg = $msg . "<strong>Guia #:  </strong>" . $guia . "<br> ";
							$msg = $msg . "<strong>Rastree su pedido aqui :  </strong><a href ='" . $rastreo . "' target=_blank > " . $rastreo  . "</a><br><br>";
							$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=500>";
							$msg = $msg . "<th align=left  width=450> ";
							$msg = $msg . " 	<strong>Detalle de Productos enviados   </strong><br>";
							$msg = $msg . "   </table>";
						} else {
							$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result3 = $pdo3->prepare("select * from sis_sucursales where código =:sucursal");
							$result3->bindParam(':sucursal', $sucursal, PDO::PARAM_STR);
							$result3->execute();
							while ($row3 = $result3->fetch(PDO::FETCH_ASSOC)) {
								$bodegaret = 	$row3['Nombre'];
								$localdireccionr = 	$row3['Dirección'];
							}
							$msg = " <img src='http://app.compu-tron.net/logos/Computron2.png' width='300' height='100'> <br><br>";
							$msg = $msg . "<strong> Gracias por su compra !!  </strong><br><br>";
							$msg = $msg . "Estimado/a &nbsp; &nbsp; <strong> " . $nombrecli . "</strong> <br> La factura # <strong>" . $secuencia . "</strong> fue embarcada en el Courier:<br><br>";
							$msg = $msg . "<strong>Transporte:  </strong>" . $Transporte . "<br>";
							$msg = $msg . "<strong>Guia #:  </strong>" . $guia . "<br> ";
							$msg = $msg . "<strong>Rastree su pedido aqui :  </strong><a href ='" . $rastreo . "' target=_blank > " . $rastreo  . "</a><br><br>";
							$msg = $msg . "<strong>Para su retiro en :  </strong>" . $bodegaret . "<br> <br> <strong>Dirección : </strong>" . $localdireccionr . "<br>";
							$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=500>";
							$msg = $msg . "<th align=left  width=450> ";
							$msg = $msg . " 	<strong>Detalle de Productos enviados   </strong><br>";
							$msg = $msg . "   </table>";
						}
					} else {
						if (($Transporte == 'Entrega en tienda')) {
							$msg = " <img src='http://app.compu-tron.net/logos/Computron2.png' width='300' height='100'> <br><br>";
							$msg = $msg . "<strong> Gracias por su compra !!  </strong><br><br>";
							$msg = $msg . "Estimado/a &nbsp; &nbsp; <strong> " . $nombrecli . "</strong> <br> La factura # <strong>" . $secuencia . "</strong> fue entregada :<br><br>";
							$msg = $msg . "<strong>Transporte:  </strong>" . $Transporte . "<br>";
							$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=500>";
							$msg = $msg . "<th align=left  width=450> ";
							$msg = $msg . " <strong>Detalle de Productos entregados   </strong><br>";
							$msg = $msg . " </table>";
						} else {
							$msg = " <img src='http://app.compu-tron.net/logos/Computron2.png' width='300' height='100'> <br><br>";
							$msg = $msg . "<strong> Gracias por su compra !!  </strong><br><br>";
							$msg = $msg . "Estimado/a &nbsp; &nbsp; <strong> " . $nombrecli . "</strong> <br> La factura # <strong>" . $secuencia . "</strong> fue embarcada en:<br><br>";
							$msg = $msg . "<strong>Transporte:  </strong>" . $Transporte . "<br>";
							$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=500>";
							$msg = $msg . "<th align=left  width=450> ";
							$msg = $msg . " <strong>Detalle de Productos enviados   </strong><br>";
							$msg = $msg . " </table>";
						}
					}
					// con SQL1 obtengo los items y cantidades de las facturas

					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('LOG_PREPARAR_FACTURA_CORREO  @FacturaID=:numfac, @bodegaFAC=:bodegaFAC ');
					$result1->bindParam(':numfac', $id, PDO::PARAM_STR);
					$result1->bindParam(':bodegaFAC', $bodegaFAC, PDO::PARAM_STR);
					$result1->execute();

					$msg = $msg .   "<table border=1 cellpadding=5 cellspacing=1 width=500>";
					$msg = $msg .  "<th align=left  width=150>Codigo</th> ";
					$msg = $msg .  "<th align=left  width=500>Articulo</th> ";
					$msg = $msg .  "<th align=left width=45>Cant.</th>";
					$msg = $msg .  "<th align=left width=40>Serie</th>";

					while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) {
						$codigo = $row1['CopProducto'];
						$nombre = $row1['Detalle'];
						$cant = $row1['Cantidad'];
						$productoid = $row1['ProductoId'];
						$registraserie = $row1['RegistaSerie'];

						if ($registraserie == 1) {

							$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result5 = $pdo5->prepare("select * from rma_facturas rf with(nolock) 
																					   inner join rma_facturas_dt rdt with(nolock) on rf.id= rdt.facturaid
																						where rf.facturaid =:numfac and rdt.productoid=:productoid ");
							$result5->bindParam(':numfac', $id, PDO::PARAM_STR);
							$result5->bindParam(':productoid', $productoid, PDO::PARAM_STR);
							$result5->execute();
							while ($row5 = $result5->fetch(PDO::FETCH_ASSOC)) {
								$serie = $row5['Serie'];

								$msg = $msg .  "<tr bgcolor=\"dddddd\"><td align=left  width=100>" . $codigo . "</td> ";
								$msg = $msg .  "<td align=left  width=500>" . $nombre . "</td> ";
								$msg = $msg .  "<td align=right width=45> 1 </td>";
								$msg = $msg .  "<td align=left  width=500>" . $serie . "</td></tr> ";
							}
						} else {
							$msg = $msg .  "<tr bgcolor=\"dddddd\"><td align=left  width=100>" . $codigo . "</td> ";
							$msg = $msg .  "<td align=left  width=500>" . $nombre . "</td> ";
							$msg = $msg .  "<td align=right width=45>" . $cant . "</td>";
							$msg = $msg .  "<td align=left  width=500> </td></tr> ";
						}

						$msg = $msg .  "</td>";
					}


					$msg = $msg .  "</table>";
					$msg = $msg .  "<br>Este correo fue generado de forma automática y no requiere respuesta..<br><br>";
					$msg = $msg .  "
										<strong>Nota de Descargo: </strong>La información contenida en este mensaje y sus anexos tiene carácter confidencial, <br>y está dirigida únicamente al destinatario de la misma y sólo podrá ser usada por éste. <br>
										Si usted ha recibido este mensaje por error, por favor borre el mensaje de su sistema. 
										";
					// PARA CAMBIAR ALEATORIAMENTE EL ENVIO DEL CORREO
					$aleatorio = (rand(1, 5));
					switch ($aleatorio) {
						case "1":
							$mailsender = "cartimexmail1@gmail.com";
							break;
						case "2":
							$mailsender = "cartimexmail2@gmail.com";
							break;
						case "3":
							$mailsender = "cartimexmail3@gmail.com";
							break;
						case "4":
							$mailsender = "cartimexmail4@gmail.com";
							break;
						default:
							$mailsender = "cartimexmail5@gmail.com";
					}
					require_once '../vendor/autoload.php';
					$m = new PHPMailer;
					$m->CharSet = 'UTF-8';
					$m->isSMTP();
					$m->SMTPAuth = true;
					$m->Host = 'smtp.gmail.com';
					$m->Username = $mailsender;
					$m->Password = 'Bruno2001';
					$m->SMTPSecure = 'ssl';
					$m->Port = 465;

					$m->From = $mailsender;
					$m->addBCC('pchavez@cartimex.com');
					$m->FromName = 'Computron';
					//$destinatario = "fmortola@cartimex.com";
					$m->addAddress($direccionmail);
					$m->isHTML(true);
					// $m->addAttachment('directorio/nombrearchivo.jpg','nombrearchivo.jpg')
					$m->Subject = "COMPUTRONSA Detalle de Compra ";
					$m->Body = $msg;
					var_dump($m->send());
				}
			}
		}

		/************************                             *******************/
		$_SESSION['base'] = $base;
		$_SESSION['usuario'] = $usuario;
		$_SESSION['acceso'] = $acceso;

		header("location: despacharfacturas.php");
	}

	?>

</body>

</html>