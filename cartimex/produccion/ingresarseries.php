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
					$EnsambleId = $_SESSION['EnsambleId'];	
					
					if ($base=='CARTIMEX'){
							require '../../headcarti.php';  
					}
					else{
							require '../../headcompu.php';
							$_SESSION['base']= $base; 
								
					}
					//$mypassword=MD5('R123456');
					//echo $EmsambleId; 
					require('../../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('SELECT EnsambleId,Scpu,Smain,Shdd,Smem1,Smem2,Soptico,Sfdd,Sotro1,Sotro2,UserId,Id 
											FROM INV_PRODUCCION_ENSAMBLE_DT
											WHERE EnsambleId =:EnsambleId');		 
					$result->bindParam(':EnsambleId',$EnsambleId,PDO::PARAM_STR);
					$result->execute();
					$arreglo = array();
					$x=0; 
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
							{ 
								$arreglo[$x][1]=$row['Scpu'];
								$arreglo[$x][2]=$row['Smain'];
								$arreglo[$x][3]=$row['Shdd'];
								$arreglo[$x][4]=$row['Smem1'];
								$arreglo[$x][5]=$row['Smem2'];
								$arreglo[$x][6]=$row['Soptico'];
								$arreglo[$x][7]=$row['Sfdd'];
								$arreglo[$x][8]=$row['Sotro1'];
								$arreglo[$x][9]=$row['Sotro2'];
								$arreglo[$x][10]=$row['UserId'];
								$arreglo[$x][11]=$row['Id'];
								$x++; 
							}
					//echo '<pre>', print_r($arreglo),'</pre>';
					//die(); 	
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Ingresar Series de Componentes Ensamble # <?php echo $EnsambleId; ?> </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
				 
	</div> 
<hr>	
	<div id="cuerpo2"  >
		<div align= "left" >
	
		</div>
	</div>	
<div  id="cuerpo2" align= "left">
	<div class=\"table-responsive-xs\" align= "center">
			<form name = "formensamble" action="ingresarseries1.php" method="POST" width="75%">
			<table id= "listado2" align= "center"  > 
				<tr> 
					<th> #Id </th>
					<th> Procesador </th>
					<th> Mainboard </th>
					<th> Disco Duro </th>
					<th> Memoria 1 </th>
					<th> Memoria 2 </th>
					<th> Optico </th>
					<th> FDD/Lector 2 </th>
					<th> Otro 1  </th>
					<th> Otro 2	 </th>
					<th> User </th>
				</tr>	
<?php
				$count = count($arreglo); 
				$y=0;
				$Maq= 1; 
				while ( $y <= $count-1 ) 
					{								
?>
						<tr> 
							<td id= "fila" align=left> <?php echo "MAQUINA".$Maq ?> </td> 
							<td id= "fila" align=left> <Input Type= "text" Name='Scpu[]' id='Scpu[]' value = <?php echo $arreglo[$y][1] ?> > </td> 
							<td id= "fila" align=left> <Input Type= "text" Name='Smain[]' id='Smain[]' value = <?php echo $arreglo[$y][2] ?> ></td> 
							<td id= "fila" align=left> <Input Type= "text" Name='Shdd[]' id='Shdd[]' value = <?php echo $arreglo[$y][3] ?> ></td> 	
							<td id= "fila" align=left> <Input Type= "text" Name='Smem1[]' id='Smem1[]' value = <?php echo $arreglo[$y][4] ?> ></td>
							<td id= "fila" align=left> <Input Type= "text" Name='Smem2[]' id='Smem2[]' value = <?php echo $arreglo[$y][5] ?> ></td>
							<td id= "fila" align=left> <Input Type= "text" Name='Soptico[]' id='Soptico[]' value = <?php echo $arreglo[$y][6] ?> ></td>
							<td id= "fila" align=left> <Input Type= "text" Name='Sfdd[]' id='Sfdd[]' value = <?php echo $arreglo[$y][7] ?> ></td>
							<td id= "fila" align=left> <Input Type= "text" Name='Sotro1[]' id='Sotro1[]' value = <?php echo $arreglo[$y][8] ?> ></td>
							<td id= "fila" align=left> <Input Type= "text" Name='Sotro2[]' id='Sotro2[]' value = <?php echo $arreglo[$y][9] ?> ></td>
							<td id= "fila" align=left> <?php echo $arreglo[$y][10] ?> </td>
							<Input Type= "hidden" Name='Id[]' id='Id[]' value = <?php echo $arreglo[$y][11] ?> > 
							
						</tr>
<?php						
						$y=$y+1;
						$Maq= $Maq+1; 
					}
					$n=$n-1; 
					$_SESSION['usuario']=$usuario1;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo; 
					$_SESSION['bodega']= $bodega;
					$_SESSION['EnsambleId']= $EnsambleId ;
					
						
			}
		else
			{
				header("location: index.html");
			}			
?>		 		
			</table>
			<br>
			<input id="submit" value="Grabar Series" type="submit">
			</form> 
			<form name = "formensamble" action="finalizarseries.php" method="POST" width="75%">
				<table  border="2" cellpadding="5" cellspacing="1">
					<tr>
						<td id="label" >Finalizar Ingreso de Series  </td> 
						<Input Type= "hidden" Name='Ensamblado' id='Ensamblado' value = <?php echo $EnsambleId ?> >
					</tr>
					<tr>	
						<td align= "center"> <input id="submit" value=" Finalizar " type="submit"></td>
					</tr>
				</table>
			<form>
	</div>
	<br>	
</div>
</div>		
</body>	
</html>	
 

