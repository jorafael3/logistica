
<meta name="viewport" content="width=device-width, height=device-height">
<html>
<head>
<title>Sistema SISCO</title>
<link rel="stylesheet" type="text/css" href="css/tablas.css">

</head>

<?php 


include("conexion.php");
session_start();
$usuario = $_SESSION["usuario"];
date_default_timezone_set('America/Guayaquil');

$year = date("Y");
$fecha = date("y-m-d", time());
$checkbox = $_POST['checkbox'] ;

$fin = count($checkbox);

$i=0;


for ($i = 0; $i <= $fin-1; $i++) {
$temp = substr($checkbox[$i],1,strlen($checkbox[$i])-2);
    //echo "Anulada: $temp <br>";
    $sql1 = "
UPDATE `covidsales` SET   `estado`='Despachado', `cierreusuario`='$usuario', `cierrefecha`='$fecha' where secuencia = '$temp' " ;
//echo $sql."<br>";
mysqli_query($con, $sql1); 

//debo buscar por numero de secuencia a quien enviar el correo
$sql = "SELECT * FROM covidsales where secuencia = $temp ";
//echo $sql."<br>";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
$nombre = $row['nombres'];
$factura = $row['factura'];
$celular = $row['celular'];
$mail = $row['mail'];
$despacho = $row['despacho'];
$despachofinal = $row['despachofinal'];
//echo "cel:".$celular;
// una vez que meti el registro en la DB debo enviar los mails
/// Parte del envio del mail:

$telefono = "593".substr($celular,-9);

if ($despachofinal =='Urbano' )
{
//$message  = "Estimado(a)".$nombre."%0a";
$message = "Su compra va en camino.%0a ";
$message .= "Urbano, No:".$despacho."%0a";
$message .= "Rastreo en : https://cutt.ly/2yWPW2V%0a";
$message .= "Gracias!%0a";  
$message .= "COMPUTRON";
echo "<br><br>Enviando SMS por orden #".$temp.":". "<iframe src=\"https://www.easysendsms.com/sms/bulksms-api/bulksms-api?username=fernfmor2019&password=esm50742&from=Test&to=$telefono&text=$message&type=0\" width=\"500\" height=\"40\"></iframe>";
}

if ($despachofinal =='Servientrega' )
{
//$message  = "Estimado(a)".$nombre."%0a";
$message = "Su compra va en camino.%0a ";
$message .= "Servientrega, No:".$despacho."%0a";
$message .= "Rastreo en : https://cutt.ly/VyWPnf8%0a";
$message .= "Gracias!%0a";
$message .= "COMPUTRON";
echo "<br><br>Enviando SMS por orden #".$temp.":". "<iframe src=\"https://www.easysendsms.com/sms/bulksms-api/bulksms-api?username=fernfmor2019&password=esm50742&from=Test&to=$telefono&text=$message&type=0\" width=\"500\" height=\"40\"></iframe>";
}

if ($despachofinal =='Tramaco' )
{
//$message  = "Estimado(a)".$nombre."%0a";
$message = "Su compra va en camino.%0a ";
$message .= "Tramaco, No:".$despacho."%0a";
$message .= "Rastreo en : https://cutt.ly/jugoIvi%0a";
$message .= "Gracias!%0a";
$message .= "COMPUTRON";
echo "<br><br>Enviando SMS por orden #".$temp.":". "<iframe src=\"https://www.easysendsms.com/sms/bulksms-api/bulksms-api?username=fernfmor2019&password=esm50742&from=Test&to=$telefono&text=$message&type=0\" width=\"500\" height=\"40\"></iframe>";
}



    





   
} // del for
mysqli_close($con);


header("Refresh:5; url=index1.php");
die();

 
?>