<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("ubi").focus();
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
			$id= $_GET["id"];
			$secu= $_GET["secu"];
			if ($base=='CARTIMEX'){
					require 'headcarti.php';  
			}
			else{
					require 'headcompu.php';
			}
			//echo $id; 
			
			
?>		
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>   Datos de Ubicacion   </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a>  </div>
				 
	</div>
<hr>	
<?php 
			
			$_SESSION['base']= $base;  
			require('conexion_mssql.php');	
			
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			   
			//Select Query
			$result = $pdo->prepare("Log_busqueda_sku_sec @id=:id, @secu=:secu" );
			$result->bindParam(':id',$id,PDO::PARAM_STR);
			$result->bindParam(':secu',$secu,PDO::PARAM_STR);
			//Executes the query
			$result->execute();
			
			
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
				  {	  
				  $id = $row['aid'];
				  $codp = $row['cod'];
				  $nombre = $row['nom'];
				  $secuencia = $row['sec'];
				  $ubica = $row['pe'];
				  }	
			//echo "ubi".$ubica; 	  
				  
?>	

<div id="cuerpo2" align= "center">
<div>
	<form name = "formusuario" action="modificasku1.php" method="POST" >
		<table align= "center" >
    	<tr >
    		<td id= "label">Id: </td> 
			<td><a> <?php echo $id ?> </a></td>
			<td id= "box"> <input name="id" type="hidden" id="id" size = "30" value= "<?php echo $id ?>"> </td>
    	</tr>
    	<tr>
    		<td id= "label">Codigo: </td> 
    		<td><a> <?php echo $codp ?> </a></td>
    	</tr>
    	<tr>
    		<td id= "label">Nombre: </td> 
    		<td width= "10px"><a> <?php echo $nombre ?> </a></td>
    	</tr>
		<tr>
    		<td id= "label">Secuencia: </td> 
    		<td><a> <?php echo $secuencia ?> </a></td>
			<td id= "box"> <input name="secu" type="hidden" id="secu" size = "30" value= "<?php echo $secuencia ?>"> </td>
    	</tr>
    	<tr>
    		<td id= "label">Ubicacion: </td> 
    		<td id= "box"> <input  name="ubi" id= "ubi" type="text" size = "30" value= "<?php echo TRIM($ubica) ?>"> </td>
    	</tr>			
		<tr>
		  <td id="label"></td>
		  <td id="label" >   Modificar
      	  <input   name="submit" id="submit" value="Grabar" src="assets\img\save.png" type="image" > 
		  <a href="consultasku.php?id=<?php echo $id ?>" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
      </table>
    </form> 
	
	
</div>	
</div> 
 <?php 
		$_SESSION['base']= $base;
		$_SESSION['id']= $id;
		}
		else
		{
			header("location: index.html");
		}
 ?>
</div>	 
</body>