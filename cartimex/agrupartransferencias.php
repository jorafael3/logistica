<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script src="https://code.jquery.com/jquery-git.js"></script>


<script type="text/javascript">
function setfocus(){
    document.getElementById("numero").focus();
}
</script>	
<script language="javascript">
/*$(document).ready(function () 
{
	$("form :checkbox").click(function () {
	var trans = this.value; 
	window.location.href  = "preparartransferencias.php?trans=" + trans;
	//alert('hi : ' + this.value);
	});
});*/ 
</script>
<link href="../estilos/estilo_general.css" rel="stylesheet" type="text/css">
<!-- <link href="../estilos/estilos.css" rel="stylesheet" type="text/css"> -->
<link rel="stylesheet" type="text/css" href="../css/tablas.css">

<body onload= "setfocus()" > 
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
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}
					 
 			
?>	
</div>	

<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Agrupar Transferencias <?php echo substr($nomsuc,10,20);  ?>  </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
				 
	</div> 
<hr>
<div id= "cuerpo2" align= "center" >
		<div class=\"table-responsive-xs\">
			<form name = "formtransferencia2" action="agrupartransferencias0.php" method="POST" width="100%">
			
			<table align ="center" >
				 
					<input   id="submit" value="Agrupar Transferencias Marcadas" type="submit"> <br>
				<tr>
					<td> </td> 
				</tr> 
				<tr>
					<td> </td> 
				</tr> 
				<tr>
					<th colspan="7">Transferencias Pendientes </th> 
				</tr>
				<tr>
					<th id= "fila4"></th>
					<th id= "fila4"> Transferencias </th>
					<th id= "fila4"> B.Origen </th>
					<th id= "fila4"> Fecha</th>
					<th id= "fila4"> Destino  </th>
					<th id= "fila4"></th>
				 
				</tr>
		<?php
							
							$_SESSION['usuario']=$usuario;
							 
							require('../conexion_mssql.php');	
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
								$arreglo[$x][6]=$row['CodDestino'];
								$x++; 
							}	
							$count = count($arreglo); 
							$y=0;
							while ( $y <= $count-1 ) 
							{
								 
		?>						
								<tr>
									<td id= "box"> <input id ="checkbox" name="checkbox[]" type="checkbox" value ="<?php echo $arreglo[$y][2] ?>"> </td>	
 									<td id= "fila4"> <?php echo $arreglo[$y][1] ?> </td>
									<td id= "filax"> <?php echo $arreglo[$y][4] ?></td>  
									<td id= "fila4"> <?php echo $arreglo[$y][5] ?></td> 
									<td id= "filax"> <?php echo $arreglo[$y][6] ?></td> 
									<td id= "fila4"> <?php echo $arreglo[$y][3] ?></td> 
									 
								</tr>
		<?php
								 
							$y=$y+1;	
							}	
							
		?>	
		</table>
		
		</form>
		</div>	
 </div> 
 
<?php	
			$usuario = $usuario1; 
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