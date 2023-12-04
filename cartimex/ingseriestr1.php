<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">
<body>
<div id= "header" align= "center">
<?PHP

	session_start();
	if (isset($_SESSION['loggedin']))
		{
			$usuario= $_SESSION['usuario'];
			$base = $_SESSION['base'] ;
			$acceso= $_SESSION['acceso'];
			$series = $_SESSION['series'];//traigo las series ingresadas en el arreglo 
			$idproducto =$_SESSION['productoid'];
			$transfer = $_SESSION['transfer'];
			$cantseries = $_SESSION['cantseries'];
			//echo '<pre>', print_r($series),'</pre>';	
			$usuario1= $usuario; 
			//echo $usuario. $base. $acceso. $idproducto. $transfer. $cantseries;
			 
			date_default_timezone_set('America/Guayaquil');
			$fecha = date("Y-m-d", time());
			$fecha= date("Ymd");
			
			//echo "Factura # ".$numerorecibido.$usuario.$Id.$base.$acceso.$codigo.$nomsuc .$cliente.$fecha. $pedido."<br>";
			if ($base=='CARTIMEX')
				{require '../headcarti.php';  	}
			else{
					require '../headcompu.php';
					$_SESSION['base']= $base; 
					$Nota = " "; 	
				}
			require('../conexion_mssql.php');		
			/* ************ Grabo las series en una tabla temporal ************** */
			$x= 0 ; //contador para recorrer el arreglo
			//echo "Deberia entrar al ciclo ".$x. $cantseries;
			while ($x <$cantseries)
				{
					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result1 = $pdo1->prepare('select estado , facturaid from rma_productos where serie=:serie and productoid=:productoid ');			
					$result1->bindParam('serie',$series[1][$x],PDO::PARAM_STR);
					$result1->bindParam('productoid',$idproducto,PDO::PARAM_STR);
					$result1->execute();
					$count1 = $result1->rowcount();
					$row = $result1->fetch(PDO::FETCH_ASSOC);
					if ($count1==0) 
						{
							$grabar= 1;// significa que hay q insertar el registro
						}
					else
						{
							$grabar = 0; //significa que hay q actualizar el registro 
						}	
					
					//echo "Esto es lo que voy a insertar". $transfer. $series[1][$x].$fecha.$idproducto. "Grabar". $grabar ; 
					// $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					// $result = $pdo->prepare('INSERT INTO SERIESSGLCARTIMEX ( FACTURAID, SERIE ,FECHA, PRODUCTO, grabar ) VALUES(:FACTURAID,:SERIE,:FECHA,:PRODUCTO,:grabar)');			
					// $result->bindParam(':FACTURAID',$transfer,PDO::PARAM_STR);
					// $result->bindParam(':SERIE',$series[1][$x],PDO::PARAM_STR);
					// $result->bindParam(':FECHA',$fecha,PDO::PARAM_STR);
					// $result->bindParam(':PRODUCTO',$idproducto,PDO::PARAM_STR);
					// $result->bindParam(':grabar',$grabar,PDO::PARAM_STR);
					// $result->execute();
					// $x++;


					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('{CALL SGL_Serie_Cartimex_Insert (?,?,?,?,?)}');			
					$result->bindParam(1,$transfer,PDO::PARAM_STR);
					$result->bindParam(2,$series[1][$x],PDO::PARAM_STR);
					$result->bindParam(3,$fecha,PDO::PARAM_STR);
					$result->bindParam(4,$idproducto,PDO::PARAM_STR);
					$result->bindParam(5,$grabar,PDO::PARAM_STR);
					$result->execute();
					$x++;

				}
//die(); 				
			?>	
			
				
			
</div>
			<?php
			
			//echo "<h1>Factura completa!</h1>";
			$_SESSION['cliente']=$cliente;
			$_SESSION['usuario']=$usuario1;
			$_SESSION['id']=$Id;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['codigo']=$codigo;
			$_SESSION['nomsuc']=$nomsuc; 
			$_SESSION['numfac']=$numerorecibido; 
			header("Refresh: 0 ; verificartransferencias2.php");
		}
	else
		{
			header("location: index.html");
		}	
	  
?>
</div>
</body>
</html>