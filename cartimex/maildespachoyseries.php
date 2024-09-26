<html>
<body>

<?php
session_start();

$usuario = $_SESSION['usuario'];
$base = $_SESSION['base'];
$acceso	=$_SESSION['acceso'];
$bodega = $_SESSION['bodega'];
$nomsuc = $_SESSION['nomsuc'];
$factura= $_SESSION['numfac'];// Recibe el id  de la factura
$usuario1=$usuario;

//echo "esto viene". $factura;

require('../conexion_mssql.php'); //Reemplaza las 4 lineas de abajo

date_default_timezone_set('America/Guayaquil');
$fechahoy = date("Y-m-d", time());
// echo "Fecha: ".$fechahoy."<br>";
$tipo= 'VEN-FA';
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare("select top (1) a.secuencia as secu , bo.Sucursal as Sucurfact ,
											a.Detalle as nomcli, s.nombre as nomsuc , s.dirección as localdireccion ,
											d.saldo as saldo , b.email as sri_em1,  B.EmailDespacho as maildespacho
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
						$correos= array();
				    $correos= explode(";", $row['sri_em1']);
						//$direccionmail = $row['sri_em1'];
						$direccionmail1 = $row['maildespacho'];
						//$direccionmail = 'pchavez@cartimex.com';
						$secuencia = $row['secu'];
						$nombrecli = $row['nomcli'];
						$sucurfact=  $row['Sucurfact'];
						$nomsuc=  $row['nomsuc'];
						$localdireccion=  $row['localdireccion'];
						$saldo=  number_format($row['saldo'],2);

						$msg = " <img src='https://www.cartimex.com/assets/img/logo200.png' width='300' height='100'> <br><br>";
						$msg = $msg . "<strong> Gracias por su compra !!  </strong><br><br>";
						$msg = $msg . "Estimado/a &nbsp; &nbsp; <strong> ". $nombrecli . "</strong> <br> La factura # <strong>" . $secuencia . "</strong> fue retirada en CARTIMEX S.A.<br><br>";
						$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=500>";
						$msg = $msg . "<th align=left  width=450> ";
						$msg = $msg . " 	<strong>Detalle de Productos retirados   </strong><br>";
						$msg = $msg . "   </table>";

						// con SQL1 obtengo los items y cantidades de las facturas
						$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						$result1 = $pdo1->prepare('LOG_PREPARAR_FACTURA_CORREO @FacturaID = :numfac');
						$result1->bindParam(':numfac',$factura,PDO::PARAM_STR);
						$result1->execute();
								$msg = $msg .   "<table border=1 cellpadding=5 cellspacing=1 width=500>";
								$msg = $msg .  "<th align=left  width=150>Codigo</th> ";
								$msg = $msg .  "<th align=left  width=500>Articulo</th> ";
								$msg = $msg .  "<th align=left width=45>Cant.</th>";
								$msg = $msg .  "<th align=left width=40>Serie</th>";
								while ($row1 = $result1->fetch(PDO::FETCH_ASSOC))
									{
										$codigo = $row1['CopProducto'];
										$nombre = $row1['Detalle'];
										$cant = $row1['Cantidad'];
										$productoid = $row1['ProductoId'];
										$registraserie = $row1['RegistaSerie'];
											if ($registraserie==1)
												{
													$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
													$result5 = $pdo5->prepare("select * from rma_facturas rf with(nolock)
																			   inner  join rma_facturas_dt rdt with(nolock) on rf.id= rdt.facturaid
												        						where rf.facturaid =:numfac and rdt.productoid=:productoid");
													$result5->bindParam(':numfac',$factura,PDO::PARAM_STR);
													$result5->bindParam(':productoid',$productoid,PDO::PARAM_STR);
													$result5->execute();
													while ($row5 = $result5->fetch(PDO::FETCH_ASSOC))
														{
															$serie = $row5['Serie'];
															$msg = $msg .  "<tr bgcolor=\"dddddd\"><td align=left  width=100>".$codigo."</td> ";
															$msg = $msg .  "<td align=left  width=500>".$nombre."</td> ";
															$msg = $msg .  "<td align=right width=45> 1 </td>";
															$msg = $msg .  "<td align=left  width=500>".$serie."</td></tr> ";
														}
												}
											else
												{
													$msg = $msg .  "<tr bgcolor=\"dddddd\"><td align=left  width=100>".$codigo."</td> ";
													$msg = $msg .  "<td align=left  width=500>".$nombre."</td> ";
													$msg = $msg .  "<td align=right width=45>".$cant."</td>";
													$msg = $msg .  "<td align=left  width=500> </td></tr> ";
												}
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
									if(filter_var($email, FILTER_VALIDATE_EMAIL)){
										$m->addAddress($email);
										}
										//$m->addAddress($email);
								}
							//	$m->addAddress($direccionmail);
								$m->addAddress($direccionmail1);
								$m->isHTML(true);
								$m->Subject = "CARTIMEX Detalle de entrega/embarque ";
								$m->Body = $msg;
								var_dump( $m->send() );
					}
//echo $msg;


$_SESSION['usuario']=$usuario1;
$_SESSION['base']= $base ;
$_SESSION['acceso']=$acceso;
$_SESSION['bodega']=$bodega;
//$_SESSION['nomsuc']=$nomsuc;
$_SESSION['numfac']=$numfac;

header("Refresh: 0 ; verificarfacturas.php");


?>

</body>
</p>
</html>
