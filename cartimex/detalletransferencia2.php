<?php



//include("barramenu.html");
session_start();
	//Variables de sesion de SGL
	$usuario = $_SESSION['usuario'];
	$base = $_SESSION['base'];
	$acceso	=$_SESSION['acceso'];
	$transfer = TRIM($_POST["transfer"]);
	$bodega = $_SESSION['bodega'];
	$nomsuc = $_SESSION['nomsuc'];
	$_SESSION['usuario']= $usuario ;
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

 
 if ($_POST['bultos']=='') {$peso ='0' ;} else {$peso=$_POST['bultos'];} 
$guia = $_POST['guia'];//numero de guia 
$numfac = $_POST['numfac'];//secuencia de la factura 
$medio = $_POST['medio'];//transporte 
$comentariodesp = $_POST['comentariodesp'];
  if ($_POST['peso']=='') {$peso ='0'; } else {$peso=$_POST['peso'];} 



//echo "datos de guia q llegan de formulario anterior ".$usuario.$bultos. $guia. $numfac .$transfer. $medio. $comentariodesp;

/***************************************Actualiza tabla de Facturaslistas en COMPUTRONSA*************/

	require("../conexion_mssql.php");
	echo "fin ". $usuariof.$base.$acceso.$bodega.$nomsuc. $bultos. $peso. $comentariodesp;
	//die();  
	$tipo = 'INV-TR';
	$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
	$result5 = $pdo5->prepare('Log_facturaslistas_despacho_update  @guia =:despacho , @bultos=:bultos,  @transporte=:medio, 
							   @entregado= :usuario ,@numfac=:transfer, @tipo=:tipo, @comentariod=:comentariod, @peso=:peso');			
	$result5->bindParam(':despacho',$guia,PDO::PARAM_STR);
	$result5->bindParam(':bultos',$bultos,PDO::PARAM_STR);
	$result5->bindParam(':medio',$medio,PDO::PARAM_STR);
	$result5->bindParam(':usuario',$usuariof,PDO::PARAM_STR);
	$result5->bindParam(':transfer',$transfer,PDO::PARAM_STR);
	$result5->bindParam(':tipo',$tipo,PDO::PARAM_STR);
	$result5->bindParam(':comentariod',$comentariodesp,PDO::PARAM_STR);
	$result5->bindParam(':peso',$peso,PDO::PARAM_STR);
	
	$result5->execute();


	header("Refresh: 1 ; ingguiastransferencias.php");

//Para enviar el correo dependiendo de lo q haya seleccionado como forma de Despacho en la Guia 

/*if (($medio =='Urbano') or ($medio =='Tramaco') or ($medio =='Servientrega') or ($medio =='Vehiculo Computron'))
	{	
		//echo "Entra aqui ";
		header("Refresh: 0 ; mailcourier.php");
	}*/


//Variables de sesion de SGL 
	$_SESSION['usuario']=$usuariof;
	$_SESSION['base']= $base ;
	$_SESSION['acceso']=$acceso;
	$_SESSION['bodega']=$bodega;
	$_SESSION['nomsuc']=$nomsuc;
	$_SESSION['numfac']=$numfac;	
	

