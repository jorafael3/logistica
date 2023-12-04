<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("secu").focus();
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
					//echo "Entra aqui"; 
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}
date_default_timezone_set('America/Guayaquil');
$fecha = date("yy-m-d", time());
$hora = date("H:i:s", time());
$fh = $fecha . " " . $hora;
					//echo "BOdega". $bodega; 
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
			 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Despachar Facturas  <?php echo substr($nomsuc,10,20); ?> </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
			 
	</div> 
<hr>	
<div id= "cuerpo2" align= "center" >
	 
			
	
		<div class=\"table-responsive-xs\">
			<form name = "formfactura2" action="despacharfacturas0.php" method="POST" width="75%">
			<table align ="center" >
				<tr>
					<th colspan="10">Facturas Por Despachar </th> 
				</tr> 
				<tr> 
					<th id= "fila4"> Bodega </th>
					<th id= "fila4"> Cliente </th>
					<th id= "fila4"> Factura </th>
					<th id= "fila4"> Fecha </th>
					<th id= "fila4">   </th>
					<th id= "fila4"> Vendedor </th>
					<th id= "fila4"> Estado </th>
					<th id= "fila4"> Tipo Pedido  </th>
					<th id= "fila4"> Transporte </th>
					<th> </th>
				</tr>
		<?php

							$_SESSION['usuario']=$usuario;
							$_SESSION['bodega']=$bodega; 
							//echo "bodega".$bodega.$base.$usuario.$acceso;
							include('../conexion_mssql.php');
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];							
							//******Proceso aqui primero todas las facturas pendientes de GUIA para ver cual ha sido Anulada o 
							$paso= 'INGRESADAGUIA'; 	
							$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];
							$result0 = $pdo0->prepare("LOG_FACTURAS_PENDIENTES_DEVUELTAS @BODEGA=:bodega , @acceso=:acceso, @Estado=:estado" );		 
							$result0->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							$result0->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							$result0->bindParam(':estado',$paso,PDO::PARAM_STR);
							$result0->execute();
							$arreglod = array();
							$xd=0; 
							while ($row0 = $result0->fetch(PDO::FETCH_ASSOC)) 
								{
									$arreglod[$xd][2]=$row0['secuencia'];
									//echo "Secuencia". $arreglod[$xd][2]; 
									$xd++; 
								}
							$countd = count($arreglod);  
							$yd=0;
							while ( $yd <= $countd-1 ) 
								{
									$devo= $arreglod[$yd][2];
									$pdod = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
									$resultd = $pdod->prepare("LOG_FACTURAS_DEVUELTA_UPDATE @Secuencia=:secuencia" );		 
									$resultd->bindParam(':secuencia',$devo,PDO::PARAM_STR);
									$resultd->execute();
									
									$countarr = $resultd->rowcount();
									//echo "Trae registro".$countarr; 
									//die();
									$yd=$yd+1;
								} 		
							
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							//new PDO($dsn, $sql_user, $sql_pwd);
							//Select Query
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];
							
							$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_DESPACHO_SELECT @BODEGA=:bodega , @acceso=:acceso" );		 
							$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							//Executes the query
							$result->execute();
							$arreglo = array();
							$x=0; 
							
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
							{
								$arreglo[$x][1]=$row['Sucursal'];
								$arreglo[$x][2]=$row['secuencia'];
								$arreglo[$x][3]=$row['fecha'];
								$arreglo[$x][4]=$row['nombodega'];
								$arreglo[$x][5]=$row['Detalle'];
								$arreglo[$x][6]=$row['Ruc'];
								$arreglo[$x][7]=$row['id'];
								$arreglo[$x][8]=$row['codbodega'];
								$arreglo[$x][9]=$row['Vendedor'];
								$arreglo[$x][10]=$row['Transporte'];
								$arreglo[$x][11]=$row['tpedido'];
								$arreglo[$x][12]=$row['Bloqueada'];
								$idfactura= $row['id'];
								$numfac = $row['secuencia'];
								$x++; 
							}	

							$count = count($arreglo); 
							$y=0;
							while ( $y <= $count-1 ) 
							{
								$numfac= $arreglo[$y][2];
		?>						
								<tr>
									<td id= "fila4"  > <a href ="trakingdesp.php?secu=<?php echo $arreglo[$y][2]?>"><?php echo $arreglo[$y][8] ?> </a></td>
									<td id= "fila4"  > <?php echo $arreglo[$y][5] ?> </td>
									<td id= "fila4"  >  <?php echo $arreglo[$y][2] ?></td> 
									<td id= "fila4"  > <?php echo $arreglo[$y][3] ?> </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][4] ?> </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][9] ?> </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][12] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][11] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][10] ?>  </td>
		<?php 	
								if ($arreglo[$y][12]=='BLOQUEADA'){	
		?>		
									<td id= "box"> <input name="checkbox[]" type="checkbox" disabled > </td>
		<?php
								}
								else {	?>	
									<td id= "box"> <input name="checkbox[]" type="checkbox" value ="<?php echo $arreglo[$y][2] ?>" > </td>									
								</tr>
		<?php
								}
							$y=$y+1;			
							}	
		?>	
		</table>
		<input   id="submit" value=" Despachar Facturas Marcadas " type="submit">
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