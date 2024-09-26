<html>

<body>

	<?php
	session_start();
	$Id = $_SESSION['id'];
	$base = $_SESSION['base'];
	$acceso = $_SESSION['acceso'];
	$codigo = $_SESSION['codigo'];
	$bodega = $_SESSION['bodega'];
	$nomsuc = $_SESSION['nomsuc'];
	$cliente = $_SESSION['cliente'];
	$usuario = $_SESSION['usuario'];
	$factura = $_SESSION['numfac']; //Recibe la secuencia de la factura

	$usuario1 = $usuario;
	date_default_timezone_set('America/Guayaquil');
	$fechahoy = date("Y-m-d", time());
	// echo "Fecha: ".$fechahoy."<br>";
	//echo "Factura".$factura;
	//die();
	$tipo = 'VEN-FA';
	require('../conexion_mssql.php');
	$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
	$result = $pdo->prepare("select top (1) id =f.id, secu=f.secuencia, nomcli= f.Detalle, sri_em1= b.email ,
						Transporte= p.nombre, TranspID= p.id, Guia = fl.guia , Placa= fl.placa
						from ven_facturas f with(nolock)
						inner join FACTURASLISTAS fl with (nolock) on f.id= fl.Factura
						inner join VEN_FACTURAS_DT dt with(nolock) on dt.FacturaID = f.id
						inner join cli_clientes b with(nolock) on f.clienteid = b.id
						inner join sis_parametros p with (nolock) on p.id= fl.TRANSPORTE
						where f.secuencia =:numfac and fl.tipo =:tipo ");
	$result->bindParam(':numfac', $factura, PDO::PARAM_STR);
	$result->bindParam(':tipo', $tipo, PDO::PARAM_STR);
	$result->execute();

	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		$direccionmail = $row['sri_em1'];
		$correos = array();
		$correos = explode(";", $direccionmail);
		//$direccionmail = 'pchavez@cartimex.com';
		$secuencia = $row['secu'];
		$nombrecli = $row['nomcli'];
		$numfac =  $row['id'];
		if ($row['TranspID'] == '0100000038') {
			$rastreo = 'www.servientrega.com.ec/rastreo/multiple';
		} else if ($row['TranspID'] == '0000618628') {
			$rastreo = 'https://www.urbano.com.ec/';
		} else {
			$rastreo = '';
		}

		$msg = " <img src='https://www.cartimex.com/assets/img/logo200.png' width='300' height='100'> <br><br>";
		$msg = $msg . "Estimado/a &nbsp; &nbsp; <strong> " . $nombrecli . "</strong> <br> La factura # <strong>" . $secuencia . "</strong> está lista para ser embarcada en el Courier:<br><br>";
		$msg = $msg . "<strong>Transporte:  </strong>" . $row['Transporte'] . "<br>";
		$msg = $msg . "<strong>Guia o Placa #:  </strong>" . $row['Guia'] . $row['Placa'] . "<br> ";
		$msg = $msg . "<strong>Rastree su pedido :  </strong><a href ='" . $rastreo . "' target=_blank > " . $rastreo  . "</a><br><br>";
		$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=600>";
		$msg = $msg . "<th align=left  width=450> ";
		$msg = $msg . "<strong>Detalle de Productos a enviar</strong><br>";
		$msg = $msg . "</table>";
		// con SQL1 obtengo los items y cantidades de las facturas
		$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$result1 = $pdo1->prepare('LOG_PREPARAR_FACTURA2 @FacturaID = :numfac');
		$result1->bindParam(':numfac', $numfac, PDO::PARAM_STR);
		$result1->execute();

		$msg = $msg .  "<table border=1 cellpadding=5 cellspacing=1 width=600>";
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
		$msg = $msg .  "<br><strong><font color=red> AVISO IMPORTANTE </font></strong><br>";
		$msg = $msg .  "Estimados Clientes:<br><strong>CARTIMEX S.A. </strong>apegándose al Art.50 de LORTI informa a sus distinguidos clientes, que se podrán recibir e ingresar las retenciones autorizadas <br> hasta el <strong><font color=red> 5to día luego de la emisión del comprobante de venta.</font></strong>";
		$msg = $msg .  "<br>En caso de no recibir las retenciones en el plazo establecido, el documento no será considerado por parte de CARTIMEX S.A <br> y el valor de la factura deberá ser cancelado en su totalidad. <br>";
		$msg = $msg .  "<br>Este correo fue generado de forma automática y no requiere respuesta..<br><br>";
		$msg = $msg .  "
					<strong>Nota de Descargo: </strong>La información contenida en este mensaje y sus anexos tiene carácter confidencial, <br>y está dirigida únicamente al destinatario de la misma y sólo podrá ser usada por éste. <br>
					Si usted ha recibido este mensaje por error, por favor borre el mensaje de su sistema.
					";
		// PARA CAMBIAR ALEATORIAMENTE EL ENVIO DEL CORREO

		require_once '../vendor/autoload.php';
		$m = new PHPMailer();
		$m->CharSet = 'UTF-8';
		$m->isSMTP();
		$m->SMTPAuth = true;
		$m->Host = 'smtp.gmail.com';
		$m->Username = 'sgo';
		$m->Password = 'nyai liux sagy jtsk';
		$m->SMTPSecure = 'ssl';
		$m->Port = 465;
		$m->From = 'facturacion@cartimex.com';
		$m->addBCC('pchavez@cartimex.com');
		$m->FromName = 'Cartimex S.A.';
		foreach ($correos as $email) {
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$m->addAddress($email);
			}
			//$m->addAddress($email);
		}
		$m->isHTML(true);
		// $m->addAttachment('directorio/nombrearchivo.jpg','nombrearchivo.jpg')
		$m->Subject = "CARTIMEX Detalle de entrega/embarque";
		$m->Body = $msg;
		var_dump($m->send());
	}
	//echo $msg;
	//die();

	$_SESSION['usuario'] = $usuario1;
	$_SESSION['base'] = $base;
	$_SESSION['acceso'] = $acceso;
	$_SESSION['bodega'] = $bodega;
	$_SESSION['nomsuc'] = $nomsuc;
	$_SESSION['numfac'] = $numfac;

	header("Refresh: 0 ; ingguiasfacturas.php");


	?>

</body>
</p>

</html>