<html>
<body>
<body oncontextmenu="return false">

<?php
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];

			if ($base=='CARTIMEX'){
					require '../headcarti.php';
			}
			else{
					require '../headcompu.php';
			}

		    $id= $_POST["id"];
			$chofer = trim($_POST["chofer"]);
			$placa = $_POST["placa"];
			$transporte = $_POST["transporte"];
			$guia = $_POST["guia"];
			$comentarioentre = $_POST["comentarioentre"];

			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $codid. "---".$chofer. "--".$placa ."--".$comentarioentre."--".$transporte."--".	$guia;
			//die();
			$usuario1= $usuario;
			require('../conexion_mssql.php');

			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare("LOG_FACTURAS_ENTREGADAS_VEHICULO @chofer=:chofer , @placa=:placa ,@comentario=:comentario , @id=:id, @usuario=:usuario,  @guia=:guia" );
			$result->bindParam(':chofer',$chofer,PDO::PARAM_STR);
			$result->bindParam(':placa',$placa,PDO::PARAM_STR);
			$result->bindParam(':comentario',$comentarioentre,PDO::PARAM_STR);
			$result->bindParam(':id',$id,PDO::PARAM_STR);
			$result->bindParam(':usuario',$usuario1,PDO::PARAM_STR);
			$result->bindParam(':guia',$guia,PDO::PARAM_STR);
			//Executes the query
			$result->execute();
			$usuario= $usuario1;
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";

			//die();
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			$tipo= 'VEN-FA';

			 /**************Enviar correo al cliente de que su pedido ya fue entregado **/
			$pdomail = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$resultmail = $pdomail->prepare("select top (1) id =f.id, secu=f.secuencia, nomcli= f.Detalle, sri_em1= b.email ,
									Transporte= p.nombre, TranspID= p.id, Guia = fl.guia , Placa= fl.placa
									from ven_facturas f with(nolock)
									inner join FACTURASLISTAS fl with (nolock) on f.id= fl.Factura
									inner join VEN_FACTURAS_DT dt with(nolock) on dt.FacturaID = f.id
									inner join cli_clientes b with(nolock) on f.clienteid = b.id
									inner join sis_parametros p with (nolock) on p.id= fl.TRANSPORTE
									where f.id =:numfac and fl.tipo =:tipo " );
			$resultmail->bindParam(':numfac',$id,PDO::PARAM_STR);
			$resultmail->bindParam(':tipo',$tipo,PDO::PARAM_STR);
			$resultmail->execute();

			while ($rowmail = $resultmail->fetch(PDO::FETCH_ASSOC))
				{
					$direccionmail = $rowmail['sri_em1'];
					$correos= array();
					$correos= explode(";", $direccionmail);
					//$direccionmail = 'pchavez@cartimex.com';
					$secuencia = $rowmail['secu'];
					$nombrecli = $rowmail['nomcli'];
					$numfac=  $rowmail['id'];
					$msg = " <img src='https://www.cartimex.com/assets/img/logo200.png' width='300' height='100'> <br><br>";
					$msg = $msg . "Estimado/a &nbsp; &nbsp; <strong> ". $nombrecli . "</strong> <br> La factura # <strong>" . $secuencia . "</strong> fue entregada:<br><br>";
					$msg = $msg . "<strong>Transporte:  </strong>" .$rowmail['Transporte'] . "<br>";
					$msg = $msg . "<strong>Guia o Placa #:  </strong>" .$rowmail['Guia'].$rowmail['Placa'] ."<br> ";
					$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=600>";
					$msg = $msg . "<th align=left  width=450> ";
					$msg = $msg . " 	<strong>Detalle de Productos entregados   </strong><br>";
					$msg = $msg . "   </table>";
					// con SQL1 obtengo los items y cantidades de las facturas
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('LOG_PREPARAR_FACTURA2 @FacturaID = :numfac');
					$result1->bindParam(':numfac',$numfac,PDO::PARAM_STR);
					$result1->execute();

					$msg = $msg .  "<table border=1 cellpadding=5 cellspacing=1 width=600>";
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
								$msg = $msg .  "<br><strong><font color=red> AVISO IMPORTANTE </font></strong><br>";
								$msg = $msg .  "Estimados Clientes:<br><strong>CARTIMEX S.A. </strong>apegándose al Art.50 de LORTI informa a sus distinguidos clientes, que se podrán recibir e ingresar las retenciones autorizadas <br> hasta el <strong><font color=red> 5to día luego de la emisión del comprobante de venta.</font></strong>";
								$msg = $msg .  "<br>En caso de no recibir las retenciones en el plazo establecido, el documento no será considerado por parte de CARTIMEX S.A <br> y el valor de la factura deberá ser cancelado en su totalidad. <br>";
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
								$m->isHTML(true);
								$m->Subject = "CARTIMEX Detalle de entrega/embarque";
								$m->Body = $msg;
								var_dump( $m->send() );
				}
			header("location: entregarfacturas.php");

		}
		else
		{
			header("location: index.html");
		}

?>
</body>

</html>
