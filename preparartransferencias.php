<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("numero").focus();
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
					
					
			?>	
			</div>	
			<div  id = "Cuerpo" >
				<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Preparar Transferencias <?php echo substr($nomsuc,10,20);  ?>  </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
				 
	</div> 
<hr>
<div id= "cuerpo2" align= "center" >
	 
			<div>
				<form name = "formtransferencia" action="preparartransferencias0.php" method="POST" width="75%">
					<table align ="center" >
					<tr>
						<td id="label" >Numero </td> 
						<td id= "box"> <input name="numero" type="text" id="numero" size = "30" value= "" > </td>
						<td id= "box"> <input name="bodega" type="hidden" id="bodega" size = "30" value= "<?php echo trim($bodega) ?>"> </td>
					</tr>
					<tr>
					  
					  <td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>
					  <td id="label">   Preparar
					  <input   name="submit" id="submit" value="Grabar" src="assets\img\lupa.png" type="image"> 
					  <a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
					  </td>
					 </tr> 
				  </table>
				</form> 
			</div>	
 
	
		<div class=\"table-responsive-xs\">
			<form name = "formtransferencia2" action="preparartransferencias0.php" method="POST" width="100%">
			<table align ="center" >
				<tr>
					<th colspan="6">Transferencias Pendientes </th> 
				</tr>
				<tr>
					<th id= "fila4"> SId </th>
					<th id= "fila4"> Transferencia</th>
					<th id= "fila4"> Fecha</th>
					<th id= "fila4"> Destino  </th>
					<th id= "fila4"></th>
					<th id= "fila4"></th>
				</tr>
		<?php

							$_SESSION['usuario']=$usuario;
							 
							require('conexion_mssql.php');	
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							//new PDO($dsn, $sql_user, $sql_pwd);
							//Select Query
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];
							//echo "bodega".$bodega.$base.$usuario.$acceso;
							$result = $pdo->prepare("LOG_TRANSFERENCIAS_PENDIENTES_SELECT @BODEGA=:bodega, @acceso=:acceso" );		 
							$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							//Executes the query
							$result->execute();
							$arreglo = array();
							$x=0; 
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
							{
								$arreglo[$x][1]=$row['Numero'];
								$arreglo[$x][2]=$row['Id'];
								$arreglo[$x][3]=$row['Destino'];
								$arreglo[$x][4]=$row['origen'];
								$arreglo[$x][5]=$row['fecha'];
								$x++; 
							}	
							$count = count($arreglo); 
							$y=0;
							while ( $y <= $count-1 ) 
							{
		?>						
								<tr>
									<td id= "fila4"> <?php echo $arreglo[$y][4] ?></td> 
									<td id= "label2" align= "center"> <input name="numero" type="submit" id="numero" size = "10" value= "<?php echo $arreglo[$y][1] ?>" > </td> 
									<td id= "fila4"> <?php echo $arreglo[$y][5] ?></td> 
									<td id= "fila4"> <?php echo $arreglo[$y][3] ?></td> 
									<td id= "fila4"> <input name="destino" type="hidden" id="destino" size = "15" value= "<?php echo $arreglo[$y][3] ?>" ></td> 
									<td id= "fila4"> <input name="id" type="hidden" id="id" value= "<?php echo $arreglo[$y][2] ?>"></td>
									<td id= "box"> <input name="bodega" type="hidden" id="bodega" value= "<?php echo $bodega ?>"></td>
								</tr>
		<?php
							$y=$y+1;			
							}	
							/*
									<td id= "etiqueta"> <input name="origen" type="label" id="origen" size = "5" value= "<?php echo $arreglo[$y][4] ?>" > </td> 
									<td id= "etiqueta"> <input name="numero" type="submit" id="numero" size = "10" value= "<?php echo $arreglo[$y][1] ?>" > </td> 
									<td id= "etiqueta"> <input name="destino" type="label" id="destino" size = "15" value= "<?php echo $arreglo[$y][3] ?>" > </td> 
									<td id= "box"> <input name="id" type="hidden" id="id" size = "40" value= "<?php echo $arreglo[$y][2] ?>"> </td>
									<td id= "box"> <input name="bodega" type="hidden" id="bodega" size = "40" value= "<?php echo $bodega ?>"> </td>
							*/
		?>	
		</table>
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