<html>
<script type="text/javascript">
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body>
<div id= "header" align= "center">
<?php 
	session_start();	
	if (isset($_SESSION['loggedin']))
		{	
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	=$_SESSION['acceso'];
			$secu	=$_SESSION['secu'];
			$bodegaFAC= $_SESSION['bodegaFAC'];
			// echo $bodegaFAC;
			// echo $secu;
			$bodega	=$_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$usuario1= trim($usuario); 
			$arreglo = array();
			$arreglo2 = array();
			$arreglo3 = array();
			require('conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare('LOG_VERIFICAR_FACTURA1 @secuencia=:secu, @bodega=:bodega');		 
			$result->bindParam(':secu',$secu,PDO::PARAM_STR);
			$result->bindParam(':bodega',$bodegaFAC,PDO::PARAM_STR);
			$result->execute();
			$x = 0;
			$count = $result->rowcount();
			if ($count == 0 ) {die("No hay datos... ");}
			
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) 
				{
					$arreglo[$x][1] = $row['id'];
					$arreglo[$x][2] = $row['facturaid'];
					$arreglo[$x][3]= $row['cantidad'];
					// aqui busco un -PRO o -PROMO en el codigo para quitarlo
					$longitud = strlen($row['code']);
					$compara = substr($row['code'],$longitud-4,4);
					$compara2 = substr($row['code'],$longitud-6,6);
					if ($compara == "-PRO1" or $compara2 == "-PROMO1")
						{
							if ($compara == "-PRO") {$arreglo[$x][4] = substr($row['code'],0,$longitud-4);}
							if ($compara2 == "-PROMO") {$arreglo[$x][4] = substr($row['code'],0,$longitud-6);}
						}
					else
						{
							$arreglo[$x][4] = $row['code'];
						} 
					$arreglo[$x][5] = $row['nombre'];
					$arreglo[$x][6] = $row['c1'];
					$arreglo[$x][7] = $row['c2'];
					$arreglo[$x][8] = $row['c3'];
					$arreglo[$x][9] = $row['serie']; 
					$arreglo[$x][10] = $row['productoid'];
					$factura= $row['facturaid'];
					$x++; 
				}
			//Para ver los productos que trae el arreglo      
			// hasta aqui tengo todo lo de la factura en ARREGLO. Ahora la paso a otro arreglos para los duplicados y unirlos
			$x = $x-1; // tamaño del arreglo original
			$xao = 0;   // Para recorrer el arreglo original xao
			$xan = 0;   // Para recorrer el arreglo nuevo xan
			$xnew = 0;   // Para crear el arreglo1
			$igual = 0 ;
			$zz = 0 ;
			// el siguiente lazo es para unificar los repetidos 
			for ($xao = 0; $xao <= $x; $xao++)
			{
				//  echo "XAO: ".$arreglo[$xao][4]."<br>";
				if (count($arreglo2)==0) 
					{ // creo el primer elemento del arreglo2
						$arreglo2[0][1] = $arreglo[0][1];
						$arreglo2[0][2] = $arreglo[0][2];
						$arreglo2[0][3] = $arreglo[0][3];
						$arreglo2[0][4] = $arreglo[0][4];
						$arreglo2[0][5] = $arreglo[0][5];
						$arreglo2[0][6] = $arreglo[0][6];
						$arreglo2[0][7] = $arreglo[0][7];
						$arreglo2[0][8] = $arreglo[0][8];      			
						$arreglo2[0][9] = $arreglo[0][9];
						$arreglo2[0][10] = $arreglo[0][10];
						$arreglo2[$xnew][11] = "";
						$xnew++;
					}
				else 
					{ // no es el primer elemento de arreglo2, asi que lo debo comparar con todos del arreglo2
						$zz = 0;
						$cantidades = 0;
						$posicion = 0;
						while ($zz < count($arreglo2))
						{
							if ($arreglo[$xao][4] == $arreglo2[$zz][4])
								{ 
								 $igual = 1 ;
								 $cantidades = $cantidades + $arreglo[$xao][3];
								 $posicion = $zz;
								}
							$zz++;
						}
						if ($igual == 0 )
							{
								$arreglo2[$xnew][1] = $arreglo[$xao][1];
								$arreglo2[$xnew][2] = $arreglo[$xao][2];
								$arreglo2[$xnew][3] = $arreglo[$xao][3];
								$arreglo2[$xnew][4] = $arreglo[$xao][4];
								$arreglo2[$xnew][5] = $arreglo[$xao][5];
								$arreglo2[$xnew][6] = $arreglo[$xao][6];
								$arreglo2[$xnew][7] = $arreglo[$xao][7];
								$arreglo2[$xnew][8] = $arreglo[$xao][8];      			
								$arreglo2[$xnew][9] = $arreglo[$xao][9]; 
								$arreglo2[$xnew][10] =$arreglo[$xao][10];
								$arreglo2[$xnew][11] = "";
								$xnew++;
							}
						else
							{
								$arreglo2[$posicion][3] = $arreglo2[$posicion][3]+$cantidades;
							}
						$igual = 0 ;
				   
					}// fin del if count
			}// fin del for xao
			$contador = 0 ;      
			$_SESSION['datosarreglos'] = $arreglo2;
			$_SESSION['contador']=$contador;
			//echo '<pre>'; print_r($arreglo2); echo '</pre>';
			//die();
			$usuario= $usuario1;  			
			$_SESSION['usuario']=$usuario;
			$_SESSION['id']=$Id;
			$_SESSION['base']= $base ;
			$_SESSION['acceso']=$acceso;
			$_SESSION['codigo']=$codigo;
			$_SESSION['nomsuc']=$nomsuc; 	
			$_SESSION['factura']=$factura; 
			$_SESSION['bodegaFAC']= $bodegaFAC;		
		header("location: verificarfacturas2.php" );
		}
	else
		{
		header("location: index.html");
		}
?>
</div>
</body>
</html>