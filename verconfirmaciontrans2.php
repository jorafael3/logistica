<!DOCTYPE html>
<html>
<head>
<title> SGL </title>

</head>
<script type="text/javascript">

</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
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
					$transa= $_GET['trans'];
					$bodega = $_SESSION['bodega'];
					 	
					if ($base=='CARTIMEX'){
							require 'headcarti.php';  
					}
					else{
							require 'headcompu.php';
					}	
					$usuario1= $usuario; 
?>
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center> Datos de Transferencia </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a> </div>
	</div>
<hr>	
<?php
					include("conexion.php");
					$sql1 = "SELECT * from covidtransferencias t
							 inner join covidsales s on s.secuencia= t.transaccion
							 where t.transaccion = '$transa'";
					$result1 = mysqli_query($con, $sql1);
					$conrow = $result1->num_rows;
					if ($conrow >0)
						{
							$row1 = mysqli_fetch_array($result1);
							$ConfirmadoPor = $row1['ConfirmadoPor'];
							If ($ConfirmadoPor == '') 
								{
									$transa="NO Verificada";
									$Cedula= ''; 
									$cliente= ''; 
									$Banco = ''; 
									$FechaAcred = ''; 
									$referecia = ''; 
									$ConfirmadoPor =''; 
									$FechaConfir = '';
								}
							else{	
									$Cedula = $row1['cedula'];
									$cliente = $row1['nombres'];
									$Banco = $row1['BancoAcr'];
									$FechaAcred = $row1['fechaacredita'];
									$referecia= $row1['numeroconfir'];
									$FechaConfir = $row1['fechaconfir'];
									$doc = $row1['doc1'];
									require('conexion_mssql.php');	
									$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
									//new PDO($dsn, $sql_user, $sql_pwd);
									//Select Query
									$result1 = $pdo1->prepare("SELECT  Nombre FROM ban_bancos WITH (NOLOCK) where id = '$Banco'" );		 
									//Executes the query
									$result1->execute();
									while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
									{ $BancoNom= $row1['Nombre'];}
								}
						}
					else
						{
							$transa="NO Verificada";
							$Cedula= ''; 
							$cliente= ''; 
							$Banco = ''; 
							$FechaAcred = ''; 
							$referecia = ''; 
							$ConfirmadoPor =''; 
							$FechaConfir = ''; 
						}	
					
?>		

<div id="cuerpo2" align= "center">
<div>
		<table align= "center">
    	<tr >
    		<td id= "label">Orden Sisco: </td> 
    		<td id= "box"> <input  readonly name="id" type="text" id="id" size = "32" value= "<?php echo $transa ?>"> </td>
    	</tr>
		<tr>
    		<td id= "label">Ruc/Cédula: </td> 
    		<td id= "box" > <input  readonly name="usermo" type="text" id="usermo" size = "32" value= "<?php echo $Cedula ?>"> </td>
    	</tr>
		<tr>
    		<td id= "label">Cliente: </td> 
    		<td id= "box" > <input  readonly name="usermo" type="text" id="usermo" size = "32" value= "<?php echo $cliente ?>"> </td>
    	</tr>
    	<tr>
    		<td id= "label">Banco: </td> 
    		<td id= "box" > <input  readonly name="usermo" type="text" id="usermo" size = "32" value= "<?php echo $BancoNom ?>"> </td>
    	</tr>
    	<tr>
    		<td id= "label">Fecha Acreditacion: </td> 
    		<td id= "box"> <input  readonly name="nombre" type="text" id="nombre" size = "32" value= "<?php echo $FechaAcred ?>"> </td>
    	</tr>
		<tr>
    		<td id= "label">Referencia: </td> 
    		<td id= "box"> <input  readonly name="clave" type="text" id="clave" size = "32" value= "<?php echo $referecia ?>"> </td>
    	</tr>
    	<tr>
    		<td id= "label">Confirmado Por : </td> 
    		<td id= "box"> <input  name="acc" type="text" id="acc" size = "32" value= "<?php echo $ConfirmadoPor ?>"> </td>
    	</tr>	
		<tr>
    		<td id= "label">Fecha Confirmacion: </td> 
    		<td id= "box"> <input  name="dpto" type="text" id="dpto" size = "32" value= "<?php echo $FechaConfir ?>"> </td>
    	</tr>	
		<tr>
		  <td id="label"></td>
		  <a href="transferenciasbancariasvta.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
		 <tr>
    		
    		<td id= "box" colspan= "2 "> <a><img src="http://app.compu-tron.net/siscodocumentos/<?php echo $doc ?>" width="550" height="250" ></a> </td>
    	</tr>	 
      </table>
</div>	
</div> 
<?php		
				$_SESSION['usuario']=$usuario1;
				$_SESSION['base']= $base ;
				$_SESSION['acceso']=$acceso;
				$_SESSION['bodega']=$bodega;
				}
				else
				{
					header("location: index.html");
				}
?>
		
</body>	