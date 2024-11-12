<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['Guardar_Guia'])) {
	include('conexion_2.php');
	try {

		$FORMA_DESPACHO = $_POST["FORMA_DESPACHO"];
		$TIENDA_RETIRO = $_POST["TIENDA_RETIRO"];
		$GUIA = $_POST["GUIA"];
		$BULTOS = $_POST["BULTOS"];
		$SECUENCIA = $_POST["SECUENCIA"];
		$BODEGAFAC = $_POST["BODEGAFAC"];
		$USUARIO = $_POST["USUARIO"];
		$COMENTARIO = $_POST["COMENTARIO"];
		$ENVIO_CLI = $_POST["ENVIO_CLI"];

		$ES_SISCO = validar_sisco($SECUENCIA);
		$VAL = 0;
		if ($ES_SISCO[0] == 1) {
			if (count($ES_SISCO[1]) > 0) {
				$SISCO = Guardar_sisco($ES_SISCO[1], $USUARIO, $COMENTARIO, $TIENDA_RETIRO, $BULTOS, $GUIA, $FORMA_DESPACHO, $ENVIO_CLI);
				$VAL = $SISCO[0];
			} else {
				$SISCO = VAlidar_Factura($SECUENCIA);
				if ($SISCO[0] == 1) {
					$FACTURA_ID = $SISCO[1][0]["ID"];
					$SISCO = Guardar_No_Sisco($FACTURA_ID, $TIENDA_RETIRO, $COMENTARIO, $USUARIO, $ENVIO_CLI);
					if ($SISCO[0] == 1) {
						$VAL = 1;
					} else {
						$VAL = 0;
					}
				} else {
					$VAL = 0;
				}
			}

		}
		if ($VAL == 1) {
			$GUARDAR_DOBRA = Guardar_Dobra($GUIA, $BULTOS, $FORMA_DESPACHO, $USUARIO, $SECUENCIA, $COMENTARIO, $BODEGAFAC);
			if ($GUARDAR_DOBRA[0] == 1) {

				if (($FORMA_DESPACHO == 'Urbano') or ($FORMA_DESPACHO == 'Tramaco')
					or ($FORMA_DESPACHO == 'Servientrega') or ($FORMA_DESPACHO == 'Vehiculo Computron')
				) {
					//echo "Entra aqui ";
					$MC = mailmailcourier($SECUENCIA, $BODEGAFAC);
				}
				if ($FORMA_DESPACHO == 'Entrega en tienda') {
					$MC = mailretiroenotratienda($SECUENCIA, $BODEGAFAC);
				}

				$REST = Restablecer_Multibodega($SECUENCIA, $BODEGAFAC);

				echo json_encode([1, $GUARDAR_DOBRA, $SISCO, $MC, $REST]);
				exit();
			} else {
				echo json_encode([0, $GUARDAR_DOBRA, $SISCO]);
				exit();
			}
		} else {
			echo json_encode([0, [], $SISCO]);
			exit();
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		echo json_encode($e);
		exit();
	}
}

function validar_sisco($SECUENCIA)
{
	try {
		include("conexion_2sisco.php");

		$sql = "SELECT a.*, p.bodega as bodegaret FROM `covidsales` a
		left outer join covidpickup p on p.orden = a.secuencia where a.factura = :factura ";
		$query = $pdo->prepare($sql);
		$query->bindParam(':factura', $SECUENCIA, PDO::PARAM_STR);
		if ($query->execute()) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			return [1, $result];
		} else {
			$err = $query->errorInfo();
			// echo json_encode($err);
			return [0, $err];
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		return [0, $e];
	}
}

function validar_sisco_2($SECUENCIA)
{
	try {
		include("conexion_2sisco.php");

		$sql = "SELECT a.*, p.bodega as bodegaret,c.sucursalid as sucursal,
		p.bodega_id as SUCURSAL_ID  
		FROM covidsales a
		inner join covidpickup p on p.orden= a.secuencia
		left outer join sisco.covidciudades c on p.bodega= c.almacen
		where a.factura = :factura and a.anulada<> '1'  ";
		$query = $pdo->prepare($sql);
		$query->bindParam(':factura', $SECUENCIA, PDO::PARAM_STR);
		if ($query->execute()) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			return [1, $result];
		} else {
			$err = $query->errorInfo();
			// echo json_encode($err);
			return [0, $err];
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		return [0, $e];
	}
}

function VAlidar_Factura($SECUENCIA)
{
	try {
		include("conexion_2.php");
		$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);

		$query = $pdo->prepare('SELECT ID,secuencia FROM ven_facturas
		where secuencia = :secuencia');
		$query->bindParam(":secuencia", $SECUENCIA, PDO::PARAM_STR);
		if ($query->execute()) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			return [1, $result];
		} else {
			$err = $query->errorInfo();
			// echo json_encode($err);
			return [0, $err];
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		return [0, $e];
	}
}

function Buscar_Sucursal($SUCURSAL)
{
	try {
		include("conexion_2.php");
		$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);

		$sql = "SELECT ID,Código, Nombre from SIS_SUCURSALES
		where ID = :factura ";
		$query = $pdo->prepare($sql);
		$query->bindParam(':factura', $SUCURSAL, PDO::PARAM_STR);
		if ($query->execute()) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			return [1, $result];
		} else {
			$err = $query->errorInfo();
			// echo json_encode($err);
			return [0, $err];
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		return [0, $e];
	}
}

function Guardar_sisco($DATOS, $USUARIO, $COMENTARIO, $TIENDA_RETIRO, $BULTOS, $GUIA, $FORMA_DESPACHO, $ENVIO_CLI)
{
	try {
		include("conexion_2sisco.php");

		date_default_timezone_set('America/Guayaquil');
		$fecha = date("Y-m-d", time());
		$hora = date("H:i:s", time());
		$fh = $fecha . " " . $hora;

		$row1 = $DATOS[0];
		$transaccion = $row1['secuencia']; //numero de orden el sisco
		$bodegaret = trim($row1['bodegaret']);
		$pickup = $row1['pickup']; // Traigo para ver si antes era envio y ahora es pick up
		$factura = $row1['factura'];

		if ($FORMA_DESPACHO == "Entrega en tienda") {
			$edespacho = Buscar_Sucursal($TIENDA_RETIRO);
			$edespacho = 'Entrega en ' . $edespacho[1][0]["Nombre"];
		} else {
			$edespacho = $FORMA_DESPACHO;
		}




		$sql = "INSERT into covidlogistica
		(
			comentario, 
			usuario, 
			fecha, 
			transaccion
		)VALUES(
			:comentario, 
			:usuario, 
			:fecha, 
			:transaccion
		)";
		$query = $pdo->prepare($sql);
		$query->bindParam(':comentario', $COMENTARIO, PDO::PARAM_STR);
		$query->bindParam(':usuario', $USUARIO, PDO::PARAM_STR);
		$query->bindParam(':fecha', $fh, PDO::PARAM_STR);
		$query->bindParam(':transaccion', $transaccion, PDO::PARAM_STR);
		if ($query->execute()) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);

			$VAL = 0;
			if ($bodegaret == "" || $bodegaret == null) {
				//echo "Esto es lo que voy a insertar en covidpickup ". $transaccion. $provincia . $newbodretiro ;
				$query2 = $pdo->prepare("INSERT into `covidpickup` 
				(
					orden, 
					bodega_id,
					envio_cli
				)  
				values(
					:orden, 
					:bodega_id,
					:envio_cli
				) ");
				$query2->bindParam(':orden', $transaccion, PDO::PARAM_STR);
				$query2->bindParam(':bodega_id', $TIENDA_RETIRO, PDO::PARAM_STR);
				$query2->bindParam(':envio_cli', $ENVIO_CLI, PDO::PARAM_STR);
				if ($query2->execute()) {
					$VAL = 1;
				} else {
					$e = $e->getMessage();
					$VAL = $e;
				}
			} else {
				$query2 = $pdo->prepare("UPDATE covidpickup
					SET
						bodega_id = :bodega_id,
						envio_cli = :envio_cli
					WHERE orden = :orden
				");
				$query2->bindParam(':orden', $transaccion, PDO::PARAM_STR);
				$query2->bindParam(':bodega_id', $TIENDA_RETIRO, PDO::PARAM_STR);
				$query2->bindParam(':envio_cli', $ENVIO_CLI, PDO::PARAM_STR);
				if ($query2->execute()) {
					$VAL = 1;
				} else {
					$e = $e->getMessage();
					$VAL = $e;
				}
			}

			if ($VAL == 1) {
				$query3 = $pdo->prepare("UPDATE `covidsales` 
					SET 
					bultos=:bultos,
					despacho=:despacho,
					fechadesp=:fechadesp,
					despachador=:despachador,
					fechafinal=:fechafinal,
					usuariofinal=:usuariofinal,
					estado=:estado,
					despachofinal=:despachofinal,
					pickup=:pickup
					where factura = :factura
				");
				$query3->bindParam(':bultos', $BULTOS, PDO::PARAM_STR);
				$query3->bindParam(':despacho', $GUIA, PDO::PARAM_STR);
				$query3->bindParam(':fechadesp', $fh, PDO::PARAM_STR);
				$query3->bindParam(':despachador', $USUARIO, PDO::PARAM_STR);
				$query3->bindParam(':fechafinal', $fecha, PDO::PARAM_STR);
				$query3->bindParam(':usuariofinal', $USUARIO, PDO::PARAM_STR);
				$query3->bindParam(':estado', $edespacho, PDO::PARAM_STR);
				$query3->bindParam(':despachofinal', $FORMA_DESPACHO, PDO::PARAM_STR);
				$query3->bindParam(':pickup', $pickup, PDO::PARAM_STR);
				$query3->bindParam(':factura', $factura, PDO::PARAM_STR);

				if ($query3->execute()) {
					return [1, $VAL, "ACTUALIZADO"];
				} else {
					$err = $query->errorInfo();
					return [0, $VAL, $err];
				}
			} else {
				return [0, $VAL];
			}
		} else {
			$err = $query->errorInfo();
			// echo json_encode($err);
			return [0, $err];
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		return [0, $e];
	}
}

function Guardar_No_Sisco($FACTURA_ID, $TIENDA_RETIRO, $COMENTARIO, $USUARIO, $ENVIO_CLI)
{

	try {
		include("conexion_2.php");
		$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		date_default_timezone_set('America/Guayaquil');
		$fecha = date("Ymd", time());
		$hora = date("His", time());
		$fh = $fecha . "" . $hora;

		$sql = "SELECT * FROM Cli_Direccion_Dropshipping
			WHERE Facturaid = :Facturaid";
		$query = $pdo->prepare($sql);
		$query->bindParam(':Facturaid', $FACTURA_ID, PDO::PARAM_STR);
		if ($query->execute()) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			// return [0, $result];
			if (count($result) > 0) {

				$query2 = $pdo->prepare("UPDATE Cli_Direccion_Dropshipping
					SET
						 tienda_retiro = :tienda_retiro,
						 fecha_ingresada_guia = GETDATE(),
						 usuario_ingresada_guia =  :usuario_ingresada_guia,
						 comentariodesp = :comentariodesp,
						 envio_cli = :envio_cli
					WHERE Facturaid = :Facturaid
				");
				$query2->bindParam(':tienda_retiro', $TIENDA_RETIRO, PDO::PARAM_STR);
				$query2->bindParam(':comentariodesp', $COMENTARIO, PDO::PARAM_STR);
				$query2->bindParam(':usuario_ingresada_guia', $USUARIO, PDO::PARAM_STR);
				$query2->bindParam(':envio_cli', $ENVIO_CLI, PDO::PARAM_STR);
				$query2->bindParam(':Facturaid', $FACTURA_ID, PDO::PARAM_STR);
				if ($query2->execute()) {
					return [1, "ACTUALIZADO Cli_Direccion_Dropshipping"];
				} else {
					$err = $query2->errorInfo();
					return [0, $err];
				}
			} else {
				$bo = "";
				$estado = "DROPSHIPPING";
				$query2 = $pdo->prepare("INSERT into Cli_Direccion_Dropshipping 
				(
					Facturaid, 
					Bodegaid,
					direccion,
					Referencia,
					Telefono,
					Email,
					Estado,
					tienda_retiro,
					fecha_ingresada_guia,
					usuario_ingresada_guia,
					comentariodesp,
					envio_cli
				)  
				values(
					:Facturaid, 
					:Bodega_id, 
					:direccion, 
					:Referencia, 
					:Telefono, 
					:Email, 
					:Estado, 
					:tienda_retiro,
					GETDATE(),
					:usuario_ingresada_guia,
					:comentariodesp,
					:envio_cli
				) ");
				$query2->bindParam(':tienda_retiro', $TIENDA_RETIRO, PDO::PARAM_STR);
				$query2->bindParam(':Bodega_id', $bo, PDO::PARAM_STR);
				$query2->bindParam(':direccion', $bo, PDO::PARAM_STR);
				$query2->bindParam(':Referencia', $bo, PDO::PARAM_STR);
				$query2->bindParam(':Telefono', $bo, PDO::PARAM_STR);
				$query2->bindParam(':Email', $bo, PDO::PARAM_STR);
				$query2->bindParam(':Estado', $estado, PDO::PARAM_STR);
				$query2->bindParam(':comentariodesp', $COMENTARIO, PDO::PARAM_STR);
				$query2->bindParam(':usuario_ingresada_guia', $USUARIO, PDO::PARAM_STR);
				$query2->bindParam(':envio_cli', $ENVIO_CLI, PDO::PARAM_STR);
				$query2->bindParam(':Facturaid', $FACTURA_ID, PDO::PARAM_STR);
				if ($query2->execute()) {
					return [1, "INSERTADO Cli_Direccion_Dropshipping"];
				} else {
					$err = $query2->errorInfo();
					return [0, $err];
				}
			}
		} else {
			$err = $query->errorInfo();
			return [0, $err];
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		return [0, $e];
	}
}

function Guardar_Dobra($GUIA, $BULTOS, $FORMA_DESPACHO, $USUARIO, $SECUENCIA, $COMENTARIO, $BODEGAFAC)
{

	try {
		include("conexion_2.php");
		$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);

		$sql = "SELECT ID,Código, Nombre from SIS_SUCURSALES
		where ID = :factura ";
		$tipo = 'VEN-FA';
		$query = $pdo->prepare('Log_facturaslistas_despacho_update  
			@guia =:guia,
			@bultos=:bultos,  
			@transporte=:medio,
			@entregado= :usuario,
			@numfac=:numfac, 
			@tipo=:tipo, 
			@comentariod=:comentariod, 
			@bodegaFAC=:bodegaFAC');

		$query->bindParam(':guia', $GUIA, PDO::PARAM_STR);
		$query->bindParam(':bultos', $BULTOS, PDO::PARAM_STR);
		$query->bindParam(':medio', $FORMA_DESPACHO, PDO::PARAM_STR);
		$query->bindParam(':usuario', $USUARIO, PDO::PARAM_STR);
		$query->bindParam(':numfac', $SECUENCIA, PDO::PARAM_STR); // ENVIO SECUENCIA DE FACTURA
		$query->bindParam(':tipo', $tipo, PDO::PARAM_STR);
		$query->bindParam(':comentariod', $COMENTARIO, PDO::PARAM_STR);
		$query->bindParam(':bodegaFAC', $BODEGAFAC, PDO::PARAM_STR);
		if ($query->execute()) {
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			return [1, "ACTUALIZADO FACTURASLISTAS"];
		} else {
			$err = $query->errorInfo();
			// echo json_encode($err);
			return [0, $err];
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		return [0, $e];
	}
}




function mailmailcourier($factura, $bodegaFAC)
{
	require('conexion_2.php'); //Reemplaza las 4 lineas de abajo 

	//echo "Factura".$factura;

	date_default_timezone_set('America/Guayaquil');
	$fechahoy = date("Y-m-d", time());
	// echo "Fecha: ".$fechahoy."<br>";
	$tipo = 'VEN-FA';
	$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
	$result = $pdo->prepare("select top (1) a.id as id, a.secuencia as secu , bo.Sucursal as Sucurfact ,a.Detalle as nomcli, 
						s.nombre as nomsuc , s.dirección as localdireccion , d.saldo as saldo , b.SRI_EM1 as sri_em1
						from ven_facturas a with(nolock) 
						inner join VEN_FACTURAS_DT dt with(nolock) on dt.FacturaID = a.id
						inner join cli_clientes b with(nolock) on a.clienteid = b.id  
						inner join INV_BODEGAS bo with(nolock) on bo.id= dt.BodegaID 
						inner join SIS_SUCURSALES s with (nolock)  on s.Código = bo.Sucursal
						inner join CLI_CLIENTES_DEUDAS d with (nolock) on d.DocumentoID= a.id 
						where a.secuencia =:numfac and d.tipo =:tipo ");
	$result->bindParam(':numfac', $factura, PDO::PARAM_STR);
	$result->bindParam(':tipo', $tipo, PDO::PARAM_STR);
	$result->execute();

	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$direccionmail = $row['sri_em1'];
		//$direccionmail = 'pchavez@cartimex.com';
		$secuencia = $row['secu'];
		$nombrecli = $row['nomcli'];
		$sucurfact =  $row['Sucurfact'];
		$nomsuc =  $row['nomsuc'];
		$numfac =  $row['id'];
		$localdireccion =  $row['localdireccion'];
		$saldo =  number_format($row['saldo'], 2);
		// include("conexion_2sisco.php");
		// $sql0 = "SELECT a.* , p.bodega as bodegaret,c.sucursalid as sucursal FROM covidsales a
		// 	left outer join covidpickup p on  a.secuencia = p.orden
		// 	left outer join sisco.covidciudades c on p.bodega= c.almacen
		// 	where a.factura = trim('$secuencia') and a.anulada<> '1'  ";
		// $result0 = mysqli_query($con, $sql0);
		// $conrow = $result0->num_rows;
		$SISCO = validar_sisco_2($secuencia);
		$DATOS = $SISCO[1];
		// return $DATOS[0]["despachofinal"];
		// echo json_encode($DATOS[]);
		if (count($DATOS) > 0) {
			// while ($row0 = mysqli_fetch_array($result0)) {
			$rastreo = '';
			$Transporte = '';
			$Transporte = $DATOS[0]['despachofinal'];
			$guia = $DATOS[0]['despacho'];
			if ($Transporte == 'Urbano') {
				$rastreo = 'https://www.urbano.com.ec/';
			}
			if ($Transporte == 'Tramaco') {
				$rastreo = 'www.tramaco.com.ec/';
			}
			if ($Transporte == 'Servientrega') {
				$rastreo = 'www.servientrega.com.ec/rastreo/multiple';
			}
			// }
			if ($Transporte <> '') {
				$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result3 = $pdo3->prepare("select * from sis_sucursales where código =:sucursal");
				$result3->bindParam(':sucursal', $sucursal, PDO::PARAM_STR);
				$result3->execute();
				while ($row3 = $result3->fetch(PDO::FETCH_ASSOC)) {
					$bodegaret = 	$row3['Nombre'];
					$localdireccionr = 	$row3['Dirección'];
				}
				if ($sucursal <> $sucurfact) {

					$msg = " <img src='http://app.compu-tron.net/logos/Computron2.png' width='300' height='100'> <br><br>";
					$msg = $msg . "Estimado/a &nbsp; &nbsp; <strong> " . $nombrecli . "</strong> <br> La factura # <strong>" . $secuencia . "</strong> está lista para ser embarcada en el Courier:<br><br>";
					$msg = $msg . "<strong>Transporte:  </strong>" . $Transporte . "<br>";
					$msg = $msg . "<strong>Guia #:  </strong>" . $guia . "<br> ";
					$msg = $msg . "<strong>Rastree su pedido :  </strong><a href ='" . $rastreo . "' target=_blank > " . $rastreo  . "</a><br><br>";
					$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=600>";
					$msg = $msg . "<th align=left  width=450> ";
					$msg = $msg . " 	<strong>Detalle de Productos a enviar   </strong><br>";
					$msg = $msg . "   </table>";




					// con SQL1 obtengo los items y cantidades de las facturas

					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('LOG_PREPARAR_FACTURA_CORREO  @FacturaID=:numfac, @bodegaFAC=:bodegaFAC');
					$result1->bindParam(':numfac', $numfac, PDO::PARAM_STR);
					$result1->bindParam(':bodegaFAC', $bodegaFAC, PDO::PARAM_STR);
					$result1->execute();

					$msg = $msg .   "<table border=1 cellpadding=5 cellspacing=1 width=600>";
					$msg = $msg .  "<th align=left  width=100>Codigo</th> ";
					$msg = $msg .  "<th align=left  width=500>Articulo</th> ";
					$msg = $msg .  "<th align=right width=45>Cantidad</th>";

					while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) {
						$codigo = $row1['CopProducto'];
						$nombre = $row1['Detalle'];
						$cant = $row1['Cantidad'];

						$msg = $msg .  "<tr bgcolor=\"dddddd\"><td align=left  width=100>" . $codigo . "</td> ";
						$msg = $msg .  "<td align=left  width=500>" . $nombre . "</td> ";
						$msg = $msg .  "<td align=right width=45>" . $cant . "</td></tr>";
						$msg = $msg .  "</td>";
					}


					$msg = $msg .  "</table>";
					$msg = $msg .  "<br>Este correo fue generado de forma automática y no requiere respuesta..<br><br>";
					$msg = $msg .  "
					<strong>Nota de Descargo: </strong>La información contenida en este mensaje y sus anexos tiene carácter confidencial, <br>y está dirigida únicamente al destinatario de la misma y sólo podrá ser usada por éste. <br>
					Si usted ha recibido este mensaje por error, por favor borre el mensaje de su sistema. 
					";
					require_once 'PHPMailer/src/Exception.php';
					require_once 'PHPMailer/src/PHPMailer.php';
					require_once 'PHPMailer/src/SMTP.php';


					$m = new PHPMailer(true);

					$m->CharSet = 'UTF-8';
					$m->isSMTP();
					$m->SMTPAuth = true;
					$m->Host = 'smtp.gmail.com';
					$m->Username = 'sgo';
					$m->Password = 'csxj xbqb uncn yuuc';
					$m->SMTPSecure = 'ssl';
					$m->Port = 465;
					$m->From = 'facturacion@compu-tron.net';
					$m->addBCC('pchavez@cartimex.com');
					$m->FromName = 'Computron';
					//$destinatario = "fmortola@cartimex.com";
					$m->addAddress($direccionmail);
					// $m->addAddress('jalvarado@cartimex.com');
					$m->isHTML(true);
					// $m->addAttachment('directorio/nombrearchivo.jpg','nombrearchivo.jpg')
					$m->Subject = "COMPUTRONSA Detalle de Compra ";
					$m->Body = $msg;
					// $m->send();
					if (!$m->Send()) {
						return 'Mail error: ' . $m->ErrorInfo;
					} else {
						return "ENVIADO";
					}
				}
			}
		} else {
			return $secuencia;
		}
	}
}

function mailretiroenotratienda($factura, $bodegaFAC)
{

	require('conexion_2.php'); //Reemplaza las 4 lineas de abajo 

	date_default_timezone_set('America/Guayaquil');
	$fechahoy = date("Y-m-d", time());
	// echo "Fecha: ".$fechahoy."<br>";
	$tipo = 'VEN-FA';
	$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
	$result = $pdo->prepare("select top (1) a.id as id,a.secuencia as secu , bo.Sucursal as Sucurfact ,a.Detalle as nomcli, s.nombre as nomsuc , s.dirección as localdireccion , d.saldo as saldo , b.SRI_EM1 as sri_em1
						from ven_facturas a with(nolock) 
						inner join VEN_FACTURAS_DT dt with(nolock) on dt.FacturaID = a.id
						inner join cli_clientes b with(nolock) on a.clienteid = b.id  
						inner join INV_BODEGAS bo with(nolock) on bo.id= dt.BodegaID 
						inner join SIS_SUCURSALES s with (nolock)  on s.Código = bo.Sucursal
						inner join CLI_CLIENTES_DEUDAS d with (nolock) on d.DocumentoID= a.id 
						where a.secuencia =:numfac and d.tipo =:tipo ");
	$result->bindParam(':numfac', $factura, PDO::PARAM_STR);
	$result->bindParam(':tipo', $tipo, PDO::PARAM_STR);
	$result->execute();

	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$direccionmail = $row['sri_em1'];
		//$direccionmail = 'pchavez@cartimex.com';
		$secuencia = $row['secu'];
		$nombrecli = $row['nomcli'];
		$sucurfact =  $row['Sucurfact'];
		$nomsuc =  $row['nomsuc'];
		$numfac =  $row['id'];
		$localdireccion =  $row['localdireccion'];
		$saldo =  number_format($row['saldo'], 2);
		// include("conexion_2sisco.php");
		// $sql0 = "SELECT a.*, p.bodega as bodegaret,c.sucursalid as sucursal  
		// 	FROM covidsales a
		// 	inner join covidpickup p on p.orden= a.secuencia
		// 	left outer join sisco.covidciudades c on p.bodega= c.almacen
		// 	where a.factura = trim('$secuencia') and a.anulada<> '1'  ";
		// $result0 = mysqli_query($con, $sql0);
		// $conrow = $result0->num_rows;

		$SISCO = validar_sisco_2($secuencia);
		$DATOS = $SISCO[1];
		// return $DATOS;


		if (count($DATOS) > 0) {
			// while ($row0 = mysqli_fetch_array($result0)) {
			$bodegaret = $DATOS[0]['bodegaret'];
			$sucursal = $DATOS[0]['SUCURSAL_ID'];
			// }
			$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result3 = $pdo3->prepare("select * from sis_sucursales where ID =:sucursal");
			$result3->bindParam(':sucursal', $sucursal, PDO::PARAM_STR);
			$result3->execute();
			$row3 = $result3->fetchAll(PDO::FETCH_ASSOC);
			// return $row3;

			// while (1==1) {
			$bodegaret = 	$row3[0]['Nombre'];
			$localdireccionr = 	$row3[0]['Dirección'];
			// }
			if ($sucursal <> $sucurfact) {

				$msg = " <img src='http://app.compu-tron.net/logos/Computron2.png' width='300' height='100'> <br><br>";
				$msg = $msg . "Estimado/a &nbsp; &nbsp; <strong> " . $nombrecli . "</strong> <br> La factura # <strong>" . $secuencia . "</strong> está en camino para que la recoja! <br><br>";
				$msg = $msg . "Acérquese y retire su(s) producto(s) en: <br><br>";
				$msg = $msg . "<strong>Local:  </strong>" . $bodegaret . "<br>";
				$msg = $msg . "<strong>Dirección:  </strong>" . $localdireccionr . "<br><br>";
				$msg = $msg . "<strong>Saldo a Cancelar:  </strong>" . $saldo . "<br><br>";
				$msg = $msg . "<strong>Nota:  </strong> Si usted canceló la factura con Transferencia o Tarjeta de Crédito el pago debe estar en proceso. <br><br>";
				$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=600>";
				$msg = $msg . "<th align=left  width=450> ";
				$msg = $msg . " 	<strong>Detalle de Productos   </strong><br>";
				$msg = $msg . "   </table>";

				//echo "BOdega de Facturacion". $bodegaFAC . "Id fe Factura".$numfac; 
				//die (); 

				// con SQL1 obtengo los items y cantidades de las facturas

				$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result1 = $pdo1->prepare('LOG_PREPARAR_FACTURA_CORREO @FacturaID = :numfac , @bodegaFAC=:bodegaFAC');
				$result1->bindParam(':numfac', $numfac, PDO::PARAM_STR); //ENVIO ID DE FACTURA 
				$result1->bindParam(':bodegaFAC', $bodegaFAC, PDO::PARAM_STR);
				$result1->execute();

				$msg = $msg .   "<table border=1 cellpadding=5 cellspacing=1 width=600>";
				$msg = $msg .  "<th align=left  width=100>Codigo</th> ";
				$msg = $msg .  "<th align=left  width=500>Articulo</th> ";
				$msg = $msg .  "<th align=right width=45>Cantidad</th>";

				while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) {
					$codigo = $row1['CopProducto'];
					$nombre = $row1['Detalle'];
					$cant = $row1['Cantidad'];

					$msg = $msg .  "<tr bgcolor=\"dddddd\"><td align=left  width=100>" . $codigo . "</td> ";
					$msg = $msg .  "<td align=left  width=500>" . $nombre . "</td> ";
					$msg = $msg .  "<td align=right width=45>" . $cant . "</td></tr>";
					$msg = $msg .  "</td>";
				}


				$msg = $msg .  "</table>";
				$msg = $msg .  "<br>Este correo fue generado de forma automática y no requiere respuesta..<br><br>";
				$msg = $msg .  "
				<strong>Nota de Descargo: </strong>La información contenida en este mensaje y sus anexos tiene carácter confidencial, <br>y está dirigida únicamente al destinatario de la misma y sólo podrá ser usada por éste. <br>
				Si usted ha recibido este mensaje por error, por favor borre el mensaje de su sistema. 
				";

				require_once 'PHPMailer/src/Exception.php';
				require_once 'PHPMailer/src/PHPMailer.php';
				require_once 'PHPMailer/src/SMTP.php';


				$m = new PHPMailer(true);
				$m->CharSet = 'UTF-8';
				$m->isSMTP();
				$m->SMTPAuth = true;
				$m->Host = 'smtp.gmail.com';
				$m->Username = 'sgo';
				$m->Password = 'csxj xbqb uncn yuuc';
				$m->SMTPSecure = 'ssl';
				$m->Port = 465;
				$m->From = 'facturacion@compu-tron.net';
				$m->FromName = 'Computron';
				$m->addAddress($direccionmail);
				// $m->addAddress('jalvarado@cartimex.com');
				$m->isHTML(true);
				$m->Subject = "COMPUTRONSA Detalle de entrega/embarque ";
				$m->Body = $msg;
				if (!$m->Send()) {
					return 'Mail error: ' . $m->ErrorInfo;
				} else {
					return "ENVIADO";
				}
			}
		} else {
			return "NO HAY DATOS SISCO PARA MAIL";
		}
	}
}

function Restablecer_Multibodega($numfac, $bodegaFAC)
{
	include("conexion_2.php");
	$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
	$result6 = $pdo5->prepare('{CALL [PER_Detalle_Facturas2] (?,?)}');
	$result6->bindParam(1, $numfac, PDO::PARAM_STR);
	$result6->bindParam(2, $bodegaFAC, PDO::PARAM_STR);
	$MULTI = 0;
	$NO_INGRESADAS = 0;
	$BODEGAS = [];
	if ($result6->execute()) {
		$result = $result6->fetchAll(PDO::FETCH_ASSOC);

		foreach ($result as $row) {
			if ($row["Section"] != "HEADER") {
				if ($row["MULTI"] != "") {
					$MULTI = $MULTI + 1;
				}
				if ((trim($row["ESTADO_FACTURAS_LISTAS"])) != "INGRESADAGUIA") {
					$NO_INGRESADAS = $NO_INGRESADAS + 1;
				}
			
			}
		}
		if ($MULTI > 0) {
			if ($NO_INGRESADAS > 0) {
				return Restablecer_Multibodega_sisco($numfac);
			} else {
				return "NO HAY MULTI PARA RESTABLECER";
			}
		} else {
			return "NO HAY MULTI PARA RESTABLECER";
		}
	} else {
		$err = $result6->errorInfo();
		return $err;
	}
}

function Restablecer_Multibodega_sisco($SECUENCIA)
{
	include("conexion_2sisco.php");
	try {
		// $pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$result6 = $pdo->prepare("UPDATE covidsales
			SET  estado='Facturado'  
			where factura = :factura");
		$result6->bindParam(":factura", $SECUENCIA, PDO::PARAM_STR);
		if ($result6->execute()) {
			return "RETABLECIDO MULTI SISCO";
		} else {
			$err = $result6->errorInfo();
			return $err;
		}
	} catch (PDOException $e) {
		//return [];
		$e = $e->getMessage();
		return [0, $e];
	}
}
