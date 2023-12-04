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
        $(function () {
            $("#ddlpickup").change(function () {
                if ($(this).val() == "Entrega en tienda" ){
                    $("#dvdespacho").show();
                } else {
                    $("#dvdespacho").hide();
                }
            });
        });
    </script>
    
<script>
function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:'idgrupo='+val,
	success: function(data){
		$("#state-list").html(data);
	}
	});
}
 </script>

<div id= "header" align= "center">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#ddlpickup").change(function() {
				if ($(this).val() == "Casillero") {
					$("#casilleros").show();
				} else {
					$("#casilleros").hide();
				}
			});
		});
	</script>


	<?PHP
	session_start();
	//Variables de sesion de SGL
	$usuario = $_SESSION['usuario'];
	$base = $_SESSION['base'];
	$acceso	=$_SESSION['acceso'];
	$secu = TRIM($_POST["secu"]);
	$bodegaf = $_SESSION['bodega'];
	$nomsuc = $_SESSION['nomsuc'];
	$bodegaFAC=$_SESSION['bodegaFAC']; 
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
	
	 	
	
	
	/*if (!mssql_select_db('COMPUTRONSA', $link)) {
		die('Unable to select database!');
	}*/

	// Recibo el ID de la factura
	if (!empty($_POST['numfac'])) {
		$numfac = $_POST['numfac'];
	} else {
		$numfac = $_GET['numfac'];
	}
	$sec = $_GET['sec'];
	
	 
	$busqueda = str_replace(" ", "%", $busqueda);
	
	//include("conexion_mssql.php");
	//$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
	//$result = $pdo->prepare('LOG_DESPACHAR_FACTURA @secuencia=:secu, @bodega=:bodega, @acceso =:acceso');	
	
	//$sqlcas = "select * from covidsales where factura ='$numfac' ";
	
	$sqlcas = "SELECT a.*, p.bodega as bodegaret, c.sucursalid as sucursalret , d.sucursalid as sucursalfact , cr.doc1, cr.doc2, cr.doc3, cr.doc4, cr.doc5
			   FROM covidsales a 
			   inner join sisco.covidciudades d on a.bodega= d.almacen 
			   left outer join covidpickup p on p.orden= a.secuencia 
               left outer join covidcredito cr on cr.transaccion= a.secuencia
			   left outer join sisco.covidciudades c on p.bodega= c.almacen where a.factura = '$numfac'  and a.anulada<> '1'";

	$resultcas = mysqli_query($con, $sqlcas);
	while ($rowcas = mysqli_fetch_array($resultcas)) {
		$casillero = $rowcas['casillero'];
		$bodega = $rowcas['bodega'];
		$pickup = $rowcas['pickup'];
		$direccion= $rowcas['direccion'];
		$referencia= $rowcas['referencias'];
		$comentario= $rowcas['comentarios'];
		$ciudad= $rowcas['ciudad'];
		$celular= $rowcas['celular'];
		$mail= $rowcas['mail'];
		$sucuret = $rowcas['sucursalret'];
		$sucufact  = $rowcas['sucursalfact'];
		$doc1  = $rowcas['doc1'];
		$doc2  = $rowcas['doc2'];
		$doc3  = $rowcas['doc3'];
		$doc4  = $rowcas['doc4'];
		$doc5  = $rowcas['doc5'];
		
	}
	//echo "Retiro".$sucuret. $sucufact"<br>";
	//echo "Factura".$sucufact."<br>";
	
	
	
	//die('Bodega' . $bodega);
	$localtotales = '';
	$cuenta = 1;
	//***************************************************************************************
	echo "<div id='principal1'>";
	include("conexion_mssql.php");
	//echo "Factura: ".$numfac;
	$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
	$result = $pdo->prepare('PER_Detalle_Facturas2 @secuencia=:secu , @bodegaFAC=:bodegaFAC');
	$result->bindParam(':secu',$numfac,PDO::PARAM_STR);
	$result->bindParam(':bodegaFAC',$bodegaFAC,PDO::PARAM_STR);
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
					if ($direccion=='' ){ 
						$direccion = $row['DIRECCION']; 
						$referencia = $row['REFERENCIA'];
						$celular = $row['TELEFONO'];
						$mail = $row['EMAIL'];
					}	
					
					echo  "<left><table border=2 cellspacing=0 width=70%  ></left>";
					echo "<tr>";
					echo  "<th colspan = 12><h3>Información de despacho de orden </h3></th></tr>";
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
					//echo  "</table><table border=1  cellspacing=0 width=80% >";
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
			
	echo "<th colspan=2> Direccion</th><td colspan=4>".$direccion."</td>";
	echo "<th colspan=2> Referencia</th><td colspan=4>".$referencia."</td></tr>";
	echo "<tr><th colspan=2> Comentario</th><td colspan=10>".$comentario."</td>";
	echo "<tr>";
	echo "<tr>";
	echo "<th colspan=2> Ciudad</th><td colspan=2>".$ciudad."</td>";
	echo "<th colspan=2> Celular</th><td colspan=2>".$celular."</td>";
	echo "<th colspan=2> Email</th><td colspan=2>".$mail."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<th colspan=2> Documentos Credito</th><td colspan=1><a href=http://app.compu-tron.net/siscocredito/$doc1 target=_blank >$doc1</a></td>";
	echo "<td colspan=2><a href=http://app.compu-tron.net/siscocredito/$doc2 target=_blank >$doc2</a> </td>";
	echo "<td colspan=3><a href=http://app.compu-tron.net/siscocredito/$doc3 target=_blank >$doc3</a> </td>";
	echo "<td colspan=2><a href=http://app.compu-tron.net/siscocredito/$doc4 target=_blank >$doc4</a></td>";
	echo "<td colspan=2><a href=http://app.compu-tron.net/siscocredito/$doc5 target=_blank >$doc5</a></td>";
	echo "</tr>";
	echo  "<Form Action='detallefactura2.php' Method='post'>";
	echo "<Input Type=hidden Name='numfac' value='$numfac'>";
	echo "<Input Type=hidden Name='sec' value='$sec'>";
	echo "<th colspan =2>Forma de Despacho:</th>";
	//<select name="pago" id = "ddlcredito" onchange = "ShowHideDiv()" required>
	if ($pickup ==0 ) 
		{
			echo "<td><select name='medio' required id = 'ddlpickup'  onchange = 'ShowHideDiv2()' >";
			echo "  <option value='Urbano'>Urbano</option>";
			echo "  <option value='Tramaco'>Tramaco</option>";
			echo "  <option value='Servientrega'>Servientrega</option>";
			echo " <option value='Vehiculo Computron'>Vehiculo Computron</option>";
			echo " <option value='Entrega en tienda'>Entrega en tienda</option>";
			echo " <option value='Casillero'>Casillero</option>";
			echo "</select></td><td></td>";
			echo "<td colspan = 8 ><div id='dvdespacho' style='display: none'>";
			echo "<strong>Provincia:<strong>";
			echo "<select name='provincia' id='country-list' class='demoInputBox'  onChange='getState(this.value)'>";
			echo "<option value=''>Seleccione provincia</option>";
				$sql1="SELECT * FROM covidprovincia";
				$results = mysqli_query($con, $sql1);
				while($rs=$results->fetch_assoc())
					{
				?>
					<option value='<?php echo $rs["idgrupo"]; ?>'><?php echo $rs["provincia"]; ?></option>
				<?php	
					}

			echo "</select>";	
			echo "<strong>&nbsp&nbsp&nbspBodega:<strong>";
			echo "<select id='state-list' name='nbodega'>";
			echo "<option value=''>Seleccione bodega</option>";
			echo "</select></td></tr>";
			echo "</td></tr></div></td>";
		}	
		else
		{
			echo "<td><select name='medio' required id = 'ddlpickup' >";
			echo "  <option value='Urbano'>Urbano</option>";
			echo "  <option value='Tramaco'>Tramaco</option>";
			echo "  <option value='Servientrega'>Servientrega</option>";
			echo " <option value='Vehiculo Computron'>Vehiculo Computron</option>";
			echo " <option value='Entrega en tienda'>Entrega en tienda</option>";
			echo " <option value='Casillero'>Casillero</option>";
			echo "</select></td><td></td>";
			echo "<td colspan = 8 > ";
			echo " ";
			echo " ";
			echo " ";	
			echo " ";
			echo " ";
			echo " ";
			echo " </td></tr>";
			echo "</td></tr> </td>";
			
		}
	echo "<th colspan =2>Guia # :</th>";
	echo "<td colspan =6><br><Input Type=Text Size = 10 Maxlenght=95 Name='despacho' id='despacho' ><br> <a> <FONT COLOR = 'red'> <b>* Ingrese SOLO NUMERO DE GUIA sin comentarios adicionales </b></FONT> </a></td>";
	echo "<th colspan = 2># de Bultos:</th>";
	echo "<td colspan =2><Input Type=Number Size = 10 Maxlenght=5 Name='bultos' id='bultos' min='1' max='10' required> </td></tr>";
	echo "<tr> <th colspan =2>Comentario de despacho:</th>";
	echo "<td colspan =10><br><textarea Size = 20 rows= 4 cols=120 Name='comentariodesp' id='comentariodesp'></textarea></td></tr>";
	echo "</table>";

	if ($casillero == "SI") {
		echo "<div id=\"casilleros\" style=\"display: inline\">";
	} else {
		echo "<div id=\"casilleros\" style=\"display: none\">";
	}

	echo "<table border=1 cellspacing=0 width=80%>";



	include("conexioncas.php");
	$sqlocup = "SELECT a.secuencia, a.localid, a.lockerid, a.posicion, a.ocupado, a.medidas,a.reserva, b.localid, b.lockerid, b.bodega
	FROM `lockers` as a 
	left join `locales` as b
	on a.localid=b.localid and a.lockerid=b.lockerid
	where b.bodega='$bodega' ";
	//$datamail2 .=  "</table><br><br><table border=1  cellspacing=0 >";

	echo "<th colspan =2>Bodega</th><th>Casillero</th><th>Ocupado</th><th colspan =2 >Medidas</th><th colspan =2> </th></tr>";

	$resultocup = mysqli_query($concom, $sqlocup);


		while ($rowocup = mysqli_fetch_array($resultocup)) 
		{
			$posicion = $rowocup['posicion'];
			$ocupado = $rowocup['ocupado'];
			$reservado = $rowocup['reserva'];
			$localid = $rowocup['localid'];
			$lockerid = $rowocup['lockerid'];

			if ($ocupado == 0 and strlen($reservado) < 4) {
				$ocupadodt = "Libre";
			} else {

				$ocupadodt = "<b>Ocupado</b>";
			}

			$medidas = $rowocup['medidas'];
			echo "<td colspan =2>" . $bodega . "</td>";
			echo "<td colspan =1>" . $posicion . "</td>";
			echo "<td colspan =1>" . $ocupadodt . " </td>";
			echo "<td colspan =2>" . $medidas . " </td>";
			if ($ocupadodt == "Libre") {
				echo "<td colspan =2> <input type='radio' value='$posicion' name='radio[]' />&nbsp&nbsp&nbspReservar  casillero</td></tr>";
			} else {
				echo "<td colspan =2>&nbsp</td></tr>";
			}

			//echo "<td colspan =2> <input type='radio' value='$posicion' name='radio[]' />&nbsp&nbsp&nbspReservar  casillero</td></tr>";
			//echo "<td align=center  ><center><input type='checkbox' value='.$sec.' name='checkbox[]'/></center></td></tr>";
			// <input type="radio" id="male" name="gender" value="male">
		}
	echo "<Input Type=hidden Name='localid' value='$localid'>";
	echo "<Input Type=hidden Name='lockerid' value='$lockerid'>";
	echo "<Input Type=hidden Name='local' value='$bodega'>";


	echo "</table>";
	echo "</div>";
	echo "<table border=0 cellspacing=0 width=100%>";
	echo "<td colspan =14><br><center><input type=\"submit\" name=\"Submit\" value=\"Guardar Información\" class=\"btn btn-sm btn-primary\"></form><br></td>";
	echo "</table>";
		
	//echo "fin". $usuariof.$base.$acceso.$bodegaf.$nomsuc;
	//Variables de sesion de SGL 
	//echo $bodegaFAC; 
	$_SESSION['usuario']=$usuariof;
	$_SESSION['base']= $base ;
	$_SESSION['acceso']=$acceso;
	$_SESSION['bodega']=$bodegaf;
	$_SESSION['nomsuc']=$nomsuc;
	$_SESSION['bodegaFAC']=$bodegaFAC;
	//echo $datamail;
	//echo $datamail2;
		
	?>
</div>	
</div>	
</body>