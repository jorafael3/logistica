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
			$id = $_SESSION['id'];
			
			if ($base=='CARTIMEX'){
					require 'headcarti.php';  
			}
			else{
					require 'headcompu.php';
			}
			$usuario1= $usuario; 
			require('conexion_mssql.php');	
			
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			  		   
			//Select Query
			$result = $pdo->prepare("UPDATE seriesusr set anulado= 1 WHERE usrid =:id" );
			$result->bindParam(':id',$id,PDO::PARAM_STR);
			//Executes the query
			$result->execute();
			$usuario= $usuario1; 
			
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			
			header("location: consultarusuarios.php");
			
		}	
?>

</body>

</html>