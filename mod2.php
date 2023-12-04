


<?php
session_start();
//Variables de sesion de SGL
	$usuario = $_SESSION['usuario'];
	$base = $_SESSION['base'];
	$acceso	=$_SESSION['acceso'];
	$secu = TRIM($_POST["secu"]);
	$bodegaf = $_SESSION['bodega'];
	$nomsuc = $_SESSION['nomsuc'];
	$_SESSION['usuario']= $usuario;
	$usuariof= $_SESSION['usuario']; 
	//echo "inicio ". $usuario.$base.$acceso.$bodegaf.$nomsuc;

//****************************************************************

if (!isset($_SESSION["usuario"])) {header("Location: index1.php");}

$usuario = $_SESSION["usuario"] ;
require("conexion.php");
date_default_timezone_set('America/Guayaquil');
$fecha = date("yy-m-d", time());
$hora = date("H:i:s", time());

$medio=$_POST['medio']; 
$numfac=$_POST['sec'];               
$sec=$_POST['sec'];
$datafinalorig=$_POST['datafinalorig'];
$datadesporig=$_POST['datadesporig'];
$despacho = $_POST['despacho'];	
$bultosorig=$_POST['bultosorig'];
$bultos = $_POST['bultos'];	

 

//Nuevos: echo "medio".$medio."<br>";
//Nuevos: echo "despacho".$despacho."<br>";
//Originales: echo "datadesporig".$datadesporig."<br>";
//Originales: echo "datafinalorig".$datafinalorig."<br>";

if($medio==$datafinalorig and $despacho==$datadesporig)
{// SIN CAMBIOS:
header("Location: despacharfacturas.php");
}
else

{// Con Cambios:
	
include("conexion.php");

// Primero leo la tabla para sacar nombre y mail
//echo $sec,$numfac; 
$sql1 = "SELECT * FROM covidsales where factura = '$numfac' ";
$result1 = mysqli_query($con, $sql1);
$row1 = mysqli_fetch_array($result1);
$nombre = $row1['nombres'];
$mail = $row1['mail'];
//$despacho = $row1['despacho'];
//$bultos = $row1['bultos'];


//Actualizo la informacion nueva en la DB
$sql = "
UPDATE `covidsales` SET  `fechafinal`='$fecha', `usuariofinal`='$usuario', `bultos`='$bultos' , `despachofinal`='$medio', `despacho`='$despacho'
where factura = '$numfac' " ;

mysqli_query($con, $sql); 

//actualizo la Db de los despachos cambiados
$sql2 = "insert into covidbultos (`guiaant`,`guiaact`,`bultosant`,`bultosact`,`usuario`,`fecha`,`medioant`,`medioact`, `id`) 
VALUES ('$datadesporig','$despacho','$datadesporig','$bultos','$usuario','$fecha','$datafinalorig','$medio','$sec')";
mysqli_query($con, $sql2); 
mysqli_close($con);


require("conexion_mssql.php");
//echo "fin1 ". $usuariof.$base.$acceso.$bodega.$nomsuc;

 	
$tipo = 'VEN-FA';

$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
$result5 = $pdo5->prepare('Log_facturaslistas_despacho_update  @guia =:despacho ,@bultos=:bultos,  @transporte=:medio, 
							   @entregado= :usuario ,@numfac=:numfac, @tipo=:tipo');			
$result5->bindParam(':despacho',$despacho,PDO::PARAM_STR);
$result5->bindParam(':bultos',$bultos,PDO::PARAM_STR);
$result5->bindParam(':medio',$medio,PDO::PARAM_STR);
$result5->bindParam(':usuario',$usuariof,PDO::PARAM_STR);
$result5->bindParam(':numfac',$numfac,PDO::PARAM_STR);
$result5->bindParam(':tipo',$tipo,PDO::PARAM_STR);
$result5->execute();

//echo "fin2". $usuariof.$base.$acceso.$bodegaf.$nomsuc;

//Variables de sesion de SGL 
$_SESSION['usuario']=$usuariof;
$_SESSION['base']= $base ;
$_SESSION['acceso']=$acceso;
$_SESSION['bodega']=$bodegaf;
$_SESSION['nomsuc']=$nomsuc;
header("Location: despacharfacturas.php");
//header("Location: pdf1.php?sec=".$sec);
}

die();

