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
					<div id = "centro" > <a class="titulo"> <center>   Finalizar Conteo   </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div>
<hr>	


<div id="cuerpo2" align= "center">
<div>
	<form name = "forminven" action="finalizarconteo1.php" method="POST" >
		<table  >
			<tr> 
			</tr>
			<tr>
				<td id= "label">Inventarios:</td> 
				<td><select name="conteoid" id = "conteoid">
<?php
				require('../conexion_mssql.php');
				$estado = 'Asignacion2'; 
				$conteo1= 'Finalizado';
				$conteo2= 'Finalizado';
				$conteo3= 'En Curso';
				$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result = $pdo->prepare('select * from inv_conteo where estado = :estado and Conteo1=:conteo1 and Conteo2=:conteo2 and Conteo3=:conteo3');	
				$result->bindParam(':estado',$estado,PDO::PARAM_STR);					
				$result->bindParam(':conteo1',$conteo1,PDO::PARAM_STR);	
				$result->bindParam(':conteo2',$conteo2,PDO::PARAM_STR);	
				$result->bindParam(':conteo3',$conteo3,PDO::PARAM_STR);	
				$result->execute();
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$bodega= $row['BodegaID'];
?>				
						<option value="<?php  echo $row['ConteoID']; ?>"><?php echo $row['Detalle']; ?></option>
<?php
					}	
?>				</select></td>
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
		$_SESSION['bodega']= $bodega; 
		$_SESSION['usuario']= $usuario1;
		}
		else
		{
			header("location: index.html");
		}
 ?>
</div>	 
</body>