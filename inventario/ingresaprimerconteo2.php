<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $prodid= $_POST['prodid'];
			$cantidad= $_POST['cant'];
			$codigo= $_SESSION['cobusqueda'];	
			$codigoconteo=$_SESSION['codigo'];	
			$usuario= $_SESSION['usuario'];
			$base = $_SESSION['base'] ;
			$acceso = $_SESSION['acceso'];
			$cod1= $_SESSION["codalter"];
			$SECCION= $_SESSION['SECCION'];
			$ConteoId = $_SESSION['ConteoID'] ; 
			
		
			$usuario1 =$usuario;
			echo "Usuario:". $prodid. $codigo. $usuario1. $base . $acceso. $cod1 .$cantidad. $SECCION.$ConteoId ; 
			//die(); 
			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			$result = $pdo->prepare('LOG_Inventario_Conteo_Insert  @Codigo=:Codigo,@Codbarras=:Codbarras,@CreadoPor=:CreadoPor,@Seccion=:Seccion,@cantidad=:cantidad, @ConteoId=:ConteoId, @id=:id');
			$result->bindParam(':Codigo',$codigoconteo,PDO::PARAM_STR);
			$result->bindParam(':Codbarras',$cod1,PDO::PARAM_STR);
			$result->bindParam(':CreadoPor',$usuario1,PDO::PARAM_STR);
			$result->bindParam(':Seccion',$SECCION,PDO::PARAM_STR);
			$result->bindParam(':cantidad',$cantidad,PDO::PARAM_STR);
			$result->bindParam(':ConteoId',$ConteoId,PDO::PARAM_STR);
			$result->bindParam(':id',$prodid,PDO::PARAM_STR);
			$result->execute();
			
			
			
			$_SESSION['base']= $base;
			$_SESSION['id']= $id;
			$_SESSION['usuario'] = $usuario1; 
			$_SESSION['idconteo'] = $ConteoId;
			$_SESSION['codigo']=$codigo;	
			
			header("location:  inventario0.php");
		}
	else
		{
			header("location: index.html");
		}
 ?>