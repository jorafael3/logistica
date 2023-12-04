<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $prodid= $_POST['proid'];
			$cantidad= $_POST['cant'];
			$codid=$_POST['codid'];	
			$usuario= $_SESSION['usuario'];
			$base = $_SESSION['base'] ;
			$acceso = $_SESSION['acceso'];
			$ubic= $_POST['ubic'];
			$seccion= $_POST['seccion'];
			$ConteoId = $_SESSION['ConteoID'] ; 
			
		
			$usuario1 =$usuario;
			//echo "Usuario:". $codid."-". $ubic. "-".$usuario1. "-".$seccion. "-".$cantidad ."-".$ConteoId. "-".$prodid ; 
			//die(); 
			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			$result = $pdo->prepare('LOG_Inventario_Conteo_Insert  @Codigo=:Codigo,@Codbarras=:Codbarras,@CreadoPor=:CreadoPor,@Seccion=:Seccion,@cantidad=:cantidad, @ConteoId=:ConteoId, @id=:id');
			$result->bindParam(':Codigo',$codid,PDO::PARAM_STR);
			$result->bindParam(':Codbarras',$ubic,PDO::PARAM_STR);
			$result->bindParam(':CreadoPor',$usuario1,PDO::PARAM_STR);
			$result->bindParam(':Seccion',$seccion,PDO::PARAM_STR);
			$result->bindParam(':cantidad',$cantidad,PDO::PARAM_STR);
			$result->bindParam(':ConteoId',$ConteoId,PDO::PARAM_STR);
			$result->bindParam(':id',$prodid,PDO::PARAM_STR);
			$result->execute();
			
			
			
			$_SESSION['base']= $base;
			$_SESSION['id']= $id;
			$_SESSION['usuario'] = $usuario1; 
			$_SESSION['ConteoId'] = $ConteoId;
			$_SESSION['proid']=$prodid;	
			
			header("location: tercerconteo.php");
		}
	else
		{
			header("location: index.html");
		}
 ?>