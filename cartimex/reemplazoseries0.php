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
			
			if ($base=='CARTIMEX'){
					require '../headcarti.php';  
			}
			else{
					require '../headcompu.php';
			}
			
			$original	=$_POST['original'];
			$reemplazo	=$_POST['reemplazo'];
			
			//echo $original . $reemplazo;
			require('../conexion_mssql.php');
			 
					 
					$usuario1= $usuario; 
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//new PDO($dsn, $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare("Log_facturas_reemplazo_series  @Factoriginal=:original ,@Factreemplazo=:reemplazo " );
					$result->bindParam(':original',$original,PDO::PARAM_STR);
					$result->bindParam(':reemplazo',$reemplazo,PDO::PARAM_STR);
					//Executes the query
					$result->execute();
					$usuario= $usuario1; 
					//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";
					
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
					     $msn=$row['msn'];	
					}
					
					if ($msn == 'Reemplazado con exito' )
						{
						 echo $msn ; 
						 header("Refresh: 1 ; ../menu.php");	
						 //header("location: ../menu.php");
						}	
					else 
						{
							echo $msn ; 
							header("Refresh: 1 ; reemplazoseries.php");
							//header("location: reemplazoseries.php");
						}	
					
					$_SESSION['base']= $base;
					$_SESSION['usuario']=$usuario;
					$_SESSION['acceso']=$acceso;
					$_SESSION['secu']=$secu;
				
		
		}
		else
		{
			header("location: index.html");
		}	

?>
</body>

</html>