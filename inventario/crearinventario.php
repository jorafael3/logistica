<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("detalle").focus();
}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload="setfocus()"> 
<div id= "header" align= "center">
<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			
			if ($base=='CARTIMEX'){
					require '../headcarti.php';  
			}
			else{
					require '../headcompu.php';
			}
			//echo $id; 
			
			
?>		
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>   Creacion de Inventario   </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div>
<hr>	


<div id="cuerpo2" align= "center">
<div>
	<form name = "forminven" action="crearinventario1.php" method="POST" >
		<table align= "center" >
			<tr> 
			</tr>
			<tr>
				<td id= "label">Nombre:</td> 
				<td id= "box"> <input  name="detalle" id= "detalle" type="text" size = "30" value= " "> </td>
			</tr>
			<tr>
				  <td id="label"></td>
				  <td id="label"> Grabar
				  <input   name="submit" id="submit" value="Agregar" src="..\assets\img\save.png" type="image" border= "1px solid"> 
				  <a href="mantenimientoinventario.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
		</table>
    </form> 
	
	
</div>	
</div> 
<?php
		$_SESSION['base']= $base;
		$_SESSION['id']= $id;
		$_SESSION['usuario']= $usuario;
		}
		else
		{
			header("location: sindex.html");
		}
 ?>
</div>	 
</body>