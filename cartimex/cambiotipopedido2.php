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
			
		    $idfac = $_POST["idfac"];
			$tipodespacho = $_POST["tipodespacho"];
			
			// echo 'Esto voy a grabar'. $idfac. $tipodespacho; 
		 
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----"; 
			 
			$usuario1= $usuario; 
			require('../conexion_mssql.php');

			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare("UPDATE FACTURASLISTAS SET TipoPedido=:TipoPedido WHERE factura=:idfact" );
			$result->bindParam(':TipoPedido',$tipodespacho,PDO::PARAM_STR);
			$result->bindParam(':idfact',$idfac,PDO::PARAM_STR);

			$result->execute();
			$usuario= $usuario1; 
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";
			
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			
			
			 
			
			header("location: ../menu.php");
			
		}
		else
		{
			header("location: index.html");
		}	

?>
</body>

</html>