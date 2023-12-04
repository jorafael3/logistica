<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
			$usuario= $_SESSION['usuario'];
			$base = $_SESSION['base'] ;
			$acceso = $_SESSION['acceso'];
			$ConteoId = $_SESSION['ConteoID']; 
			$usuarioasig = $_POST["usuarioasig"];
			$desde = $_POST["desde"];
			$hasta = $_POST["hasta"];
			$seccion = 'conteo2'; 
			$usuario1 =$usuario;
			echo "Usuario:". $usuario1. "-".$base . "-".$acceso. "-".$ConteoId ."-".$usuarioasig."-". $desde."-".$hasta. "-".$seccion ; 
			
			require('../conexion_mssql.php');
			while ( $desde <= $hasta)
				{
					//echo "entra aqui ".$ConteoId. $desde,$usuarioasig; 
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('update INV_CONTEO_DIFERENCIAS set usuasignado=:usuasignado where ConteoID=:ConteoID 
											 and seccion =:seccion and col=:col');	
					$result->bindParam(':usuasignado',$usuarioasig,PDO::PARAM_STR);
					$result->bindParam(':ConteoID',$ConteoId,PDO::PARAM_STR);
					$result->bindParam(':col',$desde,PDO::PARAM_STR);
					$result->bindParam(':seccion',$seccion,PDO::PARAM_STR);
					$result->execute();
					$desde=$desde+1;
				}
			$estado3 = 'En Curso'; 
			$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result2 = $pdo2->prepare('update INV_CONTEO set   Conteo3=:estado3 where ConteoID=:ConteoID');		
			$result2->bindParam(':ConteoID',$ConteoId,PDO::PARAM_STR);
			$result2->bindParam(':estado3',$estado3,PDO::PARAM_STR);
			$result2->execute();
			
			$_SESSION['base']= $base;
			$_SESSION['id']= $id;
			$_SESSION['usuario'] = $usuario1; 
			$_SESSION['idconteo'] = $ConteoId;
			$_SESSION['codigo']=$codigo;	
			
			header("location:  asignardiferencias.php?id=$ConteoId");
		}
	else
		{
			header("location: index.html");
		}
 ?>