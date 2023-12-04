<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body> 

<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','On');
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$bodega= $_SESSION['bodega'];
			$acceso	=$_SESSION['acceso'];
			$_SESSION['usuario']= $usuario; 
			$_SESSION['bodega']= $bodega; 
			$secu= $_POST ['secu']; 
			require('conexion.php');
			date_default_timezone_set('America/Guayaquil');
			$fecha = date("Y-m-d", time());
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;
			
			$sql = "UPDATE `covidsales` SET  `estado`='Facturado'  where factura = '$secu' ";
			if (mysqli_query($con, $sql)) {
			 //echo "Record updated successfully";
			} else {
			 echo "Error updating record: " . mysqli_error($conn);
				}
			mysqli_close($con);
			
			header("location: modificarmultibodegas.php");
			//echo $base;
			$_SESSION['base']= $base;
			$_SESSION['usuario']= $usuario;
			$_SESSION['bodega']= $bodega;
			$_SESSION['nomsuc']=$nomsuc; 
		}
	else
		{
			header("location: index.html");
		}
 ?>
</body>