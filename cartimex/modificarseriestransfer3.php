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
			$transfer = $_SESSION['transfer'];
			$ProductoId = $_SESSION['ProductoId'];
			$usuario1= trim($usuario);
			//echo "Usuario1". $usuario1."Usuario". $usuario; 
			if ($base=='CARTIMEX'){
					require '../headcarti.php';  
			}
			else{
					require '../headcompu.php';
			}
			$ante	=$_POST['serieante']; //serie anterior
			$new 	=$_POST['serie'];//serie nueva
			
			$fin = count($new );
			 
			$i=0;
			
			for ($i = 0; $i <= $fin-1; $i++) 
			{ 
					$newserie= $new[$i];
					$oldserie= $ante[$i];
					
					//print  $oldserie. $newserie; 
					require('../conexion_mssql.php');		
				//	echo "Usuario:".$usuario1. $base. $acceso. "*****-". $newserie. "---".$serieante. "--".$transfer ."--". $ProductoId  ; 
					$usuario= $usuario1; 
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare("LOG_MODIFICAR_SERIES_TRANSFERENCIAS_UPDATE  @serieante=:serieante ,@newserie=:newserie ,@transfer=:transfer, @ProductoId=:ProductoId" );
					$result->bindParam(':serieante',$oldserie,PDO::PARAM_STR);
					$result->bindParam(':newserie',$newserie,PDO::PARAM_STR);
					$result->bindParam(':transfer',$transfer,PDO::PARAM_STR);
					$result->bindParam(':ProductoId',$ProductoId,PDO::PARAM_STR);
					$result->execute();
					//echo $newserie. "---".$serieante. "--".$transfer ."--". $ProductoId  ; 
					 
					
			}
					
			//echo "Usuario1". $usuario1."Usuario". $usuario; 
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			$_SESSION['secu']=$secu; 
			
			header("location: modificarseriestransfer1.php");
		}
		else
		{
			header("location: index.html");
		}	

?>
</body>

</html>