<html>
<body>

<?php


session_start();

$usuario = $_SESSION['usuario'];
$base = $_SESSION['base'];
$acceso	=$_SESSION['acceso'];
$IDLiq= $_SESSION['IDLiq'];// Recibe el id  de la factura
$usuario1=$usuario;

require('../../conexion_mssqlxtratech.php'); //Reemplaza las 4 lineas de abajo
//echo "esto viene". $IDLiq;

$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
$result = $pdo->prepare("select l.id, Usuario= l.RecibidoPor, Fecha= CONVERT( char(10), l.FFinRecep, 103),
						l.Detalle , rl.Contenedor, rl.Tipo, rl.Sello, rl.Sello2,rl.Placa , rl.Estiba,
						l.FInicioRecep, l.FFinRecep,rl.Nota, rl.Nota1, rl.Foto1, rl.Foto2, rl.Foto3
						from IMP_LIQUIDACION  L
						inner join IMP_RECEPCION_LIQ_CONTENEDOR rl on rl.LiquidacionID= l.ID

						where l.id=:IDLiq " );
$result->bindParam(':IDLiq',$IDLiq,PDO::PARAM_STR);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC))
	{
		$RecibidoPor = $row['Usuario'];
		$Fecha = $row['Fecha'];
		$Detalle=  $row['Detalle'];
		$Contenedor=  $row['Contenedor'];
		$Tipo=  $row['Tipo'];
		$Sello=  $row['Sello'];
		$Sello2= $row['Sello2'];
		$Placa=  $row['Placa'];
		$Estiba=  $row['Estiba'];
		$Nota =  $row['Nota'];
		$Nota1 =  $row['Nota1'];
		$Foto1 =  $row['Foto1'];
		$Foto2 =  $row['Foto2'];
		$Foto3 =  $row['Foto3'];

		$FInicioRecep =date("H:i:s", strtotime($row['FInicioRecep']));
		$FFinRecep =date("H:i:s", strtotime($row['FFinRecep']));
		$Inicio = new DateTime($row['FInicioRecep']);
		$Fin = new DateTime($row['FFinRecep']);
		$diferencia = $Inicio->diff($Fin);
		$dif = date("H:i:s", strtotime($diferencia->format('%H:%i:%s')));
		$msg = " <img src='http://10.5.2.62/logistica/assets/img/xtratechlogo.png' width='300' height='100'> <br><br>";
		$msg = $msg . "<strong> INFORME DE NOVEDADES DE RECEPCION DE MERCADERIA </strong><br><br>";
		$msg = $msg . "<strong>Responsable: &nbsp; &nbsp; </strong> ". $RecibidoPor . "<br>";
		$msg = $msg.  "<strong>Fecha: </strong>". $Fecha . " <br> ";
		$msg = $msg.  "<strong>LiquidacionId: </strong>". $IDLiq . " <br><br>";
		$msg = $msg.  "<strong>Detalle : </strong>". $Detalle . " <br><br>";
		$msg = $msg.  " Informo a Ud que con fecha &nbsp;<strong>". $Fecha . " </strong>&nbsp; hemos recibido el/los siguiente/s contenedor/es: <br><br>";
		$msg = $msg . "<table border=1 cellpadding=2 cellspacing=1 width=700>";
		$msg = $msg . "<th align=center  width=100> <strong>Contenedor</strong></th>";
		$msg = $msg . "<th align=center  width=100> <strong>Sello </strong></th>";
		$msg = $msg . "<th align=center  width=100> <strong>Sello2 </strong></th>";
		$msg = $msg . "<th align=center  width=100> <strong>Tipo </strong></th>";
		$msg = $msg . "<th align=center  width=100> <strong>Placa </strong></th>";
		$msg = $msg . "<tr><td align=center  width=100>  ". $Contenedor ." </td>";
		$msg = $msg . "<td align=center  width=100> ". $Sello ."</td>";
		$msg = $msg . "<td align=center  width=100> ". $Sello2 ."</td>";
		$msg = $msg . "<td align=center  width=100> ". $Tipo ." </td>";
		$msg = $msg . "<td align=center  width=100> ". $Placa ." </td></tr>";
		$msg = $msg . "<tr><td align=center colspan= 2 width=100> <strong>Tipo de Estiba en el Interior:</strong></td> ";
		$msg = $msg . "<td align=center colspan= 3 width=100> ". $Estiba ." </td></tr> </table><br>";
		$msg = $msg . "<table border=1 cellpadding=2 cellspacing=1 width=700>";
		$msg = $msg . "<tr><td align=left><strong>Inicio</strong></td> ";
		$msg = $msg . "<td align=left><strong>Fin</strong></td>";
		$msg = $msg . "<td align=left><strong>Tiempo</strong></td></tr>";
		$msg = $msg . "<tr><td align=left   >". $FInicioRecep ." </td>";
		$msg = $msg . "<td align=left>". $FFinRecep ." </td> ";
		$msg = $msg . "<td align=left>". $dif  ." </td></tr>";
		$msg = $msg . "</table><br>";


		$msg = $msg . "<table border=1 cellpadding=5 cellspacing=1 width=700>";
		$msg = $msg . "<th align=left  width=450> ";
		$msg = $msg . " 	<strong>Novedades   </strong><br> </th>";
		$msg = $msg . "   </table>";

		// con SQL1 obtengo los items y cantidades de las facturas
		$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$result1 = $pdo1->prepare('select p.Código, p.Nombre, Facturado = sum(dt.facturado),  dt.CantRecibida, diferencia= dt.CantRecibida-sum(dt.facturado)
								from IMP_LIQUIDACION_DT dt
								inner join INV_PRODUCTOS p on p.id = dt.ProductoID
								where LiquidaciónID=:IDLiq
								group by  p.Código, p.Nombre,  dt.CantRecibida
								having sum(dt.facturado)-dt.CantRecibida <> 0');
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
								$m->FromName = 'Dpto. Logistica Xtratech Computers S.A. ';
								$m->addAddress('rluna@cartimex.com');
								$m->addAddress('lvera@cartimex.com');
								$m->addAddress('mmontero@cartimex.com');
								$m->addAddress('vfranco@cartimex.com');
								$m->addAddress('xrichards@cartimex.com');
								$m->addAddress('jmacias@cartimex.com');
								$m->addAddress('mvalle@cartimex.com');
								$m->addAddress('importaciones@cartimex.com');
								$m->isHTML(true);
								$m->AddAttachment($Foto1);
								$m->AddAttachment($Foto2);
								$m->AddAttachment($Foto3);
								$m->Subject = "Informe de Recepcion de Mercaderia  ";
								$m->Body = $msg;
								var_dump( $m->send() );

	}
//echo $msg;


$_SESSION['usuario']=$usuario1;
$_SESSION['base']= $base ;
$_SESSION['acceso']=$acceso;
$_SESSION['bodega']=$bodega;

header("Refresh: 0 ; recepcionimportaciones.php");


?>

</body>
</p>
</html>
