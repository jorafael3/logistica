<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<body>
<div id= "header" align= "center">
<?PHP
	session_start();
	if (isset($_SESSION['loggedin']))
		{
			$bloqueado = $_SESSION['bloqueo'];
			$nota = $_SESSION['nota'];
			$usuario= $_SESSION['usuario'];
			$Id=$_SESSION['id'];
			$base = $_SESSION['base'] ;
			$acceso= $_SESSION['acceso'];
			$codigo= $_SESSION['codigo'];
			$nomsuc=$_SESSION['nomsuc'];
			$cliente=$_SESSION['cliente'];
			$numerorecibido= $_SESSION['factura'];
			$pedido= $_SESSION['tipotrans'];
			//echo $usuario;
			$usuario1= $usuario;
			date_default_timezone_set('America/Guayaquil');
			$fecha = date("Ymd", time());
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;

			//echo "VIENE".  $Id.	$numerorecibido. 	$usuario1.  "<br>";

			//$arreglo2 = $_SESSION['arregloseries'];

			if ($base=='CARTIMEX')
				{require '../headcarti.php';  	}
			else{
					require '../headcompu.php';
					$_SESSION['base']= $base;
					$Nota = " ";
				}
				require('../conexion_mssql.php');
				$detalle = "Factura de Venta Nro:".$numerorecibido." Cliente:".$cliente;
				$tipo = 'VEN-FA';
				$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result5 =$pdo5->prepare('LOG_VERIFICAR_FACTURA_UPDATE2  @verificado =:usuario, @factura=:facturaid, @tipo=:tipo ');
				$result5->bindParam(':facturaid',$Id,PDO::PARAM_STR);
				$result5->bindParam(':usuario',$usuario1,PDO::PARAM_STR);
				$result5->bindParam(':tipo',$tipo,PDO::PARAM_STR);
				$result5->execute();


				$pedido='DESPACHAR';
				//echo "string". $pedido;
				//die();
			?>
</div>

<div id="cuerpo2" align= "center">

					<p style="font-weight: bold" align="center" > <font size= "6" > Factura # <?php echo $numerorecibido ?>  completa! </font><br> </p>
</div>
			<?php
			if ($pedido=='DESPACHAR')
				{
					$_SESSION['cliente']=$cliente;
					$_SESSION['usuario']=$usuario1;
					$_SESSION['id']=$Id;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo;
					$_SESSION['nomsuc']=$nomsuc;
					$_SESSION['numfac']=$Id;


					header("Refresh: 0 ; maildespachoyseries.php");
				}
			else
				{
					//header("Refresh: 0 ; verificarfacturas.php");
				}
			//echo "<h1>Factura completa!</h1>";
			$_SESSION['cliente']=$cliente;
			$_SESSION['usuario']=$usuario1;
			$_SESSION['id']=$Id;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['codigo']=$codigo;
			$_SESSION['nomsuc']=$nomsuc;
			$_SESSION['numfac']=$Id;

		}
	else
		{
			header("location: index.html");
		}

?>
</div>
</body>
</html>
