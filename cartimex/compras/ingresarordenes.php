<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("secu").focus();
}
</script>
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
					<div id = "centro" > <a class="titulo"> <center>   ORDENES DE COMPRA POR INGRESAR  </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>

	</div>
<hr>
<div id= "cuerpo2" align= "center" >

		<div class=\"table-responsive-xs\">
			<form name = "formfactura2" action=".php" method="POST" width="100%">
			<table align ="center" >
				<tr>
					<th colspan="6">  </th>
				</tr>
				<tr>
					<th id= "fila4"> Orden #  </th>
					<th id= "fila4"> Proveedor </th>
					<th id= "fila4"> Fecha Orden </th>
					<th id= "fila4"> CreadoPor </th>
					<th id= "fila4"> Fecha Recibida </th>
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
													FechaRecibida = CASE WHEN C.FRECIBIDA IS NULL THEN CONVERT( char(10), Cs.FRecibidoCompra, 103) ELSE CONVERT( char(10), C.FRECIBIDA, 103) END,
                          CASE WHEN C.Consignación= '1' THEN 'SI' ELSE 'NO' END AS Consignacion
													FROM COM_ORDENES C
													INNER JOIN ACR_ACREEDORES A ON A.ID = C.ProveedorID
													INNER JOIN COM_ORDENES_DT DT WITH (NOLOCK) ON DT.OrdenID= C.ID
													left outer JOIN INV_PRODUCTOS_SERIES_COMPRAS CS ON CS.OCompraID= C.ID
													left outer join com_facturas cf with (nolock) on cf.OrdenID= c.ID
													WHERE C.ANULADO= 0
													AND C.FECHA >'20210615' AND ( ESTADO= 'RECIBIDA' or Dropshipping= 1 )and (cs.FCompraID is null or cf.Anulado= 1) and c.facturado= 0
													GROUP BY  A.Nombre,   C.ID, C.Tipo ,   C.Fecha, C.CreadoDate, C.CreadoPor, Cs.FRecibidoCompra, C.FRECIBIDA,C.Consignación
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
								$arreglo[$x][6]=$row['FechaRecibida'];
                $arreglo[$x][7]=$row['Consignacion'];
								$x++;
							}
							$count = count($arreglo);
							$y=0;
							while ( $y <= $count-1 )
							{
		?>
								<tr>
									<td id= "fila4" align= "center"> <?php echo $arreglo[$y][1] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][2] ?></td>
									<td id= "fila4"  > <?php echo $arreglo[$y][3] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][4] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][6] ?>  </td>
                  <td id= "fila4"  > <?php echo $arreglo[$y][7] ?>  </td>
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
