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
					$transfer= $_SESSION['transfer'];
					$bodega	=$_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					$usuario1= trim($usuario); 
					 
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
							$_SESSION['base']= $base; 
								
					}
					if ($_POST['transfer']<>'')
						{$gretiro= 'text';
						 $activargr = 'submit';}
					else{$gretiro= 'hidden';
						 $activargr = 'hidden';} 
					
					require('../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('LOG_BUSQUEDA_TRANSFERENCIA @numero=:transfer');		 
					$result->bindParam(':transfer',$transfer,PDO::PARAM_STR);
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
							$Nota= $Nota .$arreglotra[$y][10]. "/" ;
							$y=$y+1;
						}	
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Transferencia <?php echo substr($nomsuc,10,20);  ?></center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
	</div> 
<hr>	
<div id="cuerpo2" align= "center">
<div align= "left">
	<table>
		<tr><td><strong> BODEGA ORIGEN: <a> <?php echo $Oricodigo ?>&nbsp;&nbsp;&nbsp; <?php echo $Origen ?> </strong></td></tr>
		<tr><td id="label2">
		<strong> T. Agrupada Id: </strong> <a> <?php echo $Idtransfer ?> </a> 
		<strong> Transferencias: </strong> <a> <?php echo $Numtransfer ?> </a>
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
			$tipo = 'INV-TR';$Zona= 'J'; $TipoP= 'T';   
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result1 = $pdo1->prepare('Log_facturaslistas_preparando_insert @id=:transfer , @usuario=:usuario, @tipo=:tipo, @TipoP=:TipoP, @Zona=:acceso');
			$result1->bindParam(':transfer',$transfer,PDO::PARAM_STR);
			$result1->bindParam(':usuario',$usuario1,PDO::PARAM_STR);	
			$result1->bindParam(':tipo',$tipo,PDO::PARAM_STR);	
			$result1->bindParam(':TipoP',$TipoP,PDO::PARAM_STR);
			$result1->bindParam(':acceso',$acceso,PDO::PARAM_STR);
			$result1->execute();
			
			$pdo6 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result6 = $pdo6->prepare('Log_facturaslistas_preparandoTR_select @id=:transfer, @tipo=:tipo, @zona=:zona');
			$result6->bindParam(':transfer',$transfer,PDO::PARAM_STR);
			$result6->bindParam(':tipo',$tipo,PDO::PARAM_STR);			
			$result6->bindParam(':zona',$Zona,PDO::PARAM_STR);			
			$result6->execute();
			
			while ($row6 = $result6->fetch(PDO::FETCH_ASSOC)) 
					{	
						$Preparando=$row6['preparando'];
						$Fechapre=$row6['fechaPreparando'];
						$ESTADO=$row6['ESTADO'];
						$zona = $row6['zona'];
					}
					
			$pdo4 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result4 = $pdo4->prepare('SELECT ESTADO,PREPARADOPOR, FECHAYHORA  FROM  facturaslistas_zonas 
									   WHERE TIPO =:tipo AND FACTURA=:transfer and zona=:zona');		 
			$result4->bindParam(':transfer',$transfer,PDO::PARAM_STR);
			$result4->bindParam(':tipo',$tipo,PDO::PARAM_STR);
			$result4->bindParam(':zona',$Zona,PDO::PARAM_STR);
			$result4->execute();
			while ($row4 = $result4->fetch(PDO::FETCH_ASSOC)) 
					{
						$PreparadoPor=$row4['PREPARADOPOR'];	
						$Fechaprepa = $row4['FECHAYHORA'];
						$ESTADO = $row4['ESTADO'];
					}	
			if ($ESTADO=='PREPARANDO') 
			{ 	
				$lprepa= $Preparando;
				$lfecha= $Fechapre;
				$ltitulo= "Preparando";
				$activar= "submit";
			}				
			else
			{
				$lprepa= $PreparadoPor;
				$lfecha= $Fechaprepa;
				$ltitulo= "Preparado";
				$activar= "hidden";
			}	
				$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd); 
				$result2 = $pdo2->prepare('LOG_PREPARAR_TRANSFERENCIAS_JAULA @TransferenciaID = :transfer  ');		 
				$result2->bindParam(':transfer',$transfer,PDO::PARAM_STR);
				$result2->execute();
				$arreglo = array(); 
				 
				$x=0; 
				while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) 
					{
						$arreglo[$x][1]=$row2['DTID'];
						$arreglo[$x][2]=$row2['ProductoId'];
						$arreglo[$x][3]=$row2['CopProducto'];
						$arreglo[$x][4]=$row2['Cantidad'];
						$arreglo[$x][5]=$row2['Detalle'];
						$arreglo[$x][6]=$row2['RegistaSerie'];
						$arreglo[$x][7]=$row2['Clase'];
						$arreglo[$x][9]=$row2['zonau'];
						if (($arreglo[$x][7])=='02')
							{$contaserv++; }
						$x++; 
					}	
				$count = count($arreglo);
?>

	<div align= "left" id = "Preparado" class=\"table-responsive-xs\">
		<table>
			<tr><td id="label3">
			<strong> <?php echo $ltitulo ?> </strong> <a> <?php echo $lprepa ?> </a> 
			<strong> Fecha: </strong> <a> <?php echo $lfecha ?> </a> 
			<br></td></tr>
		</table>
	</div>	
</div>				
<?php				

?>				
<div id="cuerpo2" align= "center">
	<div>
			<table id= "listado2" align ="center" > 
				<tr> 
					<th> UBICACION1 </th>
					<th> UBICACION2 </th>
					<th> CODIGO </th>
					<th> ARTICULO </th>
					<th> CANT. </th>
				</tr>	
				<?php

						$y=0;
						while ( $y <= $count-1 ) 
						{
							$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result3 = $pdo3->prepare('LOG_BUSCAR_MEJOR_UBICACION @ProductoId =:ProdId , @bodega =:Bodega');		 
							$result3->bindParam(':ProdId',$arreglo[$y][2],PDO::PARAM_STR);
							$result3->bindParam(':Bodega',$bodegaid,PDO::PARAM_STR);
							$result3->execute();
							
							while ($row3 = $result3->fetch(PDO::FETCH_ASSOC)) 
								{
									$ubi1=$row3['ubi1'];
									$ubi2=$row3['ubi2'];
								}
							$countubi = $result3->rowcount();	
							if ($countubi==0)
								{ 	$ubi1='';
									$ubi2='';
								}
				?>
							<tr> 
								<td id= "fila2" align=left> <?php echo $ubi1 ?></td> 
								<td id= "fila2" align=left> <?php echo $ubi2 ?></td> 
								<td id= "fila2" align=left> <?php echo $arreglo[$y][3] ?></td> 
								<td id= "fila2" align=left> <?php echo $arreglo[$y][5] ?></td> 
								<td id= "fila" align=left> <?php echo $arreglo[$y][4] ?></td> 
							</tr>
				<?php
							 
							$y=$y+1;			
						}
				?>	
			</table>
	</div>	
	<div>
		<form name "interrumpir" action="interrumpirprepatr.php" method="POST" width="75%">
			<td id= "box"> <input name="tipo" type="hidden" id="tipo" size = "30" value= "INV-TR"> </td>
			<td id= "box"> <input name="zona" type="hidden" id="zona" size = "30" value= <?php echo $zona ?>> </td>
			<input   name="submit" id="submit" value="Interrumpir Preparacion" type="submit"> 
		</form>
		
		<form name = "formpreparar" action="preparartransferenciasjaula1.php" method="POST" width="75%">
			<table align ="center" >
				<tr>
				  <td>	
				  <td id= "box"> <input name="bodega" type="hidden" id="bodega" size = "30" value= "<?php echo $bodegaid ?>"> </td>
				  <td id= "box"> <input name="transfer" type="hidden" id="transfer" size = "30" value= "<?php echo $transfer ?>"> </td>
				  <input   name="submit" id="submit" value="Grabar ubicacion Retiro"  type="<?php echo $activar ?>"  > 
<?php
					if ($activar=="hidden"){?><a href="preparartransferencias.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a>
<?php
					}else{?> 
					</td>
				</tr> 
		  </table>
		</form> 
		
		<form name "grabarretiro" action="preparartransferenciasjaula2.php" method="POST" width="75%">
			<td id= "box"> <input name="zona" type="<?php echo $gretiro ?>" id="zona" size = "30" value="" required> </td>
			<br><input   name="submit" id="submit" value="Finalizar Preparacion" type="<?php echo $activargr ?>">  
		</form>
		
					
<?php				} ?>
				  
				  
	</div>
</div>
				<?php	
				
					 
					$usuario= $usuario1; 		
					$_SESSION['usuario']=$usuario;
					$_SESSION['transfer']=$transfer;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo;
					$_SESSION['bodega']=$bodega;
					$_SESSION['nomsuc']=$nomsuc; 
					$_SESSION['serv']=$contaserv; 	
					$_SESSION['carreglo']=$count; 
				}
				else
				{
					header("location: index.html");
				}	
				?>		

</div>
</body>	
</html>