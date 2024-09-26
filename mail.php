

// Este programa es invocado desde www.cartimex.com/series/preparada.asp
// luego de ingresar las series y  oprimir el boton de completar, se invoca esto mediante:
//   <% response.redirect "http://10.5.1.233/series/mail.php?factura="&factura%>
// aqui se recibe el nuemro de factura y se hace el proceso de obtencion de las series.
// finalmente vuelvo a llamar al programa original de seriues mediante:
// header('Location: http://www.cartimex.com/series/ingresa.asp?factura='.$factura);



<html>
<head>


<style>
table {
    border-collapse: collapse;
    width: 90%;
}

th, td {
/* 
    text-align: left;
 */
    padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #00994d;
    color: white;
}


.loader {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('page-loader.gif') 50% 50% no-repeat rgb(249,249,249);
}
</style>
</head>

<body>

<?php
session_cache_limiter('private, must-revalidate');
session_cache_expire(60);
$link = mssql_connect('10.5.1.3:1433', 'jairo', 'qwertys3gur0');



// select * from ven_facturas as a with(nolock), cli_clientes as b with(nolock) where a.id='0000683301' and a.clienteid = b.id
// Saco:
// Nombre, Fact_Preimpresa, SRI_SND, SRI_EM1, FechaCompra
// 
// select a.id as id, a.cantidad as cantidad, b.código as código, b.nombre as nombre, b.registroseries as registroseries 
// from ven_facturas_dt as a with(nolock), inv_productos as b with(nolock) where a.facturaid ='0000683301' and a.productoid=b.id
// Saco:
// id, cantidad, código, nombre 


$factura = $_GET['factura']; 
// echo "Factura: ".$factura."<br>";

date_default_timezone_set('America/Guayaquil');
$fechahoy = date("Y-m-d", time());
// echo "Fecha: ".$fechahoy."<br>";

$sql = "select * from ven_facturas as a with(nolock), cli_clientes as b with(nolock) where a.id='".$factura."' and a.clienteid = b.id ";

//$sql = "select * from cli_clientes where ID ='0000000012' ";
$result = mssql_query(utf8_decode($sql));
//$row = mssql_fetch_array($result);



while ($row = mssql_fetch_array($result)) {
//echo $row['Detalle'];

$direccionmail = $row['SRI_EM1'];

$msg =  "<table border=1 cellpadding=5 cellspacing=1 width=600>";
$msg = $msg . "<th align=left  width=450> ";
$msg = $msg .  "    <strong>Registro de series de CARTIMEX  </strong><br><br>";
$msg = $msg .  "    <strong>Factura:  </strong>  " .$row['Secuencia'];
$msg = $msg .  "    <strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspID:  </strong>  " .$factura ."<br>";
$msg = $msg .  "    <strong>Cliente:  </strong>  " .$row['Nombre'];

$msg = $msg .  "   </table>";
 

}

// con SQL1 obtengo los items y cantidades de las facturas
$sql1 = "select a.id as id, a.cantidad as cantidad, b.código as código, b.nombre as nombre, " ;
$sql1 = $sql1 . "b.registroseries as registroseries   " ;
$sql1 = $sql1 . "from ven_facturas_dt as a with(nolock), inv_productos as b with(nolock) " ;
$sql1 = $sql1 . "where a.facturaid ='".$factura."' " ;
$sql1 = $sql1 . "and a.productoid=b.id ";

$msg = $msg .   "<table border=1 cellpadding=5 cellspacing=1 width=600>";

$msg = $msg .  "<th align=left  width=100>Cód.</th> ";
$msg = $msg .  "<th align=left  width=500>Articulo</th> ";
$msg = $msg .  "<th align=right width=45>Cant</th>"; 

$result1 = mssql_query(utf8_decode($sql1));

	while ($row1 = mssql_fetch_array($result1)) {
	$id = $row1['id'];
	$cantidad = $row1['cantidad'];
	$codigo = $row1['código'];
	$nombre = $row1['nombre'];

	$msg = $msg .  "<tr bgcolor=\"dddddd\"><td align=left  width=100>".$id."</td> ";
	$msg = $msg .  "<td align=left  width=500>".$nombre."</td> ";
	$msg = $msg .  "<td align=right width=45>".$cantidad."</td></tr>"; 
	

	// con sql2 obtengo cserie que supongo es la cantidad de items con serie
	$sql2 = "select count(factura) as cserie from series with(nolock) " ;
	$sql2 = $sql2 . "where factura ='".$factura."' " ;
	$sql2 = $sql2 . "and secuencia ='".$id."' " ;
	$sql2 = $sql2 . "group by factura " ;
	
	// con sql3 obtengo las series del item
	$sql3 = "select SERIE as Nserie from series " ;
	$sql3 = $sql3 . "where factura ='".$factura."' " ;
	$sql3 = $sql3 . "and secuencia ='".$id."' " ;
	$result3 = mssql_query(utf8_decode($sql3));

	$msg = $msg .  "<td>Series:</td><td colspan =2 >";
		while ($row3 = mssql_fetch_array($result3)) {
	    $msg = $msg .  $row3['Nserie']." - ";
		}
	$msg = $msg .  "</td>";
 



}

$msg = $msg .  "</table>";
$msg = $msg .  "Este correo fue generado de forma automática y no requiere respuesta..<br>";
$msg = $msg .  "
<strong>Nota de Descargo: </strong>La información contenida en este mensaje y sus anexos tiene carácter confidencial, y está dirigida únicamente al destinatario de la misma y sólo podrá ser usada por éste. <br>
Si usted ha recibido este mensaje por error, por favor borre el mensaje de su sistema. 
";

echo $msg;


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
									$m->FromName = 'Cartimex';
									//$destinatario = "fmortola@cartimex.com";
									$m->addAddress($direccionmail);
									$m->isHTML(true);
									// $m->addAttachment('directorio/nombrearchivo.jpg','nombrearchivo.jpg')
									$m->Subject = "CARTIMEX Números de serie de  su compra";
									$m->Body = $msg;
									var_dump( $m->send() );

// fin de email de tarea

header('Location: http://www.cartimex.com/series/ingresa.asp?factura='.$factura);


?>

</body>
</p>
</html>