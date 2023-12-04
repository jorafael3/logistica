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
		$usuario1= trim($usuario);
		date_default_timezone_set('America/Guayaquil');
		$year = date("Y");
		//probar si se graba bien el dia y el mes
		$fecha = date("Y-d-m", time());
		$hora = date("H:i:s", time());
		$fh = $fecha . " " . $hora;

		if ($_POST['idorden']==''){$idorden= $_SESSION['idorden'];}else {$idorden = $_POST['idorden'];}
		echo "Numero de Orden".$idorden; 
		if ($base=='CARTIMEX'){require '../../headcarti.php'; }	else{require '../../headcompu.php';$_SESSION['base']= $base; }

		require('../../conexion_mssql.php');
		$OrdenID= '';
		$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$usuario = $_SESSION['usuario'];
	    $result0 = $pdo0->prepare("SELECT LiquidacionID from IMP_RECEPCION_LIQ_CONTENEDOR where LiquidacionID=:idorden and TipoIngreso='COM-OR'" );
		$result0->bindParam(':idorden',$idorden,PDO::PARAM_STR);
		$result0->execute();
		while ($row0 = $result0->fetch(PDO::FETCH_ASSOC))
			{	$OrdenID=$row0['LiquidacionID'];
			}

	if ($OrdenID =='')
		{
				//echo "Fecha".$fh;
				//die();
				$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$usuario = $_SESSION['usuario'];
				$result = $pdo->prepare("UPDATE COM_ORDENES SET FInicioR=:Fecha from COM_ORDENES where  ID=:idorden " );
				$result->bindParam(':Fecha',$fh,PDO::PARAM_STR);
				$result->bindParam(':idorden',$idorden,PDO::PARAM_STR);
				$result->execute();


?>
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >

					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center> Recepcion de Compra Local </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
	</div>
<hr>
	<div id="cuerpo2"  >
		<div align= "left" >
			<form name = "formusuario" action="recepcioncomprascontenedor.php" method="POST" width="75%" enctype="multipart/form-data">
				<table align ="center" border= "2"  >
					<tr>
						<td id="label" >Fecha: </td>
						<td id= "box">  <input disabled name="fecha" type="text" id="fecha" size = "32" value= "<?php echo $fecha ?>"> </td>
					</tr>
					<tr>
						<td id="label" >CompraID: </td>
						<td id= "box">  <input disabled name="idorden" type="text" id="idorden" size = "32" value= "<?php echo $idorden ?> "> </td>
					</tr>
					<tr>
						<td id="label" >Placa: </td>
						<td id= "box"> <input  name="Placa" type="text" id="Placa" size = "32" value= "" > </td>
					</tr>
					<tr>
						<td id="label" >Observaciones: </td>
						<td id= "box"> <textarea id="Nota" name="Nota" rows="4" cols="50">  </textarea> </td>
					</tr>

		<table  align= center>
			<tr>
				<td id= label>
					<input id="submit" value="GRABAR" type="submit">
					<input type="hidden" name="idorden" value="<?php echo $idorden ?> ">
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
				$_SESSION['idorden']=$idorden;
			    header("location: recepcioncompras1.php");
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
