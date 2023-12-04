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
	
<div id= "cuerpo2" align= "center" >
<div>
	<form name = "formproducto" action="transferenciasbancariasvta.php" method="POST" width="75%">
		<table align ="center" >
    	<tr>
    		
			<td id="label" > Desde:  
			<input type="date" name="desde" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo date("Y-m-d");?>"></td>
			<td id="label" > Hasta:  
			<input type="date" name="hasta" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo date("Y-m-d");?>"></td>
			<td> </td>
    	</tr>
		<tr>
			
		  <td id="etiqueta" > Consultar
      	  <input   name="submit" id="submit" value="Consultar" src="assets\img\lupa.png" type="image"> </td>
		  <td id="etiqueta" >  <a href="menu.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a></td>
		 </tr> 
      </table>
    </form> 
	
</div>	
	 <div class=\"table-responsive-xs\">
			<form name = "formfactura2" action="ingguiasfacturas12120.php" method="POST" width="75%">
			<table align ="center" >
				<tr>
					<th colspan="11"> Transferencias Bancarias </th> 
				</tr> 
				<tr> 
					<th id= "fila4"> Factura </th>
					<th id= "fila4"> Ruc  </th>
					<th id= "fila4"> Cliente </th>
					<th id= "fila4"> Fecha Factura </th>
					<th id= "fila4"> Fecha Trans </th>
					<th id= "fila4"> Valor  </th>
					<th id= "fila4"> F.Confirma  </th>
					<th id= "fila4"> ConfirmadoPor  </th>
					<th id= "fila4"> Img</th>
				</tr>
		<?php
								include('conexion_mssql.php'); 
								include("conexion.php");
								$sql1 = "SELECT a.* , date_format(t.fechaconfir,'%d/%m/%Y %H:%i ') as fechac , 
										date_format(t.fecha,'%d/%m/%Y %H:%i ') as fechat , t.transaccion as transa , date_format(a.fecha,'%d/%m/%Y ') as fechaf ,
										t.doc1 as doc , t.* 
										FROM covidsales a
										left outer join covidtransferencias t on t.transaccion= a.secuencia
										where  a.anulada<> '1' and a.formapago='Transferencia'  and a.fecha >'2020-11-13' and a.factura <> ''
										 and date_format(a.fecha,'%Y-%m-%d ') between '$desde' and '$hasta' ";
								//echo $sql1; 
								$result1 = mysqli_query($con, $sql1);
								$conrow = $result1->num_rows; 
								//echo "Contad". $conrow; 
								while ( $row1 = mysqli_fetch_array($result1))
									{	
										$secu = $row1['factura'];
										//$estado = $row1['estado'];
										$formapago = $row1['formapago'];
										$fechat = $row1['fechat'];
										$doc = $row1['doc'];
										$Ruc = $row1['cedula'];
										$Cliente = $row1['nombres'];
										$fechaf = $row1['fechaf'];
										$transa = $row1['transa'];
										$fechaconfir = $row1['fechac'];
										$confirmadopor = $row1['ConfirmadoPor'];
										$Total = number_format($row1['valortotal'],2);
		?>						
								<tr>
									
									<td id= "fila4"  > <?php echo $secu ?>  </td>
									<td id= "fila4" align= "center"> <a> <?php echo $Ruc?></a></td> 
									<td id= "fila4"  > <?php echo $Cliente ?>  </td>
									<td id= "fila4"  > <?php echo $fechaf ?>  </td>
									<td id= "fila4"  > <?php echo $fechat ?>  </td>
									<td id= "filax"  > <?php echo $Total ?>  </td>
									<td id= "fila4"  > <?php echo $fechaconfir ?>  </td>
									
									<td id= "fila4"  > <a href = "verconfirmaciontrans2.php?trans=<?php echo $transa ?>" target=_blank> <?php echo $confirmadopor ?>  </td>
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