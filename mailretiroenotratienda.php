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
	$factura = $_SESSION['numfac']; //Recibe SECUENCIA DE FACTURA 
	$bodegaFAC = $_SESSION['bodegaFAC'];
	$usuario1 = $usuario;

	require('conexion_mssql.php'); //Reemplaza las 4 lineas de abajo 

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
		include("conexion.php");
		$sql0 = "SELECT a.*, p.bodega as bodegaret,c.sucursalid as sucursal  FROM covidsales a
			inner join covidpickup p on p.orden= a.secuencia
			left outer join sisco.covidciudades c on p.bodega= c.almacen
			where a.factura = trim('$secuencia') and a.anulada<> '1'  ";
		$result0 = mysqli_query($con, $sql0);
		$conrow = $result0->num_rows;

		if ($conrow > 0) {
			while ($row0 = mysqli_fetch_array($result0)) {
				$bodegaret = $row0['bodegaret'];
				$sucursal = $row0['sucursal'];
			}
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

				require_once '../vendor/autoload.php';
				$m = new PHPMailer;
				$m->CharSet = 'UTF-8';
				$m->isSMTP();
				$m->SMTPAuth = true;
				$m->Host = 'mail.cartimex.com';
				$m->Username = 'sgo';
				$m->Password = 'revolutionary*10$2024';
				$m->SMTPSecure = 'ssl';
				$m->Port = 465;
				$m->From = 'facturacion@compu-tron.net';
				$m->FromName = 'Computron';
				$m->addAddress($direccionmail);
				$m->isHTML(true);
				$m->Subject = "COMPUTRONSA Detalle de entrega/embarque ";
				$m->Body = $msg;
			}
		}
	}
	//echo $msg; 

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