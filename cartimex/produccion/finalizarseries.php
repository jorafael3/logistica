<html>
<body>
<body oncontextmenu="return false">

<?php 
	session_start();		
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$bodega	=$_SESSION['bodega'];
			$EnsambleId= $_SESSION['EnsambleId'];
			date_default_timezone_set('America/Guayaquil');
			$fecha = date("Y-m-d", time());
			$fecha= date("Ymd");
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;
			if ($base=='CARTIMEX'){
					require '../../headcarti.php';  
			}
			else{
					require '../../headcompu.php';
			}
			$_SESSION['usuario']= $usuario;
			require('../../conexion_mssql.php');
			$usuario = $_SESSION['usuario'];
			//print  $newId.  $newScpu . $newSmain .$newShdd.$newSmem1.$newSmem2.$newSoptico. $newSfdd. $newSotro1. $newSotro2  ; 
			$Estado= 'VERIFICADO'; 
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $Estado. "---".$fh. "--".$EnsambleId ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----"; 
		 
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare("update INV_PRODUCCION_ENSAMBLE set Estado=:Estado,VerificadoPor=:VerificadoPor, FechaVerificado=:FechaVerificado 
									where EnsambleId=:EnsambleId and tipo ='VEN-FA'" );
			$result->bindParam(':Estado',$Estado,PDO::PARAM_STR);
			$result->bindParam(':VerificadoPor',$usuario,PDO::PARAM_STR);
			$result->bindParam(':FechaVerificado',$fh,PDO::PARAM_STR);
			$result->bindParam(':EnsambleId',$EnsambleId,PDO::PARAM_STR);
			$result->execute();
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			$_SESSION['bodega']= $bodega;	
			header("location: ensamblespendientes.php");
		}
		else
		{
			header("location: index.html");
		}	

?>
</body>

</html>