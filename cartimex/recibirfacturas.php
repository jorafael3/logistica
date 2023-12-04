<html>
<script type="text/javascript">
function setfocus(){
    document.getElementById("guia").focus();
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
			$acceso = $_SESSION['acceso'];
			$base = $_SESSION['base'];
			$id= $_SESSION["id"];
			$secu= $_SESSION["secu"];
			$usuario1= $usuario;

			//echo $secu ;
			if ($base=='CARTIMEX'){
					require '../headcarti.php';
			}
			else{
					require '../headcompu.php';
			}



?>
</div>
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>   Recibir Factura para Entregar Mostrador</center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a>  </div>
	</div>
<hr>
<?php

			$_SESSION['base']= $base;
			require('../conexion_mssql.php');

			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
      /*$result = $pdo->prepare('LOG_VERIFICAR_FACTURA2 @facturaid=:secu');*/
			 $result = $pdo->prepare("select Secuencia,f.ID, Detalle, f.fecha , e.nombre from ven_facturas f with(nolock)
									 inner join facturaslistas fl with(nolock) on fl.factura = f.id
                   inner join EMP_EMPLEADOS e with (nolock) on e.id= f.VendedorID
									 where fl.estadoenvio = 'EN CAMINO' and f.secuencia=:secu" );
			$result->bindParam(':secu',$secu,PDO::PARAM_STR);
			//Executes the query
			$result->execute();
			$count = $result->rowcount();
			if ($count < 0 )
				{
          while ($row = $result->fetch(PDO::FETCH_ASSOC))
						  {
						  $id = $row['ID'];
						  $detalle = $row['Detalle'];
						  $secuencia = $row['Secuencia'];
              $fecha = $row['fecha'];
              $vendedor = $row['nombre'];
						  }
        $pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $result1 = $pdo1->prepare("select Código, Producto= Nombre, cantidad  from VEN_FACTURAS_DT dt
                                      inner join INV_PRODUCTOS p with (nolock) on p.id = dt.ProductoID
                                      where FacturaID =:secu" );
        $result1->bindParam(':secu',$id,PDO::PARAM_STR);
        $result1->execute();
        $arreglo  = array();
        $x=0;
        while ($row1 = $result1->fetch(PDO::FETCH_ASSOC))
          {
            $arreglo[$x][1]=$row1['Código'];
            $arreglo[$x][2]=$row1['Producto'];
            $arreglo[$x][3]= number_format($row1['cantidad'],0);
            $x++;
          }
				?>

<div id="cuerpo2" align= "center">
<div align= "left">
	<form name = "formusuario" action="recibirfacturas1.php" method="POST" >
		<table>
    	<tr><td id= "box">
            <input name="id" type="hidden" id="id" size = "30" value= "<?php echo $id ?>">
          </td>
			    <td id= "box">
            <input name="transporte" type="hidden" id="transporte" size = "30" value= "<?php echo $transporte ?>">
          </td>
    	</tr>
      <tr>
          <td id= "label2"><strong>Fecha: </strong></td><td><a> <?php echo $fecha ?> </a></td>
      </tr>
		  <tr><td id= "label2"><strong>Factura #: </strong></td><td><a> <?php echo $secuencia ?> </a></td>
			    <td id= "box"> <input name="secu" type="hidden" id="secu" size = "30" value= "<?php echo $secuencia ?>"> </td>
    	</tr>
      <tr>
          <td id= "label2"><strong>Cliente: </strong></td><td><a> <?php echo $detalle ?> </a></td>
    	</tr>
    	<tr>
          <td id= "label2"><strong>Vendedor: </strong></td><td><a> <?php echo $vendedor ?> </a></td>
    	</tr>
      </table>
      <hr>
      <table id= "listado2" align ="center" >
				<tr>
					<th> CODIGO </th>
					<th> ARTICULO </th>
					<th> CANTIDAD </th>
				</tr>
        <?php
     			$count = count($arreglo);
     			$y=0;
     			while ( $y <= $count-1 )
     				{
     		?>
     		<tr>
     			<td id= "label4" > <?php echo $arreglo[$y][1] ?> </td>
     			<td id= "label4" > <?php echo $arreglo[$y][2] ?> </td>
     			<td id= "label5" > <?php echo $arreglo[$y][3] ?> </td>
     		</tr>
     		<?php
     				$y=$y+1;
     				}
         ?>
      </table>
      <br>
      <table align= "center" >
        <tr>
    		  <td id="label"></td>
    		  <td id="label" >   Recibir
          	  <input   name="submit" id="submit" value="Grabar" src="..\assets\img\save.png" type="image" >
    		  </td>
          <td>	<a href="recibirfacturas2.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a> </td>
    		 </tr>
 
      </table>
    </form>


</div>
</div>
 <?php
		$_SESSION['base']= $base;
		$_SESSION['usuario']=$usuario1;
		$_SESSION['acceso']=$acceso;
		$_SESSION['id']= $id;
		$_SESSION['factura']= $secuencia;
		}
		else
		{ ?>
			<div id="cuerpo2" >
					<div id = "izq" >  </div>
					<div id = "centro" > <a class="titulo"> <center>  No se puede Entregar esta Factura   </center> </a></div>
			</div>

		<?php
			header("Refresh: 1 recibirfacturas.php");
		}
		}
		else
		{
			header("location: index.html");
		}
 ?>
</div>
</body>
