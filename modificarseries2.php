
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("newserie").focus();
}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload ="setfocus()">


<?php 
	session_start();		
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	=$_SESSION['acceso'];
			$bodega	=$_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$secu	=$_SESSION['secu'];
			
			if ($base=='CARTIMEX'){
					require 'headcarti.php';  
			}
			else{
					require 'headcompu.php';
			}
?>			
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Serie del Producto   </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
				 
	</div> 
<hr>	
<?php	
		    $dtid= $_GET["dtid"];
					
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----"; 
			 
			$usuario1= $usuario; 
			require('conexion_mssql.php');

			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare("LOG_CONSULTAR_SERIE_PRODUCTOID  @dtid=:dtid" );
			$result->bindParam(':dtid',$dtid,PDO::PARAM_STR);
			
			//Executes the query
			$result->execute();
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$codid=$row['codid'];
						$codnom=$row['codnom'];
						$serie=$row['serie'];
						$rmadtid=$row['rmadtid'];
					}
?>					
<div id="cuerpo2" align= "center">
<div>
	<form name = "formusuario" action="modificarseries3.php" method="POST" >
		<table align= "center">
    	<tr >
    		<td id= "label">Codigo: </td> 
    		<td id= "box"> <input  readonly name="id" type="text" id="id" size = "32" value= "<?php echo $codid ?>"> </td>
    	</tr>
    	<tr>
    		<td id= "label">Nombre: </td> 
    		<td id= "box" > <input  readonly name="usermo" type="text" id="usermo" size = "32" value= "<?php echo $codnom ?>"> </td>
    	</tr>
    	<tr>
    		<td id= "label">Serie: </td> 
    		<td id= "box"> <input  readonly name="clave" type="text" id="clave" size = "32" value= "<?php echo $serie ?>"> </td>
    	</tr>
		<tr>
    		<td id= "label">Nueva Serie: </td> 
    		<td id= "box"> <input name="newserie" type="text" id="newserie" size = "32" value= ""> </td>			
    	</tr>
		<tr>
		  <td id= "box"> <input  hidden  name="rmadtid" type="text" id="rmadtid" size = "32" value= "<?php echo $rmadtid ?>"> </td>
		  <td id="label" >   Grabar
      	  <input   name="submit" id="submit" value="Grabar" src="assets\img\save.png" type="image" > 
		  <a href="modificarseries1.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
      </table>
    </form> 
	
	
</div>	
</div> 					
<?php
			$usuario= $usuario1; 
			//echo "1Usuario:".$usuario. $base. $acceso. "*****-". $id. "---".$codid. "--".$usermo ."--".$nombre."--".$clave ."---".$acc ."---".$lugartrabajo."----";
			
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			$_SESSION['secu']=$secu;
			
		}
		else
		{
			header("location: index.html");
		}	

?>
</div> 
</body>

</html>