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
			
			$serie	=$_POST['serie'];
			$dtid	=$_POST['dtid'];
			$fin = count($serie);
			$i=0;
			
			$_SESSION['usuario']= $usuario;
			
			require('../conexion_mssql.php');
			$usuario = $_SESSION['usuario'];
			//echo "Usuario viene ".$usuario;
					 
			for ($i = 0; $i <= $fin-1; $i++) 
			{ 
						$newserie= $serie[$i] ;
						$rmadtid= $dtid[$i] ;
						//print  $rmadtid . $newserie ; 
							
					//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----"; 

					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//new PDO($dsn, $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare("LOG_MODIFICAR_SERIES_UPDATE  @rmadtid=:rmadtid ,@newserie=:newserie " );
					$result->bindParam(':rmadtid',$rmadtid,PDO::PARAM_STR);
					$result->bindParam(':newserie',$newserie,PDO::PARAM_STR);
					
					//Executes the query
					$result->execute();
					//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";
					
					$_SESSION['base']= $base;
					$_SESSION['usuario']=$usuario;
					$_SESSION['acceso']=$acceso;
					$_SESSION['secu']=$secu;
					header("location: modificarseries.php");
			}
		}
		else
		{
			header("location: index.html");
		}	

?>
</body>

</html>