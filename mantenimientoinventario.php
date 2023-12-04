<meta name="viewport" content="width=device-width, height=device-height">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("codigo").focus();
}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/tablas.css">
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
					$usuario1 = $usuario; 
					$usuario= $usuario1; 
					if ($base=='CARTIMEX'){
							require 'headcarti.php';  
					}
					else{
							require 'headcompu.php';
					}
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
					<div id = "izq" ></div>
					<div id = "centro" > <a class="titulo"> <center>   Mantenimiento Inventario </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a></div>
	</div> 
<hr>	
<div id= "cuerpo2" align= "center" >
	 
			<div>
				<form name = "formfactura" action="crearinventario.php" method="POST" width="75%">
					<table align ="center" >
						<input   id="submit" value="Crear Inventario" type="submit">
					</table>
				</form>
				<form name = "formfactura" action="zxxxxx.php" method="POST" width="75%">
					<table align ="center" >
						<input   id="submit" value="Asignar codigos " type="submit">
					</table>
				</form>
				<form name = "formfactura" action="zxxxxx.php" method="POST" width="75%">
					<table align ="center" >
						<input   id="submit" value="FinalizarInventario " type="submit">
					</table>
				</form>
			</div>
</div>			
<?php	
			
			$_SESSION['usuario']=$usuario1;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['nomsuc']=$nomsuc; 
			}
			else
			{
				header("location: index.html");
			}	
	
?>	
</div>		
</body>	