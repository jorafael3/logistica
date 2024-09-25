<?php

if (isset($_POST['Cargar_guias'])) {
	include('conexion_2.php');

	try {

		$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$acceso = $_POST["acceso"];

		$sql_nc = "{CALL LOG_FACTURAS_PENDIENTES_DEVUELTAS}";
		$query_nc = $pdo->prepare($sql_nc);
		$query_nc->execute();
		$LISTA_NC = [];
		$RESNC = $query_nc->fetchAll(PDO::FETCH_ASSOC);
		foreach ($RESNC as $row) {
			array_push($LISTA_NC, trim($row["secuencia"]));
		}

		$sql = "LOG_FACTURAS_PENDIENTES_DESPACHO_SELECT_3
            @acceso = :acceso";
		$query = $pdo->prepare($sql);
		$query->bindParam(':acceso', $acceso, PDO::PARAM_STR);
		if ($query->execute()) {
			$ARRAY = [];
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row) {
				if (in_array(trim($row["secuencia"]), $LISTA_NC)) {
				} else {
					// $MULTI = Cargar_multibodega($row["BodegaFAC"], $row["secuencia"]);
					$MULTI = Cargar_multibodega($row["BodegaFAC"], $row["secuencia"]);
					$row["MULTI"] = $MULTI[0];
					$row["MULTI_DATA"] = $MULTI[1];
					if ($row["TIPO_DATOS"] == "DROP") {
						$row["SISCO"] = [];
						array_push($ARRAY, $row);
					} else {
						// $row["SISCO"] =  Buscar_Sisco($row["secuencia"]);
						$row["SISCO"] =  [];
						array_push($ARRAY, $row);
					}
				}
			}
			echo json_encode($ARRAY);
		} else {
			$err = $query->errorInfo();
			echo json_encode($err);
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		echo json_encode($e);
		exit();
	}
}

function Cargar_multibodega($bodega, $secuencia)
{
	include('conexion_2.php');
	try {

		// $secuencia = $_POST["secuencia"];
		// $bodega = $_POST["bodega"];
		$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$query = $pdo->prepare("PER_Detalle_Facturas3
            @Secuencia=:secuencia,
            @bodegaFAC=:bodega
        ");
		$query->bindParam(':secuencia', $secuencia, PDO::PARAM_STR);
		$query->bindParam(':bodega', $bodega, PDO::PARAM_STR);
		if ($query->execute()) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			$con = 0;
			foreach ($result as $row) {
				if ($row["Section"] != "HEADER") {
					if ($row["MULTI"] == "MULTI") {
						$con++;
					}
				}
			}
			return [$con, $result];
		} else {
			$err = $query->errorInfo();
			return 0;
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		echo json_encode($e);
		exit();
	}
}

function Buscar_Sisco($secuencia)
{
	include("conexion_2sisco.php");
	try {
		$pdo = new PDO("mysql:host=10.5.1.245;dbname=" . $sql_database, $sql_user, $sql_pwd);
		$query = $pdo->prepare("SELECT 
        a.*,
        p.bodega as bodegaret, 
        date_format(a.paymentez,'%d/%m/%Y') as fechapay,
        date_format(a.tcfecha,'%d/%m/%Y') as tcfecha,
        date_format(a.l2pfecha,'%d/%m/%Y') as l2pfecha,
        c.sucursalid as sucursal  
        FROM covidsales a
        left outer join covidpickup p on p.orden= a.secuencia
        left outer join sisco.covidciudades c on p.bodega= c.almacen
        where a.factura = :factura and a.anulada<> '1'  
        ");
		$query->bindParam(':factura', $secuencia, PDO::PARAM_STR);
		if ($query->execute()) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		} else {
			$err = $query->errorInfo();
			// echo json_encode($err);
			return $err;
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		echo json_encode($e);
		exit();
	}
}

if (isset($_POST['Despachar'])) {
	include('conexion_2.php');
	//include_once("conexion_2sisco.php");
	try {
		$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$pdo->beginTransaction();
		$DATOS = $_POST["DATOS"];
		$acceso = $_POST["acceso"];
		$usuario = $_POST["usuario"];
		$year = date("Y");
		$fecha = date("Y-m-d", time());
		$hora = date("H:i:s", time());
		$fh = $fecha . " " . $hora;
		$ARRA = [];
		$errores_fac = [];
		$errores_sisco = [];
		foreach ($DATOS as $row) {
			$MULTI = $row["MULTI_DATA"];
			foreach ($MULTI as $res) {
				if ($res["Section"] != "HEADER") {
					$result = $pdo->prepare("Log_facturaslistas_despachar_update 
					@numfac=:numfac,
					@usuario=:usuario,
					@bodegaFAC=:bodegaFAC");
					$result->bindParam(':numfac', $res["Secuencia"], PDO::PARAM_STR);
					$result->bindParam(':usuario', $usuario, PDO::PARAM_STR);
					$result->bindParam(':bodegaFAC', $res["BodegaID"], PDO::PARAM_STR);
					$result->execute();
					if ($result->execute()) {
					} else {
						$err = $query->errorInfo();
						array_push($errores_fac, $err);
					}
				}
			}
			$act = Actualizar_sisco($row["secuencia"], $usuario, $fh);
			if ($act != 1) {
				array_push($errores_sisco, $act);
			}else{
				$cor = Correo($row);
			}
			array_push($ARRA, [$row, $act, $cor, $errores_fac]);
		}
		if (count($errores_fac) == 0 && count($errores_sisco) == 0) {
			$pdo->commit();
			echo json_encode([1, $ARRA]);
		} else {
			$pdo->rollBack();
			echo json_encode([0, $ARRA]);
		}
	} catch (PDOException $e) {
		$e = $e->getMessage();
		echo json_encode($e);
		exit();
	}
}


if (isset($_POST['Actualizar_Sisco'])) {

	$DATOS = $_POST["DATOS"];
	$usuario = $_POST["usuario"];
	$year = date("Y");
	$fecha = date("Y-m-d", time());
	$hora = date("H:i:s", time());
	$fh = $fecha . " " . $hora;
	$ARRA_ = [];
	foreach ($DATOS as $row) {
		$act = Actualizar_sisco($row["secuencia"], $usuario, $fh);
		array_push($ARRA_, $act);
	}

	echo json_encode([1, $ARRA_]);
}

function Actualizar_sisco($secuencia, $usuario, $fecha)
{
	include("conexion_2sisco.php");

	try {
		$pdo_sisco = new PDO("mysql:host=10.5.1.245;dbname=" . $sql_database, $sql_user, $sql_pwd);
		$esatdo = "Despachado";
		$result2 = $pdo_sisco->prepare("UPDATE covidsales SET  
			estado = :estado,
			cierreusuario = :cierreusuario,
			cierrefecha=:cierrefecha
			where factura = :factura ");

		$result2->bindParam(':estado', $esatdo, PDO::PARAM_STR);
		$result2->bindParam(':factura', $secuencia, PDO::PARAM_STR);
		$result2->bindParam(':cierreusuario', $usuario, PDO::PARAM_STR);
		$result2->bindParam(':cierrefecha', $fecha, PDO::PARAM_STR);
		if ($result2->execute()) {
			// echo json_encode(1);
			return 1;
		} else {
			$err = $result2->errorInfo();
			return $err;
		}
	} catch (PDOException $e) {
		$e = $e->getMessage();
		return $e;
	}
}

function Correo($row)
{

	include('conexion_2.php');

	try {

		// $row =  $DATOS[0];
		$secuencia = $row['secuencia'];
		$BodegaFAC = $row['BodegaFAC'];
		$nombrecli = $row['Detalle'];
		$MULTI_DATA = $row['MULTI_DATA'];
		// $sucurfact =  $row['bodegsuc'];
		// $nomsuc =  $row['Sucursal_nombre'];
		$id =  $row['id'];
		$EMAIL_ENVIO = trim($row['SRI_EM1']);
		// $localdireccion =  $row['localdireccion'];
		// $saldo =  number_format($row['saldo'], 2);
		// $SISCO = $row["SISCO"][0];

		$rastreo = '';
		$Transporte = '';
		// $Transporte = $row['despachofinal'];
		// $guia = $row['despacho'];

		// $sucursal = $row['sucursal'];
		if ($Transporte == 'Urbano') {
			$rastreo = 'www.urbano.com.ec/';
		}
		if ($Transporte == 'Tramaco') {
			$rastreo = 'www.tramaco.com.ec/';
		}
		if ($Transporte == 'Servientrega') {
			$rastreo = 'www.servientrega.com.ec/rastreo/multiple';
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


		$msg = $msg .   "<table border=1 cellpadding=5 cellspacing=1 width=500>";
		$msg = $msg .  "<th align=left  width=150>Codigo</th> ";
		$msg = $msg .  "<th align=left  width=500>Articulo</th> ";
		$msg = $msg .  "<th align=left width=45>Cant.</th>";
		$msg = $msg .  "<th align=left width=40>Serie</th>";
		$con = 1;
		// foreach ($MULTI_DATA as $row_multi) {
		for ($i = 0; $i < count($MULTI_DATA); $i++) {

			if ($MULTI_DATA[$i]["Section"] != "HEADER") {
				// $pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				// $result1 = $pdo1->prepare('LOG_PREPARAR_FACTURA_CORREO  @FacturaID=:numfac, @bodegaFAC=:bodegaFAC ');
				// $result1->bindParam(':numfac', $id, PDO::PARAM_STR);
				// $result1->bindParam(':bodegaFAC', $row_multi["BodegaID"], PDO::PARAM_STR);
				// $result1->execute();

				// $result = $result1->fetchAll(PDO::FETCH_ASSOC);
				$codigo = $MULTI_DATA[$i]['Codigo'];
				$nombre = $MULTI_DATA[$i]['Nombre'];
				$cant = $MULTI_DATA[$i]['Cantidad'];
				$productoid = $MULTI_DATA[$i]['ID'];
				$registraserie = $MULTI_DATA[$i]['IsSerie'];

				// $msg = $msg .  "<tr bgcolor=\"dddddd\"><td align=left  width=100>" . $codigo . "</td> ";
				// $msg = $msg .  "<td align=left  width=500>" . $nombre . "</td> ";
				// $msg = $msg .  "<td align=right width=45>" . $cant . "</td>";
				// $msg = $msg .  "<td align=left  width=500> </td></tr> ";

				if ($registraserie == "SI") {

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

				// while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) {
				// 	$codigo = $row1['CopProducto'];
				// 	$nombre = $row1['Detalle'];
				// 	$cant = $row1['Cantidad'];
				// 	$productoid = $row1['ProductoId'];
				// 	$registraserie = $row1['RegistaSerie'];

				// 	if ($registraserie == 1) {

				// 		$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				// 		$result5 = $pdo5->prepare("select * from rma_facturas rf with(nolock) 
				// 							inner join rma_facturas_dt rdt with(nolock) on rf.id= rdt.facturaid
				// 							where rf.facturaid =:numfac and rdt.productoid=:productoid ");
				// 		$result5->bindParam(':numfac', $id, PDO::PARAM_STR);
				// 		$result5->bindParam(':productoid', $productoid, PDO::PARAM_STR);
				// 		$result5->execute();
				// 		while ($row5 = $result5->fetch(PDO::FETCH_ASSOC)) {
				// 			$serie = $row5['Serie'];
				// 			$msg = $msg .  "<tr bgcolor=\"dddddd\"><td align=left  width=100>" . $codigo . "</td> ";
				// 			$msg = $msg .  "<td align=left  width=500>" . $nombre . "</td> ";
				// 			$msg = $msg .  "<td align=right width=45> 1 </td>";
				// 			$msg = $msg .  "<td align=left  width=500>" . $serie . "</td></tr> ";
				// 		}
				// 	} else {
				// 		$msg = $msg .  "<tr bgcolor=\"dddddd\"><td align=left  width=100>" . $codigo . "</td> ";
				// 		$msg = $msg .  "<td align=left  width=500>" . $nombre . "</td> ";
				// 		$msg = $msg .  "<td align=right width=45>" . $cant . "</td>";
				// 		$msg = $msg .  "<td align=left  width=500> </td></tr> ";
				// 	}
				// 	$msg = $msg .  "</td>";
				// }
			}
			// $con++;
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

		if ($EMAIL_ENVIO != "") {
			include 'vendor/autoload.php';
			$m = new PHPMailer;
			$m->CharSet = 'UTF-8';
			$m->isSMTP();
			$m->SMTPAuth = true;
			$m->Host = 'smtp.gmail.com';
			$m->Username = "sgocarticompu@gmail.com";
			$m->Password = 'nyai liux sagy jtsk';
			$m->SMTPSecure = 'ssl';
			$m->Port = 465;
			$m->setFrom('sgocarticompu@gmail.com', "DESPACHO REALIZADO");
			// $m->addBCC('pchavez@cartimex.com');
			// $m->FromName = 'Computron';
			//$destinatario = "fmortola@cartimex.com";
			// $m->addAddress('jalvarado@cartimex.com');
			$m->addAddress($EMAIL_ENVIO);
			$m->isHTML(true);
			// $m->addAttachment('directorio/nombrearchivo.jpg','nombrearchivo.jpg')
			$m->Subject = "COMPUTRONSA Detalle de Compra ";
			$m->Body = $msg;
			if (!$m->Send()) {
				$error = 'Mail error: ' . $mail->ErrorInfo;
				return $error;
				// echo $error; // Devuelve el mensaje de error
			} else {
				$error = 'Message sent!';
				return $error;
				// echo $error; // Devuelve un mensaje de éxito
			}
		} else {
			$error = 'Mail error: VACIO';
			return $error;
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		echo json_encode($e);
		exit();
	}
}
