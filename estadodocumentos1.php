<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<body>

<?php
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		  $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$bodega = $_SESSION['bodega'];
			$nomsuc =$_SESSION['nomsuc'];
      $StatusRecepcion = $_POST['StatusRecepcion'];
			$StatusRecdcto = $_POST['StatusRecdcto'];
			$Nota = $_POST['nota'];
			$transaccion= $_POST['transaccion'];
			date_default_timezone_set('America/Guayaquil');
				$year = date("Y");
			$fecha = date("yy-m-d", time());
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;
			include("conexion.php");
			//	echo   $StatusRecepcion.$StatusRecdcto.$Nota. $transaccion;
			//die();
				 //echo "Esto voy a actualizar en sisco".$usuario. $fh.$ordensisco;
			$sql2 = "UPDATE `covidcredito` SET   `RecibidoPor`='$usuario', `RecibidoFecha`='$fh', `StatusRecepcion`='$StatusRecepcion', `StatusRecdcto`='$StatusRecdcto',
			`Nota`='$Nota' where transaccion = '$transaccion' " ;
			mysqli_query($con, $sql2);



				$_SESSION['base']= $base;
				$_SESSION['usuario']=$usuario;
				$_SESSION['acceso']=$acceso;

				header("location: creditosdirectos.php");
		}

?>

</body>

</html>
