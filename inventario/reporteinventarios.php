<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("detalle").focus();
}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload="setfocus()"> 
<div id= "header" align= "center">
<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$desde = date('Ymd', strtotime($_POST['desde']));
			$hasta = date('Ymd', strtotime($_POST['hasta']));
			if ($base=='CARTIMEX'){
					require '../headcarti.php';  
			}
			else{
					require '../headcompu.php';
			}
			$usuario1= $usuario; 
			
			
?>		
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>   Reporte de Diferencias de Inventario Final   </center> </a></div>
					<div id = "derecha" > <a href="..\menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div>
<hr>	


<div id="cuerpo2" align= "center">
		<div>
				<form name = "formfactura" action="reporteinventariofinal.php" method="POST" width="75%">
					<table align ="center" >
					<tr>
						<td id="label" >InventarioID </td> 
						<td id= "box"> <input name="secu" type="text" id="secu" size = "30" value= "" > </td>
						<td id= "box"> <input name="bodega" type="hidden" id="bodega" value= "<?php echo trim($bodega) ?>"> </td>
					</tr>
					<tr>
					  <td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="..\assets\img\refresh.png"></img></a></td>
					  <td id="label">   Consultar
					  <input   name="submit" id="submit" value="Grabar" src="..\assets\img\lupa.png" type="image"> 
					  <a href="..\menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
					  </td>
					 </tr> 
				  </table>
				</form> 
		</div>
<div>
<div id= "cuerpo2" align= "center" >
<div>
	<form name = "formproducto" action="reporteinventarios.php" method="POST" width="75%">
		<table align ="center" >
    	<tr>
			<td id="label" > Desde:  
			<input type="date" name="desde" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo date("Y-m-d");?>"></td>
			<td id="label" > Hasta:  
			<input type="date" name="hasta" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo date("Y-m-d");?>"></td>
			<td> </td>
    	</tr>
		<tr>
		  	
		  <td id="etiqueta" > Consultar
      	  <input   name="submit" id="submit" value="Consultar" src="..\assets\img\lupa.png" type="image"> </td>
		  <td id="etiqueta" >  <a href="..\menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a></td>
		 </tr> 
      </table>
    </form> 
	
</div>
<div class=\"table-responsive-xl\">
	<form name = "formfactura2"  >
		<table id = "despacho" align ="center" >
			<tr>
				<th colspan="4">  Listado de Inventarios</th> 
			</tr> 
			<tr> 
				<th id= "fila4" > Id </th>
				<th id= "fila4" > Fecha </th>
				<th id= "fila4" > Inventario </th>
				<th id= "fila4" > Bodega </th>
			</tr>
<?php
				require('../conexion_mssql.php');
				$estado = 'Finalizado'; 
				//echo $estado. $desde.$hasta;
				$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result = $pdo->prepare('select *, b.nombre as BodNom from inv_conteo c with(nolock) 
										 inner join inv_bodegas b with(nolock) on c.bodegaid = b.id  
										 where estado = :estado and CONVERT(DATE,fecha) between :desde and :hasta');	
				$result->bindParam(':estado',$estado,PDO::PARAM_STR);						
				$result->bindParam(':desde',$desde,PDO::PARAM_STR);	
				$result->bindParam(':hasta',$hasta,PDO::PARAM_STR);	
				$result->execute();
				$arreglo = array();
				$x=0; 
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$arreglo[$x][1]=$row['Fecha'];
						$arreglo[$x][2]=$row['Detalle'];
						$arreglo[$x][3]=$row['BodNom'];
						$arreglo[$x][4]=$row['ConteoID'];
						$x++; 
					}	
				//echo "Aqui".'<pre>', print_r($arreglo),'</pre>';	
				$count = count($arreglo); 
				$y=0;
				while ( $y <= $count-1 ) 
					{	
?>	
				<tr>
					<td id= "fila4"  > <?php echo $arreglo[$y][4] ?></td> 
					<td id= "fila4"  > <?php echo $arreglo[$y][1] ?></td> 
					<td id= "fila4"  > <a href ="reporteinventariofinal.php?secu=<?php echo $arreglo[$y][4]?>"> <?php echo $arreglo[$y][2] ?></a>  </td>
					<td id= "fila4"  > <?php echo $arreglo[$y][3] ?>  </td>
				</tr>
<?php
						$y=$y+1;										
					}
?>			
		</table>
	</form>	
</div>

									
</div>	
</div> 
<?php
		$_SESSION['base']= $base;
		$_SESSION['id']= $id;
		$_SESSION['bodega']= $bodega; 
		$_SESSION['usuario']= $usuario1;
		}
		else
		{
			header("location: index.html");
		}
 ?>
</div>	 
</body>