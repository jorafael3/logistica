<html>
<body>


<?php
session_start();
$Id=$_SESSION['id'];
$base = $_SESSION['base'] ;
$acceso= $_SESSION['acceso'];
$codigo= $_SESSION['codigo'];
$bodega = $_SESSION['bodega'];
$nomsuc=$_SESSION['nomsuc'];
$cliente=$_SESSION['cliente'];
$usuario= $_SESSION['usuario'];

if ($_GET['numfac']<>'') {$factura= $_GET['numfac']; }else{$factura= $_SESSION['numfac'];} //Recibela secuencia de la factura
if ($_GET['bodegaFAC']<>'') {$bodegaFAC= $_GET['bodegaFAC']; }else{$bodegaFAC= $_SESSION['bodegaFAC'];} //Recibela BODEGA DE PROVEEDOR

date_default_timezone_set('America/Guayaquil');
$fechahoy = date("Y-m-d", time());
// echo "Fecha: ".$fechahoy."<br>";
$tipo= 'VEN-FA';
//echo "Factura".$factura.$tipo.$bodegaFAC;

require('conexion_mssql.php'); //Reemplaza las 4 lineas de abajo
$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
$result = $pdo->prepare("LOG_FACTURA_PEDIDO_PROVEEDOR @secuencia=:numfac , @tipo=:tipo, @bodegaFAC=:bodegaFAC" );
$result->bindParam(':numfac',$factura,PDO::PARAM_STR);
$result->bindParam(':tipo',$tipo,PDO::PARAM_STR);
$result->bindParam(':bodegaFAC',$bodegaFAC,PDO::PARAM_STR);
$result->execute();

$proveedor= '';
while ($row = $result->fetch(PDO::FETCH_ASSOC))
{

	$direccionmail2 = 'gcassis@cartimex.com';
	$direccionmail3 = 'coordinadora-bodega@compu-tron.net';
	$direccionmail4 = 'despachokennedy1@compu-tron.net';
	$direccionmail5 = 'despachokennedy2@compu-tron.net';
	$direccionmail6 = 'lalvarez@cartimex.com';
	$direccionmail8 = '';
	if ($bodegaFAC=='0500000002')//MABE
		{
			$proveedor= 'MABE';
			//$direccionmail1 = 'Victor.Murillo@mabe.com.ec';
			$direccionmail1 = 'danny.parrales@mabe.com.ec';
			$direccionmail6 = ' ';
			$direccionmail7 = ' ';
		}
	else
			if ($bodegaFAC=='0500000003')//ECOLINE
				{
					$proveedor= 'ECOLINE';
					$direccionmail1 = 'raquel.cajamarca@ecoline.com.ec';
					$direccionmail6 = '';
					$direccionmail7 = ' ';
				}
			else
				{
					if ($bodegaFAC=='0500000004')//BOYACA
						{
							$proveedor= 'BOYACA';
							$direccionmail1 = 'ferreteria@boyaca.com';
							$direccionmail6 = ' ';
							$direccionmail7 = ' ';
						}
					else
						{
							if ($bodegaFAC=='0500000005')//LYCAN
								{
									$proveedor= 'LYCAN';
									$direccionmail1 = 'maitte@lycan-fitness.com';
									$direccionmail6 = ' ';
									$direccionmail7 = ' ';
								}
							else
								{
									if ($bodegaFAC=='0500000006')//IROBOT
										{
											$proveedor= 'IROBOT';
											$direccionmail1 = 'office@zederbauer.com';
											$direccionmail6 = ' ';
											$direccionmail7 = ' ';
										}
									else
										{
											if ($bodegaFAC=='0500000007')//CARTIMEX
												{
													$proveedor= 'CARTIMEX';
													$direccionmail1 = 'mmontero@cartimex.com';
													$direccionmail2 = 'lvera@cartimex.com';
													$direccionmail7 = ' ';
												}
											else
												{
													if ($bodegaFAC=='0500000008')//Electrolux
														{
															$proveedor= 'ELECTROLUX';
															$direccionmail1 = 'carola.cornejo@electrolux.com';
															$direccionmail6 = 'javier.verdesoto@electrolux.com';
															$direccionmail7 = ' ';
														}
													else
														{
															$proveedor= 'INDURAMA';
															$direccionmail1 = 'rbriones@indurama.com';
															$direccionmail6 = 'mmontero@cartimex.com ';
															$direccionmail7 = 'lvera@cartimex.com ';
															$direccionmail8 = '';
														}
												}
										}
								}
						}
				}
	//$direccionmail = 'pchavez@cartimex.com';
	$secuencia = $row['secu'];
	$nombrecli = $row['nomcli'];
	$sucurfact=  $row['Sucurfact'];
	$nomsuc=  $row['nomsuc'];
	$numfac=  $row['id'];
	$comentario=  $row['comentario'];
	$Transporte=  $row['Transporte'];
	$guia=  $row['guia'];
	$saldo=  number_format($row['saldo'],2);

			if ($proveedor<>'MABE' or $proveedor<>'INDURAMA'or $proveedor=='ELECTROLUX') {
					$msg = " <img src='https://www.cartimex.com/assets/img/logo200.png' width='300' height='100'> <br><br>";
					$msg = $msg . "<strong> ORDEN DE COMPRA  </strong><br><br>";
					$msg = $msg . "<strong>Numero :  </strong>" .$secuencia . "<br>";
					$msg = $msg . "<strong> Proveedor " . $proveedor ." </strong> <br><br>";
					$msg = $msg . "<strong>Transporte:  </strong>" .$Transporte . "<br>";
					$msg = $msg . "<strong>Guia #:  </strong>" . $guia ."<br> ";
					$msg = $msg . "<strong>Detalle Despacho:  </strong>" . $comentario ."<br> ";
					$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=600>";
					$msg = $msg . "<th align=left  width=450> ";
					$msg = $msg . " <strong>Detalle de Productos </strong><br>";
					$msg = $msg . " </table>";
			}
			else	{
					$msg = " <img src='https://www.cartimex.com/assets/img/logo200.png' width='300' height='100'> <br><br>";
					$msg = $msg . "<strong> ORDEN DE COMPRA  </strong><br><br>";
					$msg = $msg . "<strong>Numero :  </strong>" .$secuencia . "<br>";
					$msg = $msg . "<strong> Proveedor " . $proveedor ." </strong> <br>";
					$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=600>";
					$msg = $msg . "<th align=left  width=450> ";
					$msg = $msg . " <strong>Detalle de Productos </strong><br>";
					$msg = $msg . " </table>";
			}

					// con SQL1 obtengo los items y cantidades de las facturas
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('LOG_PREPARAR_FACTURA_CORREO  @FacturaID = :numfac, @bodegaFAC=:bodegaFAC');
					$result1->bindParam(':numfac',$numfac,PDO::PARAM_STR);
					$result1->bindParam(':bodegaFAC',$bodegaFAC,PDO::PARAM_STR);
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
							 
													require_once '../vendor/autoload.php';
													$m = new PHPMailer;
													$m->CharSet = 'UTF-8';
													$m->isSMTP();
													$m->SMTPAuth = true;
													$m->Host = 'mail.cartimex.com';
													$m->Username = 'sgo';
													$m->Password = 'sistema2021*';
													$m->SMTPSecure = 'ssl';
													$m->Port = 465;
													$m->From = 'facturacion@compu-tron.net';
													$m->addBCC($direccionmail2);
													$m->addBCC($direccionmail3);
													$m->addBCC($direccionmail4);
													$m->addBCC($direccionmail5);
													$m->addBCC($direccionmail6);
													$m->addBCC($direccionmail7);
													$m->addBCC($direccionmail8);
													$m->addBCC('pchavez@cartimex.com');
													$m->FromName = 'CARTIMEX';
													//$destinatario = "fmortola@cartimex.com";
													$m->addAddress($direccionmail1);
													$m->isHTML(true);
													// $m->addAttachment('directorio/nombrearchivo.jpg','nombrearchivo.jpg')
													$m->Subject = "CARTIMEX Pedido Proveedor ".$proveedor;
													$m->Body = $msg;
													var_dump( $m->send() );

		$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$result2 = $pdo2->prepare('update facturaslistas set pedido = 1 where Factura=:numfac and BODEGAID=:bodegaFAC');
		$result2->bindParam(':numfac',$numfac,PDO::PARAM_STR);
		$result2->bindParam(':bodegaFAC',$bodegaFAC,PDO::PARAM_STR);
		$result2->execute();
}

//echo $msg;
$_SESSION['usuario']=$usuario1;
$_SESSION['base']= $base ;
$_SESSION['acceso']=$acceso;
$_SESSION['bodega']=$bodega;
$_SESSION['nomsuc']=$nomsuc;
$_SESSION['numfac']=$numfac;

header("Refresh: 0 ; ingguiasfacturas.php");


?>

</body>
</p>
</html>
