<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
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
			if ($_POST['facturaid']==''){ $id=$_SESSION['id'];
										  }else {$id=$_POST['facturaid'];$grabarsi='NO'; $grabarno='NO';}

			//$id = $_POST['facturaid'];
			$base = $_SESSION['base'];
			$bodega= $_SESSION['bodega'];
			$acceso	=$_SESSION['acceso'];
			$nomsuc = $_SESSION['nomsuc'];
			$contaserv= $_SESSION['serv'];
			$TipoP = $_SESSION['TipoP'];
			$conta=$_SESSION['carreglo'];
			if ($_POST['zona']==''){$zretiro = $_SESSION['zona'];}else{	$zretiro = $_POST['zona'];}
			$tipo=$_SESSION['tipo'];
			$TipoP=$_SESSION['TipoP'];
			if ($base=='CARTIMEX'){
					require '../headcarti.php';
					}
			else{
					require '../headcompu.php';
					$_SESSION['base']= $base;
					}
			$desacboto = 'submit';

			//echo "Viene de otra pagina". $TipoP;

			$ubiespera=  $_POST['grabarubicaespera'];
			//echo $ubiespera;
			//echo "presione boton   ". 	$_POST['clientepresentesi'];
			//echo "presione boton   ". 	$_POST['clientepresenteno'];
			//echo "Zona de Retiro ".$zretiro;

			if ($_POST['clientepresenteno']=='NO')
				{$gretiro= 'text';
				 $activargr = 'submit';
				 $desacboto = 'hidden';}
			else{$gretiro= 'hidden';
				 $activargr = 'hidden';
				 }
			if ($_POST['clientepresenteno']=='SI'){ $grabar = 'SI';}else {$grabar = 'no';};

			//echo "Datos a grabar"."Usuario".$usuario."FacturaId".$id."Servicios--".$contaserv."--ZonaR".$zretiro."CantidadProd".$conta."Tipo".$tipo."TipoP".$TipoP;
			//die();
			//echo "servicios contados". $contaserv. "Prodcutos contados". conta;

			if ($contaserv==$conta and $conta<>0){$soloserv= 1 ;/*1 la factura solo tiene servicios*/}
			else {$soloserv= 0 ; /*productos y servicios */}
			//echo $soloserv.$conta;

			$_SESSION['usuario']= $usuario;
			$_SESSION['bodega']= $bodega;
			require('../conexion_mssql.php');
			$usuario = $_SESSION['usuario'];
			$bodega = $_SESSION['bodega'];

			//echo "Solo servicio".$soloserv;
?>
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >

					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Preparar Facturas <?php echo substr($nomsuc,10,20);  ?></center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
	</div>
</div>
<hr>
<div >
<?php
	if ($soloserv==0)
		{

			$zona= 'J';
			//echo "Entra aqui". $id.$zona.$zretiro;
			$pdoj = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$resultj = $pdoj->prepare('select zretiro from facturaslistas_zonas where factura=:facturaid and zona=:zona
											and zretiro=:zretiro' );
			$resultj->bindParam(':facturaid',$id,PDO::PARAM_STR);
			$resultj->bindParam(':zona',$zona,PDO::PARAM_STR);
			$resultj->bindParam(':zretiro',$zretiro,PDO::PARAM_STR);
			$resultj->execute();
			$countj = $resultj->rowcount();
			while ($rowj = $resultj->fetch(PDO::FETCH_ASSOC))
				{
					$zonaret=$rowj['zretiro'];
				}
				//echo "ZOna de retiro".$zonaret;
			  //echo "Trae registros". $countj;
				 if ($countj==0 and ($zonaret<>''))//no trae registros y si tiene zona de retiro
					{
						$mensaje=  "Retiro de Jaula no coincide";
						header("Refresh: 2 ; prepararfacturas1.php");
?>
<div id="cuerpo2" align= "center">
		<p style="font-weight: bold" align="center" > <font size= "6" > <?php echo $mensaje ?>  </font><br> </p>
</div>
<?php
					}
				 else
				    {
						if ($TipoP=='MOSTRADOR-GYE' OR $TipoP=='MOSTRADOR-UIO')
							{
?>
								<div  id="cuerpo2" align= "center">

									<p style="font-weight: bold" align="center" > <font size= "6" > Cliente Presente?  </font><br> </p>
									<form name "clientepresentesi" action="prepararfacturas2.php" method="POST" width="75%">
										<td id= "box"> <input name="clientepresentesi" type="hidden" id="clientepresentesi" size = "30" value= "SI"> </td>
										<input name="submit" id="submit" value="SI" type="<?php echo $desacboto ?>">
									</form>
									<form name "clientepresenteno" action="prepararfacturas2.php" method="POST" width="75%">
										<td id= "box"> <input name="clientepresenteno" type="hidden" id="clientepresenteno" size = "30" value= "NO"> </td>
										<input name="submit" id="submit" value="NO" type="<?php echo $desacboto ?>">
									</form>
									<form name "clientepresente" action="prepararfacturas2.php" method="POST" width="75%">
										<td id= "box"> <input name="grabarubicaespera" type="<?php echo $gretiro ?>" id="grabarubicaespera" size = "30" value= ""> </td>
										<input name="Grabar" id="Grabar" value="Grabar" type="<?php echo $activargr ?>">
									</form>

		<?php
								if ($_POST['clientepresentesi']=='SI')
									{
									//	echo "Si entra aqui GRABA la PREPARACION cliente presente " ;
											$ubica='-';
											$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
											$result = $pdo->prepare('Log_facturaslistas_preparar_update  @id=:id , @usuario=:usuario ,@soloserv =:soloserv , @acceso =:acceso,  @ubica =:ubica' );
											$result->bindParam(':id',$id,PDO::PARAM_STR);
											$result->bindParam(':usuario',$usuario,PDO::PARAM_STR);
											$result->bindParam(':soloserv',$soloserv,PDO::PARAM_STR);
											$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
											$result->bindParam(':tipo',$tipo,PDO::PARAM_STR);
											$result->bindParam(':TipoP',$TipoP,PDO::PARAM_STR);
											$result->bindParam(':ubica',$ubica,PDO::PARAM_STR);
											$result->execute();
											$mensaje=  "Factura Completa !!! ";
											header("location: prepararfacturas.php");
									}
								if ($_POST['grabarubicaespera']<>'')
									{
										//	echo "Si entra aqui GRABA la PREPARACION cliente ausente con ubicacicon de retiro". $ubiespera ;
											$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
											$result = $pdo->prepare('Log_facturaslistas_preparar_update  @id=:id , @usuario=:usuario ,@soloserv =:soloserv , @acceso =:acceso,  @ubica =:ubica' );
											$result->bindParam(':id',$id,PDO::PARAM_STR);
											$result->bindParam(':usuario',$usuario,PDO::PARAM_STR);
											$result->bindParam(':soloserv',$soloserv,PDO::PARAM_STR);
											$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
											$result->bindParam(':tipo',$tipo,PDO::PARAM_STR);
											$result->bindParam(':TipoP',$TipoP,PDO::PARAM_STR);
											$result->bindParam(':ubica',$_POST['grabarubicaespera'],PDO::PARAM_STR);
											$result->execute();
											$mensaje=  "Factura Completa !!! ";
											header("location: prepararfacturas.php");
									}

							}
						else
							{
								//echo "Si entra aqui ";
								//echo $id.$usuario.$soloserv.$acceso.$tipo.$TipoP;
								//die();
								$ubica='-';
								$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
								$result = $pdo->prepare('Log_facturaslistas_preparar_update  @id=:id , @usuario=:usuario ,@soloserv =:soloserv , @acceso =:acceso,  @ubica =:ubica' );
								$result->bindParam(':id',$id,PDO::PARAM_STR);
								$result->bindParam(':usuario',$usuario,PDO::PARAM_STR);
								$result->bindParam(':soloserv',$soloserv,PDO::PARAM_STR);
								$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
								$result->bindParam(':tipo',$tipo,PDO::PARAM_STR);
								$result->bindParam(':TipoP',$TipoP,PDO::PARAM_STR);
								$result->bindParam(':ubica',$ubica,PDO::PARAM_STR);
								$result->execute();
								$mensaje=  "Factura Completa !!! ";
								header("location: prepararfacturas.php");
							}
					}
		}
	else
		{
			//echo $id.$usuario.$soloserv.$acceso.$tipo.$TipoP;
			//die();
			$ubica='-';
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('Log_facturaslistas_preparar_update  @id=:id , @usuario=:usuario ,@soloserv =:soloserv , @acceso =:acceso ,  @ubica =:ubica' );
			$result->bindParam(':id',$id,PDO::PARAM_STR);
			$result->bindParam(':usuario',$usuario,PDO::PARAM_STR);
			$result->bindParam(':soloserv',$soloserv,PDO::PARAM_STR);
			$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
			$result->bindParam(':tipo',$tipo,PDO::PARAM_STR);
			$result->bindParam(':TipoP',$TipoP,PDO::PARAM_STR);
			$result->bindParam(':ubica',$ubica,PDO::PARAM_STR);
			$result->execute();
			$mensaje=  "Factura Completa !!! ";
			header("location: prepararfacturas.php");
		}
			$_SESSION['base']= $base;
			$_SESSION['facturaid']= $id;
			$_SESSION['usuario']= $usuario;
			$_SESSION['bodega']= $bodega;
			$_SESSION['nomsuc']=$nomsuc;
			$_SESSION['acceso']=$acceso;
			$_SESSION['zona']=$zretiro;
			//echo "2Usuario:---".$usuario."Datos a grabar ". $base. $bodega;
			//die();
			//header("location: prepararfacturas.php");
		}
	else
		{
			header("location: index.html");
		}
 ?>
 </div>
</body>
</html>
