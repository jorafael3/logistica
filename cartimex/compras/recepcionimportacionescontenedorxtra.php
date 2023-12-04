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
//ini_set('display_errors','1');
session_start();	
if (isset($_SESSION['loggedin']))
	{	
		$usuario = $_SESSION['usuario'];
		$base = $_SESSION['base'];
		$acceso	=$_SESSION['acceso'];
		$bodega	=$_SESSION['bodega'];
		$nomsuc = $_SESSION['nomsuc'];
				
		$LiqID = $_POST['LiqID'];
		$Contenedor = $_POST['Contenedor'];
		$Sello = $_POST['Sello'];
		$Sello2 = $_POST['Sello2'];
		$Tipo = $_POST['Tipo'];
		$Placa = $_POST['Placa'];
		$Estiba = $_POST['Estiba'];
		$Nota = $_POST['Nota'];
		$Candado = $_POST['Candado'];
		//if ($base=='CARTIMEX'){require '../../headcarti.php';  }else{require '../../headcompu.php';	$_SESSION['base']= $base; }
		$_SESSION['usuario']= $usuario; 
		
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
			$check = getimagesize($_FILES["foto1"]["tmp_name"]);
			if($check !== false){
				$base64_string = base64_encode(file_get_contents($_FILES['foto1']['tmp_name']));
				$file_name =ltrim(rtrim($LiqID))."F1.jpg";
				$output_file = $_SERVER['DOCUMENT_ROOT']."/cdn/importaciones/".$file_name;
				$ruta1= $output_file; 
				$ruta_file = base64_to_jpeg($base64_string, $output_file);
			}
		//Imagen 2	
			$check2 = getimagesize($_FILES["foto2"]["tmp_name"]);
			if($check2 !== false){
				$base64_string = base64_encode(file_get_contents($_FILES['foto2']['tmp_name']));
				$file_name =ltrim(rtrim($LiqID))."F2.jpg";
				$output_file = $_SERVER['DOCUMENT_ROOT']."/cdn/importaciones/".$file_name;
				$ruta2= $output_file; 
				$ruta_file = base64_to_jpeg($base64_string, $output_file);
			} 
					
		//echo $LiqID.$Contenedor.$Sello.$Sello2.$Tipo.$Placa.$Estiba.$Nota;
		 
		require('../../conexion_mssqlxtratech.php');
		$usuario = $_SESSION['usuario'];
		$TipoIngreso= 'IMP-LQ';
		$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
		$result = $pdo->prepare("Imp_insertar_datos_contenedor @LiquidacionID=:LiquidacionID,@Contenedor=:Contenedor,@Sello=:Sello,
								@Sello2=:Sello2, @Tipo=:Tipo, @Placa=:Placa ,@Estiba=:Estiba,@Nota=:Nota, @Foto1=:Foto1, @Foto2=:Foto2,
								@Candado=:Candado, @TipoIngreso=:TipoIngreso " );	
		$result->bindParam(':LiquidacionID',$LiqID,PDO::PARAM_STR);
		$result->bindParam(':Contenedor',$Contenedor,PDO::PARAM_STR);
		$result->bindParam(':Sello',$Sello,PDO::PARAM_STR);
		$result->bindParam(':Sello2',$Sello2,PDO::PARAM_STR);
		$result->bindParam(':Tipo',$Tipo,PDO::PARAM_STR);
		$result->bindParam(':Placa',$Placa,PDO::PARAM_STR);
		$result->bindParam(':Estiba',$Estiba,PDO::PARAM_STR);
		$result->bindParam(':Nota',$Nota,PDO::PARAM_STR);							
		$result->bindParam(':Foto1',$ruta1,PDO::PARAM_STR);
		$result->bindParam(':Foto2',$ruta2,PDO::PARAM_STR);
		$result->bindParam(':Candado',$Candado,PDO::PARAM_STR);
		$result->bindParam(':TipoIngreso',$TipoIngreso,PDO::PARAM_STR);
		$result->execute();
		
		
		$_SESSION['usuario']=$usuario;
		$_SESSION['base']= $base ;
		$_SESSION['acceso']=$acceso;
		$_SESSION['codigo']=$codigo;
		$_SESSION['IDLiq']=	$LiqID;
		
		header("location: recepcionimportacionesxtratech1.php");
	}
else
	{
		header("location: index.html");
	}			
?>		 		
</div>		
</body>	
</html>	