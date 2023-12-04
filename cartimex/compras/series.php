<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
 
<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../css/tablas.css">
<body> 
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
					$bodega	=$_SESSION['bodega'];
					$ProductoID= $_GET['ProductoID']; 
					$ordenid= $_GET['ordenid']; 
					$usuario1= trim($usuario); 
					if ($base=='CARTIMEX'){
							require '../../headcarti.php';  
					}
					else{
							require '../../headcompu.php';
							$_SESSION['base']= $base; 	
					}
				    
					require('../../conexion_mssql.php');
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('select P.Código, P.Nombre, C.Serie from INV_PRODUCTOS_SERIES_COMPRAS C
											INNER JOIN INV_PRODUCTOS P WITH (NOLOCK) ON P.ID= C.ProductoID
											where (FCompraID=:FCompra or c.LiquidacionID=:FCompra1 ) and ProductoID=:ProductoID');		 
					$result->bindParam(':FCompra',$ordenid,PDO::PARAM_STR);
					$result->bindParam(':FCompra1',$ordenid,PDO::PARAM_STR);
					$result->bindParam(':ProductoID',$ProductoID,PDO::PARAM_STR);
					$result->execute();
					$arreglo = array(); 
					$x=0; 
					while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
					{
						$Codigo=$row['Código'];
						$Nombre=$row['Nombre'];
						$arreglo[$x][1]=$row['Serie'];
						$x++;
					}	
					$count = count($arreglo);
?>		
</div>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Series Ingresadas</center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a>  </div>
				 
	</div> 
<hr>							
<div id="cuerpo2" align= "center">
	<div>
			<table border=2 width=100% id= "series">
				 
				<tr> 
					<th> CODIGO </th>
					<th colspan= 2 > PRODUCTO </th>
				</tr>
				<tr> 
					<td id= "fila2" align=left  > <?php echo $Codigo ?></td> 
					<td id= "fila2" align=left colspan= 2 > <?php echo $Nombre ?></td> 
				</tr>
				<tr>
					 
					<td> <strong> SERIES:  <strong></td>
					<td id= "fila2" align=left>					
				<?php

						$y=0;
						while ( $y <= $count-1 ) 
						{
							
				?>
							
				<?php
						$serie= ''; 
						$cont= 0;
						$enter= ' ';
						$serie = $serie ."/".$arreglo[$y][1].$enter;	 	
				?>			
					 <?php echo $serie ?>
				
				<?php
							$y=$y+1;			
						}
				?>
					</td> 
				</tr>	
			</table>
	</div>	
 
</div>
				<?php	
					$usuario= $usuario1; 		
					$_SESSION['usuario']=$usuario;
					$_SESSION['id']=$Id;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['codigo']=$codigo;
					$_SESSION['bodega']=$bodega;
					$_SESSION['nomsuc']=$nomsuc; 
				}
				else
				{
					header("location: index.html");
				}	
				?>		

</div>
</body>	
</html>