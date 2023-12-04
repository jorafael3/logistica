<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("codigo").focus();
}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/tablas.css">
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
					$desde = $_POST['desde'];
					$hasta = $_POST['hasta'];
					$usuario1 = $usuario; 
					$usuario= $usuario1; 
					//echo "datos" .$bodega.$acceso.$desde. $hasta; 
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
					<div id = "izq" ></div>
					<div id = "centro" > <a class="titulo"> <center>   Facturas Pendientes </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a></div>
	</div> 
<hr>	 
<div id="cuerpo2" align= "center">
		<div class=\"table-responsive-xs\">
			<form action="facturaspendientesexcel.php" method="post">
				 
				<button type="submit" id="export_data" name="exportarCSV" value="Export to excel" class="btn btn-info">Exportar a Excel (CSV)</button>
				<td id= "box"> <input name="acceso" type="hidden" id="acceso" size = "30" value= "<?php echo $acceso ?>"> </td>
				<td id= "box"> <input name="bodega" type="hidden" id="bodega" size = "30" value= "<?php echo $bodega ?>"> </td>
			</form>
				<table align ="center" >
					<tr>
						<th colspan="8">  </th> 
					</tr> 
					<tr> 
						<th id= "fila4"> Bodega </th>
						<th id= "fila4"> Cliente </th>
						<th id= "fila4"> Factura </th>
						<th id= "fila4"> Vendedor </th>
						<th id= "fila4"> Fecha Factura </th>
						<th id= "fila4"> Estado SGL</th>
						<th id= "fila4"> Tipo Pedido  </th>
						<th id= "fila4"> Estado </th>
					</tr>
<?php
				
							//echo "Bodega".$bodega;
							require('../conexion_mssql.php');
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_REPORT @bodega=:bodega , @acceso=:acceso" );		 
							$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							$result->execute();
							$arreglodesp = array();
							$x=0; 
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
								{
									$arreglodesp[$x][1]=$row['bodegsuc'];
									$arreglodesp[$x][2]=$row['secuencia'];
									$arreglodesp[$x][7]=$row['vendedor'];
									$arreglodesp[$x][3]=$row['fecha'];
									$arreglodesp[$x][4]=$row['TipoP'];
									$arreglodesp[$x][5]=$row['Detalle'];
									$arreglodesp[$x][6]=$row['ESTADO'];
									$arreglodesp[$x][8]=$row['Bloqueada'];
									$x++; 
								}	
							//echo '<pre>', print_r($arreglodesp),'</pre>';	
							$count = count($arreglodesp); 
							$y=0;
							while ( $y <= $count-1 ) 
								{
		?>					
								<tr>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][1] ?> </td> 
									<td id= "fila4"  > <?php echo $arreglodesp[$y][5] ?> </td> 
									<td id= "fila4"  > <?php echo $arreglodesp[$y][2] ?> </td> 
									<td id= "fila4"  > <?php echo $arreglodesp[$y][7] ?> </td> 
									<td id= "filax"  > <?php echo $arreglodesp[$y][3] ?> </td>
									<td id= "filax"  > <?php echo $arreglodesp[$y][6] ?> </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][4] ?> </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][8] ?> </td>
								</tr>
		<?php
								$y=$y+1;						
								
								}
						
?>					
				</table>
			 
		</div>	
</div> 
<?php	
			
			$_SESSION['usuario']=$usuario1;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['nomsuc']=$nomsuc; 
			}
			else
			{
				header("location: index.html");
			}	
	
?>	
</div>		
</body>	