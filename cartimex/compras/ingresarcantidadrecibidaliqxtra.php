<!DOCTYPE html>
<html>
<head>
<title> SGL </title>

</head>
<script type="text/javascript">
function setfocus(){
    document.getElementById("cant").focus();
}
</script>
<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload= "setfocus()">
<div id= "header" align= "center"> 
<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','On');
		    session_start();	
		    if (isset($_SESSION['loggedin']))
				{
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$liqid= $_GET['liqid']; 
					$productoid= $_GET['productoid'];
					$_SESSION['usuario']=$usuario; 
					
					//echo  $idcompra. $productoid.$base; 
										
					if ($base=='CARTIMEX'){
							require '../../headcarti.php';  
					}
					else{
							require '../../headcompu.php';
					}
					require('../../conexion_mssqlxtratech.php');
					$usuario = $_SESSION['usuario'];
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('select código, nombre from inv_productos where id=:productoid');		 
					$result->bindParam(':productoid',$productoid,PDO::PARAM_STR);	
					$result->execute();	
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
						{
							$codigo=$row['código'];
							$nombre =$row['nombre'];
						}	
?>
</div>
<div  id = "Cuerpo" >	
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div> 
					<div id = "centro" > <a class="titulo"> <center> Ingresar Cantidad Recibida </center> </a></div>
					<div id = "derecha" > <a href="..\..\menu.php"><img src="..\..\assets\img\home.png"></a> </div>
				 
	</div>
<hr>	

<div id="cuerpo2" align= "center" >
	<table  align ="center" > 
		<tr> <a> <strong><?php echo $codigo . "&nbsp&nbsp&nbsp&nbsp" . $nombre   ?> </strong></a></tr>
		
 		<form name = "forminven" action="ingresarcantidadrecibidaliqxtra1.php" method="POST" >
			
			<tr>
				<th width = "10"> Recibida </th>
				<input name="proid" type="hidden" id="proid" size = "30" value= "<?php echo $productoid ?>">  
				<td  > <input name="cant" type="number" id="cant"  value= "" required> </td>			
			</tr>
			<tr>
				<td align= "center" colspan= 2 ><input   name="submit" id="submit" type ="submit" value="OK"  border= "1px solid"> </td>
			</tr>
		</form>	
		<tr>	 
			<td align="center" colspan= 2 ><a href="recepcionimportacionesxtratech1.php" style="text-decoration:none">Regresar</a></td>
		</tr>
	</table>
 
</div>	

		
<?php	
				//echo "Datos q voy a enviar".$bodegacont.$usuario1.$base. $pid ; 
				$_SESSION['usuario']=$usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;
				$_SESSION['liqid'] = $liqid;
				
			 
				}
				else
				{
					header("location: index.html");
				}
?>
		
</div>	
</body>	