<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("ref").focus();
}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">
<body onload ="setfocus()"> 
<div id= "header" align= "center">
<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$orden= $_GET["orden"];
			
			
			if ($base=='CARTIMEX'){
					require 'headcarti.php';  
			}
			else{
					require 'headcompu.php';
			}
			
			//echo " 1 esto envio".$base.$usuario.$acceso.$orden;
			//die();
?>		
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
					<div id = "izq" ></div>
					<div id = "centro" > <a class="titulo"> <center>   Datos de Transferencia   </center> </a></div>
					<div id = "derecha" > <a href="menu.php"><img src="assets\img\home.png"></a></div>
	</div> 
	<hr>
<?php 
			//echo "2esto envio".$base.$usuario.$acceso;
			$usuario1= $usuario;
			include("conexion.php");
			$sql1 = "SELECT a.cedula as cedula , a.nombres as cliente  FROM covidsales a
					left outer join covidtransferencias t on t.transaccion= a.secuencia
					where  a.secuencia = '$orden' ";
			$result1 = mysqli_query($con, $sql1); 
			while ( $row1 = mysqli_fetch_array($result1))
				{	
					$cedula = $row1['cedula'];
					$cliente = $row1['cliente'];
				}		
?>	

<div id="cuerpo2" align= "center">
<div>
	<form name = "formusuario" action="confirmartransferbancaria2.php" method="POST" >
		<table align= "center">
    	<tr >
    		<td id= "label">Ced/Ruc: </td> 
    		<td id= "box"> <input  readonly name="ruc" type="text" id="ruc" size = "32" value= "<?php echo $cedula ?>"> </td>
    	</tr>
    	<tr>
    		<td id= "label">Cliente: </td> 
    		<td id= "box" > <input  readonly name="cliente" type="text" id="cliente" size = "32" value= "<?php echo $cliente ?>"> </td>
			<td id= "box"> <input  hidden  name="orden" type="text" id="orden" size = "32" value= "<?php echo $orden ?>"> </td>
    	</tr>
		<tr>
			<td id="label" > F.Acreditacion:  </td>
			<td><input type="date" name="desde" step="1" min="2020/01/01" max="2050/12/31" value="<?php echo date("Y-m-d");?>"></td>
		</tr>
		<tr>
			<td id="label" > Banco Acreditado:  </td>
			<td>
			<select name="banco" id = "banco">
<?php
		require('conexion_mssql.php');	
		$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		//new PDO($dsn, $sql_user, $sql_pwd);
		//Select Query
		$result2 = $pdo2->prepare("SELECT ID, NombreB= case when  SUBSTRING (Nombre,1,5) ='BANCO' THEN  SUBSTRING (Nombre,7,80) ELSE NOMBRE END 
								   FROM ban_bancos WITH (NOLOCK) 
								   where id in ('0000000023','0000000028','0000000025','0000000008','0000000003')  order by 1" );		 
		//Executes the query
		$result2->execute();
		while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) 
			{
?>			
				<option value="<?php  echo $row2['ID']; ?>"><?php echo $row2['NombreB']; ?></option>
				<?php
			}
?>	
			</select></td>
		</tr>	
		<tr>
    		<td id= "label">Referencia: </td> 
    		<td id= "box"> <input  name="ref" type="text" id="ref" size = "32" value= ""> </td>
    	</tr>	
		<tr>
		  <td id="label"></td>
		  <td id="label" >   Confirmar 
      	  <input   name="submit" id="submit" value="Grabar" src="assets\img\save.png" type="image" > 
		  <a href="transferenciasbancarias.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar    	</a>
		  </td>
		 </tr> 
      </table>
    </form> 
	
	
</div>	
</div> 
 <?php 
		$_SESSION['base']= $base;
		$_SESSION['usuario']=$usuario1;
		$_SESSION['acceso']=$acceso;
		
		//echo "esto envio".$base.$usuario1.$acceso; 
		}
		else
		{
			header("location: index.html");
		}
 ?>
</div>	 
</body>