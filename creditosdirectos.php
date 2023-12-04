<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("secu").focus();
}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/tablas.css">
<body onload= "setfocus()">
<div id= "header" align= "center">
<?php
		    session_start();
		    if (isset($_SESSION['loggedin']))
				{
					//echo "Entra aqui";
					$usuario = $_SESSION['usuario'];
					$base = $_SESSION['base'];
					$acceso	=$_SESSION['acceso'];
					$bodega = $_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					$desde = $_POST['desde'];
					$hasta = $_POST['hasta'];
					if ($base=='CARTIMEX'){
							require 'headcarti.php';
					}
					else{
							require 'headcompu.php';
					}
					date_default_timezone_set('America/Guayaquil');
					$fecha = date("y-m-d", time());
					$hora = date("H:i:s", time());
					$fh = $fecha . " " . $hora;
					$usuario1= $usuario;
					//echo "BOdega". $bodega;
?>
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >

					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>   Creditos Directos  </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a>  </div>

	</div>
<hr>

<div id= "cuerpo2" align= "center" >
<div>
	 <table align ="center" >
    	<tr>
			<td id="etiqueta"> Refresh <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>
		</tr>
    </table>
     <?php
		include('conexion_mssql.php');
								include("conexion.php");
								$sql1 = "SELECT a.* ,  date_format(t.fechacompleto,'%d/%m/%Y ') as fechac ,
										t.transaccion as transa , date_format(a.fecha,'%d/%m/%Y ') as fechaf ,t.doc1 as doc ,
										(case when  a.canal= 0 then '13' else '55'end ) as SId , t.* ,
										(case when a.pickup = 1 then p.bodega else '' end ) as retiro
										FROM covidsales a
										left outer join covidcredito t on t.transaccion= a.secuencia
										left outer join covidpickup p on p.orden= a.secuencia
										where  a.anulada<> '1' and a.formapago='Directo'  and a.fecha >'2020-12-07' and a.factura <> ''
										and date_format(a.fecha,'%Y-%m-%d ') and t.StatusRecepcion not in ('RECIBIDO','NO-GESTION')";
								//echo $sql1;
								$result1 = mysqli_query($con, $sql1);
								$conrow = $result1->num_rows;
	 ?>

</div>
	 <div class=\"table-responsive-xs\">
			<form name = "formfactura2" action="creditosdirectos0.php" method="POST" width="100%">
			<table align ="center" >
				<tr>
					<th colspan="15"><right> Creditos Directos Por Recibir Documentos (<?php echo $conrow ?>) </right></th>
				</tr>
				<tr>
					<th id= "fila4"> SId </th>
          <th id= "fila4"> Ciudad </th>
					<th id= "fila4"> Factura </th>
					<th id= "fila4"> Ruc  </th>
					<th id= "fila4"> Cliente </th>
					<th id= "fila4"> Fecha Factura </th>
					<th id= "fila4"> Valor Credito  </th>
					<th id= "fila4"> Completo </th>
					<th id= "fila4"> EstadoFact  </th>
					<th id= "fila4"> Transporte  </th>
					<th id= "fila4"> Guia #    </th>
					<th id= "fila4"> Almacen  </th>
          <th id= "fila4"> Estado  </th>
          <th id= "fila4"> EstadoDcto  </th>
					<th id= "fila4"> Recibidos  </th>
				</tr>
		<?php


								//echo "Contad". $conrow;
								while ( $row1 = mysqli_fetch_array($result1))
									{
                    $transaccion = $row1['transaccion'];
                    $secu = $row1['factura'];
										$ciudad = $row1['ciudad'];
										$formapago = $row1['formapago'];
										$completo = $row1['completo'];
										$SId = $row1['SId'];
										$Ruc = $row1['cedula'];
										$Cliente = $row1['nombres'];
										$fechaf = $row1['fechaf'];
										$bodega = $row1['bodega'];
										$fechacompleto = $row1['fechac'];
										$retiro = $row1['retiro'];
										$despachofinal = $row1['despachofinal'];
                    $StatusRecdcto = $row1['StatusRecdcto'];
                    $StatusRecepcion = $row1['StatusRecepcion'];
										$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
										$result0 = $pdo0->prepare("select f.* , totalf =(f.total + f.financiamiento), fl.*,     fl.estado as estado
																  from ven_facturas  f
																  inner join facturaslistas fl on fl.factura= f.id
																  where f.secuencia=:secuencia" );
										$result0->bindParam(':secuencia',$secu,PDO::PARAM_STR);
										$result0->execute();
										$count = $result0->rowcount();
										if ($count<0 ) {
											while ($row0 = $result0->fetch(PDO::FETCH_ASSOC))
												{
													$estado = $row0['estado'];
													$guia = $row0['GUIA'];
													$Total = number_format($row0['totalf'],2);
												}
										}
										if ($despachofinal =='Urbano' or $despachofinal=='Tramaco' or $despachofianl=='Servientrega')
											{
												$guianum = $guia ;
											}

		?>
								<tr>
									<td id= "fila4"  > <?php echo $SId ?>  </td>
                  <td id= "fila4"  > <?php echo $ciudad ?>  </td>
									<td id= "fila4"  > <a href="estadodocumentos.php?transaccion=<?php echo $transaccion ?>"><?php echo $secu ?>  </td>
									<td id= "fila4" align= "center"> <a> <?php echo $Ruc?></a></td>
									<td id= "fila4"  > <?php echo $Cliente ?>  </td>
									<td id= "fila4"  > <?php echo $fechaf ?>  </td>
									<td id= "filax"  > <?php echo $Total ?>  </td>
									<td id= "fila4"  > <?php echo $fechacompleto ?>  </td>
									<td id= "fila4"  > <?php echo $estado ?>  </td>
									<td id= "fila4"  > <?php echo $despachofinal ?>  </td>
									<td id= "fila4"  > <?php echo $guianum ?>   </td>
                  <td id= "fila4"  > <?php echo $retiro ?>   </td>
                  <td id= "fila4"  > <?php echo $StatusRecepcion ?>  </td>
                  <td id= "fila4"  > <?php echo $StatusRecdcto ?>  </td>
									<td id= "box"> <input name="checkbox[]" type="checkbox" value ="<?php echo $secu ?>" > </td>

								</tr>
			<?php
								$estado = ' ';
								$guianum= '';
								}

			?>
		</table>
		<br>
		<input   id="submit" value=" Recibir Documentos Facturas Marcadas " type="submit">
		</form>
		</div>
 </div>

<?php
			$usuario= $usuario1;
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
