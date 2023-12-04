<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function visible() {
var div1 = document.getElementById('Preparando');
	div1.style.display= 'none';
}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/tablas.css">
<body> 
<div id= "header" align= "center">
<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','On');
		    session_start();	
		    if (isset($_SESSION['loggedin']))
				{	
					$usuario=$_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega	=$_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					$transfer = $_SESSION['transfer'];
					$usuario1= trim($usuario); 
					//echo $usuario1.$transfer; 
					$ProductoId= $_GET['produc']; 
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
							$_SESSION['base']= $base; 
					}
				    // $codigo= trim($codigo);
					require('../conexion_mssql.php');
				
				
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare('Select * from inv_productos where id=:Productoid ');		 
					$result->bindParam(':Productoid',$ProductoId,PDO::PARAM_STR);
					$result->execute();
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
						{
							$CodProducto= $row['Código'];
							$Detalle=$row['Nombre'];
						}	
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Series de Producto  </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div> 
<hr>	
<div id="cuerpo2" align= "center" class=\"table-responsive-xs\">
<div align= "center ">
	<table>
		<tr><td>
		<strong> Codigo : </strong> <a> <?php echo $CodProducto ?> </a> 
		<strong> Descripcion: </strong> <a> <?php echo $Detalle ?> </a>
		<br></td></tr>
    </table>
</div>
<?php
				//echo $ProductoId. $transfer; 
				$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd); 
				$result2 = $pdo2->prepare('LOG_CONSULTA_TRANSFERENCIA_SERIES_DT @numero = :transfer ,@productoid=:ProductoId');		 
				$result2->bindParam(':transfer',$transfer,PDO::PARAM_STR);
				$result2->bindParam(':ProductoId',$ProductoId,PDO::PARAM_STR);
				$result2->execute();
				$arreglo = array(); 
				$x=0; 
				while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) 
					{
						//echo "SI entra ";
						$arreglo[$x][1]=$row2['serie'];
						$x++; 
					}	
				$count = count($arreglo);
				//if ($count==0){
				//echo '<pre>', print_r($arreglo),'</pre>';	
?>

</div>				
<?php				

?>				
<div id="cuerpo2" align= "center">
	<div>
		<form name = "modificarseries" action="modificarseriestransfer3.php" method="POST" width="75%">
			<table border=2 width=100% id= "series">
				<tr> 
					<th width=10% > Serie Anterior </th>
					<th width=10%> Serie nueva </th>
					</tr>	
				<?php

						$y=0;
						while ( $y <= $count-1 ) 
						{
							
				?>
							<tr> 
								<td id= "fila2" align=left> <?php echo $arreglo[$y][1] ?></td> 
								<td id= "box"> <input name="serie[]" type="text" id="serie[]" value= "<?php echo $arreglo[$y][1] ?>"> </td>
								<input  name="serieante[]" type="hidden" id="serieante[]" value="<?php echo $arreglo[$y][1] ?>"  >
							</tr>
						
				<?php
							$y=$y+1;			
						}
				?>
			</table>
			<input type="submit" name="submit" id="submit" value="GRABAR SERIES ">
		</form>	
	</div>	
	<div>
		<form name = "formpreparar" action="#" method="POST" width="75%">
			<table align ="center" >
				<tr>
				  <td>	<a href="modificarseriestransfer1.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a> </td>
				</tr> 
		  </table>
		</form> 
 
				  
				  
	</div>
</div>
				<?php	
					$usuario= $usuario1; 		
					//echo $ProductoId; 
					$_SESSION['usuario']=$usuario;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo;
					$_SESSION['bodega']=$bodega;
					$_SESSION['nomsuc']=$nomsuc; 
					$_SESSION['transfer']= $transfer;
					$_SESSION['ProductoId']= $ProductoId;
				}
				else
				{
					header("location: index.html");
				}	
				?>		

</div>
</body>	
</html>