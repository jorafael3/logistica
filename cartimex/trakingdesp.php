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
					$secu=$_GET['secu'];
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
						$Bloqueado=$row['Bloqueado'];
						$Nota=$row['Nota'];
						$BodegaId=$row['BodegaId'];	
						$Bodeganame=$row['Bodeganame'];
						$Observacion = $row['Observacion'];
						$Medio = $row['Medio'];
						$Empmail = $row['Empmail'];
						$Fpago= $row['FPago'];
						$lcomentario= $row['Notad'];
					}
					
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('LOG_Detalle_Facturas_Traking @secu=:secu, @bodega=:bodega, @acceso=:acceso');		 
					$result1->bindParam(':secu',$secu,PDO::PARAM_STR);
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
						$PESO = $row1['PESO'];
						$DESPACHADO = $row1['DESPACHADO'];
						$FECHADESPACHADO = $row1['FECHADESPACHADO'];
						$ruta = $row1['rutaFactura'];
						$Subtotal = number_format($row1['TSUBTOTAL'],2);
						$Descuento = number_format($row1['TDESCUENTO'],2);
						$Finan = number_format($row1['TFINAN'],2);
						$Impuesto = number_format($row1['TIMPUESTOS'],2);
						$Total = number_format($row1['TTOTAL'],2);
						$arreglo[$x][1]=$row1['PRODUCTO'];
						$arreglo[$x][2]=$row1['PNOMBRE'];
						$arreglo[$x][3]=number_format($row1['SubTotal'],2);
						$arreglo[$x][4]=number_format($row1['Descuento'],2);
						$arreglo[$x][5]=number_format($row1['Financiamiento'],2);
						$arreglo[$x][6]=number_format($row1['Impuesto'],2);
						$arreglo[$x][7]=number_format($row1['Total'],2);
						$arreglo[$x][8]= number_format($row1['Cantidad'],0);
						$x++; 
						
					}
					
?>		

</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Datos de Factura </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
			 
	</div> 
<hr>

<div id="cuerpo2"  class=\"table-responsive-xs\">
<div align= "center" width= 100%>
	<table border=2 width=100% id= "traking">
		<tr> 
			<td id="td1" width="15%"> <strong> BODEGA :</strong> </td> <td id="label4"> <a> <?php echo $Codbodega ?> </a></td>
			<td id="td1" width="10%"> <strong> Fact.#: </strong> </td> <td id="label4" colspan= "2"> <a> <?php echo $Secuencia ?></a></td>  
		</tr>
		<tr>
			<td id="td1"> <strong> Ruc: </strong> </td> <td id="label4"> <a> <?php echo $Ruc ?> </a> </td>
			<td id="td1"> <strong> Fecha Factura: </strong> </td> <td id="label4" colspan= "2"> <a> <?php echo $Fecha ?> </a> </td>
		</tr>
    	<tr>
			<td id="td1"> <strong> Cliente: </strong> </td> <td id="label4" colspan= "4"> <a> <?php echo $Nombre ?> </a> </td>
		</tr>		
		<tr>
			<td id="td1"> <strong> Preparado: </strong> </td> <td id="label4"> <a> <?php echo $PREPARADOPOR ?> </a> </td> 
			<td id="td1"> <strong> Fecha Preparado: </strong> </td> <td id="label4" colspan = "2"> <a> <?php echo $FECHAYHORA ?> </a> </td> 
		</tr>
		<tr>
			<td id="td1"> <strong> Verificado: </strong> </td> <td id="label4"> <a> <?php echo $VERIFICADO ?> </a> </td> 
			<td id="td1"> <strong> Fecha Verificado: </strong> </td> <td id="label4" colspan = "2"> <a> <?php echo $FECHAVERIFICADO ?> </a> </td> 
		</tr>
		<tr>
			<td id="td1"> <strong> Ingreso Guia: </strong> </td> <td id="label4"> <a> <?php echo $GUIAPOR ?> </a> </td> 
			<td id="td1"> <strong> Fecha Guia: </strong> </td> <td id="label4" colspan = "2"> <a> <?php echo $FECHAGUIA ?> </a> </td> 
		</tr>
		<tr>
			<td id="td1"> <strong> Transporte: </strong> </td> <td id="label4"> <a> <?php echo $TRANSPORTE ?> </a> </td> 
			<td id="td1"> <strong> Guia #: </strong> </td> <td id="label4" colspan = "2"> <a> <?php echo $GUIA ?> </a> </td> 
		</tr>	
		<tr>
			<td id="td1"> <strong> Bultos: </strong> </td> <td id="label4"> <a> <?php echo $BULTOS ?> </a> </td> 
			<td id="td1"> <strong> Peso: </strong> </td> <td id="label4" colspan = "2"> <a> <?php echo $PESO ?> </a> </td> 
		</tr>			
		<tr>
			<td id="td1"> <strong> Comentario Despacho: </strong> </td> <td id="label4" colspan= "4"> <a> <?php echo $lcomentario ?> </a> </td> 
		</tr>
    </table>
</div>	
	<table border=2 width=100% id= "traking" align= "center">
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
			<td id= "label4" > <?php echo $arreglo[$y][1] ?> </td> 
			<td id= "label4" > <?php echo $arreglo[$y][2] ?> </td>
			<td id= "label5" > <?php echo $arreglo[$y][8] ?> </td>
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
					$_SESSION['secu']=$Secuencia; 
					 
    
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
				<td>	<a href="despacharfacturas.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a> </td>
			</tr> 
		</table>
	</form>


</div>

</body>	
</html>