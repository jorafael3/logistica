<!DOCTYPE html>
<html>
<head>
<title> SGL </title>

</head>
<script type="text/javascript">
function setfocus(){
    document.getElementById("ubi").focus();
}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
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
					$id= $_GET['id'];
					$codigo= $_SESSION["codigo"];
					$code= $_GET["code"];	
					$bodega = $_SESSION['bodega'];
					 	
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
					<div id = "centro" > <a class="titulo"> <center> Datos del Producto </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
				 
	</div>
<hr>	
<?php
					$usuario1=$usuario;
					$_SESSION['base']= $base;  
					require('conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//new PDO($dsn, $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare("Log_busqueda_producto_id @id=:id");
					$result->bindParam(':id',$id,PDO::PARAM_STR);
					//Executes the query
					$result->execute();
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
						  {	  
						  $co = $row['co'];
						  $nom = $row['nom'];
						  $descrip = $row['descrip'];
						  $cod_alterno1 = $row['cod_alterno1'];
						  $cod_alterno2 = $row['cod_alterno2'];
						  $marca=$row['marca']; 
						  }	
					//echo "aqui".$usuario1;
					$usuario=$usuario1;
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//new PDO($dsn, $sql_user, $sql_pwd);
					//Select Query
					$result1 = $pdo1->prepare("Log_busqueda_producto_id_ubicaciones @id=:id, @bodega=:bodega");
					$result1->bindParam(':id',$id,PDO::PARAM_STR);
					$result1->bindParam(':bodega',$bodega,PDO::PARAM_STR);
					//Executes the query
					$result1->execute();
					$arreglo= array();
					$x=0; 
					while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
					{
						 
						$arreglo[$x][1]=$row1['aid'];
						$arreglo[$x][2]=$row1['pe'];
						$arreglo[$x][3]=$row1['sec'];
						$arreglo[$x][4]=$row1['cod_alterno1'];
						$arreglo[$x][5]=$row1['cod_alterno2'];
						$x++; 
					}
					//echo "aqui2".$usuario;					
?>		

<div id="cuerpo2" align= "center" >
<div align= "left">
	<table>
		<tr><td><strong>Codigo :</strong> <a><?php echo $co ?> </a><br></td></tr>
    	<tr><td><strong>Codigo 1:</strong> <a><?php echo $cod_alterno1 ?> </a><br></td></tr>
		<tr><td><strong>Codigo 2:</strong> <a><?php echo $cod_alterno2 ?> </a><br></td></tr>
    	<tr><td>============= </td></tr>
		<tr><td><strong>Nombre:</strong> <a><?php echo $nom ?> </a><br></td></tr> 
    	<tr><td><strong>Descripcion:</strong><a><?php echo $descrip ?> </a><br></td></tr>    		
    	<tr><td><strong>Marca:</strong><a><?php echo $marca ?> </a><br></td></tr>
		<tr><td><strong>Ubicaciones para este producto: </td></tr>
    </table>
	<table id="listado2" align ="center"> 
						<tr> 
							<th> ProductoID </th>
							<th> Secuencia </th>
							<th> Ubicacion </th>
							<th> Modificar </th>
							<th> Eliminar </th>
						</tr>
<?php
					$count = count($arreglo); 
					$y=0;
					while ( $y <= $count-1 ) 
					{
					$prodid= $arreglo[$y][1];				 
?>					
						<tr> 
							<td id= "fila" align=center> <?php echo $arreglo[$y][1] ?></td> 
							<td id= "fila" align=center> <?php echo $arreglo[$y][3] ?></td> 
							<td id= "fila" align=center> <?php echo $arreglo[$y][2] ?></td> 
							<td id= "fila" align=center width=30><a href="modificasku.php?id=<?php echo $arreglo[$y][1] ?>&secu=<?php echo trim($arreglo[$y][3])?>"><img src="assets\img\edit.png" > </td>
							<td id= "fila" align=center width=30><a href="eliminasku.php?id=<?php echo $arreglo[$y][1] ?>&secu=<?php echo trim($arreglo[$y][3])?>"><img src="assets\img\delete.png" > </td>
						</tr>
<?php
					$y=$y+1;			
					}
?>		
	</table>	
<br>
<div>
	<form name = "formusuario" action="agregarsku.php" method="POST" width="75%">
		<table align ="center" >
		<tr> 
			<th colspan="2"> Agregar Ubicacion </th>
			<td id= "box"> <input name="prodid" type="hidden" id="prodid" size = "30" value= "<?php echo $prodid ?>"> </td>
		</tr>					
    	<tr>
    		<td id="label" >Ubicacion: </td> 
    		<td id= "box"> <input name="ubi" type="text" id="ubi" size = "30" value= " "> </td>
    	</tr>
		<tr>
		  <td id="label"></td>
		  <td id="label"> Agregar
      	  <input   name="submit" id="submit" value="Agregar" src="assets\img\save.png" type="image" border= "1px solid"> 
		  <a href="consultarinventario1.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
      </table>
    </form> 
</div>	
</div> 
		
<?php	
				$_SESSION["prodid"]=$prodid;
				$_SESSION["codigo"]=$codigo;	
				$_SESSION['usuario']=$usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;
				$_SESSION['bodega']=$bodega;
				}
				else
				{
					header("location: index.html");
				}
?>
</div>			
</body>	