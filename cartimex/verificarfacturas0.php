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
			$result1 = $pdo1->prepare('LOG_VERIFICAR_FACTURA @secuencia=:secu, @bodega=:bodega, @acceso =:acceso');
			$result1->bindParam(':secu',$secu,PDO::PARAM_STR);
			$result1->bindParam(':bodega',$bodega,PDO::PARAM_STR);
			$result1->bindParam(':acceso',$acceso,PDO::PARAM_STR);
			$result1->execute();
			$usuario= $_SESSION['usuario'];
			$count = $result1->rowcount();
			//echo "Trae registro". $count;
			if ($count<0)
				{
					 $row1 = $result1->fetch(PDO::FETCH_ASSOC);
					 //PARA PODER COMPARAR Y SABER SI ES MOSTRADOR
					 if ($row1['Sucursal']=='03'){$TipoP='MOSTRADOR-UIO';}else {$TipoP='MOSTRADOR-GYE';}

					 if (($row1['ESTADOENVIO']=="EN CAMINO") AND ($row1['Bloqueada']<>"BLOQUEADA")){
						 header("location: recibirfacturas.php");
					 }
					 else
					 {
						 if (($row1['ESTADOENVIO']=="EN CAMINO") AND ($row1['Bloqueada']=="BLOQUEADA")){
							 $Prepa= "FACTURA ".$secu." ESTA BLOQUEADA";
							 header("Refresh: 1 ; verificarfacturas.php");
						 }
						 else
						 {
								 if (($row1['Estado']=="PREPARADA") and ($row1['Bloqueada']<>"BLOQUEADA"))
									{

										header("location: verificarfacturas1.php");
									}
								 else
									{
										if (($row1['Estado']=="PREPARADA") and ($row1['Bloqueada']=="BLOQUEADA") and ($row1['TipoP']<>$TipoP))
											{

												header("location: verificarfacturas1.php");
											}
										else
											{
												if (($row1['Estado']=="PREPARADA") and ($row1['Bloqueada']=="BLOQUEADA") and ($row1['TipoP']==$TipoP))
													{

														$Prepa= "FACTURA ".$secu." ESTA BLOQUEADA";
														header("Refresh: 1 ; verificarfacturas.php");
													}
												else
													{

														$Prepa= "Factura ya fue Verificada por ".$row1['nombre'] ;
														header("Refresh: 1 ; verificarfacturas.php");
													}
											}
									}
							 }
				 	}
				}

			else
				{
				 $Prepa= "Factura no Existe en esta SUCURSAL o no esta PREPARADA ";
				 header("Refresh: 1 ; recibirfacturas2.php");
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
					<div id = "centro" > <a class="titulo"> <center>   Verificar Facturas  <?php echo substr($nomsuc,10,20); ?> </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>

	</div>
<hr>
</div>
<div>
	<a class="titulo"> <font color = 'red'> <center>  <?php echo $Prepa ?>   </center> </font></a>
</div>
 </div>
</body>
