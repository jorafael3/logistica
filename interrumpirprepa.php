<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body> 
<div id= "header" align= "center">
<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','On');
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	=$_SESSION['acceso'];
			$bodega = $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$id = $_SESSION ['id'];
			$tipo = $_POST ['tipo'];
			$_SESSION['usuario']= $usuario ;
			$bodegaFAC = $_SESSION['bodegaFAC'];
			//echo $bodegaFAC; 
			  
			if ($base=='CARTIMEX'){
							require 'headcarti.php';  
					}
					else{
							require 'headcompu.php';
					}
					
			require('conexion_mssql.php');
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result1 = $pdo1->prepare('LOG_preparar_delete @id=:id, @tipo=:tipo, @bodegaFAC=:bodegaFAC');		 
			$result1->bindParam(':id',$id,PDO::PARAM_STR);
			$result1->bindParam(':tipo',$tipo,PDO::PARAM_STR);
			$result1->bindParam(':bodegaFAC',$bodegaFAC,PDO::PARAM_STR);
			$result1->execute();
			$usuario= $_SESSION['usuario'];
			$count = $result1->rowcount();
			
				$_SESSION['usuario']= $usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;	
				$_SESSION['secu']=$secu;
				$_SESSION['bodega']=$bodega;
				$_SESSION['nomsuc']=$nomsuc; 
			 
				if ($tipo=='VEN-FA')
					{header("Refresh: 0 ; prepararfacturas.php");}
			    else
					{header("Refresh: 0 ; preparartransferencias.php");}
		}
		
		else
		{		
			header("location: index.html");
		}		
?>
</div>					
 </div>
</body>