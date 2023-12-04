<!DOCTYPE html>
<html>
<head>
<title> SGL </title>

</head>
<script type="text/javascript">
function setfocus(){
    document.getElementById("cant").focus();
}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload= "setfocus()">
<div id= "header" align= "center"> 
<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','On');
		    session_start();	
		    if (isset($_SESSION['loggedin']))
				{
					$usuario = $_SESSION['usuario'];
					$bodegacont = $_SESSION['Bodegacont'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$ConteoID= $_SESSION['ConteoID']; 
					$pid= $_GET['pid'];
					 
					if ($pid=='')
						{ 
							$activar ="no";
							$pid= $_SESSION['proid'];
						}
					else 
						{
							if ($pid=='5')
							{ 
								
								$activar ="si";
								$pid= $_SESSION['proid'];
								//echo "Si entra aqui".$habilitar;
							}
							else
							{ 
								$activar ="no";
								$pid= $_GET['pid'];
							}
						}	
					//echo $usuario. "-" .$Bodegacont. "-" .$ConteoID. "-".$pid;  
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}				
?>
</div>
<div  id = "Cuerpo" >
<?php
					$usuario1=$usuario;
					$_SESSION['base']= $base;  
					$SECCION= 'conteo2';
					require('../conexion_mssql.php');
					
					$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result0 = $pdo0->prepare("select isnull(sum(cantidad),0)  as sumconteo FROM INV_CONTEO_PRODUCTOS 
											 WHERE ConteoID=:ConteoID and NombreSeccion=:seccion and id =:pid");
					$result0->bindParam(':ConteoID',$ConteoID,PDO::PARAM_STR);
					$result0->bindParam(':seccion',$SECCION,PDO::PARAM_STR);
					$result0->bindParam(':pid',$pid,PDO::PARAM_STR);
					$result0->execute();
					while ($row0 = $result0->fetch(PDO::FETCH_ASSOC)) 
						{
						$sumconteo = $row0['sumconteo'];
						}
					
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare("Log_busqueda_producto_id_ubicaciones @id=:pid, @bodega=:bodegacont");
					$result->bindParam(':pid',$pid,PDO::PARAM_STR);
					$result->bindParam(':bodegacont',$bodegacont,PDO::PARAM_STR);
					$result->execute();
					$count = $result->rowcount();
					 
					
					
					//echo "entro aqui ".$count; 
					if ($count==0 )
						{
							$CANT= 0 ;
							 
						}
								
							$arreglo = array(); 
							$x=0;
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
								  {	  
									if ($row['pe']==''){$percha=1;}else{$percha=$row['pe'];}
									$arreglo[$x][1]=$percha;
									$cod=$row['cod'];
									$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
									$result1 = $pdo1->prepare("select isnull (sum(cantidad),0) as cant  from INV_CONTEO_PRODUCTOS
															   where ConteoID=:ConteoID and id=:pid
															   and (CodigoBarras =:codbarras or CodigoBarras ='1') group by codigobarras");
									$result1->bindParam(':pid',$pid,PDO::PARAM_STR);
									$result1->bindParam(':ConteoID',$ConteoID,PDO::PARAM_STR);
									$result1->bindParam(':codbarras',$percha,PDO::PARAM_STR);
									$result1->execute();
									while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
										{
											$cant= 	$row1['cant'];
										}
									$arreglo[$x][2]=$cant;	
									$cant =0;
									$x++; 
									
								}
							 	  
							$count2 = count($arreglo);	  
							
?>		
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div> 
					<div id = "centro" > <a class="titulo"> <center> 2do Conteo <?php echo $cod ?> </center> </a></div>
					<div id = "derecha" > <a href="..\menu.php"><img src="..\assets\img\home.png"></a> </div>
				 
	</div>
<hr>	


<div id="cuerpo2" align= "center" >
	<table  align ="center" > 
						<tr> <a> <strong><?php echo "Total Unidades Contadas ".$sumconteo ?> </strong></a></tr>
						<tr> 
							<th width = "10"> Ubicacion </th>
							<th width = "10"> Cantidad </th>
							<th width = "10"> </th>
							<th width = "10"> Contado </th>
						</tr>
<?php
					 
					$y=0;
					while ( $y <= $count2-1 ) 
					{
						//echo "Contador".$count2;
						if ($arreglo[$y][1]== null) 
							{$ubic=1;}
						else{
							$ubic= $arreglo[$y][1];}				 
?>					<form name = "forminven" action="ingresasegundoconteo2.php" method="POST" >
						<tr> 
							<td id= "fila" align="center"> <?php echo $ubic ?></td> 
							 <input name="proid" type="hidden" id="proid" size = "30" value= "<?php echo $pid ?>"> 
							 <input name="codid" type="hidden" id="codid" size = "30" value= "<?php echo $cod ?>"> 
							 <input name="ubic" type="hidden" id="ubic" size = "30" value= "<?php echo $ubic ?>"> 
							 <input name="seccion" type="hidden" id="seccion" size = "30" value= "<?php echo $SECCION ?>">
							<td id= "box"> <input name="cant" type="text" id="cant" size = "10" value= "" required> </td>
							<td><input   name="submit" id="submit" type ="submit" value="OK"  border= "1px solid"> </td>
							<td id= "fila" align="center">  <?php echo $arreglo[$y][2]?> </td> 
						</tr>
					</form>	
<?php
					$y=$y+1;			
					}
?>		
			<tr>
				<td> </td>
				<td id="label" align=center><a href="ingresasegundoconteo1.php?pid=5" style="text-decoration:none"> + Ubicacion </a></td>
			</tr>
			<tr>
				<td> </td>
				<td id="label" align=center><a href="segundoconteo.php" style="text-decoration:none">Regresar</a></td>
			</tr>

	</table>
<?php
		if ($activar=="si"){?>
			<div>
				<form name = "formusuario" action="agregarskuconteo.php" method="POST" width="75%">
					<table align ="center" >
						<tr> 
							<th colspan="2"> Agregar Ubicacion </th>
							<td id= "box"> <input name="prodid" type="hidden" id="prodid" size = "30" value= "<?php echo $pid ?>"> </td>
						</tr>					
						<tr>
							<td id="label" >Ubicacion: </td> 
							<td id= "box"> <input name="ubi" type="text" required id="ubi" size = "30" value= ""> </td>
						</tr>
						<tr>
						  <td id="label"></td>
						  <td id="label"> Agregar
						  <input   name="submit" id="submit" value="Agregar" src="..\assets\img\save.png" type="image" border= "1px solid"> 
						  </td>
						 </tr> 
				  </table>
				</form> 
			</div>
<?php
					}else{}
?> 	
</div>	

		
<?php	
				//echo "Datos q voy a enviar".$bodegacont.$usuario1.$base. $pid ; 
				$_SESSION['usuario']=$usuario1;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;
				$_SESSION['ConteoID'] = $ConteoID;
				$_SESSION['proid'] = $pid;
				$_SESSION['bodega']= $bodegacont;
				}
				else
				{
					header("location: index.html");
				}
?>
		
</div>	
</body>	