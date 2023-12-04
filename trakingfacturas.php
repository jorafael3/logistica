<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("secu").focus();
}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body onload= "setfocus()"> 
<div id= "header" align= "center">
<?php 
		    session_start();	
		    if (isset($_SESSION['loggedin']))
				{
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					if ($base=='CARTIMEX'){
							require 'headcarti.php';  
					}
					else{
							require 'headcompu.php';
					}
					
					//echo "BOdega". $bodega; 
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Traking Facturas  <?php if ( $acceso==1){ echo " ";  } else { echo substr($nomsuc,10,20);} ?> </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
				 
	</div> 
<hr>	
<div id= "cuerpo2" align= "center" >
	 
			<div>
				<form name = "formfactura" action="trakingfacturas0.php" method="POST" width="75%">
					<table align ="center" >
					<tr>
						<td id="label" >Factura # </td> 
						<td id= "box"> <input name="secu" type="text" id="secu" size = "30" value= "" > </td>
						<td id= "box"> <input name="bodega" type="hidden" id="bodega" value= "<?php echo trim($bodega) ?>"> </td>
					</tr>
					<tr>
					  <td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>
					  <td id="label">   Consultar
					  <input   name="submit" id="submit" value="Grabar" src="assets\img\lupa.png" type="image"> 
					  <a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
					  </td>
					 </tr> 
				  </table>
				</form> 
			</div>		
 </div> 
 
<?php	
			$_SESSION['usuario']=$usuario;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['bodega']=$bodega;
			$_SESSION['nomsuc']=$nomsuc; 	
 			
			}
			else
			{
				header("location: index.html");
			}	
	
?>	
</div>		
</body>	