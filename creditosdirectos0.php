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
			$checkbox = $_POST['checkbox'] ;

			$fin = count($checkbox);

			if ($base=='CARTIMEX'){
					require 'headcarti.php';
			}
			else{
					require 'headcompu.php';
			}
			include("conexion.php");
			date_default_timezone_set('America/Guayaquil');
		    $year = date("Y");
			$fecha = date("yy-m-d", time());
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;

			$i=0;
			for ($i = 0; $i <= $fin-1; $i++)
			{
						$numfac= $checkbox[$i] ;
						$sql = "select * from covidsales where factura= '$numfac'";
						$result = mysqli_query($con, $sql);

						while ($row = mysqli_fetch_array($result))
							{
								$ordensisco= $row['secuencia'];
							}
						 //echo "Esto voy a actualizar en sisco".$usuario. $fh.$ordensisco;
						$sql2 = "UPDATE `covidcredito` SET   `RecibidoPor`='$usuario', `RecibidoFecha`='$fh', `StatusRecepcion`='RECIBIDO', `StatusRecdcto`='OK' where transaccion = '$ordensisco' " ;
						//echo $sql2;
						mysqli_query($con, $sql2);
			}


				$_SESSION['base']= $base;
				$_SESSION['usuario']=$usuario;
				$_SESSION['acceso']=$acceso;

				header("location: creditosdirectos.php");
		}

?>

</body>

</html>
