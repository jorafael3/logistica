<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("guia").focus();
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
			$acceso = $_SESSION['acceso'];
			$base = $_SESSION['base'];
			$id= $_GET["id"];
			$secu= $_POST["secu"];
			$usuario1= $usuario; 
			
			//echo $secu ; 
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
					<div id = "centro" > <a class="titulo"> <center>   Finalizar Entrega de Factura </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
	</div>
<hr>	
<?php 
			
			$_SESSION['base']= $base;  
			require('../conexion_mssql.php');	
			
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			   
			//Select Query
			$result = $pdo->prepare("select f.id, Detalle, Secuencia, guia, transpor= p.Nombre , chofer, placa from ven_facturas f with(nolock)
									 inner join facturaslistas fl with(nolock) on fl.factura = f.id 
									 inner join SIS_PARAMETROS p with(nolock) on fl.TRANSPORTE= p.id
									 where fl.estado = 'DESPACHADA' and f.secuencia=:secu" );
			$result->bindParam(':secu',$secu,PDO::PARAM_STR);
			//Executes the query
			$result->execute();
			$count = $result->rowcount();
			if ($count < 0 )
				{		
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
						  {	  
						  $id = $row['id'];
						  $detalle = $row['Detalle'];
						  $secuencia = $row['Secuencia'];
						  $transporte = $row['transpor'];
						  $guia = $row['guia'];
						  $placa = $row['placa'];
						  }	
				   ?>						

<div id="cuerpo2" align= "center">
<div>
	<form name = "formusuario" action="entregarfacturas1.php" method="POST" >
		<table align= "center" >
    	<tr >
    		<td id= "box"> <input name="id" type="hidden" id="id" size = "30" value= "<?php echo $id ?>"> </td>
			<td id= "box"> <input name="transporte" type="hidden" id="transporte" size = "30" value= "<?php echo $transporte ?>"> </td>
    	</tr>
		<tr>
    		<td id= "label">Factura #: </td> 
    		<td><a> <?php echo $secuencia ?> </a></td>
			<td id= "box"> <input name="secu" type="hidden" id="secu" size = "30" value= "<?php echo $secuencia ?>"> </td>
    	</tr>
    	<tr>
    		<td id= "label">Cliente: </td> 
			<td><a> <?php echo $detalle ?> </a></td>
    	</tr>
		<tr>
    		<td id= "label">Transporte: </td>  
			<td id= "box"> <input  name="transporte" id= "transporte" type="text" size = "30" value= "<?php echo $transporte ?>" readonly > </td>
    	</tr>		
		<tr>		
    		<td id= "label">Guia:  </td> 
			<td id= "box"> <input  name="guia" id= "guia" type="text" size = "30" value= "<?php echo $guia ?>" > </td>
    	</tr>
		<tr>
    		<td id= "label">Chofer: </td>  
			<td id= "box"> <input  name="chofer" id= "chofer" type="text" size = "30" value= "<?php echo $chofer ?>"   > </td>
    	</tr>		
		<tr>		
    		<td id= "label">Placa:  </td> 
			<td id= "box"> <input  name="placa" id= "placa" type="text" size = "30" value= "<?php echo $placa ?>"  > </td>
    	</tr>
		<tr>
    		<td id= "label">Comentario: </td> 
			<td id= "box"> <textarea Size = 20 rows= 4 cols=32 Name='comentarioentre' id='comentarioentre'></textarea> </td>
    	</tr>				
		<tr>
		  <td id="label"></td>
		  <td id="label" >   Grabar
      	  <input   name="submit" id="submit" value="Grabar" src="..\assets\img\save.png" type="image" > 
		  <a href="entregarfacturas.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
      </table>
    </form> 
	
	
</div>	
</div> 
 <?php 
		$_SESSION['base']= $base;
		$_SESSION['usuario']=$usuario1;
		$_SESSION['acceso']=$acceso;
		$_SESSION['id']= $id;
		
		}
		else
		{ ?>
			<div id="cuerpo2" >
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>  No se puede Entregar esta Factura   </center> </a></div>
			</div>
			
		<?php
			header("Refresh: 1 entregarfacturas.php");		
		}
		}
		else
		{
			header("location: index.html");
		}
 ?>
</div>	 
</body>