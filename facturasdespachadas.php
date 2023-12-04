<meta name="viewport" content="width=device-width, height=device-height">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("codigo").focus();
}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/tablas.css">
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
					if ($_POST['tiporeporte'] == 'd') {$tipofecha = "FechaEntregado";} 
					else {$tipofecha = "fecha";}
					$usuario1 = $usuario; 
					$usuario= $usuario1; 
					if ($base=='CARTIMEX'){
							require 'headcarti.php';  
					}
					else{
							require 'headcompu.php';
					}
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
					<div id = "izq" ></div>
					<div id = "centro" > <a class="titulo"> <center>   Facturas despachadas <?php echo substr($nomsuc,10,20); ?> </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a></div>
	</div> 
<hr>	
<div id= "cuerpo2" align= "center" >
	 
			<div>
				<form name = "formfactura" action="trakingfacturas0.php" method="POST" width="75%">
					<table align ="center" >
					<tr>
						<td id="label" >Factura # </td> 
						<td id= "box"> <input name="secu" type="text" id="secu" size = "30" value= "" > </td>
						<td id= "box"> <input name="bodega" type="text" id="bodega" value= "<?php echo trim($bodega) ?>"> </td>
					</tr>
					<tr>
					  <td id="etiqueta" colspan= "2">   Consultar
					  <input   name="submit" id="submit" value="Grabar" src="assets\img\lupa.png" type="image"> 
					  </td>
					 </tr> 
				  </table>
				</form> 
			</div>		
 </div> 
<div id="cuerpo2" align= "center">
<div>
	<form name = "formproducto" action="facturasdespachadas.php" method="POST" width="75%">
		<table align ="center" >
    	<tr>
    		<td id="label" > Fecha:
			<select name="tiporeporte">
					<option value='d'>Despacho</option>
					<option value='f'>Factura</option>
			</select> </td>
			<td id="label" > Desde:  
			<input type="date" name="desde" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo date("Y-m-d");?>"></td>
			<td id="label" > Hasta:  
			<input type="date" name="hasta" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo date("Y-m-d");?>"></td>
			<td> </td>
    	</tr>
		<tr>
		  <td> </td> 	
		  <td id="etiqueta" > Consultar
      	  <input   name="submit" id="submit" value="Consultar" src="assets\img\lupa.png" type="image"> </td>
		  <td id="etiqueta" >  <a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a></td>
		 </tr> 
      </table>
    </form> 
	
</div>	
		<div class=\"table-responsive-xl\">
			<form action="facturasdespachadasexcel.php" method="post"> 
				<button type="submit" id="export_data" name="exportarCSV" value="Export to excel" class="btn btn-info">Exportar a Excel (CSV)</button>
				<td id= "box"> <input name="acceso" type="hidden" id="acceso" size = "30" value= "<?php echo $acceso ?>"> </td>
				<td id= "box"> <input name="bodega" type="hidden" id="bodega" size = "30" value= "<?php echo $bodega ?>"> </td>
				<td id= "box"> <input name="desde" type="hidden" id="desde" size = "30" value= "<?php echo $desde ?>"> </td>
				<td id= "box"> <input name="hasta" type="hidden" id="hasta" size = "30" value= "<?php echo $hasta ?>"> </td>
				<td id= "box"> <input name="tipofecha" type="hidden" id="tipofecha" size = "30" value= "<?php echo $tipofecha ?>"> </td>
			</form>
			
				<table id = "despacho" align ="center" >
					<tr>
						<th colspan="18">  </th> 
					</tr> 
					<tr> 
						<th id= "fila4" width=  "2%">   </th>
						<th id= "fila4" width=  "5%"> SId </th>
						<th id= "fila4" width=  "20%"> Cliente </th>
						<th id= "fila4" width=  "8%"> F.Orden </th>
						<th id= "fila4" width=  "8%"> F.Aprobacion </th>
						<th id= "fila4" width=  "10%"> Factura </th>
						<th id= "fila4" width=  "8%"> Fecha Factura </th>
						<th id= "fila4" width=  "15%"> Bod.Fact </th>
						<th id= "fila4" width=  "8%"> F.Envio </th>
						<th id= "fila4" width=  "10%"> Bod.Retiro </th>
						<th id= "fila4" width=  "8%"> F.Pago </th>
						<th id= "fila4" width=  "8%"> Prepa. Por </th>
						<th id= "fila4" width=  "8%"> Fecha Prepa </th>
						<th id= "fila4" width=  "8%"> Fecha Verif </th>
						<th id= "fila4" width=  "8%"> Fecha Guia </th>
						<th id= "fila4" width=  "10%"> #Guia </th>
						<th id= "fila4" width=  "15%"> Transporte </th>
						<th id= "fila4" width=  "8%"> Fecha Despacho </th>
						<th id= "fila4" width=  "8%"> Fecha E.Vehiculo </th>
					</tr>
<?php
					if ($desde <> '' )
						{
							//echo "Bodega".$bodega;
							require('conexion_mssql.php');
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result = $pdo->prepare("LOG_FACTURAS_DESPACHADAS_SGL @bodega=:bodega , @acceso=:acceso, @desde=:desde, @hasta=:hasta, @tipofecha=:tipofecha" );		 
							$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							$result->bindParam(':desde', $desde,PDO::PARAM_STR);
							$result->bindParam(':hasta',$hasta,PDO::PARAM_STR);
							$result->bindParam(':tipofecha',$tipofecha,PDO::PARAM_STR);
							$result->execute();
							$arreglodesp = array();
							$x=0; 
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
								{
									$arreglodesp[$x][1]=$row['Sucursal'];
									$arreglodesp[$x][2]=$row['secuencia'];
									$arreglodesp[$x][3]=$row['fecha'];
									$arreglodesp[$x][4]=$row['nombodega'];
									$arreglodesp[$x][5]=$row['Detalle'];
									$arreglodesp[$x][6]=$row['fdesp'];
									$arreglodesp[$x][7]=$row['guia'];
									$arreglodesp[$x][8]=$row['fguia'];
									$arreglodesp[$x][9]=$row['trans'];
									$arreglodesp[$x][10]=$row['fprepa'];
									$arreglodesp[$x][11]=$row['fverif'];
									$arreglodesp[$x][12]=$row['fvehi'];
									$arreglodesp[$x][13]=$row['BodegaFAC'];
									$arreglodesp[$x][14]=$row['cont'];
									$arreglodesp[$x][15]=$row['prepapor'];
									$x++; 
								}	
							//echo '<pre>', print_r($arreglodesp),'</pre>';	
							$count = count($arreglodesp); 
							$y=0;
							while ( $y <= $count-1 ) 
								{
								$numfac= $arreglodesp[$y][2];	
								include("conexion.php");
								$sql1 = "SELECT a.*, p.bodega as bodegaret, a.fecha as forden,
										a.fechafact as faprob,
										a.paymentez as fpayme,
										c.sucursalid as sucursal  FROM covidsales a
										left outer join covidpickup p on p.orden= a.secuencia
										left outer join sisco.covidciudades c on p.bodega= c.almacen
										where a.factura = '$numfac' and a.anulada<> '1'  ";
								$result1 = mysqli_query($con, $sql1);
								$row1 = mysqli_fetch_array($result1);
								//$forden = date("yy-m-d", $row1['fecha']);
								$forden = date ($row1['forden']);
								$formapago = $row1['formapago'];
								$bodegaretiro = $row1['bodegaret'];
								$sucuret = $row1['sucursal'];
								if ($row1['pickup']==1 or $arreglodesp[$y][9]=='Entrega en Tienda'){ $tenvio = 'Pickup';} else { $tenvio = 'Envio';}
								if ($row1['formapago'] == 'Tarjeta' or $row1['formapago'] == 'Tienda' or $row1['formapago'] == 'Directo'
								    or $row1['formapago'] == 'LinkToPay') { $Verpago= $row1['faprob']; }
									else { $Verpago= $row1['fpayme']; }
										
								
		?>					
								<tr>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][14] ?></td> 
									<td id= "fila4"  > <a href ="trakingfacturas0.php?secu=<?php echo $arreglodesp[$y][2]?>&bodegaFAC=<?php echo $arreglodesp[$y][13]?>"> <?php echo $arreglodesp[$y][1] ?></a></td> 
									<td id= "fila4"  > <?php echo $arreglodesp[$y][5] ?></td> 
									<td id= "filax"  > <?php echo $forden ?>  </td>
									<td id= "filax"  > <?php echo $Verpago ?>  </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][2] ?></td> 
									<td id= "filax"  > <?php echo $arreglodesp[$y][3] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][4] ?>  </td>
									<td id= "fila4"  > <?php echo $tenvio ?>  </td>
									<td id= "fila4"  > <?php echo $bodegaretiro ?>  </td>
									<td id= "fila4"  > <?php echo $formapago ?>  </td>
									<td id= "filax"  > <?php echo $arreglodesp[$y][15] ?>  </td>
									<td id= "filax"  > <?php echo $arreglodesp[$y][10] ?>  </td>
									<td id= "filax"  > <?php echo $arreglodesp[$y][11] ?>  </td>
									<td id= "filax"  > <?php echo $arreglodesp[$y][8] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][7] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglodesp[$y][9] ?>  </td>
									<td id= "filax"  > <?php echo $arreglodesp[$y][6] ?>  </td>
									<td id= "filax"  > <?php echo $arreglodesp[$y][12] ?>  </td>
								</tr>
		<?php
								$y=$y+1;						
								
								}
						}
?>					
				</table>
			
		</div>	
</div> 
<?php	
			//echo "Base". $base ; 
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