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
					if ($base=='CARTIMEX'){
							require 'headcarti.php';  
					}
					else{
							require 'headcompu.php';
					}
					date_default_timezone_set('America/Guayaquil');
					$fecha = date("Y-m-d", time());
					$hora = date("H:i:s", time());
					$fh = $fecha . " " . $hora;
					$usuario1= $usuario; 
					//echo "BOdega". $bodega; 
?>	
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>   Transferencias Bancarias </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a>  </div>
			 	
	</div> 
<hr>
	<div>
				<form name = "formfactura" action="" method="POST" width="75%">
					<table align ="center" >
					 <tr>
					  <td id="etiqueta"> <a href="javascript:window.location.href=window.location.href" style="text-decoration:none"> <img src="assets\img\refresh.png"></img></a></td>
					 </tr> 
				  </table>
				</form> 
		</div>
<div id= "cuerpo2" align= "center" >
	 <div class=\"table-responsive-xs\">
			<form name = "formfactura2" action="ingguiasfacturas12120.php" method="POST" width="75%">
			<table align ="center" >
				<tr>
					<th colspan="12">Confirmar Transferencias Bancarias </th> 
				</tr> 
				<tr> 
					<th id= "fila4"> SId </th>
					<th id= "fila4"> Factura </th>
					<th id= "fila4"> Ruc  </th>
					<th id= "fila4"> Cliente </th>
					<th id= "fila4"> Fecha Factura </th>
					<th id= "fila4"> Fecha Trans </th>
					<th id= "fila4"> Estado  </th>
					<th id= "fila4"> V.Factura  </th>
					<th id= "fila4"> Saldo  </th>
					<th id= "fila4"> Img</th>
					<th id= "fila4"> Confirmar</th>
				</tr>
		<?php
								include('conexion_mssql.php'); 
								include("conexion.php");
								$sql1 = "SELECT a.* , date_format(t.fecha,'%d/%m/%Y %H:%i ') as fechat , t.doc1 as doc  FROM covidsales a
										left outer join covidtransferencias t on t.transaccion= a.secuencia
										where  a.anulada<> '1' and a.formapago='Transferencia' and (a.estado= 'Verif. pago' or  a.estado= 'Facturado' ) and a.fecha >'2020-11-13' 
										and (t.ConfirmadoPor is null or t.ConfirmadoPor='')";
								$result1 = mysqli_query($con, $sql1);
								$conrow = $result1->num_rows; 
								//echo "Contad". $conrow; 
								while ( $row1 = mysqli_fetch_array($result1))
									{	
										$secu = $row1['factura'];
										$estado = $row1['estado'];
										$formapago = $row1['formapago'];
										$fechat = $row1['fechat'];
										$doc = $row1['doc'];
										$orden = $row1['secuencia'];
										
										$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
										$result0 = $pdo0->prepare("LOG_BUSQUEDA_FACTURA_TRANSBANCA @secuencia=:secuencia" );		 
										$result0->bindParam(':secuencia',$secu,PDO::PARAM_STR);
										$result0->execute();
										while ($row0 = $result0->fetch(PDO::FETCH_ASSOC)) 
											{
												$Id =$row0['SId'];
												$Ruc =$row0['Ruc'];
												$Cliente =$row0['Nombre']; 
												$Fecha = $row0['fecha2'];
												$valorf = number_format($row0['valorf'],2); 
												$saldo = number_format($row0['saldo'],2);
											}
								if (($saldo>0) ){			
		?>						
								<tr>
									<td id= "fila4"  > <?php echo $Id ?>  </td>
									<td id= "fila4"  > <?php echo $secu ?>  </td>
									<td id= "fila4" align= "center"> <a> <?php echo $Ruc?></a></td> 
									<td id= "fila4"  > <?php echo $Cliente ?>  </td>
									<td id= "fila4"  > <?php echo $Fecha ?>  </td>
									<td id= "fila4"  > <?php echo $fechat ?>  </td>
									<td id= "fila4"  > <?php echo $estado ?>  </td>
									<td id= "fila4"  > <?php echo $valorf ?>  </td>
									<td id= "filax"  > <?php echo $saldo ?>  </td>
									<td id= "fila4"  > <a href ="http://app.compu-tron.net/siscodocumentos/<?php echo $doc ?>" target=_blank" > <?php echo $doc   ?> </a> </td>
									<?php
									 if (($doc==' ') or ($doc == null)) { ?>
									<td id= "fila4"  > <a href = "#" > </a></td>
									<?php
									 }else{?>
									<td id= "filax"  > <a href = "confirmartransferbancaria.php?orden=<?php echo $orden ?>"> <?php echo $orden ?> </a></td>
									 <?php }
									 $orden= " "; 
									 ?>
									
								</tr>	
			<?php				
								}
								}
			?>
		</table>
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