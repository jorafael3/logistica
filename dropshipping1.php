<?php
session_start();
if (!isset($_SESSION["usuario"])) {
	header("Location: index1.php");
}

//Variables de sesion de SGL
$usuario = $_SESSION['usuario'];
$base = $_SESSION['base'];
$acceso	= $_SESSION['acceso'];
$bodega = $_SESSION['bodega'];
$nomsuc = $_SESSION['nomsuc'];
$_SESSION['usuario'] = $usuario;
$usuariof = $usuario;

//estas variables recibo del form anterior	
$direccion = $_POST['direccion'];
$referencia = $_POST['referencia'];
$telefono = $_POST['telefono'];
$Email = $_POST['Email'];
$numfac = $_POST['numfac'];
$bodegaf = $_POST['bodegaf'];
$tipoenvio = $_POST['medio'];
$sucursales = $_POST['sucursales'];
$SUCUSAL_ID = $_POST['Sucursal_ID'];

echo "Dirección: " . $direccion . "<br>";
echo "Referencia: " . $referencia . "<br>";
echo "Teléfono: " . $telefono . "<br>";
echo "Email: " . $Email . "<br>";
echo "Número de factura: " . $numfac . "<br>";
echo "Bodega: " . $bodegaf . "<br>";
echo "Tipo de envío: " . $tipoenvio . "<br>";
echo "sucursales: " . $sucursales . "<br>";
echo "SUCUSAL_ID: " . $SUCUSAL_ID . "<br>";

if ($tipoenvio == 1) {
	$sucursales = $SUCUSAL_ID;
}

if (!isset($_SESSION["usuario"])) {
	header("Location: index1.php");
}


require("conexion_mssql.php");

//echo "datos para guardar la direccin de drop shipping ". $direccion.$referencia.$telefono.$Email. $bodegaf . $numfac. $tipoenvio ."-----";
//echo "datos para guardar en facturaslistas ". $usuariof ;
//die(); 




$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
$result5 = $pdo5->prepare('Log_facturaslistas_dropshipping_insert @facturaid=:factura ,@bodegaid=:bodegaid, @direccion=:direccion, 
							@referencia=:referencia, @telefono=:telefono , @Email=:Email, 
							@usuario=:usuario ,@TPedido=:TPedido,
							@Tienda_retiro = :Tienda_retiro ');
$result5->bindParam(':factura', $numfac, PDO::PARAM_STR);
$result5->bindParam(':bodegaid', $bodegaf, PDO::PARAM_STR);
$result5->bindParam(':direccion', $direccion, PDO::PARAM_STR);
$result5->bindParam(':referencia', $referencia, PDO::PARAM_STR);
$result5->bindParam(':telefono', $telefono, PDO::PARAM_STR);
$result5->bindParam(':Email', $Email, PDO::PARAM_STR);
$result5->bindParam(':usuario', $usuariof, PDO::PARAM_STR);
$result5->bindParam(':TPedido', $tipoenvio, PDO::PARAM_STR);
$result5->bindParam(':Tienda_retiro', $sucursales, PDO::PARAM_STR);
if ($result5->execute()) {
} else {
	$err = $result5->errorInfo();
	var_dump($err);
}


//Variables de sesion de SGL 
$_SESSION['usuario'] = $usuariof;
$_SESSION['base'] = $base;
$_SESSION['acceso'] = $acceso;
$_SESSION['bodega'] = $bodega;
$_SESSION['nomsuc'] = $nomsuc;
$_SESSION['numfac'] = $numfac;

header("location: dropshipping.php");
