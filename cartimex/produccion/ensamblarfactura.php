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
					if ($_POST['secu']==''){$secu= $_SESSION['secu'];}else {$secu = $_POST['secu'];}
					
					if ($base=='CARTIMEX'){
							require '../../headcarti.php';  
					}
					else{
							require '../../headcompu.php';
							$_SESSION['base']= $base; 
								
					}

					require('../../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare('LOG_BUSQUEDA_FACTURA @secuencia=:secu');		 
					$result->bindParam(':secu',$secu,PDO::PARAM_STR);
					$result->execute();
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$Codbodega=$row['Codbodega'];
						$Secuencia=$row['Secuencia'];
						$Numero=$row['Numero'];
						$Id=$row['Id'];
						$ClienteId=$row['ClienteId'];
						$Ruc = $row['Ruc'];
						$Nombre=$row['Nombre'];
						$TipoCLi=$row['TipoCLi'];	
						$Fecha=$row['Fecha'];
						$Direccion=$row['Direccion'];	
						$Ciudad=$row['Ciudad'];
						$Telefono=$row['Telefono'];
						$Mail=$row['Email'];
						$Contacto=$row['Contacto'];	
						$Vendedor=$row['Vendedor'];
						$Bloqueado=$row['Bloqueado'];
						$Nota=$row['Nota'];
						$BodegaId=$row['BodegaId'];	
						$Bodeganame=$row['Bodeganame'];
						$Observacion = $row['Observacion'];
						$Medio = $row['Medio'];
						$Empmail = $row['Empmail'];
						$TipoP = $row['TipoP'];
					}	
					$Nota = " "; 
					
				
					$pdodt = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$resultdt = $pdodt->prepare('select p.Código, p.Nombre, dt.Cantidad, pr.EnsambleId 
											from VEN_FACTURAS_DT dt with (nolock)
											inner join INV_PRODUCCION_ENSAMBLE pr with (nolock) on pr.facturaid= dt.facturaid
											inner join ven_ordenes_dt odt with (nolock) on odt.id= dt.OrdenDTID
											inner join INV_PRODUCTOS p with (nolock) on dt.ProductoID= p.id 
											where IsEnsamble= 1 and dt.FacturaID= :FacturaID');		 
					$resultdt->bindParam(':FacturaID',$Id,PDO::PARAM_STR);
					$resultdt->execute();
					$arreglo  = array();
					$x=0; 
					while ($rowdt = $resultdt->fetch(PDO::FETCH_ASSOC)) 
						{ 
							$arreglo[$x][1]=$rowdt['Código'];
							$arreglo[$x][2]=$rowdt['Nombre'];
							$arreglo[$x][3]= number_format($rowdt['Cantidad'],0);
							$EnsambleId = $rowdt['EnsambleId'];
							$x++; 
						}
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Factura </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
				 
	</div> 
<hr>	
	<div id="cuerpo2"  >
		<div align= "left" >
			<table id= "listado2" align= "center">
				<tr><td><strong> BODEGA : <a> <?php echo $Codbodega ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php if (  $Bloqueado=="1") {echo "Bloqueada!!";} elseif  ($Nota<> " ") { echo "Desbloqueada!!";} else {echo "";} ?>	</strong></td></tr>
				<tr><td id="label2">
				<strong> Id: </strong> <a> <?php echo $Id ?> </a> 
				<strong> Numero: </strong> <a> <?php echo $Numero ?> </a>
				<strong> Factura # : </strong> <a> <?php echo $Secuencia ?>  </a>
				<br></td></tr>
				<tr><td id="label2">
				<strong> Cliente: </strong> <a> <?php echo $Nombre ?> </a>
				<strong> Tipo: </strong> <a> <?php echo $TipoCLi ?>  </a>
				<br></td></tr>
				<tr><td id="label2">
				<strong> Direccion: </strong> <a> <?php echo $Direccion ?> </a>
				<br></td></tr>
				<tr><td id="label2">
				<strong> Ciudad: </strong> <a> <?php echo $Ciudad ?>  </a>
				<strong> Telefono: </strong> <a> <?php echo $Telefono ?> </a>
				<strong> Mail: </strong> <a> <?php echo $Mail ?>  </a>
				<br></td></tr>
				<tr><td id="label2">
				<strong> Contacto: </strong> <a> <?php echo $Contacto ?> </a>
				<br></td></tr>
				<tr><td id="label2">
				<strong> Vendedor: </strong> <a> <?php echo $Vendedor ?> </a>
				<strong> Email: </strong> <a> <?php echo $Empmail ?> </a>
				<br></td></tr>
				<tr><td id="label2">
				<strong> Fecha y Hora de Creacion: </strong> <a> <?php echo $Fecha ?> </a>
				<br></td></tr>	
				<tr><td id="label2">
				<strong> Observaciones: </strong> <a> <?php echo $Observacion ?> </a>
				<br></td></tr>	
				<tr><td id="label2">
				<strong> Medio Transporte: </strong> <a> <?php echo $Medio  ?> </a>
				<br></td></tr>	
				<tr><td id="label2">
				<strong> Estado: </strong> <a> En Produccion </a>
				<br></td></tr>	
			</table>	
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
							<td id= "fila2" align=left> <?php echo $arreglo[$y][1] ?></td> 
							<td id= "fila2" align=left> <?php echo $arreglo[$y][2] ?></td> 
							<td id= "fila" align=left> <?php echo $arreglo[$y][3] ?></td> 	
						</tr>
					<?php						
					$y=$y+1;
					$n=$n+1;
					?>
					<?php
					
				}
					$n=$n-1; 
					$_SESSION['usuario']=$usuario1;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo;
					$_SESSION['productos']=$n;
					$_SESSION['secu']=$secu;
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
			<form name = "formseries" action="ingresarseries.php" method="POST" width="75%">
			<input id="submit" value=" Ingresar Series  " type="submit">
			</form> 
	</div>
	<br>	
</div>
</div>		
</body>	
</html>	
 

