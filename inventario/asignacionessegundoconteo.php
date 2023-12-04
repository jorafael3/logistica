<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload= "setfocus()"> 
<div id= "header" align= "center">
<?php 
		    session_start();	
		    if (isset($_SESSION['loggedin']))
				{
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_GET['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}
					$usuario1 = $usuario;
					require('../conexion_mssql.php');
					$estado = 'Asignacion2'; 
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('select * from inv_conteo where estado = :estado');	
					$result->bindParam(':estado',$estado,PDO::PARAM_STR);					
					$result->execute();
					$arreglo = array(); 
					$x=0; 
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$arreglo[$x][1]=$row['ConteoID'];
						$arreglo[$x][2]=$row['Detalle'];
						$arreglo[$x][3]=$row['Fecha'];
						$x++; 
						
					}	
					$count = count($arreglo);
?>		
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
					<div id = "izq" ></div>
					<div id = "centro" > <a class="titulo"> <center>   Inventarios por asignar diferencia conteo 2   </center> </a></div>
					<div id = "derecha" > <a href="..\menu.php"><img src="..\assets\img\home.png"></a></div>
	</div> 
<hr>	
<div id="cuerpo2" align= "center">
	<div class=\"table-responsive-xs\">
		<table id= "listado" align ="center" > 
				<tr> 
					<th> Fecha  </th>
					<th> Nombre  </th>
				</tr>	
				<?php

						$y=0;
						while ( $y <= $count-1 ) 
						{
				?>
							<tr> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][3] ?></td> 
								<td id= "fila" align=center> <a href="asignardiferenciassegundoconteo.php?id=<?php echo $arreglo[$y][1] ?>"><?php echo $arreglo[$y][2] ?></td> 
							</tr>
				<?php
						$y=$y+1;			
						}	
			$_SESSION['usuario']=$usuario1;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['nomsuc']=$nomsuc; 
			}
			else
			{
				header("location: index.html");
			}	
	
?>	
			<tr>
				<td> </td> 
				<td id="label" align=center>  
					<a href="..\menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
				</td>
			</tr>
		</table>
	</div>	
</div>	
</div>		
</body>	