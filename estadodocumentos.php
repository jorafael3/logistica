<!DOCTYPE html>
<html>
<head>
<title> SGL </title>

</head>
<script type="text/javascript">
function setfocus(){
    document.getElementById("cant").focus();
}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload= "setfocus()">
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
					$transaccion= $_GET['transaccion'];
					$_SESSION['usuario']=$usuario;
					//echo  $idcompra. $productoid.$base;
					if ($base=='CARTIMEX'){
							require 'headcarti.php';
					}
					else{
							require 'headcompu.php';
					}
          include("conexion.php");
          $sql = "SELECT a.*, b.* FROM `covidsales` a
                  inner join `covidcredito` b on b.transaccion=a.secuencia
                  where a.secuencia='$transaccion'";
          $result = mysqli_query($con, $sql);
          while ($row = mysqli_fetch_array($result))
            {
              $factura = $row['factura'];
              $cedula = $row['cedula'];
              $nombre = $row['nombres'];
              $StatusRecepcion = $row['StatusRecepcion'];
              $StatusRecdcto = $row['StatusRecdcto'];
              $nota = $row['Nota'];
            }
            //echo $nota;
?>
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center> Recepcion de Documentos </center> </a></div>
					<div id = "derecha" > <a href=" menu.php"><img src=" assets\img\home.png"></a> </div>
	</div>
<hr>

<div id="cuerpo2" align= "center">
<div>
	<form name = "fromdocumentos" action="estadodocumentos1.php" method="POST" width="75%">
		<table align ="center" >
    	<tr><td id="label" >Factura: </td>
    		  <td id= "box">  <input disabled name="factura" type="text" id="factura" size = "40" value= "<?php echo $factura ?> "> </td>
    	</tr>
    	<tr><td id="label" >Cedula: </td>
    		  <td id= "box"> <input disabled name="cedula" type="text" id="cedula" size = "40" value= "<?php echo $cedula ?>" > </td>
    	</tr>
    	<tr><td id="label" >Nombre: </td>
    		  <td id= "box"> <input disabled name="nombre" type="text" id="nombre" size = "40" value= "<?php echo $nombre ?>"> </td>
    	</tr>
		  <tr><td id="label" >Estado: </td>
    		  <td id= "box"> <select name="StatusRecepcion" id = "StatusRecepcion">
                            <option value="RECIBIDO" <?php if ($StatusRecepcion=='RECIBIDO') {echo "Selected";}?>>RECIBIDO</option>
                            <option value="DEVUELTO" <?php if ($StatusRecepcion=='DEVUELTO') {echo "Selected";}?>>DEVUELTO</option>
                            <option value="GESTION-INTERNA" <?php if ($StatusRecepcion=='GESTION-INTERNA') {echo "Selected";}?>>GESTION-INTERNA</option>
                            <option value="NO-SE-RECIBIERON" <?php if ($StatusRecepcion=='NO-SE-RECIBIERON') {echo "Selected";}?>>NO-GESTION</option>
                       </select> </td>
    	</tr>
		  <tr><td id="label" >Estado Dcto: </td>
    		  <td id= "box"><select name="StatusRecdcto" id = "StatusRecdcto">
                        <option value="OK" <?php if ($StatusRecdcto=='OK') {echo "Selected";}?>>OK</option>
                        <option value="INCOMPLETOS" <?php if ($StatusRecdcto=='INCOMPLETOS') {echo "Selected";}?>>INCOMPLETOS</option>
                        <option value="FIRMA-INCONFORME" <?php if ($StatusRecdcto=='FIRMA-INCONFORME') {echo "Selected";}?>>FIRMA-INCONFORME</option>
                        <option value="DCTO-MAL-ESTADO" <?php if ($StatusRecdcto=='DCTO-MAL-ESTADO') {echo "Selected";}?>>DCTO-MAL-ESTADO</option>
                        <option value="OTROS" <?php if ($StatusRecdcto=='OTROS') {echo "Selected";}?>>OTROS</option>
                        </select> </td>
    	</tr>
      <tr><td id="label" >Observaciones: </td>
          <td id= "box"> <textarea Size = 20 rows= 4 cols=40 Name='nota' id='nota'><?php echo $nota ?></textarea> </td>
      </tr>
		  <tr><td id="label"></td>
          <input name="transaccion" type="hidden" id="transaccion" size = "40" value= "<?php echo $transaccion ?>">
		      <td id="label">Grabar<input   name="submit" id="submit" value="Grabar" src="assets\img\save.png" type="image">
		  <a href="creditosdirectos.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		  </tr>
    </table>
  </form>
</div>
</div>


<?php
				//echo "Datos q voy a enviar".$bodegacont.$usuario1.$base. $pid ;
				$_SESSION['usuario']=$usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;
				$_SESSION['idorden'] = $idcompra;


				}
				else
				{
					header("location: index.html");
				}
?>

</div>
</body>
