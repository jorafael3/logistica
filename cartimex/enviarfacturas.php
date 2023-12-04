<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("secu").focus();
}
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/tablas.css">
<body onload= "setfocus()">
<div id= "header" align= "center">
<?php
		    session_start();
		    if (isset($_SESSION['loggedin']))
				{
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					if ($base=='CARTIMEX'){
							require '../headcarti.php';
					}
					else{
							require '../headcompu.php';
					}

					//echo "BOdega". $bodega;
?>
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >

					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center> Facturas  Por enviar UIO/GYE<?php echo substr($nomsuc,10,20); ?> </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>

	</div>
<hr>
<div id= "cuerpo2" align= "center" >

			<div>
				<form name = "formfactura" action="enviarfacturas0.php" method="POST" width="75%">
					<table align ="center" >
					<tr>
						<td id="label" >Secuencia </td>
						<td id= "box"> <input name="secu" type="text" id="secu" size = "30" value= "" > </td>
						<td id= "box"> <input name="bodega" type="hidden" id="bodega" value= "<?php echo trim($bodega) ?>"> </td>
					</tr>
					<tr>
					  <td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="..\assets\img\refresh.png"></img></a></td>
					  <td id="label">   Enviar
					  <input   name="submit" id="submit" value="Grabar" src="..\assets\img\lupa.png" type="image">
					  <a href="../menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
					  </td>
					 </tr>
				  </table>
				</form>
			</div>


		<div class=\"table-responsive-xs\">
			<form name = "formfactura2" action="enviarfacturas0.php" method="POST" width="100%">
			<table align ="center" >
				<tr>
					<th colspan="7">Facturas Por Enviar </th>
				</tr>
				<tr>
					<th id= "fila4"> Bodega </th>
					<th id= "fila4"> Cliente </th>
					<th id= "fila4"> Factura </th>
					<th id= "fila4"> Fecha </th>
					<th id= "fila4">  </th>
					<th id= "fila4"> Tipo Pedido </th>
					<th id= "fila4"> Transporte </th>
				</tr>
		<?php

							$_SESSION['usuario']=$usuario;
							$_SESSION['bodega']=$bodega;
							//echo "bodega".$bodega.$base.$usuario.$acceso;
							require('../conexion_mssql.php');
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							//new PDO($dsn, $sql_user, $sql_pwd);
							//Select Query
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];

							$result = $pdo->prepare("LOG_FACTURAS_PENDIENTES_ENVIARGYEUIO @BODEGA=:bodega , @acceso=:acceso" );
							$result->bindParam(':bodega',$bodega,PDO::PARAM_STR);
							$result->bindParam(':acceso',$acceso,PDO::PARAM_STR);
							$result->execute();
							$arreglo = array();
							$x=0;
							while ($row = $result->fetch(PDO::FETCH_ASSOC))
							{
								$arreglo[$x][1]=$row['codbodega'];
								$arreglo[$x][2]=$row['secuencia'];
								$arreglo[$x][3]=$row['fecha'];
								$arreglo[$x][4]=$row['nombodega'];
								$arreglo[$x][5]=$row['Detalle'];
								$arreglo[$x][6]=$row['Tipodespacho'];
								$arreglo[$x][7]=$row['transporte'];
								$x++;
							}
							$count = count($arreglo);
							$y=0;
							while ( $y <= $count-1 )
							{
		?>
								<tr>
									<td id= "fila4"  > <?php echo $arreglo[$y][1] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][5] ?></td>
									<td id= "label2" align= "center"> <input name="secu" type="submit" id="secu" size = "20" value= "<?php echo $arreglo[$y][2] ?>" > </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][3] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][4] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][6] ?>  </td>
									<td id= "fila4"  > <?php echo $arreglo[$y][7] ?>  </td>
									<td id= "box"> <input name="bodega" type="hidden" id="bodega" size = "30" value= "<?php echo $bodega ?>"> </td>
                  <td id= "box"> <input name="tipodespacho" type="hidden" id="tipodespacho" size = "30" value= "<?php echo $arreglo[$y][6] ?>"> </td>
								</tr>
		<?php
							$y=$y+1;
							}
		?>
		</table>
		</form>
		</div>
 </div>

<?php
			$_SESSION['usuario']=$usuario;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['bodega']=$bodega;
			$_SESSION['nomsuc']=$nomsuc;
			$_SESSION['secu']=$nomsuc;

			}
			else
			{
				header("location: index.html");
			}

?>
</div>
</body>
