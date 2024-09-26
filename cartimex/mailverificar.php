
<html>
<body>

<?php


session_start();

$bloqueado = $_SESSION['bloqueo'];
$nota = $_SESSION['nota'];
$usuario= $_SESSION['usuario'];
$Id=$_SESSION['id'];
$base = $_SESSION['base'] ;
$acceso= $_SESSION['acceso'];
$codigo= $_SESSION['codigo'];
$nomsuc=$_SESSION['nomsuc'];
$cliente=$_SESSION['cliente'];
$factura= $_SESSION['facturaid']; //Recibe el id  de la factura 

//echo "Esto llega ".$usuario; 
$usuario1 = $usuario; 
require('../conexion_mssql.php'); //Reemplaza las 4 lineas de abajo 

//echo "Factura".$factura.$base;


date_default_timezone_set('America/Guayaquil');
$fechahoy = date("Y-m-d", time());
// echo "Fecha: ".$fechahoy."<br>";
$tipo= 'VEN-FA';
$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
$result = $pdo->prepare("select top (1) a.secuencia as secu , bo.Sucursal as Sucurfact ,a.Detalle as nomcli, s.nombre as nomsuc , s.dirección as localdireccion , d.saldo as saldo , b.SRI_EM1 as sri_em1
						from ven_facturas a with(nolock) 
						inner join VEN_FACTURAS_DT dt with(nolock) on dt.FacturaID = a.id
						inner join cli_clientes b with(nolock) on a.clienteid = b.id  
						inner join INV_BODEGAS bo with(nolock) on bo.id= dt.BodegaID 
						inner join SIS_SUCURSALES s with (nolock)  on s.Código = bo.Sucursal
						inner join CLI_CLIENTES_DEUDAS d with (nolock) on d.DocumentoID= a.id 
						where a.id =:numfac and d.tipo =:tipo " );
$result->bindParam(':numfac',$factura,PDO::PARAM_STR);
$result->bindParam(':tipo',$tipo,PDO::PARAM_STR);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC))
{
	//$direccionmail = $row['sri_em1'];
	$direccionmail = 'pchavez@cartimex.com';
	$secuencia = $row['secu'];
	$nombrecli = $row['nomcli'];
	$sucurfact=  $row['Sucurfact'];
	$nomsuc=  $row['nomsuc'];
	$localdireccion=  $row['localdireccion'];
	$saldo=  number_format($row['saldo'],2);
	
				
				$msg = " <img src='http://app.compu-tron.net/logos/Computron2.png' width='300' height='100'> <br><br>";
				$msg = $msg . "Estimado/a &nbsp; &nbsp; <strong> ". $nombrecli . "</strong> <br> La factura # <strong>" . $secuencia . "</strong> está lista para que la recoja! <br><br>";
				$msg = $msg . "Acérquese y retire su(s) producto(s) en: <br><br>";
				$msg = $msg . "<strong>Local:  </strong>" .$nomsuc."<br>";
				$msg = $msg . "<strong>Dirección:  </strong>" . $localdireccion ."<br><br>";
				$msg = $msg . "<strong>Saldo a Cancelar:  </strong>" . $saldo . "<br><br>";
				$msg = $msg . "<strong>Nota:  </strong> Si usted canceló la factura con Transferencia o Tarjeta de Crédito el pago debe estar en proceso. <br><br>";
				$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=600>";
				$msg = $msg . "<th align=left  width=450> ";
				$msg = $msg . " 	<strong>Detalle de Productos   </strong><br>";
				$msg = $msg . "   </table>";
				 
				// con SQL1 obtengo los items y cantidades de las facturas

				$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result1 = $pdo1->prepare('LOG_PREPARAR_FACTURA @FacturaID = :numfac');
				$result1->bindParam(':numfac',$factura,PDO::PARAM_STR);
				$result1->execute();

				$msg = $msg .   "<table border=1 cellpadding=5 cellspacing=1 width=600>";
				$msg = $msg .  "<th align=left  width=100>Codigo</th> ";
				$msg = $msg .  "<th align=left  width=500>Articulo</th> ";
				$msg = $msg .  "<th align=right width=45>Cantidad</th>"; 

					while ($row1 = $result1->fetch(PDO::FETCH_ASSOC))
						{
							$codigo = $row1['CopProducto'];
							$nombre = $row1['Detalle'];
							$cant = $row1['Cantidad'];
							
							$msg = $msg .  "<tr bgcolor=\"dddddd\"><td align=left  width=100>".$codigo."</td> ";
							$msg = $msg .  "<td align=left  width=500>".$nombre."</td> ";
							$msg = $msg .  "<td align=right width=45>".$cant."</td></tr>"; 
							$msg = $msg .  "</td>";
						}
				 

				$msg = $msg .  "</table>";
				$msg = $msg .  "<br>Este correo fue generado de forma automática y no requiere respuesta..<br><br>";
				$msg = $msg .  "
				<strong>Nota de Descargo: </strong>La información contenida en este mensaje y sus anexos tiene carácter confidencial, <br>y está dirigida únicamente al destinatario de la misma y sólo podrá ser usada por éste. <br>
				Si usted ha recibido este mensaje por error, por favor borre el mensaje de su sistema. 
				";
						// PARA CAMBIAR ALEATORIAMENTE EL ENVIO DEL CORREO
												$aleatorio = (rand(1,5));
													switch ($aleatorio) {
													case "1":
														$mailsender="cartimexmail1@gmail.com";
														break;
													case "2":
														$mailsender="cartimexmail2@gmail.com";
														break;
													case "3":
														$mailsender="cartimexmail3@gmail.com";
														break;
													case "4":
														$mailsender="cartimexmail4@gmail.com";
														break;
													default:
														$mailsender="cartimexmail5@gmail.com";
													}
												require_once '../vendor/autoload.php';
												$m = new PHPMailer();
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
												var_dump( $m->send() );
}	

$_SESSION['cliente']=$cliente;
$_SESSION['usuario']=$usuario1;
$_SESSION['id']=$Id;
$_SESSION['base']= $base ;
$_SESSION['acceso']=$acceso;
$_SESSION['codigo']=$codigo;
$_SESSION['nomsuc']=$nomsuc; 

header("Refresh: 0 ; verificarfacturas.php");


?>

</body>
</p>
</html>