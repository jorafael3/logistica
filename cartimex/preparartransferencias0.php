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
			$bodega	=$_SESSION['bodega'];
			$transfer = TRIM($_POST["transfer"]);
			
			if ($base=='CARTIMEX'){	require '../headcarti.php';  }
			else{require '../headcompu.php';}
			
			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('Log_facturaslistas_preparandotr_select2 @transfer=:transfer , @acceso=:acceso');		 
			$result->bindParam(':transfer',$transfer,PDO::PARAM_STR);
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
				if ($Prepa<>' ' and $Preparado <>'.' and ($acceso=='12')  )
				{ 
					header("location: preparartransferenciasjaula1.php");
				}
				else
				{
					if ($Prepa<>' ' and $Preparado <>'.' and ($acceso<>'12')  )
					{ 
						header("location: preparartransferencias1.php");
					}
					else
					{ 
						$Prepa = "Transferencias en Preparacion Por ". $Prepa; 
						header("Refresh: 3 ; preparartransferencias.php");
					}
				}							
				$_SESSION['usuario']= $usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;	
				$_SESSION['transfer']=$transfer;
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
					<div id = "centro" > <a class="titulo"> <center>   Preparar Facturas <?php echo substr($nomsuc,10,20);   ?> </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
				 
	</div> 
<hr>
</div>					

<div> 
	<a class="titulo"> <center>  <?php echo $Prepa ?>   </center> </a>
</div>
 </div>
</body>