<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>

<script type="text/javascript">
	function setfocus()
	{
		document.getElementById("transferencia").focus();
	}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload="setfocus()"> 
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
					//echo "Bodega". $bodega; 
?>	
</div>  
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Verificar Transferencias  <?php echo substr($nomsuc,10,20); ?>   </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a>  </div>
				 
	</div> 
<hr>
<div id="cuerpo2" align= "center">
<div>
	<form name = "formproducto" action="verificartransferencias0.php" method="POST" width="75%">
		<table align ="center" >
    	<tr>
    		<td id="label" >Transferencia # : </td> 
    		<td id= "box"> <input name="transferencia" type="text" id="transferencia" size = "30" value= "" > </td>
			<td id= "box"> <input name="bodega" type="hidden" id="bodega" size = "30" value= "<?php echo trim($bodega) ?>"> </td>
    	</tr>
		<tr>
		  <td id="label"></td>
		  <td id="label" >   Consultar
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
			$_SESSION['bodega']= $bodega; 
			}
			else
			{
				header("location: index.html");
			}	
	
?>	
</div>		
</body>
</html>