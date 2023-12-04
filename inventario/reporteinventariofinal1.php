<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script>
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/tablas.css">
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
					$secu=$_SESSION['secu'];
					$usuario1 = $usuario;  
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
							$_SESSION['base']= $base; 
							$Nota= " "; 
					}
					
				   // $codigo= trim($codigo);
					require('../conexion_mssql.php');
					$seccion1 = 'Conteo1';
					$seccion = 'Conteo3';
					$estado= 'Finalizado';
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					
					/*$result = $pdo->prepare('select código, nombre, (stock+Consignado) as stock, diferencia  from INV_CONTEO_DIFERENCIAS d
									    inner join inv_productos p on p.id=d.ProductoID
										inner join INV_CONTEO c on c.ConteoID= d.ConteoID
										left outer  join INV_PD_BODEGA_STOCK st on st.BodegaID=c.BodegaID and st.ProductoID= d.ProductoID
										where d.Seccion =:seccion and d.ConteoID=:secu and c.Estado=:estado
										group by código, nombre,stock, Consignado, Diferencia');*/		  
					$result = $pdo->prepare('Inv_Inventario_Reporte_final_conteo @ConteoId=:secu, @Seccion=:seccion, @Seccion1=:seccion1, @Estado=:estado');
					$result->bindParam(':secu',$secu,PDO::PARAM_STR);
					$result->bindParam(':seccion',$seccion,PDO::PARAM_STR);
					$result->bindParam(':seccion1',$seccion1,PDO::PARAM_STR);	 		
					$result->bindParam(':estado',$estado,PDO::PARAM_STR);
					$result->execute();
					$arreglo = array();
					$x=0; 
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$arreglo[$x][1]=$row['código'];
						$arreglo[$x][2]=$row['nombre'];
						$arreglo[$x][3]=number_format($row['stock']);
						$arreglo[$x][4]=$row['diferencia'];
						$arreglo[$x][5]=$row['conteo'];
						$x++; 
					}
?>		

</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Reporte Diferencia Final  </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
			 
	</div> 
<hr>
<div id="cuerpo2" align= "center">
<div class=\"table-responsive-xl\">	
	<table id= "listado" align= "center">
		<th> Codigo</th>
		<th> Producto </th>
		<th> Stock </th>
		<th> Conteo </th>
		<th> Diferencia </th>
			<?php
				$count = count($arreglo); 
				$y=0;
				while ( $y <= $count-1 ) 
					{
			?>			
		<tr>
			<td id= "fila" > <?php echo $arreglo[$y][1] ?> </td> 
			<td id= "fila" > <?php echo $arreglo[$y][2] ?> </td>
			<td id= "filax" > <?php echo $arreglo[$y][3] ?> </td>
			<td id= "filax" > <?php echo $arreglo[$y][5] ?> </td> 
			<td id= "filax" > <?php echo $arreglo[$y][4] ?> </td> 
		</tr>
		<?php						
				$y=$y+1;
				}		
					//echo "Esto envio"	.$usuario1; 
					$_SESSION['usuario']=$usuario1;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
				}
				else
				{
					header("location: index.html");
				}	
				
?>		
	</table>
</div>	
	<form name = "formpreparar" action="#" method="POST" width="75%">
		<table align ="center" >
			<tr>
					<a href="reporteinventarios.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a> 	
			</td>
			</tr> 
		</table>
	</form>

</div>


</body>	
</html>