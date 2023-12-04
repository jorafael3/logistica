<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/tablas.css">
	<link rel="stylesheet" type="text/css" href="css/boton.css"> 
	<html>
	<!-- <head> -->
	<title>SGL</title>
	<!-- <link rel="stylesheet" type="text/css" href="css/menus.css"> -->
	<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
	<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

</head>
<html>
<body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#ddlpickup").change(function() {
				if ($(this).val() == "Envio") {
					$("#dvdespacho").show();
				} else {
					$("#dvdespacho").hide();
				}
			});
		});
	</script>

<div id= "header" align= "center">
	

	<?PHP
	session_start();
	//Variables de sesion de SGL
	$usuario = $_SESSION['usuario'];
	$base = $_SESSION['base'];
	$acceso	=$_SESSION['acceso'];
	$secu =  substr($_POST["secu"],0,17);
	$bodegaf = substr($_POST["secu"],18,10) ;
	$nomsuc = $_SESSION['nomsuc'];
	$_SESSION['usuario']= $usuario ;
	if ($base=='CARTIMEX'){
		require 'headcarti.php';  
		}
	else{
		require 'headcompu.php';
		} 
	// aqui ingreso numero de guia y bultos
	//echo "bodega facturacion".$bodegaf;
?>
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq">  </div>
					<div id = "centro"> <a class="titulo"> <center>   Guias Facturas  <?php echo substr($nomsuc,10,20); ?> </center> </a></div>
					<div id = "derecha"> <a href="menu.php"><img src="assets\img\home.png"></a>  </div>
				 
	</div> 
<hr>
<div id= "cuerpo2" align= "center" width="100%">
<?php	
	
		if (!isset($_SESSION["usuario"])) {
			header("Location: index.php");
		}
	//include("barramenu.html");

	$usuariof= $usuario; 
	include("conexion.php");
	date_default_timezone_set('America/Guayaquil');

	$sec = $_POST['sec'];
	//***************************************************************************************
	echo "<div id='principal1'>";
	include("conexion_mssql.php");
	//echo "Factura: ".$secu. $bodegaf;
	$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
	$result = $pdo->prepare('PER_Detalle_Facturas2 @secuencia=:secu , @bodegaFAC=:bodegaf');
	$result->bindParam(':secu',$secu,PDO::PARAM_STR);
	$result->bindParam(':bodegaf',$bodegaf,PDO::PARAM_STR);
	$result->execute();
	/*$sql = "PER_Detalle_Facturas'" . $numfac . "' ";
	$result = mssql_query(utf8_decode($sql));*/
	$count = $result->rowcount();
		
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
		{
			
			if ($row['msn'] <> "La Factura esta Anulada")
			{  
				 
				if ($row['Section'] == 'HEADER') 
				{
					echo  "<left><table border=2 cellspacing=0 width=70%  ></left>";
					echo "<tr>";
					echo  "<th colspan = 12><h3>Información de Factura </h3></th></tr>";
					echo  "<tr><th bgcolor='$color1' align=center height=0><B>Fecha :</B></th>";
					echo "<td align='left' colspan =2 > "  . substr($row['Fecha'], 0, -13) .  "</td>";
					echo  "<th bgcolor='$color1' align=center height=0><B>Secuencia:</B></th>";
					echo "<td align='left' colspan =4>"  . $row['Secuencia'] .  "</td>";
					echo  "<th bgcolor='$color1' align=center height=0><B>Vendedor:</B></th>";
					echo  "<td align='left' colspan =3>"  . $row['Vendedor'] .  "</td> </tr>";
					echo "<tr><th bgcolor='$color1' align=center height=0><B>Ruc:</B></th>";
					echo  "<td align='left' colspan =2>"  . $row['Cedula'] .  "</td>";
					echo "<th bgcolor='$color1' align=center height=0><B>Nombre</B></th>";
					echo  "<td align='left' colspan =5>"  . $row['Nombre'] .  "</td>";
					echo "<th bgcolor='$color1' align=center height=0><B>Sucursal</B></th>";
					echo  "<td align='left' colspan =3>" . $row['Sucursal'] .  "</td><tr>";

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
					$ID = $row['ID'];

					echo "<th bgcolor='$color1' align=center height=0><B>SubTotal</B></th>";
					echo "<td align='right' colspan =2>$"  . number_format($SubTotal, 2, ",", ".") .  "</td>";
					echo "<th bgcolor='$color1' align=center height=0><B>Descuento</B></th>";
					echo "<td align='right'>$"  . number_format($Descuento, 2, ",", ".") .  "</td>";
					echo "<th bgcolor='$color1' align=center height=0><B>Finan.</B></th>";
					echo "<td align='right'>$"  . number_format($Financiamiento, 2, ",", ".") .  "</td>";
					echo  "<th bgcolor='$color1' align=center height=0><B>Impuesto</B></th>";
					echo "<td align='right'>$"  . number_format($Impuesto, 2, ",", ".") .  "</td>";
					echo "<th bgcolor='$color1' align=center height=0><B>Total</B></th>";
					echo "<td align='right' colspan =2>$"  . number_format($Total, 2, ",", ".") .  "</td><tr>";



					$SubTotalt = 0;
					$Impuestot = 0;
					$Totalt = 0;
					
					echo  "<tr>";
					echo "<th bgcolor='$color1' align=center  colspan =2><B>Código</B></th>";
					echo "<th bgcolor='$color1' align=center colspan =4><B>Descripción</B></th>";
					echo "<th bgcolor='$color1' align=center ><B>Cant.</B></th>";
					echo "<th bgcolor='$color1' align=center  ><B>Precio</B></th>";
					echo  "<th bgcolor='$color1' align=center ><B>SubTotal </B></th>";
					echo  "<th bgcolor='$color1' align=center ><B>Descuento </B></th>";
					echo  "<th bgcolor='$color1' align=center <B>Impuesto </B></th>";
					echo "<th bgcolor='$color1' align=center ><B>Total </B></th>";
					echo "<tr>";
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
					echo  "<td align='left' colspan =2>"  . $row[utf8_decode('Codigo')] .  "</td>";
					echo  "<td align='left' colspan =4>"  . utf8_encode($row['Nombre']) .  "</td>";
					echo "<td align='right'>"  . number_format($row['Cantidad'], 2, ",", ".")  .  "</td>";
					echo  "<td align='right'>$"  . number_format($row['Precio'], 2, ",", ".")  .  "</td>";
					echo  "<td align='right'>$"  . number_format($row['SubTotal'], 2, ",", ".")   .  "</td>";
					echo "<td align='right'>$"  . number_format($row['Descuento'], 2, ",", ".")   .  "</td>";
					echo "<td align='right'>$"  . number_format($row['Impuesto'], 2, ",", ".")   .  "</td>";
					echo  "<td align='right'>$"  . number_format($row['Total'], 2, ",", ".")   .  "</td>";
					echo "<tr>";
				} // del if ($row['Section']=='HEADER')	
			}
			else
			{	
				echo "<h3> Factura ANULADA </h3>"; 
				//header('ingguiasfacturas.php');
				//header("Refresh: 3 ; preparartransferencias.php");
				header("Refresh:5; ingguiasfacturas.php");
			}
		}			
	echo  "<tr><th colspan = 12><h3>Información de despacho de Factura </h3></th></tr>";
	echo  "<Form Action='dropshipping1.php' Method='post'>";
	echo "<Input Type=hidden Name='numfac' value='$ID'>";
	echo "<Input Type=hidden Name='bodegaf' value='$bodegaf'>";
	echo "<th colspan =2>Forma de Despacho:</th>";
	echo "<td><select name='medio' required id = 'ddlpickup' onchange = 'ShowHideDiv2()'>";
	echo "  <option value='Ninguno'>(Ninguno)</option>";
	echo "  <option value='Entrega en Tienda'>Entrega en Tienda</option>";
	echo "  <option value='Envio'>Envio</option>";
	echo "</select></td> ";
	echo "<td colspan = 10 >";
	echo "<div id='dvdespacho' style='display: none'>";			
	echo "<strong>Direccion :<strong> <br><textarea  Size = 20 rows= 4 cols=120 Name='direccion' id='direccion'></textarea>" ;
	echo "<strong>Referencia :<strong> <br> <textarea  Size = 20 rows= 4 cols=120 Name='referencia' id='referencia'></textarea> <br>" ;
	echo "<strong>Telefono :<strong> <input  name='telefono' type='text' id='telefono' size = '30' value= '' > " ;
	echo "<strong>Email :<strong> <input  name='Email' type='text' id='Email' size = '50' value= '' > <br>" ;
	echo "</td></tr>";
	echo "</td></tr></div></td>";
	echo "</table>";
	
	echo "<table border=0 cellspacing=0 width=100%>";
	echo "<td colspan =14><br><center><input type=\"submit\" name=\"Submit\" value=\"Guardar Información\" class=\"btn btn-sm btn-primary\"></form><br></td>";
	echo "</table>";
		
	//echo "fin". $usuariof.$base.$acceso.$bodegaf.$nomsuc;
	//Variables de sesion de SGL 
	$_SESSION['usuario']=$usuariof;
	$_SESSION['base']= $base ;
	$_SESSION['acceso']=$acceso;
	//$_SESSION['bodega']=$bodegaf;
	$_SESSION['nomsuc']=$nomsuc;
	$_SESSION['bodegaFAC']=$bodegaFAC;
	//echo $datamail;
	//echo $datamail2;
		
	?>
</div>	
</div>	
</body>