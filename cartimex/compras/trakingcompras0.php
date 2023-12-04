<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script>
</script>
<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">

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
					if ($_POST["secu"]== ''){$ID = TRIM($_GET["secu"]);$Tipo = TRIM($_GET["Tipo"]);}
					else                    {$ID = TRIM($_POST["secu"]);$Tipo = TRIM($_POST["Tipo"]);}
					
					
					if ($_SESSION['status']=="statusdearribos.php"){$pag= $_SESSION['url']; } else {$pag="comprasrecibidas.php";}
				
			
					if ($base=='CARTIMEX'){require '../../headcarti.php'; }	else{require '../../headcompu.php'; $_SESSION['base']= $base; }
					require('../../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare('LOG_Detalle_Facturas_Compras @ID=:ID,@Tipo=:Tipo');		 
					$result->bindParam(':ID',$ID,PDO::PARAM_STR);
					$result->bindParam(':Tipo',$Tipo,PDO::PARAM_STR);
					$result->execute();
					$usuario= $_SESSION['usuario'];
					$arreglo  = array();
					$x=0; 
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$FECHA= $row['FECHA'];
						$ID = $row['ID'];
						$DETALLE = $row['Detalle'];
						$RECIBIDOPOR = $row['RecibidoPor'];
						$FechaInicio = $row['FInicioRecep'];
						$FechaFin = $row['FFinRecep'];
						$Contenedor= $row['Contenedor'];
						$Sellos = $row['Sello']." ". $row['Sello2'];
						$Tipo = $row['Tipo'];
						$Placa = $row['Placa'];
						$Estiba = $row['Estiba']; 
						$Foto1 = $row['Foto1'];
						$Foto2 = $row['Foto2'];
						$Foto3 = $row['Foto3'];
						$Nota = $row['Nota'];
						$Nota1 = $row['Nota1'];
						$arreglo[$x][1]=$row['Código'];
						$arreglo[$x][2]=$row['Nombre'];
						$arreglo[$x][3]= number_format($row['Facturada'],0);
						$arreglo[$x][4]= number_format($row['Ordenada'],0);
						$arreglo[$x][5]= number_format($row['Recibida'],0);
						$arreglo[$x][6]=$row['registroSeries'];
						$arreglo[$x][7]=$row['ProductoID'];
						$x++; 
					}
					
?>		

</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Liquidacion/Compra  </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
			 
	</div> 
<hr>

<div id="cuerpo2"  class=\"table-responsive-xs\">
<div align= "center" width= 75%>
	<table  width=75% id= "traking">
		<tr> 
			<td id="td1" width="15%"> <strong> Fecha Import.: </strong> </td> <td id="label4"> <a> <?php echo $FECHA ?> </a></td>
			<td id="td1" width="10%"> <strong> Liq/CompraID: </strong> </td> <td id="label4" width="20%"><a>  <?php echo $ID ?> </a></td>  
		</tr>
		<tr > 
			<td id="td1" width="15%"> <strong> Proveedor/DAU: </strong> </td> <td id="label4" colspan = "3"> <a> <?php echo $DETALLE ?> </a></td>	
		</tr>
		<tr > 
			<td id="td1" width="15%"> <strong> Contenedor: </strong> </td> <td id="label4"  > <a> <?php echo $Contenedor ?> </a></td>	
			<td id="td1" width="15%"> <strong> Sellos: </strong> </td> <td id="label4"  > <a> <?php echo $Sellos ?> </a></td>
		</tr>
		<tr > 
			<td id="td1" width="15%"> <strong> Tipo: </strong> </td> <td id="label4"  > <a> <?php echo $Tipo ?> </a></td>	
			<td id="td1" width="15%"> <strong> Placa: </strong> </td> <td id="label4"  > <a> <?php echo $Placa ?> </a></td>
		</tr>
		<tr > 
			<td id="td1" width="15%"> <strong> Recibido Por: </strong> </td> <td id="label4"  > <a> <?php echo $RECIBIDOPOR ?> </a></td>	
			<td id="td1" width="15%"> <strong> Estiba: </strong> </td> <td id="label4"  > <a> <?php echo $Estiba ?> </a></td>
		</tr>
		<tr > 
			<td id="td1" width="15%"> <strong> Fecha Inicio Recep: </strong> </td> <td id="label4"  > <a> <?php echo $FechaInicio ?> </a></td>	
			<td id="td1" width="15%"> <strong> Fecha Fin Recep: </strong> </td> <td id="label4"  > <a> <?php echo $FechaFin ?> </a></td>
		</tr>
		<tr > 
			<td id="td1" width="15%"> <strong> Imagenes Ad.: </strong> </td> <td id="label4" colspan = "3" > <a target="_blank" href= "obtenerimagen.php?foto=<?php echo substr($Foto1, 32, -4) ?>"> <?php echo substr($Foto1, 32, -4) ?> </a><br>
			<a target="_blank" href= "obtenerimagen.php?foto=<?php echo substr($Foto2, 32, -4) ?>"> <?php echo substr($Foto2, 32, -4) ?> </a><br>
			<a target="_blank" href= "obtenerimagen.php?foto=<?php echo substr($Foto3, 32, -4) ?>"> <?php echo substr($Foto3, 32, -4) ?> </a>
			</td>	
		</tr>
		<tr > 
			<td id="td1" width="15%"> <strong> Nota: </strong> </td> <td id="label4" colspan = "3" > <a> <?php echo $Nota ?> </a></td>	
		</tr>
		<tr > 
			<td id="td1" width="15%"> <strong> Observaciones: </strong> </td> <td id="label4" colspan = "3" > <a> <?php echo $Nota1 ?> </a></td>	
		</tr>
    </table>
	<br>	
	<table border=2 width=75% id= "traking" align= "center">
	<th> Codigo</th>
	<th> Producto </th>
	<th> Facturada </th>
	<th> Recibida </th>
		<?php
			$count = count($arreglo); 
			$y=0;
			while ( $y <= $count-1 ) 
				{ 
					if ($arreglo[$y][3]==($arreglo[$y][4]+$arreglo[$y][5])){$colorf="";}else{$colorf="yellow";}
					if ($arreglo[$y][4]==0){$arreglo[$y][4]='';}
					if ($arreglo[$y][5]==0){$arreglo[$y][5]='';}
		?>			
		<tr bgcolor="<?php echo $colorf ?>">
<?php
 if  ($arreglo[$y][6]==1){  
		echo "<td id= label4 > <a target=_blank href= series.php?ProductoID=".$arreglo[$y][7]."&ordenid=".$ID.">". $arreglo[$y][1]."</a></td>"; }
 else { 
		echo "<td id= label4 >".$arreglo[$y][1]."</td>"; }
?>			
  			
			<td id= "label4" > <?php echo $arreglo[$y][2] ?> </td>
			<td id= "label5" > <?php echo $arreglo[$y][3] ?> </td>
			<td id= "label5" > <?php echo $arreglo[$y][4].$arreglo[$y][5] ?> </td>
			
		</tr>
		<?php						
				$y=$y+1;
				}		
						
					$_SESSION['usuario']=$usuario;
					$_SESSION['id']=$Id;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
				}
				else
				{
					header("location: index.html");
				}	
				
?>		
	</table>
	<form name = "formpreparar" action="#" method="POST" width="75%">
		<table align ="center" >
			<tr>
						<a href="<?php echo $pag ?>" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a> 
				</td>
			</tr> 
		</table>
	</form>

</div>
</div>

</body>	
</html>