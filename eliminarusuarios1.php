<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<body> 
<div id= "header" align= "center">
<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$id= $_GET["id"];
			if ($base=='CARTIMEX'){
					require 'headcarti.php';  
			}
			else{
					require 'headcompu.php';
			}
			//echo " 1 esto envio".$base.$usuario.$acceso;
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Datos de Usuarios   </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a></div>
	</div> 
<hr>	

<?php 
			
			$usuario1= $usuario;  
			require('conexion_mssql.php');	
			
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			   
			//Select Query
			$result = $pdo->prepare("SELECT usrid,usuario,nombre,acceso,lugartrabajo,clave FROM seriesusr WITH (NOLOCK) where usrid=:id" );
			$result->bindParam(':id',$id,PDO::PARAM_STR);
			//Executes the query
			$result->execute();
			
			
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
				  {	  
				  $usrid = $row['usrid'];
				  $usermo = $row['usuario'];
				  $nombre = $row['nombre'];
				  $acc  = $row['acceso'];
				  $lugartrabajo = $row['lugartrabajo'];
				  $clave = $row['clave'];
				  }		
?>	

<div id="cuerpo2" align= "center">
<div>
	<form name = "formusuario" action="eliminarusuarios2.php" method="POST" width="75%">
		<table align ="center" >
    	<tr>
    		<td id="label" >Id: </td> 
    		<td id= "box"> <input disabled name="id" type="text" id="id" size = "30" value= "<?php echo $usrid ?>"> </td>
    	</tr>
    	<tr>
    		<td id="label" >Usuario: </td> 
    		<td id= "box"> <input  disabled name="usermo" type="text" id="usermo" size = "30" value= "<?php echo $usermo ?>"> </td>
    	</tr>
    	<tr>
    		<td id="label" >Nombre: </td> 
    		<td id= "box"> <input  disabled name="nombre" type="text" id="nombre" size = "30" value= "<?php echo $nombre ?>"> </td>
    	</tr>
		<tr>
    		<td id="label" >Clave: </td> 
    		<td id= "box"> <input  disabled name="clave" type="password" id="clave" size = "30" value= "<?php echo $clave ?>"> </td>
    	</tr>	
		<tr>
		  <td id="label"></td>
		  <td id="label">    Eliminar
      	  <input   name="submit" id="submit" value="Eliminar" src="assets\img\delete.png" type="image" border= "1px solid"> 
		  <a href="consultarusuarios.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
      </table>
    </form> 
</div>	
 </div> 
 <?php 
		$_SESSION['base']= $base;
		$_SESSION['usuario']= $usuario1;
		$_SESSION['acceso']= $acceso;
		$_SESSION['id']= $id;
		}
		else
		{
			header("location: index.html");
		}
 ?>
</div>	 
</body>