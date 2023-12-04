<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("secu").focus();
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
					if ($base=='CARTIMEX'){
							require 'headcarti.php';  
					}
					else{
							require 'headcompu.php';
					}
					
					//echo "Usuario". $usuario; 
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Revertir Preparacion <?php echo substr($nomsuc,10,20); ?> </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
				 
	</div> 
<hr>	
<div id= "cuerpo2" align= "center" >
		<div class=\"table-responsive-xs\">
			<form name = "formfactura2" action="revertirpreparacion0.php" method="POST" width="100%">
			<table align ="center" >
				<tr> 
						<td> </td>
						<td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td> 
						<td> </td>
				</tr>		
				<tr>
					<th colspan="6">Facturas que se estan preparando </th> 
				</tr> 
				<tr> 
					<th id= "fila4"> Factura </th>
					<th id= "fila4"> Cliente </th>
					<th id= "fila4"> Fecha Factura</th>
					<th id= "fila4"> Preparando </th>
					<th id= "fila4"> Desde </th>
					<th id= "fila4"> Borrar  </th>
				</tr>
		<?php

							$_SESSION['usuario']=$usuario;
							$_SESSION['bodega']=$bodega; 
							//echo "bodega".$bodega.$base.$usuario.$acceso;
							require('conexion_mssql.php');	
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							//new PDO($dsn, $sql_user, $sql_pwd);
							//Select Query
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];
							$result = $pdo->prepare("select Secuencia, Detalle,Fecha = CONVERT( char(10), f.fecha, 103), preparando , id= f.id ,  fechaPre= fl.fechaPreparando from FACTURASLISTAS fl 
													inner join ven_facturas f with (nolock) on f.id= Fl.Factura
													where PREPARADOPOR= '.' and fl.tipo = 'VEN-FA' 
													AND fl.anulado= 0 and  (fl.ESTADO = 'PREPARANDO' or fl.estado is null ) and fechaPreparando > '20210318' " );		 
							$result->execute();
							$arreglo = array();
							$x=0; 
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
							{
								$arreglo[$x][1]=$row['Secuencia'];
								$arreglo[$x][2]=$row['Detalle'];
								$arreglo[$x][3]=$row['Fecha'];
								$arreglo[$x][4]=$row['preparando'];
								$arreglo[$x][5]=$row['id'];
								$arreglo[$x][6]=$row['fechaPre'];
								$x++; 
							}	
							$count = count($arreglo); 
							$y=0;
							while ( $y <= $count-1 ) 
							{
		?>						
								<tr>
									<td id= "fila4"  > <?php echo $arreglo[$y][1] ?></td> 
									<td id= "fila4"  > <?php echo $arreglo[$y][2] ?></td> 
									<td id= "fila4"  > <?php echo $arreglo[$y][3] ?></td>
									<td id= "fila4"  > <?php echo $arreglo[$y][4] ?></td>
									<td id= "fila4"  > <?php echo $arreglo[$y][6] ?></td>
									<td id= "label2" align= "center"> <input name="secu" type="submit" id="secu" size = "20" value= "<?php echo $arreglo[$y][5] ?>" ></td> 
									<td id= "box"> <input name="bodega" type="hidden" id="bodega" size = "30" value= "<?php echo $bodega ?>"> </td>	
									
								</tr>
		<?php
							$y=$y+1;			
							}	
		?>	
				</table>
			</form>
		</div>	
		<a align= "center"> <strong>**Esta pantalla es para reversar el proceso cuando se "desaparece la factura sin FINALIZAR PREPARACION" </strong><br>
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