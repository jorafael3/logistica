<?php
ini_set('display_errors', 1);
if(isset($_POST['sigRawData']))
{
	$base64_string = $_POST['sigRawData'];
	$file_name ="archivo2.jpg";
	echo $base64_string; 
	die(); 
	$output_file = $_SERVER['DOCUMENT_ROOT']."/logistica/cartimex/firmas/".$file_name;
	//$output_file = 'C:\Users\PRISCILA\Documentos'."/".$file_name;
	$ruta_file = base64_to_jpeg($base64_string, $output_file);
	 
}
  
function base64_to_jpeg($base64_string, $output_file) {
    	// abrimos el archivo para escribir, si no existe creamos
    	$ifp = fopen( $output_file, 'w+' ); 
		$data = $base64_string;
    	//podríamos agregar validación asegurando que $data >1
    	fwrite( $ifp, base64_decode( $data ) );
   	 //cerramos el archivo resultado
    	fclose( $ifp ); 
   	 //mostramos la ruta con el nombre_archivo
    	echo $output_file;
}
 
?>