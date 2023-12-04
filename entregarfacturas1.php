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
					require 'headcarti.php';  
			}
			else{
					require 'headcompu.php';
			}
			
		    $id= $_POST["id"];
			$chofer = $_POST["chofer"];
			$placa = $_POST["placa"];
			$comentarioentre = $_POST["comentarioentre"];
					
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $codid. "---".$chofer. "--".$placa ."--".$comentarioentre."--"; 
			 
			$usuario1= $usuario; 
			require('conexion_mssql.php');

			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare("LOG_FACTURAS_ENTREGADAS_VEHICULO  @chofer=:chofer , @placa=:placa ,@comentario=:comentario , @id=:id" );
			$result->bindParam(':chofer',$chofer,PDO::PARAM_STR);
			$result->bindParam(':placa',$placa,PDO::PARAM_STR);
			$result->bindParam(':comentario',$comentarioentre,PDO::PARAM_STR);
			$result->bindParam(':id',$id,PDO::PARAM_STR);
			
			//Executes the query
			$result->execute();
			$usuario= $usuario1; 
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";
			
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			
			
			 
			
			header("location: entregarfacturas.php");
			
		}
		else
		{
			header("location: index.html");
		}	

?>
</body>

</html>