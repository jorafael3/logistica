<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$detalle = $_POST["detalle"];
			
			//echo "Usuario:".$usuario. $base. $detalle; 
			$usuario1 =$usuario;
			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			$result = $pdo->prepare('LOG_Inventario_Cabecera_Insert  @detalle= :detalle, @CreadoPor=:creadopor');
			$result->bindParam(':detalle',$detalle,PDO::PARAM_STR);
			$result->bindParam(':creadopor',$usuario1,PDO::PARAM_STR);
			$result->execute();
			
			
			$_SESSION['base']= $base;
			$_SESSION['id']= $id;
			$_SESSION['usuario'] = $usuario1; 
			header("location: mantenimientoinventario.php");
		}
	else
		{
			header("location: index.html");
		}
 ?>