<html>

<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script type="text/javascript">
function setfocus(){
    document.getElementById("usuario").focus();
}

$(document).ready(function() {	
    $('#usuario').on('blur', function() {
        $('#result-username').html('<img src="assets/img/load.gif" />').fadeOut(1000);
 
        var codigo = $(this).val();		
        var dataString = 'codigo='+codigo;
		
        $.ajax({
            type: "POST",
            url: "valida_usuario.php",
            data: dataString,
            success: function(data) {
                $('#result-username').fadeIn(1000).html(data);
            }
        });
    }); 
});    
</script>

<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body  onload= "setfocus()"> 
<div id= "header" align= "center">
<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
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
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Datos de Usuarios   </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
	</div> 
<hr>	
<div id="cuerpo2" align= "center">
<div>
	<form name = "formusuario" action="agregarusuarios2.php" method="POST" width="75%">
		<table align ="center" >
    	<tr>
    		<td id="label" >Id: </td> 
    		<td id= "box">  <input disabled name="id" type="text" id="id" size = "32" value= " "> </td>
    	</tr>
    	<tr>
    		<td id="label" >Usuario: </td> 
    		<td id= "box"> <input  name="usuario" type="text" id="usuario" size = "32" value= "" > </td>
			
    	</tr>
    	<tr>
    		<td id="label" >Nombre: </td> 
    		<td id= "box"> <input  name="nombre" type="text" id="nombre" size = "32" value= ""> </td>
    	</tr>
		<tr>
    		<td id="label" >Clave: </td> 
    		<td id= "box"> <input  name="clave" type="password" id="clave" size = "32" value= ""> </td>
    	</tr> 
    	<tr>
    		<td id="label" >Acceso: </td> 
    		<td id= "box"> <input  name="acceso" type="text" id="acceso" size = "32" value= ""> </td>
    	</tr>	
    	<tr>
		<td id="label" >Sucursal: </td> 
			<td>
				<select name="lugartrabajo" id = "lugartrabajo">	
<?php
		require('conexion_mssql.php');	
		$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		//new PDO($dsn, $sql_user, $sql_pwd);
		//Select Query
		$result = $pdo->prepare("SELECT ID, Nombre FROM sis_sucursales WITH (NOLOCK) where anulado = 0 " );		 
		//Executes the query
		$result->execute();
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
			{
?>			
				<option value="<?php  echo $row['ID']; ?>"><?php echo $row['Nombre']; ?></option>
				<?php
			}
?>	
				</select>
			</td>	
    	</tr>
		<tr>
    		<td id="label" >Departamento: </td> 
    		<td id= "box"> <input  name="dpto" type="text" id="dpto" size = "32" value= ""> </td>
    	</tr>
		<tr>
		  <td id="label"></td>
		  <td id="label">   Grabar
      	  <input   name="submit" id="submit" value="Grabar" src="assets\img\save.png" type="image" >
		  <a></a>
		  <a href="consultarusuarios.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
		 
      </table>
	  <div id="result-username"></div>
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