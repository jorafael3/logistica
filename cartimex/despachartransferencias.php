<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("secu").focus();
}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/tablas.css">
<body onload= "setfocus()"> 
<div id= "header" align= "center">
<?php 
		    session_start();	
		    if (isset($_SESSION['loggedin']))
				{
					//echo "Entra aqui"; 
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}
date_default_timezone_set('America/Guayaquil');
$fecha = date("yy-m-d", time());
$hora = date("H:i:s", time());
$fh = $fecha . " " . $hora;
					//echo "BOdega". $bodega; 
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
			 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Despachar Transferencias </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
			 
	</div> 
<hr>	
<div id= "cuerpo2" align= "center" >	
		<div class=\"table-responsive-xs\">
			<form name = "formfactura2" action="despachartransferencias0.php" method="POST" width="75%">
			<table align ="center" >
				<tr>
					<th colspan="7">Transferencias Por Despachar </th> 
				</tr> 
				<tr> 
					<th id= "fila4"> Origen</th>
					<th id= "fila4"> ID </th>
					<th id= "fila4"> Fecha</th>
					<th id= "fila4"> B.Destino</th>
					<th id= "fila4"> Destino </th>
					<th id= "fila4"> Transferencias </th>
					<th> </th>
				</tr>
		<?php

							$_SESSION['usuario']=$usuario;
							$_SESSION['bodega']=$bodega; 
							//echo "bodega".$bodega.$base.$usuario.$acceso;
							include('../conexion_mssql.php');	
							//******Proceso aqui primero todas las facturas pendientes de GUIA para ver cual ha sido Anulada o 
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];
							$result = $pdo->prepare("LOG_TRANSFERENCIAS_PENDIENTES_DESPACHO_SELECT @BODEGA=:bodega , @acceso=:acceso" );		 
							$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							$result->execute();
							$arreglo = array();
							$x=0; 
							
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
							{
								$arreglo[$x][1]=$row['Id'];
								$arreglo[$x][2]=$row['CodDestino'];
								$arreglo[$x][3]=$row['Destino'];
								$arreglo[$x][4]=$row['origen'];
								$arreglo[$x][5]=$row['fecha'];
								$x++; 
							}	

							$count = count($arreglo); 
							$y=0;
							while ( $y <= $count-1 ) 
							{
								$numfac= $arreglo[$y][1];
								$pdot = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
								$resultt = $pdo->prepare(" select TransferenciasID from inv_transferencias_agrupadas where agruparid=:agruparid" );
								$resultt->bindParam(':agruparid',$arreglo[$y][1],PDO::PARAM_STR);
								$resultt->execute();
								$transferencias= ""; 
								while ($rowt = $resultt->fetch(PDO::FETCH_ASSOC)) 
									{
										$transferencias= $transferencias . "/". $rowt['TransferenciasID'];
									}
		?>						
								<tr>
									<td id= "fila4"  > <a href ="trakingdesptr.php?transfer=<?php echo $arreglo[$y][1]?>"><?php echo $arreglo[$y][4] ?> </a></td>
									<td id= "fila4"  > <?php echo $arreglo[$y][1] ?> </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][5] ?></td> 
									<td id= "fila4"  > <?php echo $arreglo[$y][2] ?> </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][3] ?> </td>
									<td id= "fila4"  > <?php echo $transferencias ?>  </td>
									<td id= "box"> <input name="checkbox[]" type="checkbox" value ="<?php echo $arreglo[$y][1] ?>" > </td>									
								</tr>
		<?php
								$y=$y+1;			
							}	
		?>	
		</table>
		<input   id="submit" value=" Despachar Transferencias Marcadas " type="submit">
		</form>
		</div>	
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