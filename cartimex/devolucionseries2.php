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
			$secu = $_SESSION['secu'];
			$checkbox = $_POST['checkbox'] ;
			$Id = $_SESSION['id'];
			$fin = count($checkbox);
			
			if ($base=='CARTIMEX'){
					require '../headcarti.php';  
			}
			else{
					require '../headcompu.php';
			}
			
		    $i=0;	
			for ($i = 0; $i <= $fin-1; $i++) 
				{
					$serie= $checkbox[$i] ;
					//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----"; 
					
					$usuario1= $usuario; 
					require('../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare("select serie from rma_productos where  Serie=:serie and FacturaID=:facturaid" );
					$result->bindParam(':serie',$serie,PDO::PARAM_STR);
					$result->bindParam(':facturaid',$Id,PDO::PARAM_STR);
					//Executes the query
					$result->execute();
					$count = $result->rowcount();
					if ($count<>0) 	
						{
							//Si la serie esta en la tabla de rma_productos la libera para poder venderla 
							$row = $result->fetch(PDO::FETCH_ASSOC);
							$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);	
							$result1 = $pdo1->prepare("update rma_productos set estado = 'INVENTARIO' , FacturaID = '', rmadtid= '' where  Serie=:serie and FacturaID=:facturaid" );
							$result1->bindParam(':serie',$serie,PDO::PARAM_STR);
							$result1->bindParam(':facturaid',$Id,PDO::PARAM_STR);
							$result1->execute();
						}
					//borro la linea del detalle que tiene la serie marcada.		
					$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result2 = $pdo2->prepare("LOG_ELIMINAR_SERIES_RMA_FACTURA @serie=:serie , @facturaid=:facturaid" );
					$result2->bindParam(':serie',$serie,PDO::PARAM_STR);
					$result2->bindParam(':facturaid',$Id,PDO::PARAM_STR);	
					$result2->execute();
					$count = $result2->rowcount();
					$row2 = $result2->fetch(PDO::FETCH_ASSOC);
					//echo "SERIES DEVUELTAS";
				}

			$usuario= $usuario1; 
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";
			
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			$_SESSION['secu']=$secu;
			header("Refresh: 2 ; devolucionseries.php");
			
		}
		else
		{
			header("location: index.html");
		}	

?>
</body>

</html>