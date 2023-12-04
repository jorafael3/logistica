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
				//	echo "Usuario".$usuario; 
					if ($base=='CARTIMEX'){
							require '../../headcarti.php';  
					}
					else{
							require '../../headcompu.php';
					}
					
					
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Ensambles por etiquetar  </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
				 
	</div> 
<hr>	
<div id= "cuerpo2" align= "center" >
	
		<div class=\"table-responsive-xs\">
		<form name = "formfactura2" action="etiquetarensambles1.php" method="POST" width="100%">
		<table align ="center" >
				<tr>
					<th colspan="7">Facturas por ensamblar </th> 
				</tr> 
				<tr> 
					<th id= "fila4"> Bodega </th>
					<th id= "fila4"> Tipo </th>
					<th id= "fila4"> Factura </th>
					<th id= "fila4"> Cliente </th>
					<th id= "fila4"> EnsambleId </th>
					<th id= "fila4"> Fecha Doc. </th>
					<th id= "fila4"> Fecha Verificado </th>
				</tr>
		<?php

							$_SESSION['usuario']=$usuario;
							$_SESSION['bodega']=$bodega; 
							//echo "Esto envio".$usuario. $base. $acceso. $bodega;
							require('../../conexion_mssql.php');	
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];
				            $result = $pdo->prepare("select 
													Bo.Código, F.Detalle , F.Secuencia, FechaF = CONVERT( char(10), f.fecha, 103) ,  FechaVerificado,  e.EnsambleId, e.Tipo
													from VEN_FACTURAS F WITH (NOLOCK) 
													inner join VEN_FACTURAS_DT fdt WITH (NOLOCK) on fdt.FacturaID= F.id 
													INNER JOIN INV_BODEGAS BO WITH (NOLOCK) ON BO.ID= FDT.BodegaID
													inner join INV_PRODUCCION_ENSAMBLE E with (nolock) on E.FacturaId= F.id
													where F.Anulado= 0 
													and f.id in (select factura from facturaslistas with (nolock) WHERE tipo = 'VEN-FA' )--and Estado= 'PREPARADA'  )
													AND F.ID IN (SELECT FacturaId FROM INV_PRODUCCION_ENSAMBLE WITH (NOLOCK)  WHERE tipo = 'VEN-FA' and Estado= 'VERIFICADO' )   
													GROUP BY Bo.Código,F.Detalle ,  F.Secuencia, F.Fecha, FechaVerificado, e.EnsambleId, e.Tipo
													ORDER BY E.EnsambleId" );		 
							//$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							//$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							//Executes the query
							$result->execute();
							$arreglo = array();
							$x=0; 
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
							{
								$arreglo[$x][1]=$row['Código'];
								$arreglo[$x][2]=$row['Secuencia'];
								$arreglo[$x][3]=$row['FechaF'];
								$arreglo[$x][4]=$row['Detalle'];
								$arreglo[$x][5]=$row['EnsambleId'];
								$arreglo[$x][6]=$row['FechaVerificado'];
								$arreglo[$x][7]=$row['Tipo'];
								$x++; 
							}	
							$count = count($arreglo); 
							$y=0;
							while ( $y <= $count-1 ) 
							{
		?>						
								<tr>
									<td id= "fila4"  > <?php echo $arreglo[$y][1] ?></td> 
									<td id= "fila4"  > <?php echo $arreglo[$y][7] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][2] ?></td> 	
									<td id= "fila4"  > <?php echo $arreglo[$y][4] ?></td> 
									<td id= "label2" align= "center"> <input name="ensamble" type="submit" id="ensamble" size = "20" value= "<?php echo $arreglo[$y][5] ?>" > </td> 
									<td id= "fila4"  > <?php echo $arreglo[$y][3] ?>  </td>
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