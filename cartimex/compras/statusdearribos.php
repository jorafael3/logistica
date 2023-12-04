<meta name="viewport" content="width=device-width, height=device-height">
<!DOCTYPE html>
<html>
<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">


 
<strong>Ultima Actualizacion:</strong>
<script>
miFecha = new Date()
document.write(miFecha.getHours() + ":" + miFecha.getMinutes())
</script>

 
<body onload= "setfocus()"> 
<div id= "header" align= "center">
<?php 
		    session_start();	
			 
			
		    if (isset($_SESSION['loggedin']))
				{
					header("refresh:5;url=statusdearribos.php?desde=".$desde."&hasta=".$hasta);
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					
					if ($_GET ['desde']=='')
						{   if ($_POST['desde']=='') 
								{  $desde1 = new DateTime();$desde1-> modify('first day of this month'); $desde= $desde1->format('Y-m-d'); 
								} 
							else
								{ $desde = $_POST['desde'];}
						}
					else
						{$desde = $_GET['desde'];}
						
					if ($_GET ['hasta']=='')
						{
							if ($_POST['hasta']=='') 
								{  $hasta1 = new DateTime();$hasta1-> modify('last day of this month');$hasta= $hasta1->format('Y-m-d'); 
								} 
							else 
								{$hasta = $_POST['hasta'];}
						}
					else	
						{$hasta = $_GET['hasta'];}
					 
					header("refresh:300;url=statusdearribos.php?desde=".$desde."&hasta=".$hasta);
					 
					$usuario1 = $usuario; 
					$usuario= $usuario1; 
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
					<div id = "izq" ></div>
					<div id = "centro" > <a class="titulo"> <center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Status de Arribos  </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a></div>
	</div> 
<hr>	

<div id="cuerpo2" align= "center">
<div>
	<form name = "formproducto" action="statusdearribos.php" method="POST" width="75%">
		<table align ="center" >
    	<tr>
			<td id="label" > Desde:  
			<input type="date" name="desde" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo $desde;?>"></td>
			<td id="label" > Hasta:  
			<input type="date" name="hasta" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo $hasta;?>"></td>
			<td> </td>
    	</tr>
		<tr>	
		  <td id="etiqueta" > Consultar
      	  <input   name="submit" id="submit" value="Consultar" src="..\..\assets\img\lupa.png" type="image"> </td>
		  <td id="etiqueta" >  <a href="../../menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a></td>
		 </tr> 
      </table>
    </form> 
	
	
</div>	
		<div class=\"table-responsive-xl\">
				<table id = "despacho" align ="center" >
					<tr>
						<th colspan="15">  </th> 
					</tr> 
					<tr> 
						<th id= "fila4" width=  "1%">   </th>
						<th id= "fila4" width=  "2%"> Tipo </th>
						<th id= "fila4" width=  "2%"> OrdenID/LiqID </th>
						<th id= "fila4" width=  "8%">Proveedor/Dau </th>
						<th id= "fila4" width=  "5%"> Referencia/DUI</th>
						<th id= "fila4" width=  "2%"> FactID/LiqID </th>
						<th id= "fila4" width=  "4%"> Estado </th>
						<th id= "fila4" width=  "2%"> F.Orden</th>
						<th id= "fila4" width=  "2%"> F.Embarque</th>
						<th id= "fila4" width=  "2%"> F.Liquidado</th>
						<th id= "fila4" width=  "2%"> F.Facturado </th>
						<th id= "fila4" width=  "2%"> B. Ingreso </th>
						<th id= "fila4" width=  "4%"> Recibido Por  </th>
						<th id= "fila4" width=  "8%"> Inicio Recepcion </th>
						<th id= "fila4" width=  "8%"> Fin Recepcion </th>
					</tr>
<?php
					if ($desde <> '' )
						{
							//echo "Bodega".$bodega;
							require('../../conexion_mssql.php');
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result = $pdo->prepare("LOG_Informe_ComprasImportaciones @desde=:desde,@hasta=:hasta" );		 
							$result->bindParam(':desde', $desde,PDO::PARAM_STR);
							$result->bindParam(':hasta',$hasta,PDO::PARAM_STR);
							//$result->bindParam(':tipofecha',$tipofecha,PDO::PARAM_STR);
							$result->execute();
							$arreglodesp = array();
							$x=0; 
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
								{
									$arreglodesp[$x][1]=$row['FOrden'];
									$arreglodesp[$x][2]=$row['OrdenID'];
									$arreglodesp[$x][3]=$row['Detalle'];
									$arreglodesp[$x][4]=$row['Bodega'];
									$arreglodesp[$x][5]=$row['TIPO'];
									$arreglodesp[$x][6]=$row['FacturaID'];
									$arreglodesp[$x][7]=$row['Referencia'];
									$arreglodesp[$x][8]=$row['FFactura'];
									$arreglodesp[$x][9]=$row['RecibidoPor'];
									$arreglodesp[$x][10]=$row['FRecibida'];
									$arreglodesp[$x][11]=$row['Inicio'];
									$arreglodesp[$x][12]=$row['FEmbarque'];
									$arreglodesp[$x][13]=$row['FLlegada'];
									$arreglodesp[$x][14]=$row['Estado'];
									$x++; 
								}	
							//echo '<pre>', print_r($arreglodesp),'</pre>';	
							$count = count($arreglodesp); 
							$y=0;
							$n = 1; 
							while ( $y <= $count-1 ) 
								{
								$numfac= $arreglodesp[$y][2];
								 
								if ((rtrim(ltrim($arreglodesp[$y][14]))=="PENDIENTE")){$colorf="PINK";} 
		?>					
								<tr bgcolor="<?php echo $colorf ?>">
									<td id= "fila4"  > <?php echo $n ?>  </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][5] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][2] ?> </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][3] ?></td> 
									<td id= "fila4"  > <?php echo $arreglodesp[$y][7] ?>  </td>
									<td id= "fila4"  > <a href ="trakingcompras0.php?secu=<?php echo $arreglodesp[$y][6]?>&Tipo=<?php echo $arreglodesp[$y][5]?>"> <?php echo $arreglodesp[$y][6] ?> </a>  </td>
									<td id= "filax"  > <?php echo $arreglodesp[$y][14] ?></td> 
									<td id= "fila4"  > <?php echo $arreglodesp[$y][1] ?></td> 
									<td id= "fila4"  > <?php echo $arreglodesp[$y][12] ?></td> 
									<td id= "fila4"  > <?php echo $arreglodesp[$y][13] ?></td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][8] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][4] ?></td> 
									<td id= "fila4"  > <?php echo $arreglodesp[$y][9] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][11] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][10] ?>  </td>
								</tr>
		<?php
								$y=$y+1;						
								$colorf= ""; 	
								$n=$n+1;	
								}
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
			$_SESSION['status']="statusdearribos.php"; 
			$_SESSION['url']= "statusdearribos.php?desde=".$desde."&hasta=".$hasta;
			
			}
			else
			{
				header("location: index.html");
			}	
	
?>	
</div>		
</body>	