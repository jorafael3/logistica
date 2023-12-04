<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>

<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../css/tablas.css">
<body onload= "setfocus()">
<div id= "header" align= "center">
<?php
session_start();
		    if (isset($_SESSION['loggedin']))
				{
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					if ($base=='CARTIMEX'){
							require '../../headcarti.php';
					}
					else{
							require '../../headcompu.php';
					}

					//$clave = md5("webmaster2021");
					//echo $clave;
?>
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >

					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   ORDENES DE COMPRA LOCALES  </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>

	</div>
<hr>
<div id= "cuerpo2" align= "center" >

		<div class=\"table-responsive-xs\">
			<form name = "formfactura2" action="recepcioncompras0.php" method="POST" width="100%">
			<table align ="center" >
				<tr>
					<th colspan="6">  </th>
				</tr>
				<tr>
					<th id= "fila4">   #  </th>
					<th id= "fila4"> Orden </th>
					<th id= "fila4"> Proveedor </th>
					<th id= "fila4"> Fecha </th>
					<th id= "fila4"> Product Manager </th>
					<th id= "fila4"> Consignacion </th>
				</tr>
		<?php

							$_SESSION['usuario']=$usuario;
							$_SESSION['bodega']=$bodega;
							//echo "Esto envio".$usuario. $base. $acceso. $bodega;
							require('../../conexion_mssql.php');
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];
				            $result = $pdo->prepare("SELECT Detalle= A.Nombre,  C.ID, C.Tipo ,   Fecha = CONVERT( char(10), C.Fecha, 103),  C.CreadoDate, c.CreadoPor,
													CASE WHEN C.Consignación= '1' THEN 'SI' ELSE 'NO' END AS Consignacion
													FROM COM_ORDENES C
													INNER JOIN ACR_ACREEDORES A ON A.ID = C.ProveedorID
													INNER JOIN COM_ORDENES_DT DT WITH (NOLOCK) ON DT.OrdenID= C.ID
													WHERE C.ANULADO= 0
													AND C.FECHA >'20210722' AND ESTADO= ' ' and Dropshipping= 0
													GROUP BY  A.Nombre,   C.ID, C.Tipo ,   C.Fecha, C.CreadoDate, c.CreadoPor,C.Consignación
													ORDER BY C.CreadoDate " );
							$result->execute();
							$arreglo = array();
							$x=0;
							while ($row = $result->fetch(PDO::FETCH_ASSOC))
							{
								$arreglo[$x][1]=$row['ID'];
								$arreglo[$x][2]=$row['Detalle'];
								$arreglo[$x][3]=$row['Fecha'];
								$arreglo[$x][4]=$row['CreadoPor'];
								$arreglo[$x][5]=$row['ID'];
								$arreglo[$x][6]=$row['Consignacion'];
								$x++;
							}
							$count = count($arreglo);
							$y=0;
							while ( $y <= $count-1 )
							{
		?>
								<tr>
									<td id= "label2" > <a href ="recepcioncomprasconsulta.php?ordenid=<?php echo $arreglo[$y][1]?>"><?php echo $arreglo[$y][1] ?> </td>
		<?php
							if ($acceso<>"8") {
		?>
									<td id= "label2" align= "center"> <input name="idorden" type="submit" id="idorden" size = "20" value= "<?php echo $arreglo[$y][1] ?>" > </td>
		<?php
							}
								else {
		?>
									<td id= "label2" align= "center"> <input name="idorden" type="text" id="idorden" size = "20" value= "<?php echo $arreglo[$y][1] ?>" > </td>
	<?php
								}
		?>
									<td id= "fila4"  > <?php echo $arreglo[$y][2] ?></td>
									<td id= "fila4"  > <?php echo $arreglo[$y][3] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][4] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][6] ?>  </td>
									<td id= "box"> <input name="bodega" type="hidden" id="bodega" size = "30" value= "<?php echo $bodega ?>"> </td>

								</tr>
		<?php
							$y=$y+1;
							}
		?>
		</table>
		</form>
		</div>
 </div>

<?php
			$_SESSION['usuario']=$usuario;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['bodega']=$bodega;
			$_SESSION['nomsuc']=$nomsuc;
			}
			else
			{
				header("location: index.html");
			}

?>
</div>
</body>
