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
			if ($_POST["secu"]== '')
				{$secu = TRIM($_GET["secu"]);}
			else
				{$secu = TRIM($_POST["secu"]);}	
			
			$usuario1=$usuario; 
			if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}
			echo $usuario1.$base.$acceso.$secu;
			require('../conexion_mssql.php');
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$estado= 'Finalizado';
			$result1 = $pdo1->prepare('select * from INV_CONTEO 
									   where ConteoID=:secu and Estado=:estado');		 
			$result1->bindParam(':secu',$secu,PDO::PARAM_STR);
			$result1->bindParam(':estado',$estado,PDO::PARAM_STR);
			$result1->execute();
			$_SESSION['usuario']= $usuario1;
			$count = $result1->rowcount();
			//echo "Trae registro". $count;
			
			if ($count<0)
				{
				 header("location: reporteinventariofinal1.php");
				}	
			else
				{	
				 $Prepa= "Inventario NO EXISTE O NO FINALIZADO ";
				 header("Refresh: 1 ; reporteinventarios.php");
				} 
				$usuario= $_SESSION['usuario'];
				$_SESSION['usuario']= $usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;	
				$_SESSION['secu']=$secu;
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
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
				 
	</div> 
<hr>
</div>					

<div> 
	<a class="titulo"> <center>  <?php echo $Prepa ?>   </center> </a>
</div>
 </div>
</body>