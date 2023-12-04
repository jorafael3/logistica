<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$codigo = $_POST["usuario"];
			$nombre = $_POST["nombre"];
			$clave = $_POST["clave"];
			$acceso = $_POST["acceso"];
			$lugartrabajo = $_POST["lugartrabajo"];
			$dpto = $_POST["dpto"];
			
			//echo "Usuario:".$usuario. $base. $codigo. $nombre.$clave . $acceso .$lugartrabajo.$usrid.$id; 
			require('conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			$result = $pdo->prepare("select top 1 usrid from seriesusr order by usrid desc");
			$result->execute();
			
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
				{	  
				  $nexxt = $row['usrid']+1;
				  $id = str_repeat("0",10-strlen($nexxt)).$nexxt;
				  
				   
				} 			
			//echo "Usuario:---".$usuario."Datos a grabar ". $base. $id. $codigo. $nombre.$clave . $acceso .$lugartrabajo, $nexxt; 
			//die(); 
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);

			//Select Query
			$result1 = $pdo1->prepare("Insert into seriesusr (usuario, nombre, clave , acceso, lugartrabajo, usrid, anulado, Departamento) values (:codigo,:nombre ,:clave ,:acceso ,:lugartrabajo, :usrid, 0, :dpto )" );
			$result1->bindParam(':usrid',$id,PDO::PARAM_STR);
			$result1->bindParam(':codigo',$codigo,PDO::PARAM_STR);
			$result1->bindParam(':nombre',$nombre,PDO::PARAM_STR);
			$result1->bindParam(':clave',$clave,PDO::PARAM_STR);
			$result1->bindParam(':acceso',$acceso,PDO::PARAM_STR);
			$result1->bindParam(':lugartrabajo',$lugartrabajo,PDO::PARAM_STR);
			$result1->bindParam(':dpto',$dpto,PDO::PARAM_STR);
			
			
			//Executes the query
			$result1->execute();
			
			$_SESSION['base']= $base;
			$_SESSION['id']= $id;
			header("location: consultarusuarios.php");
		}
	else
		{
			header("location: index.html");
		}
 ?>