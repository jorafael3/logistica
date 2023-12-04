<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("codigo").focus();
}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload= "setfocus()"> 
<div id= "header" align= "center">
<?php 
		    session_start();	
		    if (isset($_SESSION['loggedin']))
				{
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					if ($_GET['id']=='') { $idinventario=$_SESSION['idconteo']; } else {$idinventario = $_GET['id'];}
					$nomsuc = $_SESSION['nomsuc'];
					$usuario1= $usuario; 
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}
					require('../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('select * from inv_conteo where ConteoId=:idconteo');
					$result->bindParam(':idconteo',$idinventario,PDO::PARAM_STR);					
					$result->execute();					
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$detalle=$row['Detalle'];
					}
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
					<div id = "izq" ></div>
					<div id = "centro" > <a class="titulo"> <center> <?php echo  $detalle  ?> </center> </a></div>
					<div id = "derecha" > <a href="..\menu.php"><img src="..\assets\img\home.png"></a></div>
	</div> 
<hr>	
<div id="cuerpo2" align= "center">
<div>
	<form name = "formproducto" action="inventario1.php" method="POST" width="75%">
		<table align ="center" >
    	<tr>
    		<td id="label" >Codigo o Descripcion </td> 
    		<td id= "box"> <input name="codigo" type="text" id="codigo" size = "30" value= "" required > </td>
			<td id= "box"> <input name="bodega" type="hidden" id="bodega" size = "30" value= "<?php echo trim($bodega) ?>"> </td>
    	</tr>
		<tr>
		  <td id="label"></td>
		  <td id="label" >   Consultar
      	  <input   name="submit" id="submit" value="Grabar" src="..\assets\img\lupa.png" type="image"> 
		  <a href="..\menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
      </table>
    </form> 
</div>	
 </div> 
<?php	
			$_SESSION['usuario']=$usuario1;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['nomsuc']=$nomsuc; 
			$_SESSION['idconteo']= $idinventario;
			}
			else
			{
				header("location: index.html");
			}	
	
?>	
</div>		
</body>	