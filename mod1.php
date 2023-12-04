<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/tablas.css">
<link rel="stylesheet" type="text/css" href="css/boton.css">
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<meta name="viewport" content="width=device-width, height=device-height">
<html>
<head>
<title>Sistema SISCO</title>
</head>
<body>
<div id= "header" align= "center">
<?PHP
// para modificar numero de guia y bultos
session_cache_limiter('private, must-revalidate');
session_cache_expire(60);

session_start();
	//Variables de sesion de SGL
	$usuario = $_SESSION['usuario'];
	$base = $_SESSION['base'];
	$acceso	=$_SESSION['acceso'];
	$secu = TRIM($_POST["secu"]);
	$bodegaf = $_SESSION['bodega'];
	$nomsuc = $_SESSION['nomsuc'];
	$_SESSION['usuario']= $usuario;
	$usuariof= $_SESSION['usuario']; 
	if ($base=='CARTIMEX'){
		require 'headcarti.php';  
		}
	else{
		require 'headcompu.php';
		}
//*******************************************************************
	
//if (!isset($_SESSION["usuario"])) {header("Location: index1.php");}
//include("barramenu.html");
if ($_SESSION["nivel"]=='99'){die( "<br><br><h2>No tiene acceso a esta opción!</h2>");}

include("conexion.php");
date_default_timezone_set('America/Guayaquil');

// Recibo el ID de la factura
//if (!empty($_POST['numfac'])) { $numfac=$_POST['numfac'];}  else {$numfac=$_GET['numfac'];}
$sec=$_GET['sec'];
 
//antes de leer factura leo los bultos y demas cosas
$sqlmy = "SELECT * FROM covidsales where factura = '$sec' ";
//echo "<br><br><br><br>".$sqlmy;
$resultmy = mysqli_query($con, $sqlmy);
$rowmy = mysqli_fetch_array($resultmy);
$bultos = $rowmy['bultos'];
$datadesp = $rowmy['despacho'];
$datafinalorig= $rowmy['despachofinal'];
$datadesporig = $rowmy['despacho'];
$bultosorig = $rowmy['bultos'];
$direccion= $rowmy['direccion'];
$referencia= $rowmy['referencias'];
$comentario= $rowmy['comentarios'];
$ciudad= $rowmy['ciudad'];
$celular= $rowmy['celular'];
$mail= $rowmy['mail'];

?>
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				<div id="container"  >
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Guias Facturas  <?php echo substr($nomsuc,10,20); ?> </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a></div>
				</div>
	</div> 
<hr>
<?php
$busqueda = str_replace(" ","%",$busqueda);
$localtotales = '';
$cuenta =1;
$datamail = '';
//$datamail .=  "<br><br>";
include("conexion_mssql.php");
 
$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
$result = $pdo->prepare('PER_Detalle_Facturas2 @secuencia=:secu ');
$result->bindParam(':secu',$sec,PDO::PARAM_STR);
$result->execute();
$count = $result->rowcount();
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
 				if ($row['Section']=='HEADER')
				{
						$datamail .=  "<center><table border=2  cellspacing=0 width=80% ></center>";
						$datamail .=  "<tr>";
						$datamail .=  "<th colspan =12><H2>Detalle de factura : ".$rowmy['factura']."</th><tr>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Fecha</B></th>";
						$datamail .=  "<td align='left'>"  .substr($row['Fecha'],0,-13).  "</td>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Secuencia</B></th>";
						$datamail .=  "<td align='left'>"  .$row['Secuencia'].  "</td>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Nombre</B></th>";
						$datamail .=  "<td align='left' colspan =2>"  .$row['Nombre'].  "</td>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Vendedor</B></th>";
						$datamail .=  "<td align='left' colspan =2>"  .$row['Vendedor'].  "</td>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Otro</B></th>";
						$datamail .=  "<td align='left'>".$row['Sucursal'].  "</td><tr>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Ciudad</B></th>";
						$datamail .=  "<td align='left'>"  .$ciudad.  "</td> ";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Telefono</B></th>";
						$datamail .=  "<td align='left'>"  .$celular.  "</td> ";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Correo</B></th>";
						$datamail .=  "<td align='left' colspan =2>"  .$mail.  "</td>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Comentario</B></th>";
						$datamail .=  "<td align='left' colspan =4>"  .$comentario.  "</td><tr> ";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Direccion</B></th>";
						$datamail .=  "<td align='left' colspan =6>"  .$direccion.  "</td>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Referencia</B></th>";
						$datamail .=  "<td align='left' colspan =4>"  .$referencia.  "</td><tr>";
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
						
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>SubTotal</B></th>";
						$datamail .=  "<td align='left'>$"  .number_format($SubTotal,2,",",".").  "</td>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Descuento</B></th>";
						$datamail .=  "<td align='left'>$"  .number_format($Descuento,2,",",".").  "</td>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Financiamiento</B></th>";
						$datamail .=  "<td align='left'>$"  .number_format($Financiamiento,2,",",".").  "</td>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0><B>Impuesto</B></th>";
						$datamail .=  "<td align='left'>$"  .number_format($Impuesto,2,",",".").  "</td>";
						$datamail .=  "<th bgcolor='$color1' align=center height=0 colspan =2><B>Total</B></th>";
						$datamail .=  "<td align='left' colspan =2>$"  .number_format($Total,2,",",".").  "</td><tr>";


				$SubTotalt = 0;
				$Impuestot = 0;
				$Totalt = 0;
				$datamail .=  " <table border=1  cellspacing=0 width=80% >";
				
				$datamail .=  "<tr>";
				$datamail .=  "<th bgcolor='$color1' align=center  width=10% height=0><B>Código</B></th>";
				$datamail .=  "<th bgcolor='$color1' align=center  width=30% height=0><B>Descripción</B></th>";
				$datamail .=  "<th bgcolor='$color1' align=center   height=0><B>Cant.</B></th>";
				$datamail .=  "<th bgcolor='$color1' align=center   height=0><B>Precio</B></th>";
				$datamail .=  "<th bgcolor='$color1' align=center   height=0><B>SubTotal </B></th>";
				$datamail .=  "<th bgcolor='$color1' align=center   height=0><B>Descuento </B></th>";
				$datamail .=  "<th bgcolor='$color1' align=center   height=0><B>Impuesto </B></th>";
				$datamail .=  "<th bgcolor='$color1' align=center   height=0><B>Total </B></th></td>";
				$datamail .= "<tr>";
				$SubTotalt = $row['SubTotal'];
				$Impuestot =  $row['Impuesto'];
				$Totalt =  $row['Total'];	
				$SubTotalt2 =  $row['SubTotal'];
				$TotFin =  $row['Financiamiento'];
				$Impuestot2 =  $row['Impuesto'];
				$Totalt2 = $row['Total'];
				}
				else  // del if ($row['Section']=='HEADER')
				{				
					$datamail .=  "<td align='left'>"  .$row[utf8_decode('Codigo')].  "</td>";
					$datamail .=  "<td align='left'>"  .utf8_encode($row['Nombre']) .  "</td>";	  
					$datamail .=  "<td align='right'>"  .number_format($row['Cantidad'],2,",",".")  .  "</td>";
					$datamail .=  "<td align='right'>$"  .number_format($row['Precio'],2,",",".")  .  "</td>";
					$datamail .=  "<td align='right'>$"  .number_format($row['SubTotal'],2,",",".")   .  "</td>";
					$datamail .=  "<td align='right'>$"  .number_format($row['Descuento'],2,",",".")   .  "</td>";
					$datamail .=  "<td align='right'>$"  .number_format($row['Impuesto'],2,",",".")   .  "</td>";
					$datamail .=  "<td align='right'>$"  .number_format($row['Total'],2,",",".")   .  "</td>";
					$datamail .= "<tr>";
				} // del if ($row['Section']=='HEADER')				    
     }

$datamail .=  "<tr>";
$datamail .=  "<Form Action='mod2.php' Method='post'>";
$datamail .= "<Input Type=hidden Name='numfac' value='$numfac'>";
$datamail .= "<Input Type=hidden Name='sec' value='$sec'>";
// paso los valores originales
$datamail .= "<Input Type=hidden Name='datafinalorig' value='$datafinalorig'>";
$datamail .= "<Input Type=hidden Name='datadesporig' value='$datadesporig'>";
$datamail .= "<Input Type=hidden Name='bultosorig' value='$bultosorig'>";

$datamail .="<td ><strong># de Bultos:</strong></td>";
$datamail .="<td ><Input Type=Text Size = 4 Maxlenght=4 Name='bultos' id='bultos' value = '$bultos' required></td>";
$datamail .="<td colspan = 2><strong>Info. Envio:</strong></td>";
$datamail .="<td colspan = 2><br><Input Type=Text Size = 40 Maxlenght=100 Name='despacho' id='despacho' value = '$datadesp' required>*<br> *Si el despacho es con URBANO, ingrese únicamente el número de guía sin comentarios adicionales</td>";

$datamail .= "<td><strong>Medio de Despacho:</strong></td><td>";
$datamail .= "<select name='medio'>";
if ($rowmy['despachofinal'] =='Urbano')
{$datamail .= "  <option value='Urbano' selected>Urbano</option>";}
else {$datamail .= "  <option value='Urbano'>Urbano</option>";}
if ($rowmy['despachofinal'] =='Servientrega')
{$datamail .= "  <option value='Servientrega' selected>Servientrega</option>";}
else {$datamail .= "  <option value='Servientrega'>Servientrega</option>";}
if ($rowmy['despachofinal'] =='Tramaco')
{$datamail .= "  <option value='Tramaco' selected>Tramaco</option>";}
else {$datamail .= "  <option value='Tramaco'>Tramaco</option>";}
if ($rowmy['despachofinal'] =='Vehiculo Computron')
{ $datamail .= " <option value='Vehiculo Computron' selected>Vehiculo Computron</option>";} 
else { $datamail .= " <option value='Vehiculo Computron'>Vehiculo Computron</option>";} 
if ($rowmy['despachofinal'] =='Entregado en tienda')
{ $datamail .= " <option value='Entregado en tienda' selected>Entregado en tienda</option>";}
else { $datamail .= " <option value='Entregado en tienda'>Entregado en tienda</option>";}

$datamail .= "</select></td></tr>";

$datamail .=  "<td colspan =10><br><center><input type=\"submit\" name=\"Submit\" value=\"Guardar Informacion\" class=\"btn btn-sm btn-primary\"></form><br></td>";
 
 //echo $numfac."aaaa".$sec;
echo $datamail ;
//echo "fin". $usuariof.$base.$acceso.$bodegaf.$nomsuc;
//Variables de sesion de SGL 
$_SESSION['usuario']=$usuariof;
$_SESSION['base']= $base ;
$_SESSION['acceso']=$acceso;
$_SESSION['bodega']=$bodegaf;
$_SESSION['nomsuc']=$nomsuc;

 ?>
</div>
</body>



