<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $prodid= $_POST['proid'];
			$cantidad= $_POST['cant'];
			$usuario= $_SESSION['usuario'];
			$base = $_SESSION['base'] ;
			$acceso = $_SESSION['acceso'];
			$liqid = $_SESSION['liqid'];
			//echo "Usuario:". $usuario. "-".$cantidad ."-".$liqid. "-".$prodid.$peso.$alto.$ancho.$largo ; 
			//die(); 
			
			$_SESSION['usuario']=$usuario; 
			require('../../conexion_mssqlxtratech.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$usuario= $_SESSION['usuario'];
			$result = $pdo->prepare('update IMP_LIQUIDACION_DT set cantrecibida=:cantidad from  IMP_LIQUIDACION_DT where LiquidaciónID=:liqid and ProductoID=:productoid');
			$result->bindParam(':cantidad',$cantidad,PDO::PARAM_STR);
			$result->bindParam(':liqid',$liqid,PDO::PARAM_STR);
			$result->bindParam(':productoid',$prodid,PDO::PARAM_STR);
			$result->execute();
			
			$_SESSION['base']= $base;
			$_SESSION['usuario'] = $usuario; 
			$_SESSION['idorden']=$idorden;	
			header("location:  recepcionimportacionesxtratech1.php");
		}
	else
		{
			header("location: index.html");
		}
 ?>