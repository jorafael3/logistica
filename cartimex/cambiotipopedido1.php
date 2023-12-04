<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("id").focus();
}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload= "setfocus()">
<div id= "header" align= "center">
<?php
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$secu= $_POST['secuencia'];
			if ($base=='CARTIMEX'){
					require '../headcarti.php';
			}
			else{
					require '../headcompu.php';
			}

			//echo " 1 esto envio".$base.$usuario.$acceso.$secu;
?>
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
					<div id = "izq" ></div>
					<div id = "centro" > <a class="titulo"> <center>   Cambiar Tipo de Despacho   </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a></div>
	</div>
	<hr>
 <?php
			//echo "2esto envio".$base.$usuario.$acceso;
			$usuario1= $usuario;
			require('../conexion_mssql.php');
			//echo "3esto envio".$base.$usuario1.$acceso;
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);

			//Select Query
			$result = $pdo->prepare("select cliente= cl.nombre, vendedor = e.nombre, idfac= f.id, fact= f.secuencia , tipo= fl.tipopedido from facturaslistas fl
									 inner join ven_facturas f with (nolock) on f.id = fl.factura
									 inner join cli_clientes cl with (nolock) on f.clienteid = cl.id
									 inner join emp_empleados e with (nolock) on e.id = f.vendedorid
									 where secuencia=:secuencia and fl.estado not in ('DESPACHADA','ENTREGADA')" );
			$result->bindParam(':secuencia',$secu,PDO::PARAM_STR);
			//Executes the query
			$result->execute();


			while ($row = $result->fetch(PDO::FETCH_ASSOC))
				  {
					  $cliente = $row['cliente'];
					  $vendedor = $row['vendedor'];
					  $idfac = $row['idfac'];
					  $fact = $row['fact'];
					  $tipopedido = $row['tipo'];

				  }
				  //echo "IDfact".$idfac;
?>


<div id="cuerpo2" align= "center">
<div>
	<form name = "formusuario" action="cambiotipopedido1.php" method="POST" >
		<table align= "center">
    	<tr >
    		<td id= "label">Buscar Factura # </td>
    		<td id= "box"> <input name="secuencia" type="text" id="secuencia" size = "32" value= ""> </td>
			<td><input   name="submit" id="submit" value="Grabar" src="..\assets\img\lupa.png" type="image"> </td>
    	</tr>
		</table>
	</form>
	<br>
	<form name = "formusuario" action="cambiotipopedido2.php" method="POST" >
		<table align= "center">
    	<tr >
    		<td id= "label">Factura #: </td>
    		<td id= "box"> <input readonly name="fact" type="text" id="fact" size = "32" value= "<?php echo $fact ?>"> </td>
			<td id= "box"> <input  name="idfac" type=hidden id="idfac" size = "32" value= "<?php echo $idfac ?>"> </td>
    	</tr>
		<tr>
    		<td id= "label">Cliente: </td>
    		<td id= "box" > <input  readonly name="cliente" type="text" id="usermo" size = "32" value= "<?php echo $cliente ?>"> </td>
    	</tr>
    	<tr>
    		<td id= "label">Vendedor: </td>
    		<td id= "box"> <input readonly  name="nombre" type="text" id="nombre" size = "32" value= "<?php echo $vendedor ?>"> </td>
    	</tr>
    	<tr>
    		<td id= "label">Tipo Despacho: </td>
			<td>
				<select name="tipodespacho" id = "tipodespacho">
				<option value="MOSTRADOR-GYE" >MOSTRADOR-GYE</option>
				<option value="MOSTRADOR-UIO" >MOSTRADOR-UIO</option>
				<option value="CIUDAD-GYE" >CIUDAD-GYE</option>
        <option value="CIUDAD-UIO" >CIUDAD-UIO</option>
        <option value="PROVINCIA" >PROVINCIA</option>
				</select>
			</td>
    	</tr>
		<tr>
		  <td id="label"></td>
		  <td id="label" >   Grabar
      	  <input   name="submit" id="submit" value="Grabar" src="..\assets\img\save.png" type="image" >
		  <a href="../menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr>
      </table>
    </form>


</div>
</div>
 <?php




		$_SESSION['base']= $base;
		$_SESSION['usuario']=$usuario1;
		$_SESSION['acceso']=$acceso;

		//echo "esto envio".$base.$usuario1.$acceso;
		}
		else
		{
			header("location: index.html");
		}
 ?>
</div>
</body>
