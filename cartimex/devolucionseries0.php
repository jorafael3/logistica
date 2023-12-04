<html>
<script type="text/javascript">
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">

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
			$secu = TRIM($_POST["secu"]);
			$bodega = $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$_SESSION['usuario']= $usuario ;
			if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}

			require('../conexion_mssql.php');
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result1 = $pdo1->prepare('Log_modificar_series_tienda @secu=:secu, @bodega=:bodega, @acceso=:acceso');		 
			$result1->bindParam(':secu',$secu,PDO::PARAM_STR);
			$result1->bindParam(':bodega',$bodega,PDO::PARAM_STR);
			$result1->bindParam(':acceso',$acceso,PDO::PARAM_STR);
			$result1->execute();
			$usuario= $_SESSION['usuario'];
			
			$count = $result1->rowcount();
			//echo "Trae registro". $count;
			if ($count<0)
				{
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//new PDO($dsn, $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare('Log_facturaslistas_preparando_select2 @secu=:secu, @acceso =:acceso');		 
					$result->bindParam(':secu',$secu,PDO::PARAM_STR);
					$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
					$result->execute();
					$usuario= $_SESSION['usuario']; 
					$count = $result->rowcount();
					//echo "Trae registro". $count;
			 		
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
						{
							$Prepa=$row['Prepa'];
							$Preparado=$row['preparado'];
						}
					//echo "Preparando". $Prepa;
					//echo "Preparado".	$Preparado;	
					
					
					if ($Prepa<>' ' and $Preparado <>'.' )
						{ header("location: devolucionseries1.php");}
					else
						{ $Prepa = "Factura en Preparacion Por ". $Prepa; 
						  header("Refresh: 3 ; devolucionseries.php");}
						
					
				}
			else
				{	
				 $Prepa= "Factura no Existe en esta SUCURSAL ";
				 header("Refresh: 3 ; devolucionseries.php");
				} 		
				$_SESSION['usuario']= $usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;	
				$_SESSION['secu']=$secu;
				$_SESSION['bodega']=$bodega;
				$_SESSION['nomsuc']=$nomsuc; 
		}
		
		else
		{		
			header("location: index.html");
		}		
?>
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>   Serie de Facturas  </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
			 
	</div> 
<hr>
</div>					

<div> 
	<a class="titulo"> <center>  <?php echo $Prepa ?>   </center> </a>
</div>
 </div>
</body>