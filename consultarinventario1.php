<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
	function setfocus(){
		document.getElementById("codigo").focus();
	}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/tablas.css">
<body onload= "setfocus()"> 
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
					$bodega = TRIM($_POST["bodega"]);
					$stock = TRIM($_POST["stock"]);
					if ($_POST["codigo"]=='') {$codigo= $_SESSION['codigo'];}else{$codigo= $_POST["codigo"];}
					if ($_POST["bodega"]=='') {$bodega= $_SESSION['bodega'];}else{$bodega= $_POST["bodega"];}
					//echo $bodega.$stock.$base; 
			 
					$usuario1= $usuario; 
					if ($base=='CARTIMEX'){
							require 'headcarti.php';  
					}
					else{
							require 'headcompu.php';
							$_SESSION['base']= $base;  
					}
				    $codigo= trim($codigo);
					require('conexion_mssql.php');
					 
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//new PDO($dsn, $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare('Log_consultar_productos @nombre=:codigo , @stock=:stock, @bodega=:bodega');		 
					$result->bindParam(':codigo',$codigo,PDO::PARAM_STR);
					$result->bindParam(':stock',$stock,PDO::PARAM_STR);
					$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
					$result->execute();
					$arreglo = array(); 
					
					$x=0; 
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$arreglo[$x][1]=$row['aid'];
						$arreglo[$x][2]=$row['co'];
						$arreglo[$x][3]=$row['cod_alterno1'];
					//	$arreglo[$x][4]=$row['cod_alterno2'];
						$arreglo[$x][5]=$row['nom'];
						$arreglo[$x][6]=$row['StockMA'];
						$arreglo[$x][7]=$row['Stockx'];	
						$arreglo[$x][8]=$row['pe'];
						$arreglo[$x][9]=$row['bodega1'];	
						$arreglo[$x][10]=$row['alterbo'];
						$arreglo[$x][11]=number_format($row['precio6'],2);
						$bodega1 = $row['bodega1'];
						$bodega2 = $row['alterbo'];
						$x++; 
						
					}	
					$count = count($arreglo);
					if ($stock =='SI')
						{
							$titulo= "Listado sin Stock ";
						}
					else
						{
							$titulo= "Listado con Stock  ";
						}	

				
?>		
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >			 
			<div id = "izq" >  </div>
			<div id = "centro"> <a class="titulo"> <center>  <?php echo $titulo . $codigo ?>   </center> </a></div>
			<div id = "derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>
	</div> 
<hr>	
	<div>
		<font  size="5" color="black" ><a href= "consultarinventario.php?=<?php echo trim($bodega) ?>" style="text-decoration:none">
			   <strong><center> Nueva consulta <img src="assets\img\lupa.png"> </center></strong></a>
		</font>
	</div>
</div>	
<div id="cuerpo2" align= "center">
	<div class=\"table-responsive-xs\">
		<table id= "listado" align ="center" > 
				<tr> 
					<th> ID </th>
					<th> CODIGO </th>
					<th> CODIGO1 </th>
					 
					<th> NOMBRE </th>
					<th> <?php echo $bodega1?> </th>
					<th> <?php echo $bodega2?> </th>
					<th> UBICACION </th>
					<th> PRECIO6</th>
				</tr>	
				<?php

						$y=0;
						while ( $y <= $count-1 ) 
						{
				?>
							<tr> 
								<td id= "fila" align=center> <a href="consultasku.php?id=<?php echo $arreglo[$y][1] ?>"><?php echo $arreglo[$y][1] ?></a></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][2] ?></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][3] ?></td> 
							
								<td id= "fila" align=center> <a href="showpicture.php?code=<?php echo $arreglo[$y][2] ?>"> <?php echo $arreglo[$y][5] ?></a></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][6] ?></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][7] ?></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][8] ?></td>
								<td id= "fila" align=center> <?php echo $arreglo[$y][11] ?></td>
							</tr>
				<?php
						$y=$y+1;			
						}	
				$usuario= $usuario1; 				
				$_SESSION['usuario']=$usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;
				$_SESSION['codigo']=$codigo;
				$_SESSION['bodega']=$bodega;
				}
				else
				{
					header("location: index.html");
				}	
				?>		
			</table>
		</div>	
</div>	
</body>	
</html>