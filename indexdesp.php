<meta name="viewport" content="width=device-width, height=device-height">
<html>

<head>
	<title>Sistema SISCO</title>
	<link rel="stylesheet" type="text/css" href="css/tablas.css">

</head>

<!-- 
<style>
tbody tr td {color:black;border-right:1px solid;width:100px;}
</style>
 -->

<?PHP
// parte final del proceso cuando la factura por despachar le ponemos marca de que ha sido ya despachada y entregada

session_start();

if (!isset($_SESSION["usuario"])) {
	header("Location: index1.php");
}
include("barramenu.html");
//echo "<br><br><br><br><br>u:".$_SESSION["usuario"]." - ".$_SESSION["nivel"];
//if ($_SESSION["nivel"]<>'30' and $_SESSION["usuario"]<>'JFUROIANI'){die( "<br><br><h2>No tiene acceso a esta opción!</h2>");}
//echo "<br><br><br>Usuario: ".$_SESSION["usuario"]." ".$_SESSION["nivel"];
$nivel = $_SESSION["nivel"];
//echo "<br><br>";

// Creates string of comma-separated variables(*) for unset.
$all_vars = implode(', $', array_keys(get_defined_vars()));
unset($all_vars);





echo "<H2>Listado de Ventas por despachar:<br></H2>";


include("conexion.php");


date_default_timezone_set('America/Guayaquil');

$year = date("Y");
$yearant = $year - 1;





// $sql = "SELECT * FROM covidsales where estado ='Por despachar'  and despacho <>'' and bultos <>'' and anulada <>'1' order by secuencia desc ";



$sql = "SELECT a.secuencia, a.cedula, a.nombres, a.celular, a.ciudad, a.venta, a.valortotal, a.ordenweb, a.bodega,a.fecha, a.pdf,
 a.formapago, a.vendedor, a.estado, a.factura, a.fechafact, a.despacho, a.bultos, a.estado, a.fechadesp, a.anulada, a.despachofinal, 
 a.comentlogi, b.transaccion, b.doc1, b.doc2, b.doc3, b.doc4, b.doc5 , b.completo 
 FROM `covidsales` as a  left join  covidcredito as b on a.secuencia = b.transaccion  where (a.estado ='Por despachar' or substring(a.estado,1,5)='Entre' )
  and a.bultos <>'' and a.anulada <>'1' order by secuencia desc ";


//echo $sql . "<br>";
$result = mysqli_query($con, $sql);
$count = mysqli_num_rows($result);
echo "Coincidencias:" . $count;

echo "<Form Action='indexdesp2.php' Method='post' >";

echo "<br><table border=1 cellpadding=10 cellspacing=1 width = 80% align='center' class=\"table\"><tr>
<th align=center width=  5% >#</th>
<th align=center width=  5% >Cédula</th>
<th align=center width= 5% >Nombre</th>
<th align=center width=  5% >Ciudad</th>
<th align=center width= 5% >Venta</th>
<th align=center width= 5% >Orden<br>WEB</th>
<th align=center width= 5% >Bodega</th>
<th align=center width= 10% >Factura</th>
<th align=center width= 5% >Guia</th>
<th align=center width= 5% >Bultos</th>
<th align=center width=  5% >Fecha<br>Guia</th>
<th align=center width= 5% >Despacho</th>

<th align=center width=  5% ><center>Ver<br>Doc.1</center></th>
<th align=center width=  5% ><center>Ver<br>Doc.2</center></th>
<th align=center width=  5% ><center>Ver<br>Doc.3</center></th>
<th align=center width=  5% ><center>Ver<br>Doc.4</center></th>
<th align=center width=  5% ><center>Ver<br>Doc.5</center></th>
<th align=center width=  5% >Info.</th>
<th align=center width=  5% ><center>Ver</center></th>
<th align=center width=  5% ><center>Despachar</center></th><tr>";
while ($row = mysqli_fetch_array($result)) {

	if ((trim($row['despachofinal']) == 'Servientrega' or trim($row['despachofinal']) == 'Urbano'
		or trim($row['despachofinal']) == 'Tramaco')      or trim($row['despachofinal']) == 'Entrega en tienda') {
		// 	echo "aaaaaaaa";
		// } else {

		$sec = $row['secuencia'];


		echo "<td>" . $sec . "</td>";
		echo "<td>" . $row['cedula'] . "</td>";
		echo "<td>" . $row['nombres'] . "</td>";
		echo "<td>" . $row['ciudad'] . "</td>";
		echo "<td>" . $row['venta'] . "</td>";
		echo "<td>" . $row['ordenweb'] . "</td>";
		echo "<td>" . $row['bodega'] . "</td>";
		// echo "<td>".$row['formapago']."</td>";
		// echo "<td>".$row['vendedor']."</td>";
		// echo "<td>".$row['fecha']."</td>";



		$factura = $row['factura'];



		echo "<td>" . $row['factura'] . "</td>";
		echo "<td>" . $row['despacho'] . "</td>";
		echo "<td>" . $row['bultos'] . "</td>";

		echo "<td>" . $row['fechadesp'] . "</td>";

		if ($row['despachofinal'] == 'Urbano' or $row['despachofinal'] == 'Servientrega') {
			echo "<td><a href=mod1.php?sec=$sec>" . $row['despachofinal'] . "</td>";
			//echo "<td>".$row['despachofinal']."</td>";
		} else {
			echo "<td>" . $row['despachofinal'] . "</td>";
		}

		// if ($row['pdf'] <> '') {
		// 	$pdfn = trim($row['pdf']);
		// 	echo "<td align=center  ><center><a href=muestrafoto1.php?sec=$pdfn>  <img src='images/pdf.png' alt='Vista Previa' border='0' height='24' width='24'></center></td>";
		// } else {
		// 	echo "<td> </td>";
		// }
		if ($row['doc1']) {
			echo "<td align=center  ><a href=muestrafoto1.php?sec=$sec&a=1>  <center><img src='images/pdf.png' alt='Vista Previa' border='0' height='24' width='24'></center></td>";
		} else {
			echo "<td align=center  >  <center><img src='images/cancel.png' alt='Vista Previa' border='0' height='12' width='12'></center></td>";
		}
		if ($row['doc2']) {
			echo "<td align=center  ><a href=muestrafoto1.php?sec=$sec&a=2>  <center><img src='images/pdf.png' alt='Vista Previa' border='0' height='24' width='24'></center></td>";
		} else {
			echo "<td align=center  > <center><img src='images/cancel.png' alt='Vista Previa' border='0' height='12' width='12'></center></td>";
		}
		if ($row['doc3']) {
			echo "<td align=center  ><a href=muestrafoto1.php?sec=$sec&a=3><center>  <img src='images/pdf.png' alt='Vista Previa' border='0' height='24' width='24'></center></td>";
		} else {
			echo "<td align=center  >  <center><img src='images/cancel.png' alt='Vista Previa' border='0' height='12' width='12'></center></td>";
		}
		if ($row['doc4']) {
			echo "<td align=center  ><a href=muestrafoto1.php?sec=$sec&a=4>  <center><img src='images/pdf.png' alt='Vista Previa' border='0' height='24' width='24'></center></td>";
		} else {
			echo "<td align=center  >  <center><img src='images/cancel.png' alt='Vista Previa' border='0' height='12' width='12'></center></td>";
		}
		if ($row['doc5']) {
			echo "<td align=center  ><a href=muestrafoto1.php?sec=$sec&a=5>  <center><img src='images/pdf.png' alt='Vista Previa' border='0' height='24' width='24'></center></td>";
		} else {
			echo "<td align=center  >  <center><img src='images/cancel.png' alt='Vista Previa' border='0' height='12' width='12'></center></td>";
		}


		echo "<td><a href=despcoment1.php?sec=$sec>" . $row['comentlogi'] . "</td>";

		echo "<td align=center  ><a href=verorden1.php?sec=$sec>  <center><img src='images/icon_preview.png' alt='Vista Previa' border='0' height='24' width='24'></center></td>";

		echo "<td align=center  ><center><input type='checkbox' value='.$sec.' name='checkbox[]'/></center></td></tr>";
	}
}

if ($_SESSION["nivel"] == '30' or $_SESSION["usuario"] == 'JFUROIANI') {
	echo "<Center><Input Type=\"Submit\" name=\"Submit\" Value=\"CONFIRMAR DESPACHO DE ÓRDENES SELECCIONADAS (Recomendado máximo 10 por vez)\" class=\"btn btn-sm btn-primary\"></Center>";
	//echo "<Center><input type=\"button\" value=\"Carriage&#13;&#10;return&#13;&#10;separators\" style=\"text-align:center;\"></Center>";


}
?>



</html>