<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function visible() {
var div1 = document.getElementById('Preparando');
	div1.style.display= 'none';
}
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
					$usuario=$_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega	=$_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					$transfer = $_SESSION['transfer'];
					$usuario1= trim($usuario); 
					//echo $usuario1.$transfer; 
					
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
							$_SESSION['base']= $base; 
					}
				    // $codigo= trim($codigo);
					require('../conexion_mssql.php');
					 
					//$pdo = new PDO($dsn, $sql_user, $sql_pwd);
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare('LOG_BUSQUEDA_TRANSFERENCIA_SERIES @numero=:transfer');		 
					$result->bindParam(':transfer',$transfer,PDO::PARAM_STR);
					$result->execute();
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
						{
							$Numtransfer=$transfer;
							$Idtransfer=$row['Idtransfer'];
							$Detalle=$row['Detalle'];
							$Descodigo=$row['Descodigo'];
							$Destino=$row['Destino'];
							$Oricodigo=$row['Oricodigo'];
							$Origen=$row['Origen'];
							$Fecha=$row['Fecha'];
						}	
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Transferencias </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div> 
<hr>	
<div id="cuerpo2" align= "center" class=\"table-responsive-xs\">
<div align= "left ">
	<table>
		<tr><td><strong> BODEGA ORIGEN: <a> <?php echo $Oricodigo ?>&nbsp;&nbsp;&nbsp; <?php echo $Origen ?> </strong></td></tr>
		<tr><td id="label2">
		<strong> AgruparId: </strong> <a> <?php echo $Idtransfer ?> </a> 
		<strong> Transferencia: </strong> <a> <?php echo $Numtransfer ?> </a>
		<br></td></tr>
    	<tr><td id="label2">
		<strong> Fecha: </strong> <a> <?php echo $Fecha ?> </a>
		<strong> Destino: </strong> <a> <?php echo $Descodigo ?>   <?php echo $Destino ?></a>
		<br></td></tr>
		<tr><td id="label2">
		<strong> Detalle: </strong> <a> <?php echo $Detalle ?>  </a>
		<br></td></tr>
		<tr><td id="label2">
		<strong> Nota: </strong> <a> <?php echo $bodegaid ?> </a>
		<br></td></tr>	
    </table>
</div>
<?php
				$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd); 
				//new PDO($dsn, $sql_user, $sql_pwd);
				//Select Query
				$result2 = $pdo2->prepare('LOG_BUSQUEDA_TRANSFERENCIA_DT @TransferenciaID = :transfer ');		 
				$result2->bindParam(':transfer',$Numtransfer,PDO::PARAM_STR);
				$result2->execute();
				$arreglo = array(); 
				$x=0; 
				while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) 
					{
					//	echo "SI entra ";
						$arreglo[$x][1]=$row2['ProductoId'];
						$arreglo[$x][2]=$row2['CopProducto'];
						$arreglo[$x][3]=$row2['Detalle'];
						$arreglo[$x][4]=$row2['cantidad'];
						//$arreglo[$x][5]=$row2['serie'];
						$x++; 
					}	
				$count = count($arreglo);
				//if ($count==0){
				//echo '<pre>', print_r($arreglo),'</pre>';	
?>

</div>				
<?php				

?>				
<div id="cuerpo2" align= "center">
	<div>
			
			<table border=2 width=100% id= "series">
				<tr> 
					<th width=10% > Codigo </th>
					<th width=60%> Producto </th>
					<th width=10%> Cantidad </th>
					</tr>	
				<?php

						$y=0;
						while ( $y <= $count-1 ) 
						{
							
				?>
							<tr> 
								<td id= "fila2" align=left> <?php echo $arreglo[$y][2] ?></td> 
								<td id= "fila2" align=left> <?php echo $arreglo[$y][3] ?></td>
								<td id= "fila3" > <a href ="modificarseriestransfer2.php?produc=<?php echo $arreglo[$y][1]?>"><?php echo number_format(  $arreglo[$y][4], 0, ",", ".") ?> </a></td>
								
							</tr>
						
				<?php
							$y=$y+1;			
						}
				?>
			</table>
			
	</div>	
	<div>
		<form name = "formpreparar" action="#" method="POST" width="75%">
			<table align ="center" >
				<tr>
				  <td>	<a href="modificarseriestransfer.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a> </td>
				</tr> 
		  </table>
		</form> 
 
				  
				  
	</div>
</div>
				<?php	
					$usuario= $usuario1; 		
					$_SESSION['usuario']=$usuario;
					$_SESSION['id']=$Id;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo;
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
</html>