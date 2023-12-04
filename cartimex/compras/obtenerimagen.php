<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
</head>
<body>


<?php

session_start();	
if (isset($_SESSION['loggedin']))
	{
		$foto= $_GET['foto'];
		//echo $foto; 
		//echo $ruta= $_SERVER["DOCUMENT_ROOT"]."/cdn/importaciones/".$foto.".jpg"; 
		//echo "<img src=".$_SERVER["DOCUMENT_ROOT"]."/cdn/importaciones/".$foto.".jpg"">	";
		//$imagedata = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/cdn/importaciones/".$foto);
		//echo base64_encode($imagedata);
?>
		<img src="<?php echo "http://10.5.2.62/cdn/importaciones/".$foto.".jpg"?>" width="400" height="650">
<?php
		 
	}	
?>


</body>
</html>    