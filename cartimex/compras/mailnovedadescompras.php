<html>
<body>

<?php


session_start();

$usuario = $_SESSION['usuario'];
$base = $_SESSION['base'];
$acceso	=$_SESSION['acceso'];
$idorden= $_SESSION['idorden'];
$usuario1=$usuario;

require('../../conexion_mssql.php'); //Reemplaza las 4 lineas de abajo
//echo "esto viene". $IDLiq;

$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
$result = $pdo->prepare("select C.id, Usuario= u.nombre, Fecha= CONVERT( char(10), C.FRecibida, 103),
						C.Detalle , rl.Contenedor, rl.Tipo, rl.Sello, rl.Sello2,rl.Placa , rl.Estiba,
						C.FInicioR , C.FRecibida,rl.Nota, rl.Nota1,CFactura= cf.Id, e.Nombre, e.email
						from COM_ORDENES C
						left outer JOIN COM_FACTURAS CF on cf.OrdenID=C.ID
						inner join IMP_RECEPCION_LIQ_CONTENEDOR rl on rl.LiquidacionID= C.ID
						inner join SERIESUSR u on u.usuario = C.RecibidoPor
						inner join SEG_USUARIOS su on su.Código = c.CreadoPor
						inner join EMP_EMPLEADOS e on e.id= su.EmpleadoID
						where C.ID=:idorden " );
$result->bindParam(':idorden',$idorden,PDO::PARAM_STR);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC))
	{
		$RecibidoPor = $row['Usuario'];
		$Fecha = $row['Fecha'];
		$Detalle=  $row['Detalle'];
		$Placa=  $row['Placa'];
		$Nota =  $row['Nota'];
		$Nota1 =  $row['Nota1'];
		$CFactura =  $row['CFactura'];
		$Gerente =  $row['Nombre'];
		$email =  $row['email'];
		$FInicioRecep =date("H:i:s", strtotime($row['FInicioR']));
		$FFinRecep =date("H:i:s", strtotime($row['FRecibida']));
		$Inicio = new DateTime($row['FInicioR']);
		$Fin = new DateTime($row['FRecibida']);
		$diferencia = $Inicio->diff($Fin);
		$dif = date("H:i:s", strtotime($diferencia->format('%H:%i:%s')));
		$msg = " <img src='https://www.cartimex.com/assets/img/logo200.png' width='300' height='100'> <br><br>";
		$msg = $msg . "<strong> INFORME DE NOVEDADES DE RECEPCION DE MERCADERIA </strong><br><br>";
		$msg = $msg . "<strong>Responsable: &nbsp; &nbsp; </strong> ". $RecibidoPor . "<br>";
		$msg = $msg . "<strong>Para: &nbsp; &nbsp; </strong> ". $Gerente . "<br>";
		$msg = $msg.  "<strong>Fecha: </strong>". $Fecha . " <br> ";
		$msg = $msg.  "<strong>Orden Compra: </strong>". $idorden . "<br>";
		$msg = $msg.  "<strong>Factura Compra: </strong>". $CFactura . " <br><br>";
		$msg = $msg.  "<strong>Detalle : </strong>". $Detalle . " <br><br>";
		$msg = $msg.  " Informo a Ud que con fecha &nbsp;<strong>". $Fecha . " </strong>&nbsp; hemos recibido la Orden de Compra: <br><br>";
		$msg = $msg . "<table border=1 cellpadding=2 cellspacing=1 width=700>";
		$msg = $msg . "<th align=center  width=100> <strong>Placa </strong></th>";
		$msg = $msg . "<td align=left><strong>Inicio</strong></td> ";
		$msg = $msg . "<td align=left><strong>Fin</strong></td>";
		$msg = $msg . "<td align=left><strong>Tiempo</strong></td></tr>";
		$msg = $msg . "<tr><td align=center  width=100> ". $Placa ." </td>";
		$msg = $msg . "<td align=left   >". $FInicioRecep ." </td>";
		$msg = $msg . "<td align=left>". $FFinRecep ." </td> ";
		$msg = $msg . "<td align=left>". $dif  ." </td></tr>";
		$msg = $msg . "</table><br>";
//Detalle de Compra
    $msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=700>";
    $msg = $msg . "<th align=left  width=450> ";
    $msg = $msg . " 	<strong>Detalle de Productos </strong><br> </th>";
    $msg = $msg . "   </table>";
    // con SQL1 obtengo los items y cantidades de las facturas
		$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$result1 = $pdo1->prepare('select p.Código, p.Nombre, Facturado = sum(dt.Cantidad),  dt.CantRecibida, diferencia= dt.CantRecibida-sum(dt.Cantidad)
													from COM_ORDENES_DT dt
													inner join INV_PRODUCTOS p on p.id = dt.ProductoID
													where OrdenID=:IDLiq
													group by  p.Código, p.Nombre,  dt.CantRecibida
													order by 1');
		$result1->bindParam(':IDLiq',$orden_clean,PDO::PARAM_STR);
		$result1->execute();
		$arreglo = array();
		$x=0;
		while ($row1 = $result1->fetch(PDO::FETCH_ASSOC))
			{
				$arreglo[$x][1]=$row1['Código'];
				$arreglo[$x][2]=$row1['Nombre'];
				$arreglo[$x][3]=number_format($row1['Facturado'],0);
				$arreglo[$x][4]=$row1['CantRecibida'];
				$arreglo[$x][5]=number_format($row1['diferencia'],0);
				$x++;
			}
		$count = count($arreglo);
		if ($count>0)
			{
				$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=700>";
				$msg = $msg . "<th align=left  width=150>Codigo</th> ";
				$msg = $msg . "<th align=left  width=500>Articulo</th> ";
				$msg = $msg . "<th align=left width=45>Facturado</th>";
				$msg = $msg . "<th align=left width=40>Recibida</th>";
				$msg = $msg . "<th align=left width=40>Diferencia</th>";
				$y=0;
				while ( $y <= $count-1 )
					{
						$msg = $msg .  "<tr><td align=left  width=100>".$arreglo[$y][1]."</td> ";
						$msg = $msg .  "<td align=left >".$arreglo[$y][2]."</td> ";
						$msg = $msg .  "<td align=center>".$arreglo[$y][3]."</td>";
						$msg = $msg .  "<td align=center>".$arreglo[$y][4]."</td>";
						$msg = $msg .  "<td align=center >".$arreglo[$y][5]."</td></tr>";
						$y=$y+1;
					}
			}
		$msg = $msg .  "</table><br>";
		$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=700>";
		$msg = $msg . "<th align=left  width=450> ";
		$msg = $msg . " 	<strong>Novedades   </strong><br> </th>";
		$msg = $msg . "   </table>";

		// con SQL1 obtengo los items y cantidades de las facturas
		$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$result1 = $pdo1->prepare('select p.Código, p.Nombre, Facturado = sum(dt.Cantidad),  dt.CantRecibida, diferencia= dt.CantRecibida-sum(dt.Cantidad)
								from COM_ORDENES_DT dt
								inner join INV_PRODUCTOS p on p.id = dt.ProductoID
								where OrdenID=:IDLiq
								group by  p.Código, p.Nombre,  dt.CantRecibida
								having sum(dt.Cantidad)-dt.CantRecibida <> 0');
		$result1->bindParam(':IDLiq',$IDLiq,PDO::PARAM_STR);
		$result1->execute();
		$arreglo = array();
		$x=0;
		while ($row1 = $result1->fetch(PDO::FETCH_ASSOC))
			{
				$arreglo[$x][1]=$row1['Código'];
				$arreglo[$x][2]=$row1['Nombre'];
				$arreglo[$x][3]=number_format($row1['Facturado'],0);
				$arreglo[$x][4]=$row1['CantRecibida'];
				$arreglo[$x][5]=number_format($row1['diferencia'],0);
				$x++;
			}
		$count = count($arreglo);
		if ($count==0)
			{
				$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=700>";
				$msg = $msg . "<tr align=left  width=450> ";
				$msg = $msg . "<strong>Sin Novedades</strong></tr>";
				$msg = $msg . "</table>";
			}
		else
			{
				$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=700>";
				$msg = $msg . "<th align=left  width=150>Codigo</th> ";
				$msg = $msg . "<th align=left  width=500>Articulo</th> ";
				$msg = $msg . "<th align=left width=45>Facturado</th>";
				$msg = $msg . "<th align=left width=40>Recibida</th>";
				$msg = $msg . "<th align=left width=40>Diferencia</th>";
				$y=0;
				while ( $y <= $count-1 )
					{

						$msg = $msg .  "<tr bgcolor='yellow'><td align=left  width=100>".$arreglo[$y][1]."</td> ";
						$msg = $msg .  "<td align=left >".$arreglo[$y][2]."</td> ";
						$msg = $msg .  "<td align=center>".$arreglo[$y][3]."</td>";
						$msg = $msg .  "<td align=center>".$arreglo[$y][4]."</td>";
						$msg = $msg .  "<td align=center >".$arreglo[$y][5]."</td></tr>";
						$y=$y+1;
					}
			}
				$msg = $msg .  "</table><br>";
				$msg = $msg . "<strong>Nota: &nbsp; &nbsp; </strong> ". $Nota . "<br>";
				$msg = $msg . "<strong>Comentarios: &nbsp; &nbsp; </strong> ". $Nota1. "<br>";
				$msg = $msg .  "<br>Este correo fue generado de forma automática y no requiere respuesta..<br><br>";

				// PARA CAMBIAR ALEATORIAMENTE EL ENVIO DEL CORREO
								$aleatorio = (rand(1,5));
									switch ($aleatorio)
										{
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
								require_once '../../vendor/autoload.php';
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
								$m->FromName = 'Dpto. Logistica Cartimex S.A.';
								$m->addAddress('lvera@cartimex.com');
								$m->addAddress('mmontero@cartimex.com');
								$m->addAddress('xrichards@cartimex.com');
								$m->addAddress('vfranco@cartimex.com');
								$m->addAddress($email);
								$m->isHTML(true);
								$m->Subject = "Informe de Recepcion de Mercaderia ";
								$m->Body = $msg;
								var_dump( $m->send() );

	}
//echo $msg;


$_SESSION['usuario']=$usuario1;
$_SESSION['base']= $base ;
$_SESSION['acceso']=$acceso;
$_SESSION['bodega']=$bodega;

header("Refresh: 0 ; recepcioncompras.php");


?>

</body>
</p>
</html>
