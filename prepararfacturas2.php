<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body> 

<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','On');
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$id = $_SESSION['id']; 
			$base = $_SESSION['base'];
			$bodega= $_SESSION['bodega'];
			$acceso	=$_SESSION['acceso'];
			$nomsuc = $_SESSION['nomsuc'];
			$contaserv= $_SESSION['serv'];
			$conta=$_SESSION['carreglo'];
			$bodegaFAC = $_SESSION['bodegaFAC'];			
			echo "1Usuario:---".$contaserv."Datos a grabar ". $conta;
		  
			 if ($contaserv==$conta){
				$soloserv= 1 ;//1 la factura solo tiene servicios 
				}	
			else 
				{
					$soloserv= 0 ; //productos y servicios 
				}
			$_SESSION['usuario']= $usuario; 
			$_SESSION['bodega']= $bodega; 
			require('conexion_mssql.php');
			$usuario = $_SESSION['usuario'];
			$bodega = $_SESSION['bodega'];
			
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('Log_facturaslistas_preparar_update @id=:id , @usuario=:usuario ,@soloserv =:soloserv, @bodegaid=:bodega' );
			$result->bindParam(':id',$id,PDO::PARAM_STR);
			$result->bindParam(':usuario',$usuario,PDO::PARAM_STR);		
			$result->bindParam(':soloserv',$soloserv,PDO::PARAM_STR);		
			$result->bindParam(':bodega',$bodegaFAC,PDO::PARAM_STR);		
			//Executes the query
			$result->execute();	
			//echo $base;
			
			$_SESSION['base']= $base;
			$_SESSION['id']= $id;
			$_SESSION['usuario']= $usuario;
			$_SESSION['bodega']= $bodega;
			$_SESSION['nomsuc']=$nomsuc; 
			echo "2Usuario:---".$usuario."Datos a grabar ". $base. $bodega; 
			//die(); 
			header("location: prepararfacturas.php");
		}
	else
		{
			header("location: index.html");
		}
 ?>
</body>