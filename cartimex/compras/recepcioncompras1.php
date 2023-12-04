<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../css/tablas.css">
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
					$nomsuc = $_SESSION['nomsuc'];
					$usuario1= trim($usuario);
					$fecha = date("Y-d-m", time());
					$hora = date("H:i:s", time());
					$fh = $fecha . " " . $hora;
					if ($_POST['idorden']==''){$idorden= $_SESSION['idorden'];}else {$idorden = $_POST['idorden'];}

					if ($base=='CARTIMEX'){
							require '../../headcarti.php';
					}
					else{
							require '../../headcompu.php';
							$_SESSION['base']= $base;

					}

					require('../../conexion_mssql.php');
					$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result0 = $pdo0->prepare('update com_ordenes set FInicioR=:fecha from com_ordenes
											   where  id =:idorden');
					$result0->bindParam(':fecha',$fh,PDO::PARAM_STR);
					$result0->bindParam(':idorden',$idorden,PDO::PARAM_STR);
					//$result0->execute();


					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('select ID= o.ID, Fecha = CONVERT( char(10),  o.Fecha, 103), o.Detalle , Gerente=  U.Nombre
											from COM_ORDENES O with (nolock)
											INNER JOIN SEG_USUARIOS U with (nolock) ON U.Código = O.CREADOPOR
											where o.id =:idorden');
					$result->bindParam(':idorden',$idorden,PDO::PARAM_STR);
					$result->execute();
					while ($row = $result->fetch(PDO::FETCH_ASSOC))
					{
						$idcompra=$row['ID'];
						$Fecha=$row['Fecha'];
						$Detalle=$row['Detalle'];
						$Gerente=$row['Gerente'];
					}

					$pdodt = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$resultdt = $pdodt->prepare('COM_Ordenes_Select_Detalle_Series @ID=:ID');
					$resultdt->bindParam(':ID',$idorden,PDO::PARAM_STR);
					$resultdt->execute();
					$arreglo  = array();
					$cantprod= 0 ;
					$sering =0 ;
					$x=0;
					while ($rowdt = $resultdt->fetch(PDO::FETCH_ASSOC))
						{
							$arreglo[$x][1]=$rowdt['Código'];
							$arreglo[$x][2]=$rowdt['Nombre'];
							$arreglo[$x][3]= number_format($rowdt['Cantidad'],0,'','');
							$arreglo[$x][4]=$rowdt['registroSeries'];
							$arreglo[$x][5]=$rowdt['ProductoID'];
							$arreglo[$x][6]=$rowdt['Ingresadas'];
							$arreglo[$x][7]=$rowdt['CantRecibida'];
							if ($arreglo[$x][4]=$rowdt['registroSeries']){$cantprod= $cantprod + $arreglo[$x][7]; }
							$sering= $sering + $arreglo[$x][6];

							$x++;
						}

?>
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >

					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Factura </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
	</div>
<hr>
	<div id="cuerpo2"  >
		<div align= "left" >
			<table id= "listado2" align= "center">
				<tr><td id="label2">
				<strong> Id: </strong> <a> <?php echo $idorden ?> </a> </tr>
				<tr><td id="label2">
				<strong> Proveedor: </strong> <a> <?php echo $Detalle ?> </a> </tr>
				<tr><td id="label2">
				<strong> Fecha: </strong> <a> <?php echo $Fecha ?>  </a> </tr>
				<tr><td id="label2">
				<strong> Gerente Marca: </strong> <a> <?php echo $Gerente ?>  </a> </tr>
			</table>
				<br>
		</div>
	</div>
<div  id="cuerpo2" align= "left">
	<div class=\"table-responsive-xs\" align= "center">
			<form name = "formensamble" action="recibirensamble0.php" method="POST" width="75%">
			<table id= "listado2" align= "center"  class="table">
				<tr>
					<th> # </th>
					<th> CODIGO </th>
					<th> ARTICULO </th>
					<th> CANT. </th>
					<th> RECIB. </th>
					<th> DIF.	</th>

<?php
				if ($cantprod == $sering )
					{
						echo "<th><a> SERIES </th>";
					}
				else
					{
						echo "<th> <a href=registraseriescompraslote.php?idorden=".  $idorden."> SERIES </th>";
					}
				echo "</tr>";
			 
				$count = count($arreglo);
				$xrecibir= 0;
				$cantrecibida= 0;
				$y=0;
				$n=1;
				while ( $y <= $count-1 )
				{
					?>

						<tr>
							<td id= "fila2" align=left> <?php echo $n ?></td>
							<td id= "fila2" align=left> <a href="ingresarcantidadrecibida.php?idcompra=<?php echo $idorden ?>&productoid=<?php echo $arreglo[$y][5] ?>" > <?php echo $arreglo[$y][1] ?></td>
							<td id= "fila2" align=left> <?php echo $arreglo[$y][2] ?></td>
							<td id= "fila" align=left> <?php echo $arreglo[$y][3] ?></td>
							<td id= "fila" align=left> <?php echo $arreglo[$y][7] ?></td>
							<td id= "fila" align=left> <?php echo $arreglo[$y][3]- $arreglo[$y][7] ?></td>
<?php
					if ($arreglo[$y][4]==1 and ($arreglo[$y][7] > $arreglo[$y][6]) )
						{

							echo "<td id=fila align=left> <a href=registraseriescompras.php?idorden=".trim($idorden)."&productoid=".$arreglo[$y][5]." > Subir </td>" ;
 						}
						else
							{
								if ($arreglo[$y][4]==1 and ($arreglo[$y][7] == $arreglo[$y][6]) )
									{
										echo "<td id= fila align=left>".$arreglo[$y][6]." </td>";
									}
								else
									{
										echo "<td id= fila align=left> N/A </td>";
									}
							}

					echo "</tr>";
 					$cantrecibida= $cantrecibida+$arreglo[$y][7];
					$xrecibir = $xrecibir + $arreglo[$y][3];
					$y=$y+1;
					$n=$n+1;
				}
				//echo "Cantidad series".$cantprod. "Series ingresadas".$sering."cantidadingresada".$cantrecibida."Por recibir".$xrecibir;
				if (($cantrecibida==$xrecibir ) and ($cantprod== $sering))
					{
?>						</table>
						<br>
						</form>
						<form name = "formcompra" action="recepcioncompras2.php" method="POST" width="75%">
							<table  align= "center"  >
								<tr>
									<br>
									<td id= "label">
									<input id="submit" value=" RECIBIR ORDEN COMPLETA " type="submit">
									<input type="hidden" name="idorden" value="<?php echo $idorden ?>" />
								</tr>
							</table>
						</form>
<?php				}
				else
					{
						if (($cantrecibida >0 ) and ($cantprod== $sering))
							{
		?>						</table>
								<br>
								</form>
								<form name = "formcompra" action="recepcioncompras2.php" method="POST" width="75%">
									<table  align= "center"  >
										<tr>
											<br>
											<td id= "label">
											<input id="submit" value=" RECIBIR ORDEN CON DIFERENCIAS " type="submit">
											<input type="hidden" name="idorden" value="<?php echo $idorden ?>" />
										</tr>
									</table>
								</form>
<?php
							}
					}
					$n=$n-1;
					$_SESSION['usuario']=$usuario1;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo;
					$_SESSION['bodega']= $bodega;
			}
		else
			{
				header("location: index.html");
			}
?>



	</div>
	<br>
</div>
</div>
</body>
</html>
