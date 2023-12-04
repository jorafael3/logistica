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


			if ($secu=='')
				{$secu= $_GET['secu'];}
			//echo "Secuencia". $secu;

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
			$result1 = $pdo1->prepare('LOG_INGGUIA_FACTURA @secuencia=:secu, @bodega=:bodega, @acceso =:acceso');
			$result1->bindParam(':secu',$secu,PDO::PARAM_STR);
			$result1->bindParam(':bodega',$bodega,PDO::PARAM_STR);
			$result1->bindParam(':acceso',$acceso,PDO::PARAM_STR);
			$result1->execute();
			$usuario= $_SESSION['usuario'];
			$count = $result1->rowcount();
			 //echo "Trae registro". $count;

			//Si trae registro significa q ya fue verificada y registradas series
			if ($count==0)
				{
				 $Prepa= "Factura no Existe en esta SUCURSAL o no esta VERIFICADA ";
				 header("Refresh: 2 ; ingguiasfacturas.php");
				}
			else
				{
					while ($row = $result1->fetch(PDO::FETCH_ASSOC))
						{
							$Estado=$row['estado'];
							$Sucursal=$row['Sucursal'];
							$ESTADOENVIO=$row['ESTADOENVIO'];
						}
					 
						if ($Sucursal=='03'){$TipoP='CIUDAD-UIO';}else {$TipoP='CIUDAD-GYE';}
						if ($ESTADOENVIO=="EN CAMINO"){
 						 header("location: recibirfacturasguias.php");
	 					 }
	 					 else
	 					 {
								If ($Estado == 'VERIFICADA'  )
									{
										//header( "location: http://app.compu-tron.net/sisco2/detallefactura.php?numfac=".$secu);
										header( "location: detallefactura.php?numfac=".$secu);
									}
								else
									{
										$Prepa= "Ya fue Ingresada GUIA ";
										header("Refresh: 2 ; ingguiasfacturas.php");
									}
						 }
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
