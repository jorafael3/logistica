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
					<div id = "centro" > <a class="titulo"> <center>  DROP-SHIPPING </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
				 
	</div> 
<hr>	
<div id= "cuerpo2" align= "center" >
	 
			<div>
				<form name = "formfactura" action="dropshipping0.php" method="POST" width="75%">
					<table align ="center" >
					 
					<tr>
					  <td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>
					  <td id="label">   
					  
					  <a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
					  </td>
					 </tr> 
				  </table>
				</form> 
			</div>	
 
	
		<div class=\"table-responsive-xs\">
			<form name = "formfactura2" action="dropshipping0.php" method="POST" width="100%">
			<table align ="center" >
				<tr>
					<th colspan="6">Facturas Pendientes </th> 
				</tr> 
				<tr> 
					<th id= "fila4"> SId </th>
					<th id= "fila4"> Cliente </th>
					<th id= "fila4"> Factura </th>
					<th id= "fila4"> Fecha </th>
					<th id= "fila4">  </th>
					<th id= "fila4"> Comentario </th>
				</tr>
		<?php

							$_SESSION['usuario']=$usuario;
							$_SESSION['bodega']=$bodega; 
							//echo "bodega".$bodega.$base.$usuario.$acceso;
							require('conexion_mssql.php');	
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];

							$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result0 = $pdo0->prepare("LOG_FACTURAS_DROP_SHIPPING_DEVUELTAS @BODEGA=:bodega , @acceso=:acceso" );	 
							$result0->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							$result0->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							$result0->execute();
							$arreglod = array();
							$xd=0; 
							while ($row0 = $result0->fetch(PDO::FETCH_ASSOC)) 
								{
									$arreglod[$xd][1]=$row0['FID']; 
									$arreglod[$xd][2]=$row0['bodegaid'];
									$xd++; 
								}
							$countd = count($arreglod); 
							$yd=0;
							while ( $yd <= $countd-1 ) 
								{
									$FID= $arreglod[$yd][1];
									$bodegaFAC= $arreglod[$yd][2];
									$pdod = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
									$resultd = $pdod->prepare("Log_facturaslistas_dropshipping_anular @facturaid=:facturaid, @bodegaid=:bodegaid, @usuario=:usuario" );		 
									$resultd->bindParam(':facturaid',$FID,PDO::PARAM_STR);
									$resultd->bindParam(':bodegaid',$bodegaFAC,PDO::PARAM_STR);
									$resultd->bindParam(':usuario',$usuario,PDO::PARAM_STR);
									$resultd->execute();
									$yd=$yd+1;
								}	
							
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result = $pdo->prepare("LOG_FACTURAS_DROP_SHIPPING @BODEGA=:bodega , @acceso=:acceso" );	 
							$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							$result->execute();
							$arreglo = array();
							$x=0; 
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
							{
								$arreglo[$x][1]=$row['Sucursal'];
								$arreglo[$x][2]=$row['secuencia'];
								$arreglo[$x][3]=$row['fecha'];
								$arreglo[$x][4]=$row['nombodega'];
								$arreglo[$x][5]=$row['Detalle'];
								$arreglo[$x][6]=$row['nota'];
								$arreglo[$x][7]=$row['Bodegaf'];
								$x++; 
							}	
							$count = count($arreglo); 
							$y=0;
							while ( $y <= $count-1 ) 
							{
		?>						
								<tr>
									<td id= "fila4"  style="font-weight: bold;font-size: 16px;"> <?php echo $arreglo[$y][1] ?></td> 
									<td id= "fila4"  > <?php echo $arreglo[$y][5] ?></td> 
									<td id= "label2" align= "center">
										 <input style="font-weight: bold;font-size: 16px;" name="secu" type="submit" id="secu" size = "20" value = "<?php echo $arreglo[$y][2]. " ".$arreglo[$y][7] ?>">   </td> 
									<td id= "fila4"  > <?php echo $arreglo[$y][3] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][4] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][6] ?>  </td>
								</tr>
		<?php
							$y=$y+1;			
							}	
		?>	
		</table>
		</form>
		</div>	
		<a align= "center"> <strong> ** FACTURAS BODEGA DE CONSIGNACION  **</strong><br>
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