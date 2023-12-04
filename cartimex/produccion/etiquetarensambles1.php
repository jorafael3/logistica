<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../css/tablas.css">
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
					$acceso	=$_SESSION['acceso'];
					$bodega	=$_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					$usuario1= trim($usuario); 
					if ($_POST['ensamble']==''){$ensamble= $_SESSION['ensamble'];}else {$ensamble = $_POST['ensamble'];}
					
					if ($base=='CARTIMEX'){
							require '../../headcarti.php';  
					}
					else{
							require '../../headcompu.php';
							$_SESSION['base']= $base; 
								
					}
					require('../../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('select Detalle, Cant ,GeneradaSerie from INV_PRODUCCION_ENSAMBLE E WITH (NOLOCK) 
											INNER JOIN VEN_FACTURAS F WITH (NOLOCK) ON F.ID = E.FacturaId
											where EnsambleId=:ensamble');		 
					$result->bindParam(':ensamble',$ensamble,PDO::PARAM_STR);
					$result->execute();
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$Detalle = $row['Detalle']; 
						$Cant = $row['Cant'];
						
					}
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('SELECT  UserId,Id , SerieEnsamblado
											FROM INV_PRODUCCION_ENSAMBLE_DT
											WHERE EnsambleId =:EnsambleId');		 
					$result1->bindParam(':EnsambleId',$ensamble,PDO::PARAM_STR);
					$result1->execute();
					$arreglo = array();
					$x=0; 
					while ($row1= $result1->fetch(PDO::FETCH_ASSOC)) 
							{ 
								$arreglo[$x][1]=$row1['Id'];
								$arreglo[$x][2]=$row1['SerieEnsamblado'];
								$EnsambladoPor=$row1['UserId'];
								$x++; 
							}
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Ensamble Etiquetado </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
	</div> 
<hr>	
	<div id="cuerpo2"  >
		<div align= "left" >
			<table id= "listado2" align= "center">
				<tr><td id="label2">
				<strong> Ensamble: </strong> <a> <?php echo $ensamble ?> </a> <br>
				<strong> ClienteId: </strong> <a> <?php echo $Detalle ?> </a> <br>
				<strong> EnsambladoPor: </strong> <a> <?php echo $EnsambladoPor ?>  </a>
				<strong> # Equipos: </strong> <a> <?php echo $Cant ?>  </a>
				<br></td></tr>		
			</table>	
			<br>
		</div>
	</div>	
<div  id="cuerpo2" align= "left">
	<div class=\"table-responsive-xs\" align= "center">
<?php
	echo "GS".$GS; 
	if ($GS==0)
		{
?>
		<form name = "formensamble" action="generarseriesensamb.php" method="POST" width="75%">
			<table  border="2" cellpadding="5" cellspacing="1">
				<tr>
					<td id="label" >Generar Series a Etiquetar: </td> 
				</tr>
				<tr>	
					<td colspan="2" align= "center"> <input id="submit" value=" Generar Series " type="submit"></td>
					<input name="ensamble" type="hidden" id="ensamble" size = "30" value= "<?php echo $ensamble ?>">
					<input name="cant" type="hidden" id="cant" size = "30" value= "<?php echo $Cant ?>">
				</tr>
			</table>
		</form>	
<?php
		}
?>		
			<br>
			<form name = "formensamble" action="recibirensamble0.php" method="POST" width="75%">
			<table id= "listado2" align= "center"  > 
				<tr> 
					<th> # </th>
					<th> SerieEnsamblado </th>
				</tr>	
				<?php
				$count = count($arreglo); 
				$y=0;
				$n=1; 
				while ( $y <= $count-1 ) 
				{				
					?>
						<tr> 
							<td id= "fila2" align=left> <?php echo "Equipo".$n ?></td> 
							<td id= "fila2" align=left> <?php echo $arreglo[$y][2] ?></td> 
							<input name="bodega" type="hidden" id="bodega" size = "30" value= "<?php echo $arreglo[$y][1] ?>">
						</tr>
					<?php						
					$y=$y+1;
					$n=$n+1;
				}
					$n=$n-1; 
					$_SESSION['usuario']=$usuario1;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['bodega']= $bodega;	
			}
		else
			{
				header("location: index.html");
			}			
?>		 		
			</table>
			</form> 
	</div>
	<br>	
</div>
</div>		
</body>	
</html>	
 

