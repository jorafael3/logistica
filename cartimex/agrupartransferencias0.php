<html>
<script type="text/javascript">
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">

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
			$bodega = $_SESSION['bodega'];
			if ($base=='CARTIMEX'){	require '../headcarti.php';  }
			else{require '../headcompu.php';}
			$usuario1=$usuario;
			require('../conexion_mssql.php');
			$checkbox = $_POST['checkbox'] ;
			$fin = count($checkbox);
			$arreglo= array(); 
			$arreglotrans= array(); 
			date_default_timezone_set('America/Guayaquil');
			$fecha = date("Y-m-d", time());
			$fecha= date("Ymd");
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;
			
			$x=0; 
			$i=0;
			for ($i = 0; $i <= $fin-1; $i++) 
				{ 
					//echo $checkbox[$i].'<br>';
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('select * from inv_transferencias where id=:id');	
					$result1->bindParam(':id',$checkbox[$i],PDO::PARAM_STR);
					$result1->execute();
					while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) 
						{
							if ($row1['BodegaID_Origen']=='0000000016' or $row1['BodegaID_Origen']=='0000000005' ){$bodegao='0000000006';} else {$bodegao=$row1['BodegaID_Origen'];}
								
							$bodegao= $bodegao.$row1['BodegaID_Destino'];
							//echo "Bodega".$bodegao."<br>"; 
						}
					$arreglotrans[1][$x]= $checkbox[$i]; 
					$arreglo[1][$x]=$bodegao;	
					$x++;
				}
				 
				//echo '<pre>', print_r($arreglotrans),'</pre>';
				//echo "BOdegaOrigen ".substr($bodegao,0,10); 
 
			//recorrer el arreglo para ver q sea la misma bodega d Origen 
			$i=0;	
			for ($i = 0; $i <= $fin-1; $i++) 
				{
					//echo "debe mostrar". $arreglo[1][0].$arreglo[1][$i];
					if ($arreglo[1][0]==$arreglo[1][$i]) { $mismabod= "SI"; } else {$mismabod= "NO";}
					//echo $arreglo[1][0].$arreglo[1][$i]; 
				}
				//echo "Misma". $mismabod;
			if ($mismabod=='NO')
				{
					$Prepa = "Transferencias no se pueden agrupar";
					header("Refresh: 3 ;  agrupartransferencias.php ");}
			else
				{ 	
					$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result2 = $pdo2->prepare('LOG_AGRUPAR_TRANSFERENCIAS_SELECTID');	
					$result2->execute();
	
					$codigo= 'LOG_AGRUPARTRANS-ID%';
					$pdo4 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result4 = $pdo4->prepare('LOG_AGRUPAR_TRANSFERENCIAS_SELECT @CODIGO=:CODIGO');	
					$result4->bindParam(':CODIGO',$codigo,PDO::PARAM_STR);		
					$result4->execute();
					$row4 = $result4->fetch(PDO::FETCH_ASSOC);
					$AgruparID = $row4['IDAgrupar'];
					$i=0;	
					for ($i = 0; $i <= $fin-1; $i++) 
						{
							//echo "Esto es lo q va a grabar". $AgruparID.$arreglotrans[1][$i].$usuario1.$fecha ; 
							$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
							$result3 = $pdo3->prepare('insert into INV_Transferencias_agrupadas (AgruparID, TransferenciasID,AgrupadoPor,FechaAgrupado,BodegaO) 
													   values (:AgruparID,:TransferenciaID,:AgrupadoPor,:FechaAgrupado,:BodegaO)');
							$result3->bindParam(':AgruparID',$AgruparID,PDO::PARAM_STR);
							$result3->bindParam(':TransferenciaID',$arreglotrans[1][$i],PDO::PARAM_STR);							
							$result3->bindParam(':AgrupadoPor',$usuario1,PDO::PARAM_STR);
							$result3->bindParam(':FechaAgrupado',$fh,PDO::PARAM_STR);
							$result3->bindParam(':BodegaO',substr($bodegao,0,10),PDO::PARAM_STR);
							$result3->execute();
						}
					$Prepa = "Transferencias agrupadas con éxito ";
					header("Refresh: 2;  agrupartransferencias.php ");}

			$_SESSION['usuario']= $usuario1;	
			$_SESSION['base']= $base;
			$_SESSION['acceso']=$acceso;	
			$_SESSION['numero']=$numero;
			$_SESSION['bodega']=$bodega;
			$_SESSION['nomsuc']=$nomsuc;  
		}
		else
		{		
			header("location: index.html");
		}		
?>
</div>	
<div  id = "Cuerpo" >
	<div id="cuerpo2" >
				 
					<div id = "izq" > </div>
					<div id = "centro" > <a class="titulo"> <center>   Agrupar Transferencias  <?php echo substr($nomsuc,10,20);  ?> </center> </a></div>
					<div id = "derecha" > <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
				 
	</div> 
<hr>
</div>					

<div> 
	<a class="titulo"> <center>  <?php echo $Prepa ?>   </center> </a>
</div>
 </div>
</body>