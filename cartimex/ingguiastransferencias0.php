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
			$transfer = TRIM($_POST["transfer"]);
			$bodega = $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			 
			
			if ($transfer=='')
				{$transfer= $_GET['transfer'];}
			//echo "Secuencia". $transfer; 	
			
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
			echo $transfer;
			echo $bodega;
			// 0000028063 0000000006
			$result1 = $pdo1->prepare('LOG_INGGUIA_TRANSFERENCIA @transfer=:transfer, @bodega=:bodega, @acceso =:acceso');		 
			$result1->bindParam(':transfer',$transfer,PDO::PARAM_STR);
			$result1->bindParam(':bodega',$bodega,PDO::PARAM_STR);
			$result1->bindParam(':acceso',$acceso,PDO::PARAM_STR);
			$result1->execute();
			$usuario= $_SESSION['usuario'];
			$count = $result1->rowcount();
			//echo "Trae registro". $count;
			//Si trae registro significa q ya fue verificada y registradas series 
			if ($count==0)
				{	
				 $Prepa= "NO esta habilitado para esta Transferencia o no esta VERIFICADA ";
				 header("Refresh: 2 ; ingguiastransferencias.php");
				} 	
			else
				{
					while ($row = $result1->fetch(PDO::FETCH_ASSOC)) 
						{
							$Estado=$row['estado'];
						}
						If ($Estado == 'VERIFICADA'  ) 
							{
								//header( "location: http://app.compu-tron.net/sisco2/detallefactura.php?numfac=".$secu); 
								header( "location: detalletransferencia.php?transfer=".$transfer); 
							}
						else
							{							
								$Prepa= "Ya fue Ingresada GUIA ";
								header("Refresh: 2 ; ingguiastransferencias.php");
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
				<div id = "izq" > </div>
				<div id = "centro" > <a class="titulo"> <center>   Guias Facturas  <?php echo substr($nomsuc,10,20); ?> </center> </a></div>
				<div id = "derecha" ></div>
				
	</div> 
<hr>
</div>					
<div> 
	<a class="titulo"> <center>  <?php echo $Prepa ?>   </center> </a>
</div>
 </div>
</body>



