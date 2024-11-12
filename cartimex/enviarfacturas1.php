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
			$tipodespacho = $_SESSION['tipodespacho'];
			echo "1Usuario:".$usuario.  $codid. "---".$chofer. "--".$placa ."--".$comentarioentre."--".$transporte."--".	$guia;

			$usuario1= $usuario;
			require('../conexion_mssql.php');

			if ($tipodespacho=="MOSTRADOR-GYE" OR $tipodespacho=="MOSTRADOR-UIO"){
				$estado="PREPARADA";
			}
			else {
				if ($tipodespacho=="CIUDAD-GYE" OR $tipodespacho=="CIUDAD-UIO"){
					$estado="VERIFICADA";
				}
			}

			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare("LOG_FACTURAS_ENVIOS_FACTURASGYEUIO @chofer=:chofer , @placa=:placa ,@comentario=:comentario , @id=:id, @usuario=:usuario,  @guia=:guia ,  @transporte=:transporte ,  @estado=:estado" );
			$result->bindParam(':chofer',$chofer,PDO::PARAM_STR);
			$result->bindParam(':placa',$placa,PDO::PARAM_STR);
			$result->bindParam(':comentario',$comentarioentre,PDO::PARAM_STR);
			$result->bindParam(':id',$id,PDO::PARAM_STR);
			$result->bindParam(':usuario',$usuario1,PDO::PARAM_STR);
			$result->bindParam(':guia',$guia,PDO::PARAM_STR);
			$result->bindParam(':transporte',$transporte,PDO::PARAM_STR);
			$result->bindParam(':estado',$estado,PDO::PARAM_STR);
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
			$resultmail = $pdomail->prepare("select top (1) id =f.id, secu=f.secuencia, nomcli= f.Detalle,e.email,Vendedor= e.nombre,Fecha= fl.Fechaenviar,
													Guia = fl.guiaenvio,  Chofer= choferenvio, Placa= placaenvio,
													Transporte= fl.transporenvio,fl.TipoPedido,f.SucursalID
													from ven_facturas f with(nolock)
													inner join FACTURASLISTAS fl with (nolock) on f.id= fl.Factura
													inner join VEN_FACTURAS_DT dt with(nolock) on dt.FacturaID = f.id
													inner join cli_clientes b with(nolock) on f.clienteid = b.id
													inner join EMP_EMPLEADOS e  with (nolock) on e.id= f.VendedorID
													where f.id =:numfac and fl.tipo =:tipo " );
			$resultmail->bindParam(':numfac',$id,PDO::PARAM_STR);
			$resultmail->bindParam(':tipo',$tipo,PDO::PARAM_STR);
			$resultmail->execute();
			while ($rowmail = $resultmail->fetch(PDO::FETCH_ASSOC))
				{
					if ($rowmail['SucursalID']=='03') {
						$direccionmail1='dflores@uio.cartimex.com';
						$direccionmail2='jcjami@uio.cartimex.com';
					}
					else {
						$direccionmail1='mmontero@cartimex.com';
						$direccionmail2='lvera@cartimex.com';
						$direccionmail3='vfranco@cartimex.com';
					}

					$direccionmail = $rowmail['email'];
					//$direccionmail = 'pchavez@cartimex.com';
					$secuencia = $rowmail['secu'];
					$nombrecli = $rowmail['nomcli'];
					$numfac=  $rowmail['id'];
					$vendedor=  $rowmail['Vendedor'];

					$msg = " <img src='https://www.cartimex.com/assets/img/logo200.png' width='300' height='100'> <br><br>";

					$msg = $msg . "Estimado/a &nbsp; &nbsp; <strong> ". $vendedor . "</strong> <br> ";

					$msg = $msg . "La factura # <strong>" . $secuencia . "</strong> del cliente ". $rowmail['nomcli'] ." fue enviada desde la bodega de acopio.<br><br>";
					$msg = $msg . "<strong>Tipo Despacho : </strong>" .$rowmail['TipoPedido'] . "<br>";
					$msg = $msg . "<strong>Fecha/Hora Envío : </strong>" .$rowmail['Fecha'] . "<br>";
					$msg = $msg . "<strong>Transporte:  </strong>" .$rowmail['Transporte'] . "<br>";
					$msg = $msg . "<strong>Guia / Placa #:  </strong>" .$rowmail['Guia']."/".$rowmail['Placa'] ."<br> ";
					$msg = $msg . "<strong>Chofer :  </strong>" .$rowmail['Chofer']."<br> ";
					$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=600>";
					$msg = $msg . "<th align=left  width=450> ";
					$msg = $msg . " 	<strong>Detalle de Productos Enviados   </strong><br>";
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
					$msg = $msg .  "</table><br><br>";
					$msg = $msg .  "<br>Este correo fue generado de forma automática y no requiere respuesta..<br><br>";
					$msg = $msg .  "
								<strong>Nota de Descargo: </strong>La información contenida en este mensaje y sus anexos tiene carácter confidencial, <br>y está dirigida únicamente al destinatario de la misma y sólo podrá ser usada por éste. <br>
								Si usted ha recibido este mensaje por error, por favor borre el mensaje de su sistema.
								";
								require_once '../vendor/autoload.php';
								$m = new PHPMailer();
								$m->CharSet = 'UTF-8';
								$m->isSMTP();
								$m->SMTPAuth = true;
								$m->Host = 'smtp.gmail.com';
								$m->Username = 'sgo';
								$m->Password = 'csxj xbqb uncn yuuc';
								$m->SMTPSecure = 'ssl';
								$m->Port = 465;
								$m->From = 'facturacion@cartimex.com';
								$m->addBCC('pchavez@cartimex.com');
								$m->FromName = 'Cartimex S.A.';
								$m->addAddress($direccionmail);
								$m->addAddress($direccionmail1);
								$m->addAddress($direccionmail2);
								$m->addAddress($direccionmail3);
								$m->isHTML(true);
								$m->Subject = "CARTIMEX Factura enviada para entregar";
								$m->Body = $msg;
								var_dump( $m->send() );

				}
			header("location: enviarfacturas.php");

		}
		else
		{
			header("location: index.html");
		}

?>
</body>

</html>
