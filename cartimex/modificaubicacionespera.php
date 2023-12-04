<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("ubinueva").focus();
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
			$acceso	=$_SESSION['acceso'];
			$bodega = $_SESSION['bodega'];
			$secu= $_GET["secu"];
			
			$usuario1= $usuario; 
			if ($base=='CARTIMEX'){
					require '../headcarti.php';  
			}
			else{
					require '../headcompu.php';
			}
		//	echo $secu ; 
		
			
			
?>		
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>   Modificar Ubicacion espera    </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div>
<hr>	
<?php 
			
			$_SESSION['base']= $base;  
			require('../conexion_mssql.php');	
			
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			   
			//Select Query
			$result = $pdo->prepare("select ubicaespera from facturaslistas where factura=:factura and tipo = 'VEN-FA'" );
			$result->bindParam(':factura',$secu,PDO::PARAM_STR);
			//Executes the query
			$result->execute();
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
				  {	  
				  $ubica = $row['ubicaespera'];
				  }	
			//echo "ubi".$ubica; 	   
?>	

<div id="cuerpo2" align= "center">
<div>
	<form name = "formusuario" action="modificaubicacionespera1.php" method="POST" >
		<table align= "center" >
    	<tr >
    		<td id= "label">Ubicacion Actual : </td> 
			<td><a> <?php echo $ubica ?> </a></td>
		<tr>
			<td id= "label">Ubicacion Nueva : </td> 
			<td id= "box"> <input name="ubinueva" type="text" id="ubinueva" size = "10" value= ""> </td>
		</tr>
		<tr>	
			<td id= "box"> <input name="id" type="hidden" id="id" size = "30" value= "<?php echo $secu ?>"> </td>
    	</tr>			
		<tr>
		   
		  <td id="label" >   Modificar
      	  <input   name="submit" id="submit" value="Grabar" src="..\assets\img\save.png" type="image" > 
		  <a href="verificarfacturas.php " style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
      </table>
    </form> 
	
	
</div>	
</div> 
 <?php 
		//echo "Usuario".$usuario1; 
		$_SESSION['usuario']=$usuario1;
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