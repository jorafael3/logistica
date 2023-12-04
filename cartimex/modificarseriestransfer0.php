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
			$transfer = TRIM($_POST["secu"]);
			$bodega = $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$_SESSION['usuario']= $usuario ;
			if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}
			//echo $transfer; 
			require('../conexion_mssql.php');
			//$pdo = new PDO($dsn, $sql_user, $sql_pwd);
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('LOG_BUSQUEDA_TRANSFERENCIA_SERIES @numero=:transfer');		 
			$result->bindParam(':transfer',$transfer,PDO::PARAM_STR);
			$result->execute();
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
				{
					$Numtransfer=$transfer;
					$Idtransfer=$row['Idtransfer'];
					$Detalle=$row['Detalle'];
					$Descodigo=$row['Descodigo'];
					$Destino=$row['Destino'];
					$Oricodigo=$row['Oricodigo'];
					$Origen=$row['Origen'];
					$Fecha=$row['Fecha'];
				}	
			$count = $result->rowcount();
		//	echo "Trae registro 1". $count;
			
			if ($count==1)
				{
					//$Idtransfer = $Idtransfer; 
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//new PDO($dsn, $sql_user, $sql_pwd);
					//Select Query
					$result1 = $pdo->prepare("select * from facturaslistas where factura=:secu and tipo= 'INV-TR'");		 
					$result1->bindParam(':secu',$Idtransfer,PDO::PARAM_STR);
					$result1->execute();
					$usuario= $_SESSION['usuario']; 
					$count1 = $result1->rowcount();
					//echo "Trae registro".  $Idtransfer;
					while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
						{
							$Estado=$row1['Estado'];
							//echo "Estado". $Estado;
						} 	
					if ($Estado== 'VERIFICADA' or $Estado== 'INGRESADAGUIA' or  $Estado== 'DESPACHADA' or $Estado== 'ENTREGADA')
						{ header("location: modificarseriestransfer1.php");}
					else
						{ $Prepa = "Transferencia sin verificar  "; 
						  header("Refresh: 3 ; modificarseriestransfer.php");}
						
					
				}
			else
				{	
				 $Prepa= "Transferencia no Existe en esta SUCURSAL ";
				 header("Refresh: 3 ; modificarseriestransfer.php");
				} 		
				$_SESSION['usuario']= $usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;	
				$_SESSION['agruparid']=$Idtransfer;
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
					<div id = "centro" > <a class="titulo"> <center>   Serie de Transferencias  </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
			 
	</div> 
<hr>
</div>					

<div> 
	<a class="titulo"> <center>  <?php echo $Prepa ?>   </center> </a>
</div>
 </div>
</body>