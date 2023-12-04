<html>
<body>
<body oncontextmenu="return false">

<?php 
	session_start();		
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	=$_SESSION['acceso'];
			$bodega = $_SESSION['bodega'];
			
			$id = $_POST['id'];
			$ubinueva = $_POST['ubinueva'];
			
			//echo $usuario;
			 
			
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----"; 
			 
			$usuario1= $usuario; 
			require('../conexion_mssql.php');

			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare("Update facturaslistas set ubicaespera=:ubinueva where factura=:factura and tipo = 'VEN-FA'" );
			$result->bindParam(':ubinueva',$ubinueva,PDO::PARAM_STR);
			$result->bindParam(':factura',$id,PDO::PARAM_STR);
			$result->execute();
			$usuario= $usuario1; 
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";
			
			$_SESSION['usuario']=$usuario1;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['bodega']= $bodega; 
			
			header("location: verificarfacturas.php");
			
		}
		else
		{
			header("location: index.html");
		}	

?>
</body>

</html>