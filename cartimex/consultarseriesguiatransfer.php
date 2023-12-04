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
					$TransId = $_GET['TransId'];
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
					$result = $pdo->prepare('LOG_BUSQUEDA_TRANSFERENCIA @numero=:transfer');		 
					$result->bindParam(':transfer',$TransId,PDO::PARAM_STR);
					$result->execute();
					$arreglotra= array();
					$x=0; 
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
						{
							$arreglotra[$x][1]=$row['Numtransfer'];
							$arreglotra[$x][2]=$row['Idtransfer'];
							$arreglotra[$x][3]=$row['Detalle'];
							$arreglotra[$x][4]=$row['Descodigo'];
							$arreglotra[$x][5]=$row['Destino'];
							$arreglotra[$x][6]=$row['Oricodigo'];
							$arreglotra[$x][7]=$row['Origen'];
							$arreglotra[$x][8]=$row['Fecha'];
							$arreglotra[$x][9]=$row['BodegaId'];
							$arreglotra[$x][10]=$row['Nota'];
							$x++; 
						}	
					$count = count($arreglotra); 
					$y=0;
					while ( $y <= $count-1 ) 
						{
							$Idtransfer= $arreglotra[$y][2];
							$Numtransfer= $Numtransfer. "/". $arreglotra[$y][1];
							$Fecha= $arreglotra[$y][8];
							$Oricodigo = $arreglotra[$y][6];
							$Origen = $arreglotra[$y][7];
							$Descodigo = $arreglotra[$y][4];
							$Destino = $arreglotra[$y][5];
							$Detalle = $arreglotra[$y][3];
							$bodegaid= $arreglotra[$y][9];
							$Nota = $arreglotra[$y][10]."/". $arreglotra[$y][10];
							$y=$y+1;
						}	
					
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Transferencia </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div> 
<hr>	
<div id="cuerpo2" align= "center" class=\"table-responsive-xs\">
<div align= "left">
	<table>
		<tr><td><strong> BODEGA ORIGEN: <a> <?php echo $Oricodigo ?>&nbsp;&nbsp;&nbsp; <?php echo $Origen ?> </strong></td></tr>
		<tr><td id="label2">
		<strong> Transferencia Agrupada: </strong> <a> <?php echo $Idtransfer ?> </a> 
		<strong> Numero: </strong> <a> <?php echo $Numtransfer ?> </a>
		<br></td></tr>
    	<tr><td id="label2">
		<strong> Fecha: </strong> <a> <?php echo $Fecha ?> </a>
		<strong> Destino: </strong> <a> <?php echo $Descodigo ?>   <?php echo $Destino ?></a>
		<br></td></tr>
		<tr><td id="label2">
		<strong> Detalle: </strong> <a> <?php echo $Detalle ?>  </a>
		<br></td></tr>
		<tr><td id="label2">
		<strong> Nota: </strong> <a> <?php echo $Nota ?> </a>
		<br></td></tr>	
    </table>	
</div>
<?php
				$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd); 
				//new PDO($dsn, $sql_user, $sql_pwd);
				//Select Query
				$result2 = $pdo2->prepare('LOG_CONSULTA_TRANSFERENCIA_SERIES @numero=:transfer ');		 
				$result2->bindParam(':transfer',$TransId,PDO::PARAM_STR);
				$result2->execute();
				$arreglo = array(); 
				$x=0; 
				while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) 
					{
						$arreglo[$x][1]=$row2['Código'];
						$arreglo[$x][2]=$row2['Nombre'];
						$arreglo[$x][3]=$row2['Serie'];
						$arreglo[$x][4]=$row2['ID'];
						$x++; 
					}	
				$count = count($arreglo);
					
?>

</div>				
<?php				

?>				
<div id="cuerpo2" align= "center" >
	<div >
			<table border=2 width=100% id= "series" class=\"..\table-responsive-xs\">
				<tr> 
					<th> CODIGO </th>
					<th> ARTICULO </th>
					 
					</tr>	
				<?php

						$y=0;
						while ( $y <= $count-1 ) 
						{
							
				?>
							<tr> 
								<td id= "fila2" align=left> <?php echo $arreglo[$y][1] ?></td> 
								<td id= "fila2" align=left style="width:15px"> <?php echo $arreglo[$y][2] ?></td> 
				<?php
								//echo $TransId. $arreglo[$y][4]; 
								$serie= ''; 
								$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
								$result3 = $pdo3->prepare("select serie from RMA_PRODUCTOS_TEMPORAL where TransferenciaId=:transfer and productoid=:productoid");		 
								$result3->bindParam(':transfer',$TransId,PDO::PARAM_STR);
								$result3->bindParam(':productoid',$arreglo[$y][4],PDO::PARAM_STR);
								$result3->execute();
								$cont= 0;
								$enter= ' ';
								while ($row3 = $result3->fetch(PDO::FETCH_ASSOC)) 
									{
										
										$serie = $serie ."/".$row3['serie'].$enter;
										/*$cont++;
										if ($cont==10 ){
											//dar enter y enviar series a la linea sgte
											//$enter= '.\n'; 
											}
										else
										{
											$enter= ' ';
										}*/
									}	
								
				?>			</tr>
							<tr>
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
		
 
				  
				  
	</div>
</div>
				<?php	
					$usuario= $usuario1; 		
					$_SESSION['usuario']=$usuario;
					$_SESSION['id']=$TransId;
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