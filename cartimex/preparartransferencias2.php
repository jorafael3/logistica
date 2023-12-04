<html>
<script type="text/javascript">
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">

<body> 

<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','On');
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$transfer = $_SESSION['transfer']; 
			$base = $_SESSION['base'];
			$bodega= $_SESSION['bodega'];
			$acceso	=$_SESSION['acceso'];
			$zretiro = $_POST['zona'];			
			$tipo=$_SESSION['tipo']; 
			$TipoP=$_SESSION['TipoP']; 
			
			
			$_SESSION['usuario']= $usuario; 
			$_SESSION['bodega']= $bodega; 
			require('../conexion_mssql.php');
			$usuario = $_SESSION['usuario'];
			$bodega = $_SESSION['bodega'];
			 $soloserv= 0; 
			if ($zretiro<>'')
				{
				 $zona= 'J';
				 $pdoj = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);	
				 $resultj = $pdoj->prepare('select zretiro from facturaslistas_zonas where factura=:transfer and zona=:zona
											and zretiro=:zretiro' );
				 $resultj->bindParam(':transfer',$transfer,PDO::PARAM_STR);
				 $resultj->bindParam(':zona',$zona,PDO::PARAM_STR);
				 $resultj->bindParam(':zretiro',$zretiro,PDO::PARAM_STR);
				
				 $resultj->execute();
				 $countj = $resultj->rowcount();	
				  //echo "Trae registros". $countj;
				  
				 if ($countj==0)//no trae registros	
					{
						echo "Retiro de Jaula no coincide";
						header("Refresh: 1 ; preparartransferencias1.php");
					}
				 else
				    {
					   echo "Si entra aqui ";
					   echo $transfer.$usuario.$acceso; 
					  
					  
					   $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						$result = $pdo->prepare('Log_facturaslistas_preparartr_update  @id=:transfer , @usuario=:usuario , @acceso =:acceso ' );
						$result->bindParam(':transfer',$transfer,PDO::PARAM_STR);
						$result->bindParam(':usuario',$usuario,PDO::PARAM_STR);			
						$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
						$result->execute();	
						header("location: preparartransferencias.php");
				    }   
				}
			 else
				{
					echo "Solo productos de Galpon";
					echo "Datos a grabar"."Usuario".$usuario."FacturaId".$transfer. "Acceso".$acceso;					
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('Log_facturaslistas_preparartr_update  @id=:transfer , @usuario=:usuario , @acceso =:acceso' );
					$result->bindParam(':transfer',$transfer,PDO::PARAM_STR);
					$result->bindParam(':usuario',$usuario,PDO::PARAM_STR);				
					$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
					$result->execute();	
					header("location: preparartransferencias.php");
					//echo $base;
				}
			$_SESSION['base']= $base;
			$_SESSION['id']= $id;
			$_SESSION['usuario']= $usuario;
			$_SESSION['bodega']= $bodega;
			$_SESSION['nomsuc']=$nomsuc; 
			//echo "2Usuario:---".$usuario."Datos a grabar ". $base. $bodega; 
			//die(); 
			//header("location: prepararfacturas.php");
		}
	else
		{
			header("location: index.html");
		}
 ?>
</body>