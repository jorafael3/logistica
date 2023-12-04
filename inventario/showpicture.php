<meta name="viewport" content="width=device-width, height=device-height">

<html>
<p>
<link rel="stylesheet" type="text/css" href="css/tablas.css">
<body>

<?php
session_start();
$usuario = $_SESSION['usuario'];
$base = $_SESSION['base'];
$acceso	=$_SESSION['acceso'];
$codigo= $_SESSION["codigo"];
$code= $_GET["code"];
$nomfoto = $code."%20gr.jpg";
//echo $base. $code, $codigo .$usuario .$acceso;
 
?>

<br>
<div align="center"> CODIGO:     <?php echo $code ?> 
<a href="inventario1.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a>
</div> 
<div align="center" class=\"table-responsive-xs\">
<table border="0">
  <tr>
   	<td><img src="http://img.cartimex.com/v2/upload/<?php echo $nomfoto?>" style="max-width:100%;width:auto;height:auto; "/></td>
	
  </tr>
</table>
</div>

<br>
<br>

</body>
</p>
<?php
			$_SESSION["codigo"]=$codigo;	
			$_SESSION['usuario']=$usuario;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
?>
</html>
