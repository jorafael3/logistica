
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, height=device-height">
</head>
<script type="text/javascript">//alert("sdfsd");</script>
<body>
<?php
require_once("conexion.php");
//$db_handle = new DBController();

	$query ="SELECT * FROM covidciudades WHERE idgrupo = '" . $_POST["idgrupo"] . "' order by sucursalid";
	$results = mysqli_query($con, $query);
	
	
?>
	<option value="">Seleccione bodega</option>
<?php
	while($rs=$results->fetch_assoc()) {
?>
	<option value="<?php echo $rs["almacen"]; ?>"><?php echo $rs["sucursalid"]."-".$rs["almacen"]; ?></option>
<?php

}
?>
</body>
</html>