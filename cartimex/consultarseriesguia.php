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
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega	=$_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					$secu = $_SESSION['secu'];
					$usuario1= trim($usuario); 
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
							$_SESSION['base']= $base; 
							$Nota = " "; 	
					}
				   // $codigo= trim($codigo);
					require('../conexion_mssql.php');
					 
					//$pdo = new PDO($dsn, $sql_user, $sql_pwd);
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare('LOG_BUSQUEDA_FACTURA @secuencia=:secu');		 
					$result->bindParam(':secu',$secu,PDO::PARAM_STR);
					$result->execute();
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$Codbodega=$row['Codbodega'];
						$Secuencia=$row['Secuencia'];
						$Numero=$row['Numero'];
						$Id=$row['Id'];
						$ClienteId=$row['ClienteId'];
						$Ruc = $row['Ruc'];
						$Nombre=$row['Nombre'];
						$TipoCLi=$row['TipoCLi'];	
						$Fecha=$row['Fecha'];
						$Direccion=$row['Direccion'];	
						$Ciudad=$row['Ciudad'];
						$Telefono=$row['Telefono'];
						$Mail=$row['Email'];
						$Contacto=$row['Contacto'];	
						$Vendedor=$row['Vendedor'];
						$Bloqueado=$row['Bloqueado'];
						$Nota=$row['Nota'];
						$BodegaId=$row['BodegaId'];	
						$Bodeganame=$row['Bodeganame'];
						$Observacion = $row['Observacion'];
						$Medio = $row['Medio'];
						$Empmail = $row['Empmail'];
					}	
					
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Factura <?php echo $nomsuc  ?></center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div> 
<hr>	
<div id="cuerpo2" align= "center" class=\"table-responsive-xs\">
<div align= "center">
	<table border=2 width=100% id= "series">
		<tr>
			<td id="td1" width="15%"> <strong> BODEGA :</strong> </td> <td id="label4"> <a> <?php echo $Codbodega ?> </a></td>
			<td id="label4" colspan= 4>  </td>  
		</tr>	
		<tr>
			<td id="td1"> <strong> Id: </strong> </td> <td id="label4"> <a> <?php echo $Id ?> </a> </td>
			<td id="td1"> <strong> Numero: </strong> </td> <td id="label4"> <a> <?php echo $Numero ?> </a></td>
			<td id="td1" width="10%"> <strong> Fact.#: </strong> </td> <td id="label4" width="20%"> <a> <?php echo $Secuencia ?>  </a></td>  
		</tr>
		<tr>
			<td id="td1"> <strong> Cliente: </strong> </td> <td id="label4"> <a> <?php echo $Nombre ?> </a> </td>
			<td id="td1"> <strong> Tipo: </strong> </td> <td id="label4"> <a> <?php echo $TipoCLi ?>  </a> </td>
			<td id="td1"> <strong> Ciudad: </strong> </td> <td id="label4"> <a> <?php echo $Ciudad ?>  </a> </td>
		</tr>
		<tr>
			<td id="td1"> <strong> Direccion: </strong> </td> <td id="label4"> <a> <?php echo $Direccion ?> </a> </td>
			<td id="td1"> <strong> Telefono: </strong> </td> <td id="label4"> <a> <?php echo $Telefono ?> </a> </td>
			<td id="td1"> <strong> Email: </strong> </td> <td id="label4"> <a> <?php echo $Mail ?>  </a> </td>
		</tr>
		<tr>
			<td id="td1"> <strong> Fecha: </strong> </td> <td id="label4"> <a> <?php echo $Fecha ?> </a> </td>
			<td id="label4" colspan= 4>  </td>  
		</tr>	
    </table>	
</div>
<?php
				$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd); 
				//new PDO($dsn, $sql_user, $sql_pwd);
				//Select Query
				$result2 = $pdo2->prepare('LOG_BUSQUEDA_FACTURA_DT2 @FacturaID = :Id ');		 
				$result2->bindParam(':Id',$Id,PDO::PARAM_STR);
				$result2->execute();
				$arreglo = array(); 
				$x=0; 
				while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) 
					{
						$arreglo[$x][1]=$row2['rmaid'];
						$arreglo[$x][2]=$row2['ProductoId'];
						$arreglo[$x][3]=$row2['CopProducto'];
						$arreglo[$x][4]=$row2['Detalle'];
						$arreglo[$x][5]=$row2['RegistaSerie'];
						$x++; 
					}	
				$count = count($arreglo);
				//if ($count==0){
					
?>

</div>				
<?php				

?>				
<div id="cuerpo2" align= "center">
	<div>
			<table border=2 width=100% id= "series">
				<tr> <th colspan= 5> DETALLE DE PRODUCTOS </th></tr>
				<tr> 
					<th> CODIGO </th>
					<th colspan= 2 > ARTICULO </th>
					</tr>	
				<?php

						$y=0;
						while ( $y <= $count-1 ) 
						{
							
				?>
							<tr> 
								<td id= "fila2" align=left> <?php echo $arreglo[$y][3] ?></td> 
								<td id= "fila2" align=left colspan= 2 > <?php echo $arreglo[$y][4] ?></td> 
				<?php
								//echo $TransId. $arreglo[$y][4]; 
								echo $Id;
								$serie= ''; 
								$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
								$result3 = $pdo3->prepare("select Serie from RMA_FACTURAS_DT dt
															inner join RMA_FACTURAS f with(nolock) on f.id = dt.FacturaID
															where f.FacturaID=:facturaid and productoid=:productoid");		 
								$result3->bindParam(':facturaid',$Id,PDO::PARAM_STR);
								$result3->bindParam(':productoid',$arreglo[$y][2],PDO::PARAM_STR);
								$result3->execute();
								$cont= 0;
								$enter= ' ';
								while ($row3 = $result3->fetch(PDO::FETCH_ASSOC)) 
									{
										
										$serie = $serie ."/".$row3['Serie'].$enter;
									}	
								
				?>			</tr>
							<tr>
								<td></td>
								<td> <strong> SERIES:  <strong></td>
								<td id= "fila2" align=left> <?php echo $serie ?></td> 
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
				  <td>	<a href="detallefactura.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a> </td>
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
					$_SESSION['numfac']= $Secuencia; 	
				}
				else
				{
					header("location: index.html");
				}	
				?>		

</div>
</body>	
</html>