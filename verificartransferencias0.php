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
			$transferencia = TRIM($_POST["transferencia"]);
			$bodega = $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$_SESSION['usuario']= $usuario ;
			
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
			$result1 = $pdo1->prepare('LOG_VERIFICAR_TRANSFERENCIA @transferencia=:transferencia, @bodega=:bodega');		 
			$result1->bindParam(':transferencia',$transferencia,PDO::PARAM_STR);
			$result1->bindParam(':bodega',$bodega,PDO::PARAM_STR);
			$result1->execute();
			$usuario= $_SESSION['usuario'];
			$count = $result1->rowcount();
			//echo "Trae registro". $count;
			if ($count<0)
				{	
					 $row1 = $result1->fetch(PDO::FETCH_ASSOC);
					 if ($row1['nombre']==""){
						header("location: verificartransferencias1.php"); }	
					 else
					 {
						 $Prepa= "Transferencia ya fue Verificada por ".$row1['nombre'];
						 header("Refresh: 1 ; verificartransferencias.php");
					 }	 
				}
			else
				{	
				 $Prepa= "Transferencia no Existe en esta SUCURSAL o no esta PREPARADA ";
				 header("Refresh: 1 ; verificartransferencias.php");
				} 		
				$_SESSION['usuario']= $usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;	
				$_SESSION['transferencia']=$transferencia;
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
					<div id = "centro" > <a class="titulo"> <center>   Verificar Transferencias <?php echo substr($nomsuc,10,20); ?>   </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a>  </div>
				 
	</div> 
<hr>
</div>					
<div> 
	<a class="titulo"> <center>  <?php echo $Prepa ?>   </center> </a>
</div>
 </div>
</body>

