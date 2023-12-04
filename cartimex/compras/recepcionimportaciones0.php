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
		$fecha	=$_POST['fecha'];
		$usuario1= trim($usuario); 
		date_default_timezone_set('America/Guayaquil');
		$year = date("Y");
		//probar si se graba bien el dia y el mes 
		$fecha = date("Y-d-m", time());
		$hora = date("H:i:s", time());
		$fh = $fecha . " " . $hora;
		if ($_POST['IDLiq']==''){$LiqID= $_SESSION['IDLiq'];}else {$LiqID = $_POST['IDLiq'];}
		if ($base=='CARTIMEX'){require '../../headcarti.php'; }	else{require '../../headcompu.php';$_SESSION['base']= $base; }
				
		require('../../conexion_mssql.php');	
		$LiquidacionID= ''; 	
		$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$usuario = $_SESSION['usuario'];
	    $result0 = $pdo0->prepare("SELECT LiquidacionID from IMP_RECEPCION_LIQ_CONTENEDOR where  LiquidacionID=:IDLiq and TipoIngreso = 'IMP-LQ'" );		 
		$result0->bindParam(':IDLiq',$LiqID,PDO::PARAM_STR);
		$result0->execute();
		while ($row0 = $result0->fetch(PDO::FETCH_ASSOC)) 
			{	$LiquidacionID=$row0['LiquidacionID'];
			}
		 
	if ($LiquidacionID =='') 
		{
				$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$usuario = $_SESSION['usuario'];
				$result = $pdo->prepare("UPDATE IMP_LIQUIDACION SET FInicioRecep=:Fecha from IMP_LIQUIDACION where  ID=:IDLiq " );		 
				$result->bindParam(':Fecha',$fh,PDO::PARAM_STR);
				$result->bindParam(':IDLiq',$LiqID,PDO::PARAM_STR);
				$result->execute();				
			
		
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center> Recepcion de Importacion</center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
	</div> 
<hr>	
	<div id="cuerpo2"  >
		<div align= "left" >
			<form name = "formusuario" action="recepcionimportacionescontenedor.php" method="POST" width="75%" enctype="multipart/form-data">
				<table align ="center" border= "2"  >
					<tr>
						<td id="label" >Fecha: </td> 
						<td id= "box">  <input disabled name="fecha" type="text" id="fecha" size = "32" value= "<?php echo $fecha ?>"> </td>
					</tr>
					<tr>
						<td id="label" >LiquidacionID: </td> 
						<td id= "box">  <input disabled name="liquidacion" type="text" id="liquidacion" size = "32" value= "<?php echo $LiqID ?> "> </td>
					</tr>
					<tr>
						<td id="label" >Contenedor: </td> 
						<td id= "box">  <input  name="Contenedor" type="text" id="Contenedor" size = "32" value= " "> </td>
					</tr>
					<tr>
						<td id="label" >Sello1: </td> 
						<td id= "box"> <input  name="Sello" type="text" id="Sello" size = "32" value= "" > </td>
					</tr>
					<tr>
						<td id="label" >Sello2: </td> 
						<td id= "box"> <input  name="Sello2" type="text" id="Sello2" size = "32" value= "" > </td>
					</tr>
					<tr>
						<td id="label" >Candado #: </td> 
						<td id= "box"> <input  name="Candado" type="text" id="Candado" size = "32" value= "" > </td>
					</tr>
					<tr>
						<td id="label" >Foto1: </td> 
						<td id= "box">  <input type="file" name="foto1"/>
					</tr>
					<tr>
						<td id="label" >Foto2: </td> 
						<td id= "box">  <input type="file" name="foto2"/>
					</tr>	
					<tr>
						<td id="label" >Tipo: </td> 
						<td id="label">	
							<select name="Tipo" id = "Tipo">	
								<option value="Ninguno">(Ninguno)</option>
								<option value="AEREO">AEREO</option>	
								<option value="CAMION">CAMION</option>
								<option value="CONTENEDOR 20 ">CONTENEDOR 20 </option>
								<option value="CONTENEDOR 40 ">CONTENEDOR 40 </option>
							</select>
						</td>	
					</tr>
					<tr>
						<td id="label" >Placa: </td> 
						<td id= "box"> <input  name="Placa" type="text" id="Placa" size = "32" value= "" > </td>
					</tr>
					<tr>
						<td id="label" >Estiba en el interior : </td>
						<td id="label">	
							<select name="Estiba" id = "Estiba">
								<option value="Ninguno">(Ninguno)</option>
								<option value="A PISO"> A PISO</option>
								<option value="PALETIZADO">PALETIZADO </option>
							</select>
						</td> 
					</tr>
					<tr>
						<td id="label" >Observaciones: </td> 
						<td id= "box"> <textarea id="Nota" name="Nota" rows="4" cols="50">  </textarea> </td>
					</tr>
				
		<table  align= center> 
			<tr>
				<td id= label>
					<input id="submit" value="GRABAR" type="submit"> 
					<input type="hidden" name="LiqID" value="<?php echo $LiqID ?> ">
				</td>
			</tr>	
		</table>
	</table>
	</form> 
	</div> 
 </div> 
				
 <?php
		}
	else
			{	
				$_SESSION['usuario']=$usuario1;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;
				$_SESSION['IDLiq']=$LiqID; 
			    header("location: recepcionimportaciones1.php");
			}
	}		
else
	{
		header("location: index.html");
	}			
?>		 		 	
</div>
</div>		
</body>	
</html>	
 

