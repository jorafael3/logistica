<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $prodid= $_POST['proid'];
			$cantidad= $_POST['cant'];
			$peso= $_POST['peso'];
			$alto= $_POST['alto'];
			$ancho= $_POST['ancho'];
			$largo= $_POST['largo'];
			$usuario= $_SESSION['usuario'];
			$base = $_SESSION['base'] ;
			$acceso = $_SESSION['acceso'];
			$idorden = $_SESSION['idorden'];
			echo "Usuario:". $usuario. "-".$cantidad ."-".$idorden. "-".$prodid ; 
			//die(); 
			
			$_SESSION['usuario']=$usuario; 
			require('../../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$usuario= $_SESSION['usuario'];
			$result = $pdo->prepare('update com_ordenes_dt set CantRecibida=:cantidad from  com_ordenes_dt where ordenid=:ordenid and ProductoID=:productoid');
			$result->bindParam(':cantidad',$cantidad,PDO::PARAM_STR);
			$result->bindParam(':ordenid',$idorden,PDO::PARAM_STR);
			$result->bindParam(':productoid',$prodid,PDO::PARAM_STR);
			$result->execute();
			
			/*Actualizar peso del producto nuevo */
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result1 = $pdo1->prepare('update inv_productos set medidapeso=:peso, MedidaVolumen=:ancho, MedidaLongitud=:largo, MedidaSuperficie=:alto
									  from  inv_productos  where ID=:productoid');
			$result1->bindParam(':peso',$peso,PDO::PARAM_STR);
			$result1->bindParam(':ancho',$ancho,PDO::PARAM_STR);
			$result1->bindParam(':largo',$largo,PDO::PARAM_STR);
			$result1->bindParam(':alto',$alto,PDO::PARAM_STR);
			$result1->bindParam(':productoid',$prodid,PDO::PARAM_STR);
			$result1->execute();
			
			
			$_SESSION['base']= $base;
			$_SESSION['usuario'] = $usuario; 
			$_SESSION['idorden']=$idorden;	
			header("location:  recepcioncompras1.php");
		}
	else
		{
			header("location: index.html");
		}
 ?>