<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("secu").focus();
}
</script>
<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../css/tablas.css">
<body onload= "setfocus()">
<div id= "header" align= "center">
<?php
session_start();

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

		    if (isset($_SESSION['loggedin']))
				{
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					if ($base=='CARTIMEX'){
							require '../../headcarti.php';
					}
					else{
							require '../../headcompu.php';
					}

					//$clave = md5("webmaster2021");
					//echo $clave;

							$_SESSION['usuario']=$usuario;
							$_SESSION['bodega']=$bodega;
							//echo "Esto envio".$usuario. $base. $acceso. $bodega;
							require('../../conexion_mssql.php');
							$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$usuario = $_SESSION['usuario'];
							$bodega = $_SESSION['bodega'];
				            $result = $pdo->prepare("Imp_Liquidaciones_Pendientes" );
							$result->execute();
							$arreglo = array();
							$x=0;
							while ($row = $result->fetch(PDO::FETCH_ASSOC))
							{
								$arreglo[$x][1]=$row['LiqID'];
								$arreglo[$x][2]=$row['Detalle'];
								$arreglo[$x][3]=$row['Fecha'];
								$arreglo[$x][4]=$row['Empresa'];
								$x++;
							}
							//contador para unir el segundo arreglo
							$z = count($arreglo);
							require('../../conexion_mssqlxtratech.php');
							$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				            $result1 = $pdo1->prepare("Imp_Liquidaciones_Pendientes" );
							$result1->execute();
							while ($row1 = $result1->fetch(PDO::FETCH_ASSOC))
							{
								$arreglo[$z][1]=$row1['LiqID'];
								$arreglo[$z][2]=$row1['Detalle'];
								$arreglo[$z][3]=$row1['Fecha'];
								$arreglo[$z][4]=$row1['Empresa'];
								$z++;
							}

?>
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >

					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   LIQUIDACIONES PENDIENTES </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>

	</div>
<hr>
<div id= "cuerpo2" align= "center" >

		<div class=\"table-responsive-xs\">
			<form name = "formfactura2" action="recepcionimportaciones0.php" method="POST" width="100%">
			<table align ="center" >
				<tr>
					<th colspan="5">  </th>
				</tr>
				<tr>
					<th id= "fila4"> Empresa </th>
					<th id= "fila4"> #  </th>
					<th id= "fila4"> Liquidacion  </th>
					<th id= "fila4"> Detalle </th>
					<th id= "fila4"> Fecha </th>
				</tr>
<?php
							$count = count($arreglo);
							$y=0;
							while ( $y <= $count-1 )
							{
								 if 	($arreglo[$y][4]=="CARTIMEX"){
		?>
								<tr>
									<td id= "fila4"  > <?php echo $arreglo[$y][4] ?></td>

									<td id= "label2" > <a href ="recepcionimportacionesconsulta.php?IDLiq=<?php echo $arreglo[$y][1]?>"><?php echo $arreglo[$y][1] ?> </td>
  <?php
  						  if ($acceso<>"8") {
  	?>
  							<td id= "label2" align= "center"> <input name="IDLiq" type="submit" id="IDLiq" size = "20" value= "<?php echo $arreglo[$y][1] ?>" > </td>
  	<?php
  							}
  						else {
    ?>
    							<td id= "label2" align= "center"> <input name="IDLiq" type="text" id="IDLiq" size = "20" value= "<?php echo $arreglo[$y][1] ?>" > </td>
    <?php
    					}
    ?>
									<td id= "fila4"  > <?php echo $arreglo[$y][2] ?></td>
									<td id= "fila4"  > <?php echo $arreglo[$y][3] ?>  </td>
									<td id= "box"> <input name="fecha" type="hidden" id="fecha" size = "30" value= "<?php echo $arreglo[$y][3] ?>"> </td>
									<td id= "box"> <input name="empresa" type="hidden" id="empresa" size = "30" value= "<?php echo $arreglo[$y][4] ?>"> </td>
								</tr>
		<?php
								}
							$y=$y+1;
							}
		?>
		</table>
		</form>

		</div>
				<div class=\"table-responsive-xs\">
			<form name = "formfactura2" action="recepcionimportacionesxtratech0.php" method="POST" width="100%">
			<table align ="center" >
				<tr>
					<th colspan="5">  </th>
				</tr>
				<tr>
					<th id= "fila4"> Empresa </th>
					<th id= "fila4"> #  </th>
					<th id= "fila4"> Liquidacion  </th>
					<th id= "fila4"> Detalle </th>
					<th id= "fila4"> Fecha </th>
				</tr>
<?php
							$count = count($arreglo);
							$y=0;
							while ( $y <= $count-1 )
							{
								if 	($arreglo[$y][4]=="XTRATECH"){
		?>
								<tr>
									<td id= "fila4"  > <?php echo $arreglo[$y][4] ?></td>
									<td id= "label2" > <a href ="recepcionimportacionesconsultaxtratech.php?IDLiq=<?php echo $arreglo[$y][1]?>"><?php echo $arreglo[$y][1] ?> </td>
  <?php
              if ($acceso<>"8") {
  ?>
                <td id= "label2" align= "center"> <input name="IDLiq" type="submit" id="IDLiq" size = "20" value= "<?php echo $arreglo[$y][1] ?>" > </td>
  <?php
                }
              else {
  ?>
            <td id= "label2" align= "center"> <input name="IDLiq" type="text" id="IDLiq" size = "20" value= "<?php echo $arreglo[$y][1] ?>" > </td>
  <?php
              }
  ?>                     
									<td id= "fila4"  > <?php echo $arreglo[$y][2] ?></td>
									<td id= "fila4"  > <?php echo $arreglo[$y][3] ?>  </td>
									<td id= "box"> <input name="fecha" type="hidden" id="fecha" size = "30" value= "<?php echo $arreglo[$y][3] ?>"> </td>
									<td id= "box"> <input name="empresa" type="hidden" id="empresa" size = "30" value= "<?php echo $arreglo[$y][4] ?>"> </td>
								</tr>
		<?php					}
							$y=$y+1;
							}
		?>
		</table>
		</form>
		<a><strong>*** Para empezar la recepcion de la liquidacion dar clic en el boton ***</strong> </a>
		</div>
 </div>

<?php
			$_SESSION['usuario']=$usuario;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
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
