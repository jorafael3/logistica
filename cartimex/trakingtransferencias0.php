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
			if ($_POST["transfer"]== '')
				{
					$transfer = TRIM($_GET["transfer"]);
				}
			else
				{				
					$transfer = TRIM($_POST["transfer"]);
				}	
			$bodega = $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$usuario1=$usuario; 

			if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}

			require('../conexion_mssql.php');
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result1 = $pdo1->prepare('LOG_Detalle_Transferencias2_Traking @numero=:transfer , @bodega=:bodega, @acceso=:acceso');		 
			$result1->bindParam(':transfer',$transfer,PDO::PARAM_STR);
			$result1->bindParam(':bodega',$bodega,PDO::PARAM_STR);
			$result1->bindParam(':acceso',$acceso,PDO::PARAM_STR);
			$result1->execute();
			 
			$_SESSION['usuario']= $usuario1;
			$count = $result1->rowcount();
			echo "Trae registro". $transfer;
			
			if ($count<0)
				{
				 header("location: trakingtransferencias1.php");
				}	
			else
				{	
				 $Prepa= "Transferencia NO  Existe en esta SUCURSAL ";
				//  header("Refresh: 1 ; transferenciasdespachadas.php");
				} 
				$usuario= $_SESSION['usuario'];
				$_SESSION['usuario']= $usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;	
				$_SESSION['transfer']=$transfer;
				$_SESSION['bodega']=$bodega;
				$_SESSION['nomsuc']=$nomsuc; 
				$_SESSION['vdesp']= $vdesp;

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
					<div id = "centro" > <a class="titulo"> <center>   Traking Facturas <?php echo substr($nomsuc,10,20);   ?> </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
				 
	</div> 
<hr>
</div>					

<div> 
	<a class="titulo"> <center>  <?php echo $Prepa ?>   </center> </a>
</div>
 </div>
</body>