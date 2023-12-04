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
					$ordenid= $_GET['ordenid']; 
					
					if ($base=='CARTIMEX'){
							require '../../headcarti.php';  
						}
						else{
								require '../../headcompu.php';
								$_SESSION['base']= $base; 	
						}
					require('../../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('select OCompraID = id, Detalle,  Fecha = CONVERT( char(10), Fecha, 103), CreadoDate 
											from com_ordenes where ID =:ordenid');		 
					$result->bindParam(':ordenid',$ordenid,PDO::PARAM_STR);
					$result->execute();
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
						{
							$OCompraID=$row['OCompraID'];
							$Fecha=$row['Fecha'];
							$Detalle=$row['Detalle'];
						}	
					$pdodt = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$resultdt = $pdodt->prepare('select P.Código, P.Nombre, CDT.Cantidad,
												p.registroSeries, Ingresadas= COUNT(SC.SERIE) , ProductoID= p.id 	
												from COM_ORDENES C
												INNER JOIN COM_ORDENES_DT CDT WITH (NOLOCK) ON CDT.OrdenID= C.ID
												INNER JOIN INV_PRODUCTOS P WITH (NOLOCK) ON P.ID= CDT.ProductoID
												LEFT OUTER JOIN INV_PRODUCTOS_SERIES_COMPRAS SC WITH (NOLOCK) ON C.ID = SC.LiquidacionID and sc.productoid= CDT.ProductoID
												where C.ID =:ID 
												GROUP BY CDT.ProductoID,CDT.Cantidad, P.Código, P.Nombre, p.registroSeries, p.id   order by 1');		 
					$resultdt->bindParam(':ID',$OCompraID,PDO::PARAM_STR);
					$resultdt->execute();
					$arreglo  = array();
					$cantprod= 0 ; 
					$sering =0 ; 
					$x=0; 
					while ($rowdt = $resultdt->fetch(PDO::FETCH_ASSOC)) 
						{ 
							$arreglo[$x][1]=$rowdt['Código'];
							$arreglo[$x][2]=$rowdt['Nombre'];
							$arreglo[$x][3]=number_format($rowdt['Cantidad'], 0, '.', '');
							$arreglo[$x][4]=$rowdt['registroSeries'];
							$arreglo[$x][5]=$rowdt['ProductoID'];
							$arreglo[$x][6]=$rowdt['Ingresadas'];
							if ($arreglo[$x][4]=$rowdt['registroSeries']){$cantprod= $cantprod + $arreglo[$x][3]; }
							$sering= $sering + $arreglo[$x][6];
							$x++; 
						}
						
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Importacion</center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
	</div> 
<hr>	
	<div id="cuerpo2"  >
		<div align= "left" >
			<table id= "listado2" align= "center">
				<tr><td id="label2">
				<strong> Id: </strong> <a> <?php echo $OCompraID ?> </a> </tr>
				<tr><td id="label2">
				<strong> Proveedor: </strong> <a> <?php echo $Detalle ?> </a> </tr>
				<tr><td id="label2">
				<strong> Fecha: </strong> <a> <?php echo $Fecha ?>  </a> </tr>
			</table>
				<br>
		</div>
	</div>	
	<div  id="cuerpo2" align= "left">
			<div class=\"table-responsive-xs\" align= "center">	
				<table id= "listado2" align= "center"  > 
					<tr> 
						<th> # </th>
						<th> CODIGO </th>
						<th> ARTICULO </th>
						<th> CANT. </th>
						<th> SERIE </th>
					</tr>	
	<?php	
					$count = count($arreglo); 
					$y=0;
					$n=1; 
					while ( $y <= $count-1 ) 
					{				
 
						echo "<tr> ";
						echo "<td id= fila2 align=left>".$n."</td> ";
						echo "<td id= fila2 align=left>".$arreglo[$y][1] ."</td> ";
						echo "<td id= fila2 align=left>".$arreglo[$y][2] ."</td> ";
						echo "<td id= fila align=left>".$arreglo[$y][3]."</td>";
						if ($arreglo[$y][4]==1) {
						echo "<td id= fila align=left><strong>Serie</strong></td>";}
						else {
						echo "<td id= fila align=left>N/A</td>";}	
						echo "</tr>";
					$y=$y+1;
					$n=$n+1;
					}
	?>				
					</table>
				<br>
			</div>
			<div class=\"table-responsive-xs\" align= "center">			
				<table align ="center" >
					<td>
						<tr><a href="recepcioncompras.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a> </tr> 
					</td>
				</table>
			</div>	 
	</div>
</div>
<?php			
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
			
			
			
	
	
		
</body>	
</html>	
 

