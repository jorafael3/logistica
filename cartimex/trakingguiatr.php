<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script>
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
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];		 
					$numero=$_GET['transfer'];
					$bodega	=$_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					$usuario= trim($usuario); 
					$usuario1 = $usuario; 
					$usuario= $usuario1; 
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
							$_SESSION['base']= $base; 
							$Nota= " "; 
					}
					
				   // $codigo= trim($codigo);
					require('../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//Select Query
					$result = $pdo->prepare('LOG_BUSQUEDA_TRANSFERENCIA @numero=:numero');		 
					$result->bindParam(':numero',$numero,PDO::PARAM_STR);
					$result->execute();
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$Numtransfer=$row['Numtransfer'];
						$Idtransfer=$row['Idtransfer'];
						$Detalle=$row['Detalle'];
						$Fecha=$row['Fecha'];
						$Nota=$row['Nota'];
						$Descodigo = $row['Descodigo'];
						$Destino=$row['Destino'];
						$Oricodigo=$row['Oricodigo'];	
						$Origen=$row['Origen'];
						$BodegaId=$row['BodegaId'];	
					}
					
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('LOG_Detalle_Transferencias_Traking @numero=:numero, @bodega=:bodega, @acceso=:acceso');		 
					$result1->bindParam(':numero',$numero,PDO::PARAM_STR);
					$result1->bindParam(':bodega',$bodega,PDO::PARAM_STR);
					$result1->bindParam(':acceso',$acceso,PDO::PARAM_STR);
					$result1->execute();
					$usuario= $_SESSION['usuario'];
					$arreglo  = array();
					$x=0; 
					while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
					{
						$PREPARADOPOR= $row1['PREPARADOPOR'];
						$FECHAYHORA= $row1['FECHAYHORA'];
						$VERIFICADO = $row1['VERIFICADO'];
						$FECHAVERIFICADO = $row1['FECHAVERIFICADO'];
						$GUIAPOR = $row1['GUIAPOR'];
						$FECHAGUIA = $row1['FECHAGUIA'];
						$GUIA = $row1['GUIA'];
						$TRANSPORTE = $row1['TRANSPORTE'];
						$BULTOS = $row1['BULTOS'];
						$DESPACHADO = $row1['DESPACHADO'];
						$FECHADESPACHADO = $row1['FECHADESPACHADO'];
						$PREPARAJAULA = $row1['PREPARAJAULA'];
						$FYHJAULA = $row1['FYHJAULA'];
						$arreglo[$x][1]=$row1['PRODUCTO'];
						$arreglo[$x][2]=$row1['PNOMBRE'];
						$arreglo[$x][3]=$row1['Cantidad'];
						$arreglo[$x][4]=$row1['NUMERO'];
						$x++; 
						
					}
					
?>		

</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Transferencia </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
			 
	</div> 
<hr>

<div id="cuerpo2"  class=\"table-responsive-xs\">
<div align= "center" width= 100%>
	<table border=2 width=100% id= "traking">
		<tr> 
			<td id="td1" > <strong> BODEGA ORIGEN :</strong> </td> <td id="label4"> <a> <?php echo $Oricodigo ."  ". $Origen?> </a></td>
			<td id="td1" > <strong> BODEGA DESTINO : </strong> </td> <td id="label4" > <a> <?php echo $Descodigo ."  ". $Destino ?>  </a></td>  
		</tr>
		<tr>
			<td id="td1"> <strong> Transf.Agrup.: </strong> </td> <td id="label4"> <a> <?php echo $Idtransfer ?> </a> </td>
			<td id="td1"> <strong> Fecha : </strong> </td> <td id="label4"> <a> <?php echo $Fecha ?> </a> </td>
			
		</tr>
    	<tr>
			<td id="td1"> <strong> Detalle: </strong> </td> <td id="label4" colspan= "3"> <a> <?php echo $Detalle ?> </a> </td>
		</tr>		
		<tr>
			<td id="td1"> <strong> Preparado: </strong> </td> <td id="label4"> <a> <?php echo $PREPARADOPOR ?> </a> </td> 
			<td id="td1"> <strong> Fecha Preparado: </strong> </td> <td id="label4" colspan = "2"> <a> <?php echo $FECHAYHORA ?> </a> </td> 
		</tr>
		<tr>
			<td id="td1"> <strong> Preparado Jaula : </strong> </td> <td id="label4"> <a> <?php echo $PREPARAJAULA ?> </a> </td> 
			<td id="td1"> <strong> Fecha Preparado Jaula: </strong> </td> <td id="label4" colspan = "2"> <a> <?php echo $FYHJAULA ?> </a> </td> 
		</tr>
			<tr>
			<td id="td1"> <strong> Verificado Por  : </strong> </td> <td id="label4"> <a> <?php echo $VERIFICADO ?> </a> </td> 
			<td id="td1"> <strong> Fecha Verificado: </strong> </td> <td id="label4" colspan = "2"> <a> <?php echo $FECHAVERIFICADO ?> </a> </td> 
		</tr>
    </table>	
	<table border=2 width=100% id= "traking" align= "center">
	<th> Transfer. </th>
	<th> Codigo</th>
	<th> Producto </th>
	<th> Cant. </th>
		<?php
			$count = count($arreglo); 
			$y=0;
			while ( $y <= $count-1 ) 
				{
		?>			
		<tr>
			<td id= "label4" > <?php echo $arreglo[$y][4] ?> </td>
			<td id= "label4" > <?php echo $arreglo[$y][1] ?> </td> 
			<td id= "label4" > <?php echo $arreglo[$y][2] ?> </td>
			<td id= "label5" > <?php echo number_format($arreglo[$y][3]) ?> </td>
		</tr>
		<?php						
				$y=$y+1;
				}			
					$_SESSION['usuario']=$usuario;
					$_SESSION['id']=$Id;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo;
					$_SESSION['bodega']=$bodega;
					$_SESSION['nomsuc']=$nomsuc; 
					 
    
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
				<td>	<a href="ingguiastransferencias.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a> </td>
			</tr> 
		</table>
	</form>

</div>
</div>

</body>	
</html>