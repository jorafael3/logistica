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
			$bodega = $_SESSION["bodega"];
			$conteo = TRIM($_POST["conteoid"]);
			$seccion = 'conteo2';
			$estado = "";
			$estconteo = "";
			$usuario1= $usuario; 
			//echo "esto viene". $usuario. $base .$bodega.$conteo.$seccion;
			//die(); 
			if ($base=='CARTIMEX'){
					require '../headcarti.php';  
			}
			else{
					require '../headcompu.php';
			}
			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result = $pdo->prepare('INV_PRODUCTOS_DIFERENCIAS_CONTEO2 @CONTEO=:CONTEO , @SECCION=:SECCION, @BODEGA=:BODEGA');		 
			$result->bindParam(':CONTEO',$conteo,PDO::PARAM_STR);
			$result->bindParam(':SECCION',$seccion,PDO::PARAM_STR);
			$result->bindParam(':BODEGA',$bodega,PDO::PARAM_STR);
			$result->execute();
			$arreglo = array(); 
			$x=0; 
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
				{
					$arreglo[$x][1]=$row['codigo'];
					$arreglo[$x][2]=$row['nombre'];
					$arreglo[$x][3]=$row['ProductoID'];
					$arreglo[$x][4]=$row['stock_bodega'];
					$arreglo[$x][5]=$row['Stock_conteo'];
					$x++; 
				}
			$count = count($arreglo);
			//echo "Contador". $count; 
			$y=0;
			$col = 0 ; 
			while ( $y <= $count-1 ) 
				{
					$diferencia = ($arreglo[$y][5]-$arreglo[$y][4]);
					//echo "-".$diferencia."-"; 
					if ($diferencia<>0 ) 
						{
							$col = $col+1; 
							//echo "veces a insertar ". $y .$arreglo[$y][3].$diferencia.$seccion.$conteo.$col;
							$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result1 = $pdo1->prepare('INSERT INTO INV_CONTEO_DIFERENCIAS(ProductoID , Diferencia, Seccion, ConteoID, col ) 
													   VALUES (:ProductoID, :Diferencia, :Seccion, :ConteoID , :col)');
							$result1->bindParam(':ProductoID',$arreglo[$y][3],PDO::PARAM_STR);
							$result1->bindParam(':Diferencia',$diferencia,PDO::PARAM_STR);
							$result1->bindParam(':Seccion',$seccion,PDO::PARAM_STR);
							$result1->bindParam(':ConteoID',$conteo,PDO::PARAM_STR);
							$result1->bindParam(':col',$col,PDO::PARAM_STR);
							$result1->execute();
						}
				$y=$y+1;
				$diferencia= 0 ; 
				}
//die(); 				
			$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result2 = $pdo2->prepare('select p.código as codigo ,p.nombre as nombre, c.* from  INV_CONTEO_DIFERENCIAS C
									   inner join INV_PRODUCTOS P  WITH (NOLOCK) ON  P.ID= C.PRODUCTOID
									   where C.ConteoID =:CONTEO AND c.seccion=:seccion order by c.col');		 
			$result2->bindParam(':CONTEO',$conteo,PDO::PARAM_STR);	
			$result2->bindParam(':seccion',$seccion,PDO::PARAM_STR);	
			$result2->execute();
			$arreglo2 = array(); 
			$x2=0; 
			while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) 
				{
					$arreglo2[$x2][1]=$row2['codigo'];
					$arreglo2[$x2][2]=$row2['nombre'];
					$arreglo2[$x2][3]=$row2['ProductoID'];
					$arreglo2[$x2][4]=$row2['Diferencia'];
					$x2++; 
				}
			$count2 = count($arreglo2);
			//echo "contador2 ". $count2; 
			$estado = "Asignacion2";
			$estconteo2 = "Finalizado"; 
			$estconteo3 = "En Curso"; 
			//echo "Actualiza".$estado.$estconteo2.$estconteo3.$conteo.$bodega ;
			$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result3 = $pdo3->prepare('update inv_conteo set Estado=:estado , Conteo2=:estconteo2 , BodegaID=:bodega, Conteo3=:estconteo3
									   where  ConteoID =:CONTEO');		 
			$result3->bindParam(':estado',$estado,PDO::PARAM_STR);	
			$result3->bindParam(':estconteo2',$estconteo2,PDO::PARAM_STR);	
			$result3->bindParam(':CONTEO',$conteo,PDO::PARAM_STR);	
			$result3->bindParam(':bodega',$bodega,PDO::PARAM_STR);
			$result3->bindParam(':estconteo3',$estconteo3,PDO::PARAM_STR);
			$result3->execute();
?>		
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>   Diferencias de Inventario  <?php echo $count2." Codigos" ?> </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
				 
	</div>
<hr>	
<div id="cuerpo2" align= "center">
	<div class=\"table-responsive-xs\">
		<table id= "listado" align ="center" > 
			<tr> 
					<th> CODIGO </th>
					<th> NOMBRE </th>
					<th> PRODUCTOID </th>
					<th> DIFERENCIA </th>
				</tr>
			<?php

						$y2=0;
						while ( $y2 <= $count2-1 ) 
						{
			?>	
			<tr> 
					<td id= "fila" align=center> <?php echo $arreglo2[$y2][1] ?></td> 
					<td id= "fila" align=center> <?php echo $arreglo2[$y2][2] ?></td> 
					<td id= "fila" align=center> <?php echo $arreglo2[$y2][3] ?></td> 
					<td id= "fila" align=center> <?php echo $arreglo2[$y2][4] ?></td> 
			</tr>
			<?php
						$y2=$y2+1;			
						}
				$_SESSION['base']= $base;
				$_SESSION['id']= $id;
				$_SESSION['usuario']= $usuario1;
				}
			else
				{
				header("location: sindex.html");
				}	
			?>			
		</table>
	</div>	
</div>
</div>  
</body>