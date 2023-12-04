<meta name="viewport" content="width=device-width, height=device-height">
<html>
<head>
<title>Sistema SISCO</title>
</head>
<?php



session_start();

$code=$_GET['sec'];

echo "<br><br><br><center><table border=1 cellpadding=0 cellspacing=0  height=0>";
echo "  <th colspan = 2>";

echo "<strong><center>Imagen: </strong> ".$code;
echo "  </center></th>";

echo $code ;
die(); 
if (file_exists("\\10.5.1.233/var/www/html/siscocredito/".$code))
  {
  //echo "<center><tr><td><img src=\"http://app.compu-tron.net/siscocredito/".$code."\" /></td>";
  echo "<embed src=\"http://app.compu-tron.net/siscocredito/".$code."#toolbar=0&navpanes=0&scrollbar=0\" type=\"application/pdf\" width=\"100%\" height=\"800px\" /></td>";

  }
  else
  {die("<br><br><br><br>Pagaré seleccionado no existe1!");}


//echo "<iframe src=\"http://app.compu-tron.net/siscopdf/".$code.\" width=\"100%\" style=\"height:100%\"></iframe>";

 

echo "  </td>";

echo "</table>";


?>
<form><br>
<!-- 
<input type="button" value="IMPRIMIR" onClick="window.print()">
</form>
 -->