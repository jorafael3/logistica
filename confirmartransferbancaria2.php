<html>
<body>
<body oncontextmenu="return false">

<?php 
	session_start();		
	if (isset($_SESSION['loggedin']))
		{
		    date_default_timezone_set('America/Guayaquil');
			$fecha = date("Y-m-d", time());
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;
			
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
						
			if ($base=='CARTIMEX'){
					require 'headcarti.php';  
			}
			else{
					require 'headcompu.php';
			}
			
		    $orden= $_POST["orden"];
			$ref= $_POST["ref"];
			$banco= $_POST["banco"];
			$desde= $_POST["desde"];
			$ref = "CONFIRMADO-".$ref; 
			
			echo "1Usuario:".$usuario. $base. $acceso. "*****-". $orden. "---".$ref . "---". $banco. "---".$desde."------".$fh; 
			//die(); 
			
			/****** Actualiza los datos de confirmacion de la transferencia *****/ 
			$usuario1= $usuario; 
			include("conexion.php");
			$sql1 = "update covidtransferencias 
					set ConfirmadoPor = '$usuario1', fechaconfir= '$fh', numeroconfir= '$ref' ,fechaacredita= '$desde', BancoAcr= '$banco' 
					where transaccion= '$orden'";
			$result1 = mysqli_query($con, $sql1); 
			
			
			/****** Actualiza los datos de estado y fecha en covidsales*****/ 
			$sql = "UPDATE `covidsales` SET `apruebapayz` = '$usuario1', `paymentez` = '$fh', `estado` = 'Facturado' where secuencia = $orden" ;
			mysqli_query($con, $sql); 
			
			$usuario= $usuario1;
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			
			header("location: transferenciasbancarias.php");
			
		}
		else
		{
			header("location: index.html");
		}	

?>
</body>

</html>