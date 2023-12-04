
<!DOCTYPE html>
<html>

<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/tablas.css">
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
					$acceso = $_SESSION['acceso'];
					
					if ($base=='CARTIMEX'){
							require 'headcarti.php';  
					}
					else{
							require 'headcompu.php';
					}
					$_SESSION['base']= $base;  
					
					//echo " 1 esto envio".$base.$usuario.$acceso;
?>		
</div>
<div id="Cuerpo">
	<div id="cuerpo2">
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Listado de usuarios   </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
	</div> 
<hr>
<div id="cuerpo2" align= "center">
<div class=\"table-responsive-xs\">	
<table id="listado" align ="center"> 
			<tr> 
				<th> Secuencia </th>
				<th> UsuarioId </th>
				<th> Nombre </th>
				<th> Acceso </th>
				<th> Sucursal </th>
				<th> Dpto </th>
				<th> Modificar </th>
				<th> Eliminar </th>
				<th> Agregar </th>
			</tr>	
			<?php
					$_SESSION['usuario'] = $usuario ;
					$usuario1= $_SESSION['usuario']; 
					require('conexion_mssql.php');	
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//new PDO($dsn, $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare("SELECT usrid,usuario,nusu=u.nombre,acceso,sucnom = s.nombre, dpto= departamento 
											FROM seriesusr U WITH (NOLOCK)
											LEFT OUTER JOIN SIS_SUCURSALES S WITH (NOLOCK) ON  S.ID= U.lugartrabajo
											where U.anulado = 0 " );		 
					//Executes the query
					$result->execute();
					$arreglo = array();
					$x=0; 
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$arreglo[$x][1]=$row['usrid'];
						$arreglo[$x][2]=$row['usuario'];
						$arreglo[$x][3]=$row['nusu'];
						$arreglo[$x][4]=$row['acceso'];
						$arreglo[$x][5]=$row['sucnom'];
						$arreglo[$x][6]=$row['dpto'];
						$x++; 
					}	
					$count = count($arreglo); 
					$y=0;
					while ( $y <= $count-1 ) 
					{
			?>
						<tr> 
							<td id= "fila" align=center> <?php echo $arreglo[$y][1] ?></td> 
							<td id= "fila"> <?php echo $arreglo[$y][2] ?></td> 
							<td id= "fila" align=center> <?php echo $arreglo[$y][3] ?></td> 
							<td id= "fila" align=center> <?php echo $arreglo[$y][4] ?></td> 
							<td id= "fila" align=center> <?php echo $arreglo[$y][5] ?></td> 
							<td id= "fila" align=center> <?php echo $arreglo[$y][6] ?></td> 
							<td id= "fila" align=center  width=30><a href="modificausuario1.php?id=<?php echo $arreglo[$y][1] ?>"><img src="assets\img\edit.png" alt="Modificar" border="0"> </td>
							<td id= "fila" align=center  width=30><a href="eliminarusuarios1.php?id=<?php echo $arreglo[$y][1] ?>"><img src="assets\img\delete.png" alt="Eliminar" border="0"> </td>
							<td id= "fila" align=center  width=30><a href="agregarusuarios1.php"><img src="assets\img\add.png" alt="Agregar" border="0"> </td>
						</tr>
			<?php
					$y=$y+1;			
					}
						//echo " 2 esto envio".$base.$usuario1.$acceso;
					$_SESSION['base']= $base; 
					$_SESSION['usuario']= $usuario1;
					$_SESSION['acceso']= $acceso;
				}
				else
				{
					header("location: index.html");
				}
			?>		
</table>			
</div>
</div>
</div>
</body>	