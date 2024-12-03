<!DOCTYPE html>
<html>
<script type="text/javascript">
	function setfocus()
	{
		document.getElementById("guia").focus();
	}
</script>
<head>
	<link rel="stylesheet" type="text/css" href="../css/tablas.css">
	<link rel="stylesheet" type="text/css" href="../css/boton.css"> 
	<html>
	<!-- <head> -->
	<title>SGL</title>
	<!-- <link rel="stylesheet" type="text/css" href="css/menus.css"> -->
	<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
	<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">

</head>
<html>
<body onload="setfocus()"> 
<div id= "header" align= "center">
	<?PHP
//error_reporting(E_ALL);
//ini_set('display_errors','On');
	session_start();
	//Variables de sesion de SGL
	$usuario = $_SESSION['usuario'];
	$base = $_SESSION['base'];
	$acceso	=$_SESSION['acceso'];
	if (TRIM($_POST["secu"])=='')
			{$secu= $_SESSION['secu'];}
	else	{$secu = TRIM($_POST["secu"]);}
	//$secu = TRIM($_POST["secu"]);
	$bodegaf = $_SESSION['bodega'];
	$nomsuc = $_SESSION['nomsuc'];
	 
	
	
		
	$_SESSION['usuario']= $usuario ;
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
				 
					<div id = "izq">  </div>
					<div id = "centro"> <a class="titulo"> <center>   Guias Facturas  <?php echo substr($nomsuc,10,20); ?> </center> </a></div>
					<div id = "derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div> 
<hr>
<div id= "cuerpo2" align= "center" width="100%">
<?php	
	
		if (!isset($_SESSION["usuario"])) {
			header("Location: index.php");
		}
	$usuariof= $usuario;
	 	
	// Recibo secuencia de la factura
	if (!empty($_POST['numfac'])) 
		{$numfac = $_POST['numfac'];}	 
	else 
				{
					$numfac = $_GET['numfac'];
				}	
		
	$sec = $_GET['sec'];
	
	echo "<div id='principal1'>";
	include("../conexion_mssql.php");
	//echo "Factura: ".$numfac;
	$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
	$result = $pdo->prepare('PER_Detalle_Facturas2 @secuencia=:secu ');
	$result->bindParam(':secu',$numfac,PDO::PARAM_STR);
	$result->execute();
	$count = $result->rowcount();
		
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
		{
			
			if ($row['msn'] <> "La Factura esta Anulada")
			{  
				 
				if ($row['Section'] == 'HEADER') 
				{
					$transporte= $row['Transporte'];
					// $bultos= $row['Bultos'];
					$peso = number_format($row['Peso'], 2, ".", ",");
					$Fid = $row['ID'];
					$ruta= $row['rutaFactura'];
					$BULTOS= $row['BULTOS'];
					  
					?>
					<left><table border=2 cellspacing=0 width=100%  ></left>
					<tr>
						<th colspan = 12><a>Detalle de Cliente </a></th>
					</tr> 
					<tr>
						<th bgcolor='$color1' align=center height=0 colspan =1><B>Fecha </B></th> 
						<td align='left' colspan =1 > <?php echo substr($row['Fecha'],0, -13); ?> </td>
						<th bgcolor='$color1' align=center height=0 colspan =1><B>Factura</B></th>
						<td align='left' colspan =4> <?php echo  $row['Secuencia']; ?></td>
						<th bgcolor='$color1' align=center height=0><B>Vendedor</B></th>
						<td align='left' colspan =4><?php echo $row['Vendedor']; ?></td> 
					</tr>
					<tr>
						<th bgcolor='$color1' align=center height=0 colspan =1><B>Ruc</B></th>
						<td align='left' colspan =1><?php echo $row['Cedula']; ?></td>
						<th bgcolor='$color1' align=center height=0 colspan =1><B>Cliente</B></th>
						<td align='left' colspan =4><?php echo $row['Nombre']; ?></td>
						<th bgcolor='$color1' align=center height=0><B>Sucursal</B></th>
						<td align='left' colspan =3><?php echo $row['Sucursal']; ?></td>
					</tr>
					<tr>
						<th bgcolor='$color1' align=center height=0 colspan =1>Ciudad</th>
						<td align='left' colspan=1><?php echo $row['Ciudad']; ?></td>
						<th bgcolor='$color1' align=center height=0 colspan=1>Email</th>
						<td align='left' colspan=4><?php echo $row['Email']; ?></td>
						<th bgcolor='$color1' align=center height=0 colspan=1>Telefono</th>
						<td align='left' colspan=3><?php echo $row['Telefono']; ?></td>
					
					</tr>	
					<tr>
						<th bgcolor='$color1' align=center height=0 colspan=2> Direccion</th>
						<td align='left' colspan=4><?php echo $row['Direccion']; ?></td>
						<th colspan=2> Direccion envío</th>
						<td colspan=4><?php echo $row['Envio']; ?></td>
					</tr>
					<tr>
						<th bgcolor='$color1' colspan=2> Comentario</th>
						<td align='left' colspan=10><?php echo $row['Nota']; ?></td>
					</tr>
					
					<left><table border=2 cellspacing=0 width=100%  ></left>
					<tr>
						<th colspan = 12><a>Detalle de Productos </a></th>
					</tr>					
					<tr>
						<th bgcolor='$color1' align=center colspan =2><B>Código</B></th>
						<th bgcolor='$color1' align=center colspan =4><B>Descripción</B></th>
						<th bgcolor='$color1' align=center ><B>Cant.</B></th>
						<th bgcolor='$color1' align=center ><B>Precio</B></th>
						<th bgcolor='$color1' align=center ><B>SubTotal </B></th>
						<th bgcolor='$color1' align=center ><B>Descuento </B></th>
						<th bgcolor='$color1' align=center ><B>Impuesto </B></th>
						<th bgcolor='$color1' align=center ><B>Total </B></th>
					</tr>
				
			<?php
				} 
				else   
				{
					if ($row['Section'] == 'DETALLE') 
					{
			?>		
						<tr>
						<td align='left' colspan =2><?php echo $row[utf8_decode('Codigo')]?> </td> 
						<td align='left' colspan =4><?php echo utf8_encode($row['Nombre'])?></td> 
						<td align='center'> <?php echo number_format($row['Cantidad'], 0, ",", ".") ?></td>
						<td align='right'>$<?php echo number_format($row['Precio'], 2, ",", ".")  ?></td>
						<td align='right'>$<?php echo  number_format($row['SubTotal'], 2, ",", ".")   ?></td>
						<td align='right'>$<?php echo  number_format($row['Descuento'], 2, ",", ".")   ?></td>
						<td align='right'>$<?php echo number_format($row['Impuesto'], 2, ",", ".")   ?></td>
						<td align='right'>$<?php echo number_format($row['Total'], 2, ",", ".")   ?></td>
						</tr>	
			<?php
					
					 	$SubTotal = $row['SubTotal'];
						$Descuento = $row['Descuento'];
						$Financiamiento = $row['Financiamiento'];
						$Impuesto = $row['Impuesto'];
						$Total = $row['Total'];
						$RentUSD = $row['RentUSD'];
						$Rent = $row['Rent'];
						$RentUSD2 = $row['RentUSD2'];
						$Rent2 = $row['Rent2'];
						$RetEsperada = $row['RetEsperada'];
						$Sucursal = $row['Sucursal'];
						$RecargoTC = $row['RecargoTC'];
					}	
					else
					{
						$SubTotalt = 0;
						$Impuestot = 0;
						$Totalt = 0;
						
						$SubTotalt = $row['SubTotal'];
						$Impuestot =  $row['Impuesto'];
						$Totalt =  $row['Total'];
						$SubTotalt2 =  $row['SubTotal'];
						$TotFin =  $row['Financiamiento'];
						$Impuestot2 =  $row['Impuesto'];
						$Totalt2 = $row['Total']; 
			?>		
					<tr>
						<td colspan="8">  </td>
						<th bgcolor='$color1' align=center height=0><B>SubTotal</B></th> 
						<td align='right' colspan =3>$<?php echo number_format($SubTotalt, 2, ",", ".") ?></td>
					</tr>
					<tr>
						<td colspan="8">  </td>
						<th bgcolor='$color1' align=center height=0><B>Descuento</B></th>
						<td align='right' colspan =3>$<?php echo number_format($Descuento, 2, ",", ".") ?></td>
					</tr>
					<tr>	
						<td colspan="8">  </td>
						<th bgcolor='$color1' align=center height=0><B>Iva 12%.</B></th>
						<td align='right' colspan =3>$<?php echo number_format($Impuestot, 2, ",", ".") ?></td>
					</tr>
					<tr>	
						<td colspan="8">  </td>
						<th bgcolor='$color1' align=center height=0><B>Total</B></th>
						<td align='right' colspan =3>$<?php echo number_format($Totalt, 2, ",", ".") ?></td>
					</tr>	 
			<?php
					
					}	
				}
			}
			else
			{	
			
			
				echo "<h3> Factura ANULADA </h3>"; 
				header("Refresh:5; ingguiasfacturas.php");
			}
		}				
	?>	
		<Form Action='detallefactura2.php' Method='post'>
		<Input Type=hidden Name='numfac' id= 'numfac' value=<?php echo $numfac ?> >
		<tr>
			<th colspan = 12><a>Detalle de Envío</a></th>
		</tr>
		<tr>
			
			<td colspan= 3> <a href="http://<?php echo $ruta ?>" target="_blank"> PDF_Factura </a> </td>
			<td colspan= 9> <a href= "consultarseriesguia.php?factid=<?php echo $Fid ?>" target="_blank" > CONSULTAR SERIES </a></td> 
		</tr>
		<tr>
			<th colspan =2>Transporte </th>
			<td>
				<select name='medio' required id = 'ddlpickup'>
<?php					
		require('../conexion_mssql.php');	
		$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$result1 = $pdo1->prepare("select id, Código  from SIS_PARAMETROS where PadreID='0000000090' order by 2" );
		$result1->execute();
		while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
			{
				$selected = '';
				if ($transporte == $row1['id']){
					$selected = 'selected';
				}
?>			
				<option value="<?php  echo $row1['id']; ?>" <?php echo $selected ?> ><?php echo $row1['Código']; ?></option>
<?php
			}
?>
				</select>
			</td>
			<td colspan= 10> </td>
		</tr>	
		<tr>	
			<th colspan =2>Guia # </th>
			<td colspan =1><Input Type='text'  Size = 30 Maxlenght=80  Name='guia' id='guia' > <font color = 'red'> <a>***Solo ingresar numero de guia o placa de camión sin comentarios adicional </a></font></td> 
			<th colspan =2># de Bultos:</th>
			<td colspan =3><Input Type=Text Size = 10 Maxlenght=5 Name='peso' id='peso'  value = '<?php echo $bultos ?>' required > </td>
			<th colspan =2>Peso Productos:</th>
			<td colspan =2><Input Type=Text Size = 10 Maxlenght=5 Name='peso' id='peso'  value = '<?php echo $peso ?>' required > </td>
			
		</tr>
		<tr> 
			<th colspan =2>Comentario de despacho:</th>
			<td colspan =10><br><textarea Size = 20 rows= 4 cols=120 Name='comentariodesp' id='comentariodesp'></textarea></td>
		</tr>
		</table>
		</div>
		<table border=0 cellspacing=0 width=100%>
		<td colspan =14><br><center><input type="submit" name="Submit" value="Guardar Información" class="btn btn-sm btn-primary"></td> 
		</form><br>
		</table>
<?php 		
	$_SESSION['usuario']=$usuariof;
	$_SESSION['base']= $base ;
	$_SESSION['acceso']=$acceso;
	$_SESSION['bodega']=$bodegaf;
	$_SESSION['nomsuc']=$nomsuc;

?>
</div>	
</div>	
</body>