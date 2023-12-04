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
<link rel="stylesheet" type="text/css" href="../css/tablas.css">
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
					$idinventario= $_SESSION['idconteo'];
					if ($_POST["codigo"]=='') {$codigo= $_SESSION['codigo'];}else{$codigo= $_POST["codigo"];}
					//echo $bodega.$stock.$base; 
			 
					$usuario1= $usuario; 
					if ($base=='CARTIMEX'){
							require '../headcarti.php';  
					}
					else{
							require '../headcompu.php';
							$_SESSION['base']= $base;  
					}
				    $codigo= trim($codigo);
					//echo "codigo".$codigo;
					require('../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('LOG_PRODUCTOS_SELECT_INVENTARIO @NOMBRE=:codigo');		 
					$result->bindParam(':codigo',$codigo,PDO::PARAM_STR);
					$result->execute();
					
					$arreglo = array(); 
					
					$x=0; 
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$arreglo[$x][1]=$row['aid'];
						$arreglo[$x][2]=$row['co'];
						$arreglo[$x][3]=$row['cod_alterno1'];
						$arreglo[$x][4]=$row['cod_alterno2'];
						$arreglo[$x][5]=$row['nom'];
						$x++; 
						
					}	
					$count = count($arreglo);
					// echo "Registros".$count;

?>		
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >			 
			<div id = "izq" >  </div>
			<div id = "centro"> <a class="titulo"> <center>  <?php echo "Listado de Productos " . $codigo ?>   </center> </a></div>
			<div id = "derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
	</div> 
<hr>	
	<div>
		<font  size="5" color="black" ><a href= "inventario0.php" style="text-decoration:none">
			   <strong><center> Nueva consulta <img src="..\assets\img\lupa.png"> </center></strong></a>
		</font>
	</div>
</div>	
<div id="cuerpo2" align= "center">
	<div class=\"table-responsive-xs\">
		<table id= "listado" align ="center" > 
				<tr> 
					<th> ID </th>
					<th> CODIGO </th>
					<th> NOMBRE </th>
				</tr>	
				<?php

						$y=0;
						while ( $y <= $count-1 ) 
						{
				?>
							<tr> 
								<td id= "fila" align=center> <?php echo $arreglo[$y][1] ?> </td> 
								<td id= "fila" align=center> <a href="ingresaprimerconteo1.php?id=<?php echo $arreglo[$y][1] ?>"><?php echo $arreglo[$y][2] ?></td> 
								<td id= "fila" align=center> <a href="showpicture.php?code=<?php echo $arreglo[$y][2] ?>"> <?php echo $arreglo[$y][5] ?></a></td> 
							</tr>
				<?php
						$y=$y+1;			
						}	
				$usuario= $usuario1; 				
				$_SESSION['usuario']=$usuario;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;
				$_SESSION['codigo']=$codigo;
				$_SESSION['idconteo']= $idinventario;
				}
				else
				{
					header("location: index.html");
				}	
				?>		
			</table>
		</div>	
</div>	
</body>	
</html>