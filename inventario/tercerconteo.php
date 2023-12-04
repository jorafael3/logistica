<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
	function setfocus(){
		document.getElementById("codigo").focus();
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
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$usuario1= $usuario;
					$estado = "En Curso";
					$seccion = "Conteo3";
					//echo $bodega. $stock. $base. $conteo. $estado. $usuario1; 	 
					 
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
							$_SESSION['base']= $base;  
					}
					require('../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('LOG_Inventario_Tercer_Conteo @Usuario=:usuasignado , @Estado=:estado , @seccion=:conteo');		 
					$result->bindParam(':usuasignado',$usuario1,PDO::PARAM_STR);	
					$result->bindParam(':estado',$estado,PDO::PARAM_STR);	
					$result->bindParam(':conteo',$seccion,PDO::PARAM_STR);
					$result->execute();
					$arreglo = array(); 
					$x=0; 
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
						{
							$arreglo[$x][1]=$row['col'];
							$arreglo[$x][2]=$row['codigo'];
							$arreglo[$x][3]=$row['nombre'];
							$arreglo[$x][4]=number_format($row['stock']);
							$arreglo[$x][5]=$row['Diferencia'];
							$arreglo[$x][6]=$row['pid'];
							$arreglo[$x][7]=$row['ConteoID'];
							$arreglo[$x][8]=$row['cont3'];
							$arreglo[$x][9]=number_format($row['ndi']);
							$x++; 
							$conteoid= $row['ConteoID'];
							$Bodegacont= $row['Bodegacont'];
						}	
					$count = count($arreglo);		
?>		
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >			 
			<div id = "izq" >  </div>
			<div id = "centro"> <a class="titulo"> <center>  <?php echo $count. " items 3er Conteo " ?>   </center> </a></div>
			<div id = "derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
	</div> 
<hr>	
</div>	

<div id="cuerpo2" align= "center">
	<div>
		<table id= "listado" align ="center" > 
				<tr> 
					<th> CODIGO </th>
					<th> NOMBRE </th>
					<th> STOCK </th>
					<th> DIF.CONTEO2 </th>
					<th> CONTEO3 </th>
					<th> DIF.CONTEO3 </th>
				</tr>	
				<?php

						$y=0;
						while ( $y <= $count-1 ) 
						{
							if ($arreglo[$y][8]==0){$colorf="yellow";}else{
							if ($arreglo[$y][9]==0){$colorf="lightblue";}else{$colorf="lightpink";}}
				?>
							<tr bgcolor="<?php echo $colorf ?>">
								<td id= "fila"><a href="ingresatercerconteo1.php?pid=<?php echo $arreglo[$y][6] ?>"> <?php echo $arreglo[$y][2] ?></a></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][3] ?></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][4] ?></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][5] ?></td>
								<td id= "fila" align=center> <?php echo $arreglo[$y][8] ?></td>
								<td id= "fila" align=center> <?php echo $arreglo[$y][9] ?></td>
							</tr>
				<?php
						$y=$y+1;
						$colorf= ""; 	
						}	
				$usuario= $usuario1; 				
				$_SESSION['usuario']=$usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;
				$_SESSION['codigo']=$codigo;
				$_SESSION['Bodegacont']=$Bodegacont;
				$_SESSION['ConteoID']=$conteoid;
				//echo "HEllo". $conteoid; 
				//die(); 
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