<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../css/tablas.css">
<body> 
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
					$bodega	=$_SESSION['bodega'];
					$nomsuc = $_SESSION['nomsuc'];
					 
					date_default_timezone_set('America/Guayaquil');
					$year = date("Y");
					//probar si se graba bien el dia y el mes 
					
					$fecha = date("Y-d-m", time());
					$hora = date("H:i:s", time());
					$fh = $fecha . " " . $hora;
					$IDLiq = $_POST['LiqID'];
					$Nota1 = $_POST['Nota1'];
					if ($base=='CARTIMEX'){require '../../headcarti.php';  }else{require '../../headcompu.php';	$_SESSION['base']= $base; }
					$_SESSION['usuario']= $usuario; 
					//echo  $IDLiq ;
					function base64_to_jpeg($base64_string, $output_file) 
					{
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
				//Imagen 1
					$check = getimagesize($_FILES["foto3"]["tmp_name"]);
					if($check !== false){
						$base64_string = base64_encode(file_get_contents($_FILES['foto3']['tmp_name']));
						$file_name =ltrim(rtrim($IDLiq))."F3.jpg";
						$output_file = $_SERVER['DOCUMENT_ROOT']."/cdn/importaciones/".$file_name;
						$ruta3= $output_file; 
						$ruta_file = base64_to_jpeg($base64_string, $output_file);
					}
					
					require('../../conexion_mssqlxtratech.php');
					$usuario = $_SESSION['usuario'];
					$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result0 = $pdo0->prepare("UPDATE IMP_LIQUIDACION SET IngSeries = 1 ,FFinRecep =:FFinRecep,RecibidoPor=:RecibidoPor  from IMP_LIQUIDACION where  ID=:IDLiq " );	
					$result0->bindParam(':IDLiq',$IDLiq,PDO::PARAM_STR);							
					$result0->bindParam(':FFinRecep',$fh,PDO::PARAM_STR);
					$result0->bindParam(':RecibidoPor',$usuario,PDO::PARAM_STR);
					$result0->execute();
					
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare("UPDATE IMP_RECEPCION_LIQ_CONTENEDOR SET Foto3=:Foto3 , Nota1 =:Nota1 from IMP_RECEPCION_LIQ_CONTENEDOR where  LiquidacionID=:IDLiq " );	
					$result1->bindParam(':IDLiq',$IDLiq,PDO::PARAM_STR);	
					$result1->bindParam(':Foto3',$ruta3,PDO::PARAM_STR);					
					$result1->bindParam(':Nota1',$Nota1,PDO::PARAM_STR);
					$result1->execute();
					
						 	
					$_SESSION['usuario']=$usuario;
					$_SESSION['base']= $base ;
					$_SESSION['acceso']=$acceso;
					$_SESSION['IDLiq']=$IDLiq;
					
					header("location: mailnovedadesimportacionxtratech.php");
			}
		else
			{
				header("location: index.html");
			}			
?>		 		
			</table>
			<br>
			</form> 
	</div>
	<br>	
</div>
</div>		
</body>	
</html>	