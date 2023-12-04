<?php



//include("barramenu.html");
session_start();
	//Variables de sesion de SGL
	$usuario = $_SESSION['usuario'];
	$base = $_SESSION['base'];
	$acceso	=$_SESSION['acceso'];
	$secu = TRIM($_POST["secu"]);
	$bodega = $_SESSION['bodega'];
	$nomsuc = $_SESSION['nomsuc'];
	$_SESSION['usuario']= $usuario ;
	$bodegaFAC=$_SESSION['bodegaFAC'];
	//echo "inicio variables SGL ". $usuario.$base.$acceso.$bodega.$nomsuc;
	$usuariof= $usuario;


if (!isset($_SESSION["usuario"])) {
    header("Location: index1.php");
}

$usuario = $_SESSION["usuario"];

date_default_timezone_set('America/Guayaquil');
$fecha = date("Y-m-d", time());
$hora = date("H:i:s", time());
$fh = $fecha . " " . $hora;

//echo "fecha".$fh;
$radio = $_POST['radio'];
$bultos = $_POST['bultos'];
$lockerid = $_POST['lockerid'];
$localid = $_POST['localid'];
$local = $_POST['local'];
$despacho = $_POST['despacho'];//numero de guia
$numfac = $_POST['numfac'];//secuencia de la factura
$sec = $_POST['sec'];
$medio = $_POST['medio'];//urbano, servi, tramaco, entrega en tienda, casille, etc
$provincia = $_POST['provincia'];
$newbodretiro = $_POST['nbodega'];
$comentariodesp = $_POST['comentariodesp'];


//echo "datos de guia q llegan de formulario anterior ". $radio.$bultos.$lockerid.$localid.$local.$despacho.$numfac.$sec.$medio.$provincia.$newbodretiro.$comentariodesp;


/***************************************Actualiza en SISCO el estado de PREPARADO*************/


	require('conexion.php');
	date_default_timezone_set('America/Guayaquil');
	$fecha = date("Y-m-d", time());
	$hora = date("H:i:s", time());
	$fh = $fecha . " " . $hora;




	$sql = "UPDATE `covidsales` SET  `preppor`='$usuario' , `preparada`='$fh' where factura = '$numfac' ";
	if (mysqli_query($con, $sql)) {
	 //echo "Record updated successfully";
	} else {
     echo "Error updating record: " . mysqli_error($conn);
		}
    mysqli_close($con);


// despacho = informacion del cuadro de guia
// medio = metodo de despacho, urbano, en tienda, casillero, ...
if ($radio == '' and $medio == 'Casillero') {
    echo "<h2>Error... Si selecciona CASILLEROS debe seleccionar un número de casillero!</h2>";
}

// primero grabo la reserva en los casilleros
include("conexioncas.php");

$hash = mt_rand(10000000, 99999999);
$hash5 = md5($hash);



$sqlcasilleros = "UPDATE `lockers` SET `reserva`='$numfac',`hash` = '$hash5',`fechareserva` = '$fh'  where
`lockerid` = '$lockerid' and `localid` = '$localid' and `posicion` ='$radio[0]' ";
$resultocup = mysqli_query($concom, $sqlcasilleros);



include("conexion.php");
// Primero leo la tabla para sacar nombre y mail lo busco por el numero de la factura
$sql1 = "SELECT a.*, p.bodega as bodegaret FROM `covidsales` a
left outer join covidpickup p on p.orden = a.secuencia where a.factura = '$numfac' ";



$array = array();
$result1 = mysqli_query($con, $sql1);

$row1 = mysqli_fetch_array($result1);
$nombre = $row1['nombres'];
$mail = $row1['mail'];
$factura = $row1['factura'];
$celular = $row1['celular'];
$estado = $row1['estado'];
$pickup = $row1['pickup'];// Traigo para ver si antes era envio y ahora es pick up
$transaccion = $row1['secuencia'];//numero de orden el sisco
$edespacho = 'Por despachar'; // estado default despacho
$bodegaret = $row1['bodegaret'];



$sqllogistica = "
		insert into `covidlogistica` (`comentario`, `usuario`, `fecha`, `transaccion`)
		values ('$comentariodesp', '$usuariof', '$fh' , '$transaccion') " ;
		mysqli_query($con, $sqllogistica);



if ($medio == 'Entrega en tienda') {
    $edespacho = 'Entrega en ' . $bodegaret;
}
if ($medio == 'Casillero') {
    $edespacho = 'Casillero';
}

//Esto es para cambiar cuando era envio  y ahora es pickup

if (($medio == 'Entrega en tienda') and ($pickup==0))
	{
		//echo "Esto es lo que voy a insertar en covidpickup ". $transaccion. $provincia . $newbodretiro ;
		$edespacho = 'Entrega en ' . $newbodretiro;
		$pickup = 1;
		$sqlpick = "insert into `covidpickup` (`orden`, `provincia`, `bodega`)  values ('$transaccion', '$provincia', '$newbodretiro' ) " ;
		mysqli_query($con, $sqlpick);
	}



// si es casillero pongo en  desapcho los datos casillero
if ($radio <> '' and $medio == 'Casillero') {
    $despacho = $local . " Cas:. " . $radio[0];
}



/// ahora actualizo la table
$sql = "
UPDATE `covidsales` SET `bultos`='$bultos', `despacho`='$despacho' , `fechadesp`='$fh', `despachador`='$usuariof'  ,
`fechafinal`='$fecha', `usuariofinal`='$usuariof' , `estado`='$edespacho'  , `despachofinal`='$medio' , `pickup`='$pickup'
where factura = '$numfac' ";
mysqli_query($con, $sql);


mysqli_close($con);




/***************************************Actualiza tabla de Facturaslistas en COMPUTRONSA*************/
  //hasta aqui la variable usuario no lleva nada
	require("conexion_mssql.php");
	//echo "UsuarioF ". $usuariof."Usuario".$usuario. $base.$acceso.$bodega.$nomsuc, $comentariodesp;
	//echo $bodegaFAC;

	$tipo = 'VEN-FA';
	$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
	$result5 = $pdo5->prepare('Log_facturaslistas_despacho_update  @guia =:despacho ,@bultos=:bultos,  @transporte=:medio,
							   @entregado= :usuario ,@numfac=:numfac, @tipo=:tipo, @comentariod=:comentariod , @bodegaFAC=:bodegaFAC');
	$result5->bindParam(':despacho',$despacho,PDO::PARAM_STR);
	$result5->bindParam(':bultos',$bultos,PDO::PARAM_STR);
	$result5->bindParam(':medio',$medio,PDO::PARAM_STR);
	$result5->bindParam(':usuario',$usuariof,PDO::PARAM_STR);
	$result5->bindParam(':numfac',$numfac,PDO::PARAM_STR); // ENVIO SECUENCIA DE FACTURA
	$result5->bindParam(':tipo',$tipo,PDO::PARAM_STR);
	$result5->bindParam(':comentariod',$comentariodesp,PDO::PARAM_STR);
	$result5->bindParam(':bodegaFAC',$bodegaFAC,PDO::PARAM_STR);
	$result5->execute();



//Para enviar el correo dependiendo de lo q haya seleccionado como forma de Despacho en la Guia

if ($medio == 'Casillero') {
		echo "<h2>Use este código para abrir el casillero cuando vaya a cargar la orden:</h2>";
		echo "<img src=\"https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl=" . $hash5 . "&choe=UTF-8\" title=\"Computron\" /><br>";
		echo "Factura: " . $numfac . "<br>";
		echo "Casillero: " . $radio[0] . " del COMPUTRON " . $local . "<br>";
		echo "<b><u>Nota:</u></b> Este código es válido para 1 solo uso.<br>";
		echo "<br>Imprima este documento y tengalo junto con la mercadería <br>";
		echo "Necesitará este código para poder abrir el casillero <br>";
		}
if ( $bodegaFAC =='0500000003' or $bodegaFAC =='0500000005' or $bodegaFAC =='0500000006' or $bodegaFAC =='0500000007' )//$bodegaFAC =='0500000002' oror $bodegaFAC =='0500000008') //Si la bodega de facturacion es MABE,ECOLINE, BOYACA,LYCAN o CONSIGNACION CARTIMEX debe enviar correo al Proveedor or $bodegaFAC =='0500000004'
	{
		header("Refresh: 0 ; mailproveedor.php");
	}
else
	{
		if (($medio =='Urbano') or ($medio =='Tramaco') or ($medio =='Servientrega') or ($medio =='Vehiculo Computron'))
				{
					//echo "Entra aqui ";
					header("Refresh: 0 ; mailcourier.php");
				}
			if ($medio =='Entrega en tienda')
				{
					header("location: mailretiroenotratienda.php");
				}
	}

//Variables de sesion de SGL
$_SESSION['usuario']=$usuariof;
$_SESSION['base']= $base ;
$_SESSION['acceso']=$acceso;
$_SESSION['bodega']=$bodega;
$_SESSION['nomsuc']=$nomsuc;
$_SESSION['numfac']=$numfac;//SECUENCIA DE FACTURA
$_SESSION['bodegaFAC']=$bodegaFAC;//BODEGA DE FACTURACION
