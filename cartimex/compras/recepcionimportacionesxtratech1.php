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
					if ($_POST['IDLiq']==''){$LiqID= $_SESSION['IDLiq'];}else {$LiqID = $_POST['IDLiq'];}
					
					if ($base=='CARTIMEX'){
							require '../../headcarti.php';  
					}
					else{
							require '../../headcompu.php';
							$_SESSION['base']= $base; 
								
					}

					require('../../conexion_mssqlxtratech.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare('select LiqID = id, Detalle,  Fecha = CONVERT( char(10), Fecha, 103), CreadoDate 
											from IMP_LIQUIDACION where ID =:LiqID');		 
					$result->bindParam(':LiqID',$LiqID,PDO::PARAM_STR);
					$result->execute();
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$IDLiq=$row['LiqID'];
						$Fecha=$row['Fecha'];
						$Detalle=$row['Detalle'];
					}	
					$pdodt = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$resultdt = $pdodt->prepare('IMP_PRODUCTOS_SERIES_LIQUIDACION @ID=:ID');		 
					$resultdt->bindParam(':ID',$IDLiq,PDO::PARAM_STR);
					$resultdt->execute();
					$arreglo  = array();
					$cantprod= 0 ; 
					$ingprod =0 ; 
					$x=0; 
					while ($rowdt = $resultdt->fetch(PDO::FETCH_ASSOC)) 
						{ 
							$arreglo[$x][1]=$rowdt['Código'];
							$arreglo[$x][2]=$rowdt['Nombre'];
							$arreglo[$x][3]=number_format($rowdt['Facturado'], 0, '.', '');
							$arreglo[$x][4]=$rowdt['registroSeries'];
							$arreglo[$x][5]=$rowdt['ProductoID'];
							$arreglo[$x][6]=$rowdt['cantrecibida'];
							$cantprod = $cantprod + number_format($rowdt['Facturado'], 0, '.', '');
							$ingprod  = $ingprod  + $rowdt['cantrecibida'];
							$x++; 
						}
						
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Importacion</center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
	</div> 
<hr>	
	<div id="cuerpo2"  >
		<div align= "left" >
			<table id= "listado2" align= "center">
				<tr><td id="label2">
				<strong> Id: </strong> <a> <?php echo $IDLiq ?> </a> </tr>
				<tr><td id="label2">
				<strong> DAU: </strong> <a> <?php echo $Detalle ?> </a> </tr>
				<tr><td id="label2">
				<strong> Fecha: </strong> <a> <?php echo $Fecha ?>  </a> </tr>
			</table>
				<br>
		</div>
	</div>	
<div  id="cuerpo2" align= "left">
	<div class=\"table-responsive-xs\" align= "center">
			
			<table id= "listado2" align= "center"  > 
				<tr> 
					<th> # </th>
					<th> CODIGO </th>
					<th> ARTICULO </th>
					<th> CANT. </th>
					<th> RECIB. </th>
					<th> DIF.	</th>
				</tr>	
<?php
				$count = count($arreglo); 
				$y=0;
				$n=1; 
				while ( $y <= $count-1 ) 
				{				
					?>
						<tr> 
							<td id= "fila2" align=left> <?php echo $n ?></td> 
							<td id= "fila2" align=left> <a href="ingresarcantidadrecibidaliqxtra.php?liqid=<?php echo $IDLiq ?>&productoid=<?php echo $arreglo[$y][5] ?>" > <?php echo $arreglo[$y][1] ?></td> 
							<td id= "fila2" align=left> <?php echo $arreglo[$y][2] ?></td> 
							<td id= "fila" align=left> <?php echo $arreglo[$y][3] ?></td> 
							<td id= "fila" align=left> <?php echo $arreglo[$y][6] ?></td>
							<td id= "fila" align=left> <?php echo $arreglo[$y][3]- $arreglo[$y][6] ?></td>
						</tr>	
<?php
 						
					$y=$y+1;
					$n=$n+1;
				}
				if (($ingprod > 0  )  ){
?>
			</table>
			<br>
			
			<form name = "formcompra" action="recepcionimportacionesxtratech2.php" method="POST" width="75%" enctype="multipart/form-data">
				<table  align= "center" border= "2" >
					<tr>
						<td id="label" >Foto: </td>
						<td><input type="file" name="foto3"/></td> 
					</tr>
					<tr>
						<td id= "label"> Observaciones:  </td>
						<td> <textarea id="Nota1" name="Nota1" rows="4" cols="50">  </textarea> </td>
					</tr>
					<tr>
						 
						<td colspan = "2" align= "center"><input id="submit" value=" RECIBIR LIQUIDACION " type="submit">
						<input type="hidden" name="LiqID" value="<?php echo $IDLiq ?>" /></td>
					</tr>	
				</table>
			</form>
<?php
				}
					$n=$n-1; 
					$_SESSION['usuario']=$usuario1;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo;
					$_SESSION['bodega']= $bodega;
			}
		else
			{
				header("location: index.html");
			}			
?>		 		
			
			
			
	</div>
	<br>	
</div>
</div>		
</body>	
</html>	
 

