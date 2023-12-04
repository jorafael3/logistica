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

			if ($base=='CARTIMEX'){
					require '../headcarti.php';
			}
			else{
					require '../headcompu.php';
			}

			$Id=$_SESSION["id"];
			$secuencia =$_SESSION["secuencia"];



			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $codid. "---".$chofer. "--".$placa ."--".$comentarioentre."--".$transporte."--".	$guia;
			//die();
			$usuario1= $usuario;
			require('../conexion_mssql.php');

			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$tipo = 'VEN-FA';



			$result5 = $pdo->prepare("LOG_INGRESARGUIA_FACTURA_UPDATE  @verificado =:usuario, @factura=:facturaid, @tipo=:tipo");
			$result5->bindParam(':facturaid',$Id,PDO::PARAM_STR);
			$result5->bindParam(':usuario',$usuario1,PDO::PARAM_STR);
			$result5->bindParam(':tipo',$tipo,PDO::PARAM_STR);
			$result5->execute();

			$usuario= $usuario1;
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";

			//die();
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			$_SESSION['secu']=$secuencia;

			header("location: ingguiasfacturas.php");

		}
		else
		{
			header("location: index.html");
		}

?>
</body>

</html>
