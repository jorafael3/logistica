<meta name="viewport" content="width=device-width, height=device-height">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("serie").focus();
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
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					$usuario1 = $usuario; 
					$usuario= $usuario1; 
					$serie = $_POST['serie'];
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
					<div id = "izq" ></div>
					<div id = "centro" > <a class="titulo"> <center>   Consultar series   </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a></div>
	</div> 
<hr>	
<div id= "cuerpo2" align= "center" >
	<div>
			<form name = "formfactura" action="consultarseriesservitech.php" method="POST" width="75%">
				<table align ="center" >
					<tr>
						<td id="label" >Serie # </td> 
						<td id= "box"> <input name="serie" type="text" id="serie" size = "30" value= "" > </td>
						<td id= "box"> <input name="bodega" type="hidden" id="bodega" value= "<?php echo trim($bodega) ?>"> </td>
					</tr>
					<tr>
					  <td id="etiqueta" colspan= "2">   Consultar
					  <input   name="submit" id="submit" value="Grabar" src="assets\img\lupa.png" type="image"> 
					  </td>
					 </tr> 
				 </table>
			</form> 
	</div>		

 <?php
///Tabla de datos del producto 
	if ($serie <>'')
		{
			
?>
<div class=\"table-responsive-xs\">
	<form  name = "formproducto"  method="POST" width="75%">
		<div >
			<table align ="center"> 
				<tr>
					<th colspan="7"> Detalle de Producto </th> 
				</tr>
				<tr> 
					<th id= "fila4"  > Fecha </th>
					<th id= "fila4"  > Factura </th>
					<th id= "fila4"  > Cliente </th>
					<th id= "fila4"  > Código </th>
					<th id= "fila4"  > Producto </th>
					<th id= "fila4"  > Serie </th>
				</tr>
		</div>
		
		<div  >
			 
<?php
			require('conexion_mssql.php');
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database",$sql_user, $sql_pwd);
			$result1 = $pdo1->prepare('Inv_buscar_series_productos_SERVITECH  @serie=:serie');		 
			$result1->bindParam(':serie',$serie,PDO::PARAM_STR);
			$result1->execute();
			$count = $result1->rowcount();
			//echo "Contador". $count; 
			 
			if ($count< 0 ) 
				{
					while ($row = $result1->fetch(PDO::FETCH_ASSOC)) 
					{	  
						$Fecha = $row['Fecha'];
						$Secuencia = $row['Secuencia'];
						$Cliente = $row['Detalle'];
						$Codigo = $row['Código'];
						$Nombre = $row['Nombre']; 
						$Serie = $row['Serie']; 
						$Id = $row['ID']; 
						$BodegaFAC = $row['BodegaFAC']; 
?>
					<tr>
						<td id= "fila4" > <?php echo $Fecha ?> </td>	
						<td id= "fila4" > <a href ="trakingfacturasservitech.php?secu=<?php echo $Secuencia ?>&bodegaFAC=<?php echo $BodegaFAC?>"> <?php echo $Secuencia ?> </td>
						<td id= "fila4" > <?php echo $Cliente ?> </td>
						<td id= "fila4" > <?php echo $Codigo ?> </td>
						<td id= "fila4" > <?php echo $Nombre ?> </td>
						<td id= "fila4" > <?php echo $Serie ?> </td>
					</tr>
<?php						
					}
?>
			</table>
		</div>
	</form>	
</div>
 </div> 
</div>
					
<?php	
					$Serie=''; 
				}
		}		
	else
		{
			echo "SERIE NO REGISTRADA";	
		}

?>
 
	
<?php	
			
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
 		
</body>	