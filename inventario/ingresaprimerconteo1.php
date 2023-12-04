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
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
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
					$id= $_GET['id'];
					$codigo= $_SESSION["codigo"];
					$idinventario= $_SESSION['idconteo']; 	
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
					}				
?>
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center> Datos del Producto </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="../assets\img\home.png"></a> </div>
				 
	</div>
<hr>	
<?php
					$usuario1=$usuario;
					$_SESSION['base']= $base;  
					require('../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare("Log_busqueda_producto_id @id=:id");
					$result->bindParam(':id',$id,PDO::PARAM_STR);
					$result->execute();
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
						  {	  
						  $codid = $row['aid'];
						  $co = $row['co'];
						  $cod1 = $row['cod_alterno1'];
						  $nom = $row['nom'];
						  $descrip = $row['descrip'];
						  }
						  
					$SECCION= 'conteo1';
					//echo $codid. $idinventario. $SECCION; 
					
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare(' SELECT  ID, CANT = isnull (SUM (CANTIDAD),0)    
												FROM INV_CONTEO_PRODUCTOS WHERE  ID =:PRODUCTOID AND NOMBRESECCION =:SECCION and ConteoID =:ConteoID
												GROUP BY  ID');
					$result1->bindParam(':PRODUCTOID',$codid,PDO::PARAM_STR);
					$result1->bindParam(':SECCION',$SECCION,PDO::PARAM_STR);
					$result1->bindParam(':ConteoID',$idinventario,PDO::PARAM_STR);
					$result1->execute();
					$count = $result1->rowcount();
					
					if ($count==0 )
						{
							$CANT= 0 ;
						}
					else 
						{			
							//echo "entro aqui ".$count; 
							while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
								  {	  
									$CANT = $row1['CANT'];
								  }
						}		  
?>		

<div id="cuerpo2" align= "center" >
<div align= "left">
	<table>
		<tr><td><strong>Id :</strong> <a><?php echo $codid ?> </a><br></td></tr>
		<tr><td><strong>Codigo :</strong> <a><?php echo $co ?> </a><br></td></tr>
		<tr><td><strong>Nombre:</strong> <a><?php echo $nom ?> </a><br></td></tr> 
		 
		<tr><td><br><strong>Ingresados :</strong> <a><?php echo $CANT ?> </a><br></td></tr> 
    </table>
<br>
<div>
	<form name = "formusuario" action="ingresaprimerconteo2.php" method="POST" width="75%">
		<table align ="center" >
		<tr> 
			<th colspan="2"> Agregar Conteo </th>
			<td id= "box"> <input name="prodid" type="hidden" id="prodid" size = "30" value= "<?php echo $codid ?>"> </td>
		</tr>					
    	<tr>
    		<td id="label" >Cantidad: </td> 
    		<td id= "box"> <input name="cant" type="text" id="cant" size = "30" value= " "> </td>
    	</tr>
		<tr>
		  <td id="label"></td>
		  <td id="label"> Agregar
      	  <input   name="submit" id="submit" value="Agregar" src="..\assets\img\save.png" type="image" border= "1px solid"> 
		  <a href="inventario1.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
      </table>
    </form> 
</div>	
</div> 
		
<?php	
				$_SESSION['cobusqueda']=$codigo;	
				$_SESSION['codigo']=$co;
				$_SESSION['codalter']=$cod1;	
				$_SESSION['usuario']=$usuario1;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;
				$_SESSION['SECCION'] = $SECCION;
				$_SESSION['ConteoID'] = $idinventario;
				}
				else
				{
					header("location: index.html");
				}
?>
</div>			
</body>	