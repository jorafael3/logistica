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
			$usuario1= $usuario; 
			
			
?>		
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>   Diferencias de Inventario   </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div>
<hr>	


<div id="cuerpo2" align= "center">
<div>
	<form name = "forminven" action="diferenciasprimerconteo1.php" method="POST" >
		<table  >
			<tr> 
			</tr>
			<tr>
				<td id= "label">Inventarios:</td> 
				<td><select name="conteoid" id = "conteoid">
<?php
				require('../conexion_mssql.php');
				$estado = 'En curso'; 
				$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result = $pdo->prepare('select * from inv_conteo where estado = :estado');	
				$result->bindParam(':estado',$estado,PDO::PARAM_STR);					
				$result->execute();
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
?>				
						<option value="<?php  echo $row['ConteoID']; ?>"><?php echo $row['Detalle']; ?></option>
<?php
					}	
?>				</select></td>
			</tr>
			<tr>
				<td id= "label">Bodega :</td> 
				<td><select name="bodega" id = "bodega">
<?php
				$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result1 = $pdo1->prepare('select * from inv_bodegas where Anulado= 0  ');
				$result1->execute();
				while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
					{
?>						
						<option value="<?php  echo $row1['ID']; ?>"><?php echo $row1['Código']." - ".$row1['Nombre']; ?></option>
<?php
					}
?>
				</select></td>
			</tr>	
			<tr>
				  <td id="label"></td>
				  <td id="label"> Procesar
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
		$_SESSION['usuario']= $usuario1;
		}
		else
		{
			header("location: sindex.html");
		}
 ?>
</div>	 
</body>