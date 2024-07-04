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
	$transfer = TRIM($_POST["transfer"]);
	$bodegaf = $_SESSION['bodega'];
	$nomsuc = $_SESSION['nomsuc'];
	$id = $_SESSION['id']; 

	
	
		
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
					<div id = "centro"> <a class="titulo"> <center>   Guias Transferencia </center> </a></div>
					<div id = "derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div> 
<hr>
<div id= "cuerpo2" align= "center" width="100%">
<?php	
	
		if (!isset($_SESSION["usuario"])) {
			header("Location: index.php");
		}
	$usuariof= $usuario;
	 	
	// Recibo el ID de la factura
	if (!empty($_POST['transfer'])) {
		$transfer = $_POST['transfer'];
	} else {
		$transfer = $_GET['transfer'];
	}
	$sec = $_GET['sec'];
	
	echo "<div id='principal1'>";
	include("../conexion_mssql.php");
	//echo "Factura: ".$numfac;
	$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
	$result = $pdo->prepare('LOG_Detalle_Transferencias2 @secuencia=:transfer ');
	$result->bindParam(':transfer',$transfer,PDO::PARAM_STR);
	$result->execute();
	$count = $result->rowcount();
	
		//echo "Si trae registros ".$count; 
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
		{ 
				if ($row['Section'] == 'HEADER') 
				{
					//$transporte= $row['Transporte'];
					
					$TransId= $row['AgruparID'];
					$BULTOS= $row['BULTOS'];

					//$peso = $row['Peso'];
					?>
					<left><table border=2 cellspacing=0 width=100%  ></left>
					<tr>
						<th colspan = 7><a>Detalle de Transferencias </a></th>
					</tr> 
					<tr>
						<th bgcolor='$color1' align=center height=0 colspan =1><B>Fecha </B></th> 
						<td align='left' colspan =1 > <?php echo substr($row['Fecha'],0, -13); ?> </td>
						<th bgcolor='$color1' align=center height=0 colspan =1><B>Trans. Agrup. </B></th>
						<td align='left' colspan =4> <?php echo  $row['AgruparID']; ?></td>
					</tr>
					<tr>
						<th bgcolor='$color1' align=center height=0 colspan =1><B>B.Origen</B></th>
						<td align='left' colspan =1><?php echo $row['BOrigen']; ?></td>
						<th bgcolor='$color1' align=center height=0 colspan =1><B>Nombre</B></th>
						<td align='left' colspan =4><?php echo $row['NomOrigen']; ?></td>
					</tr>
					<tr>
						<th bgcolor='$color1' align=center height=0 colspan =1>B.Destino</th>
						<td align='left' colspan=1><?php echo $row['CodDestino']; ?></td>
						<th bgcolor='$color1' align=center height=0 colspan=1>Nombre</th>
						<td align='left' colspan=4><?php echo $row['BDestino']; ?></td>
					
					</tr>	
					<tr>
						<th bgcolor='$color1' align=center height=0 colspan=1> Nota</th>
						<td align='left' colspan=6><?php echo $row['Nota']; ?></td>
					</tr>
					
					<left><table border=2 cellspacing=0 width=100%  ></left>
					<tr>
						<th colspan = 11><a>Detalle de Productos </a></th>
					</tr>					
					<tr>
						<th bgcolor='$color1' align=center colspan =1><B>Transferencia</B></th>
						<th bgcolor='$color1' align=center colspan =1><B>Código</B></th>
						<th bgcolor='$color1' align=center colspan =8><B>Descripción</B></th>
						<th bgcolor='$color1' align=center colspan =1><B>Cant.</B></th>
					</tr>
				
			<?php
				} 
				else   
				{
					if ($row['Section'] == 'DETALLE') 
					{
			?>		
						<tr>
						<td align='left' colspan =1><?php echo $row[utf8_decode('Transferencia')]?> </td> 
						<td align='left' colspan =1><?php echo utf8_encode($row['AgruparID'])?></td> 
						<td align='center'colspan =8> <?php echo $row[utf8_decode('Codigo')] ?></td>
						<td align='right' colspan =1><?php echo number_format($row['Cantidad'] )  ?></td>
						</tr>	
			<?php
					}	
					else
					{
						$Totalu = 0;
						$Total = 0; 
						 
						$Totalu =  $row['Cantidad'];
						$Total =  $row['Costo'];
						 
			?>		
					<tr>
						<td colspan="7">  </td>
						<th bgcolor='$color1' align=center height=0><B>Total Unidades </B></th> 
						<td align='right' colspan =3><?php echo number_format($Totalu) ?></td>
					</tr>
					<tr>
						<td colspan="7">  </td>
						<th bgcolor='$color1' align=center height=0><B>Costo Total  </B></th> 
						<td align='right' colspan =3><?php echo number_format($Total, 2 ) ?></td>
					</tr>					
			<?php
					
					}	
				}
			
		}				
	?>	
		<Form Action='detalletransferencia2.php' Method='post'>
		<Input Type=hidden Name='transfer' id= 'transfer' value=<?php echo $transfer ?> >
		<tr>
			<th colspan = 12><a>Detalle de Envío</a></th>
		</tr>
		<tr>
			<td colspan= 3> <a href="<?php echo $ruta ?>" target="_blank">   </a> </td>
			<td colspan= 9> <a href= "consultarseriesguiatransfer.php?TransId=<?php echo $TransId ?>" target="_blank" > CONSULTAR SERIES </a></td> 
		</tr>
		<tr>
			<th colspan =2>Transporte </th>
			<td>
				<select name='medio' required id = 'ddlpickup'>
				<option value="Ninguno">(Ninguno)</option>
				<option value="SERVIENTREGA">SERVIENTREGA</option>
				<option value="CAMION CARTIMEX">CAMION CARTIMEX</option>
				<option value="CAMION COMPUTRON">CAMION COMPUTRON</option>
				<option value="URBANO">URBANO</option>
				<!-- <option value="TRAMACO">TRAMACO</option> -->
				<option value="TRANSDYR">TRANSDYR</option>
				</select>
			</td>
			<td colspan= 10> </td>
		</tr>	
		<tr>	
			<th colspan =2>Guia # </th>
			<td colspan =1><Input Type='text'  Size = 30 Maxlenght=80  Name='guia' id='guia' > <font color = 'red'> <a>***Solo ingresar numero de guia o placa de camión sin comentarios adicional </a></font></td> 
			<th colspan =2># de Bultos:</th>
			<td colspan =3> <?php echo $BULTOS ?></td>
			<th colspan =2>Peso Productos:</th>
			<td colspan =2><Input Type=Text Size = 10 Maxlenght=5 Name='peso' id='peso'  value = ''> </td>
			
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