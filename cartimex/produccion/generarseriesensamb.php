<html>
<script type="text/javascript">
</script>
<link href="../../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../../estilos/estilos.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../css/tablas.css">
<body> 
<div id= "header" align= "center">
<?php 
	session_start();
	if (isset($_SESSION['loggedin']))
		{
		    $usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$bodega = $_SESSION['bodega'];
			$ensamble= $_POST['ensamble'];
			$cant= $_POST['cant'];
			
			$_SESSION['usuario']= $usuario;
			date_default_timezone_set('America/Guayaquil');
			$fecha = date("Y-m-d", time());
			$fecha= date("Ymd");
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;
			if ($base=='CARTIMEX'){
					require '../../headcarti.php';  	
			}
			else{
					require '../../headcompu.php';
			}
			
			require('../../conexion_mssql.php');
			$usuario = $_SESSION['usuario'];
?>
<div id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>  Generacion de Series Ensambles </center> </a></div>
					<div id = "derecha" > <a href="../../menu.php"><img src="..\..\assets\img\home.png"></a> </div>
	</div>
</div>
<hr>
<?php		
			if ($cant>0 )
				{				
					/******* Genera y graba las series para cada maquina ensamblada ******/
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('SELECT  UserId,Id , SerieEnsamblado FROM INV_PRODUCCION_ENSAMBLE_DT
											  WHERE EnsambleId =:EnsambleId');		 
					$result1->bindParam(':EnsambleId',$ensamble,PDO::PARAM_STR);
					$result1->execute();
					$arreglo = array();
					$x=0; 
					while ($row1= $result1->fetch(PDO::FETCH_ASSOC)) 
						{ 
							$arreglo[$x][1]=$row1['Id'];
							$x++; 
						}
					$count = count($arreglo); 
					 
					$y=0;
					 
					while ( $y <= $count-1 ) 
						{					
							 
							$EnsambleDtId=$arreglo[$y][1]; 
							$pdo4 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result4 = $pdo4->prepare('INV_PRODUCCION_GENERAR_SERIES');	
							$result4->execute();
							
							$codigodt= 'INV_PRODUCCION_GENERAR_SERIES-XTR%';
							$pref = 'XTR'; 
							$pdo5 = new PDO("sqlsrv:server=$sql_serverName; Database = $sql_database", $sql_user, $sql_pwd);
							$result5 = $pdo5->prepare('INV_PRODUCCION_GENERAR_SERIES_SELECTID @CODIGO=:CODIGO, @pref=:pref');	
							$result5->bindParam(':CODIGO',$codigodt,PDO::PARAM_STR);		
							$result5->bindParam(':pref',$pref,PDO::PARAM_STR);	
							$result5->execute();				 
							$row5 = $result5->fetch(PDO::FETCH_ASSOC);
							$serie = $row5['serie']; 		
							 
							$GS= 1; 
							$pdo6 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result6 = $pdo6->prepare('update INV_PRODUCCION_ENSAMBLE_DT Set SerieEnsamblado=:serie, GeneradaSerie=:GS
													   where EnsambleId=:EnsambleId and Id=:Id');	
							$result6->bindParam(':serie',$serie,PDO::PARAM_STR);
							$result6->bindParam(':EnsambleId',$ensamble,PDO::PARAM_STR);
							$result6->bindParam(':Id',$EnsambleDtId,PDO::PARAM_STR);							
							$result6->execute();
							$y=$y+1;
						}
					$pdo0 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result0 = $pdo0->prepare('update INV_PRODUCCION_ENSAMBLE  Set GeneradaSerie=:GS
													   where EnsambleId=:EnsambleId and Id=:Id');		 
					$result1->bindParam(':EnsambleId',$ensamble,PDO::PARAM_STR);
					$result1->execute();	
					$_SESSION['base']= $base;
					$_SESSION['usuario']=$usuario;
					$_SESSION['acceso']=$acceso;
					$_SESSION['ensamble']=$ensamble;
					$_SESSION['bodega']= $bodega; 
					header("Refresh: 1 ; etiquetarensambles1.php");	
				}				
			else
				{
 					
					header("Refresh: 1 ; recibirensamble.php");					
					 
				}	
			$_SESSION['base']= $base;
			$_SESSION['usuario']=$usuario;
			$_SESSION['acceso']=$acceso;
			$_SESSION['ensamble']=$ensamble;
			$_SESSION['bodega']= $bodega; 	
							 
			//header("location: despacharfacturas.php");		
		}

?>

		
</div>
</body>
</html>