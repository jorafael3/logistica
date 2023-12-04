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
<link rel="stylesheet" type="text/css" href="../css/tablas.css">
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
					$conteo= $_GET['id'];
					
					$usuario1= $usuario;
					$estado = "Asignacion";
					//echo $bodega.$stock.$base.$conteo.$estado; 	 
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
							$_SESSION['base']= $base;  
					}
					require('../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('select p.código as codigo ,p.nombre as nombre, c.* 
									   from  INV_CONTEO_DIFERENCIAS C
									   inner join INV_PRODUCTOS P  WITH (NOLOCK) ON  P.ID= C.PRODUCTOID
									   inner join inv_conteo con with (nolock) on con.ConteoID = c.ConteoID 
									   where C.ConteoID =:CONTEO and con.Estado=:estado and c.usuasignado is null order by p.nombre');		 
					$result->bindParam(':CONTEO',$conteo,PDO::PARAM_STR);	
					$result->bindParam(':estado',$estado,PDO::PARAM_STR);	
					$result->execute();
					$arreglo = array(); 
					$x=0; 
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
						{
							$arreglo[$x][1]=$row['col'];
							$arreglo[$x][2]=$row['codigo'];
							$arreglo[$x][3]=$row['nombre'];
							$arreglo[$x][4]=$row['ProductoID'];
							$arreglo[$x][5]=$row['Diferencia'];
							$x++; 
						}	
					$count = count($arreglo);
					
					$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result3 = $pdo3->prepare('select min(col) as desde,  max(col) as hasta from inv_conteo_diferencias where usuasignado is null and  ConteoID=:conteo ');		 
					$result3->bindParam(':conteo',$conteo,PDO::PARAM_STR);	
					$result3->execute();
					while ($row3 = $result3->fetch(PDO::FETCH_ASSOC)) 
						{
							$desde=$row3['desde'];
							$hasta=$row3['hasta']; 
						}		
?>		
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >			 
			<div id = "izq" >  </div>
			<div id = "centro"> <a class="titulo"> <center>  <?php echo $count. " items por asignar " ?>   </center> </a></div>
			<div id = "derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
	</div> 
<hr>	
</div>	

<div id="cuerpo2" align= "center">
<div>
	<form name = "forminven" action="asignardiferencias1.php" method="POST" >
		<table align= "center" >
			<tr> 
			</tr>
			<tr>
				<td id= "label">Usuario:</td> 
				<td><select name="usuarioasig" id = "usuarioasig">
<?php
				require('../conexion_mssql.php');
				$acc1 = '1';
				$acc = '4'; 
				$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result = $pdo->prepare('select usrid, usuario from seriesusr where acceso =:acc or acceso=:acc1 and anulado = 0 order by usrid desc');	
				$result->bindParam(':acc1',$acc1,PDO::PARAM_STR);	
				$result->bindParam(':acc',$acc,PDO::PARAM_STR);					
				$result->execute();
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
?>				
						<option value="<?php  echo $row['usuario']; ?>"><?php echo $row['usuario']; ?></option>
<?php
					}	
?>				</select></td>
			</tr>
			<tr>
				<td id= "label">Desde:</td> 
				<td id= "box"> <input  name="desde" id= "desde" type="number" size = "5" required value= "<?php echo $desde; ?>" min= "<?php echo $desde; ?>"> </td>
			</tr>
			<tr>
				<td id= "label">Hasta:</td> 
				<td id= "box"> <input  name="hasta" id= "hasta" type="number" size = "5" required  value= "<?php echo $hasta; ?>" max= "<?php echo $hasta; ?>"> </td>
			</tr>
			<tr>
				  <td id="label"></td>
				  <td id="label"> Asignar 
				  <input   name="submit" id="submit" value="Agregar" src="..\assets\img\save.png" type="image" border= "1px solid"> 
				  <a href="mantenimientoinventario.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
		</table>
    </form> 
</div>	
	<div class=\"table-responsive-xs\">
		<table id= "listado" align ="center" > 
				<tr> 
					<th> NUM </th>
					<th> CODIGO </th>
					<th> NOMBRE </th>
					<th> PRODUCTOID </th>
					<th> DIFERENCIA </th>
				</tr>	
				<?php

						$y=0;
						while ( $y <= $count-1 ) 
						{
				?>
							<tr> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][1] ?></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][2] ?></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][3] ?></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][4] ?></td> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][5] ?></td>
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
				$_SESSION['ConteoID']=$conteo;
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