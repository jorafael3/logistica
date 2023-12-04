<html>
<script type="text/javascript">
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">

<body>
	<div id="header" align="center">
		<?php
		session_start();
		if (isset($_SESSION['loggedin'])) {
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	= $_SESSION['acceso'];
			$bodega	= $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$usuario1 = trim($usuario);
			$numerorecibido = $_SESSION['transferencia'];
			//echo "<br>Transfer:".$numerorecibido;
			/*Defino los arreglos a utilizar */
			$arreglo = array();
			$arreglo2 = array();
			require('../conexion_mssql.php');
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare('LOG_VERIFICAR_TRANSFERENCIA1 @Transferencia=:numerorecibido');
			$result->bindParam(':numerorecibido', $numerorecibido, PDO::PARAM_STR);

			//Executes the query
			$result->execute();
			$count = $result->rowcount();
			$x = 0;
			$totalPeso = 0;
			if ($count == 0) {
				die("No hay datos... ");
			}
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$arreglo[$x][1] = $row['id'];
				$arreglo[$x][2] = $row['TransferenciaID'];
				$arreglo[$x][3] = $row['cantidad'];
				$arreglo[$x][4] = $row['code'];
				$arreglo[$x][5] = $row['nombre'];
				$arreglo[$x][6] = $row['c1'];
				$arreglo[$x][7] = $row['c2'];
				$arreglo[$x][8] = $row['c3'];
				$arreglo[$x][9] = $row['serie'];
				$arreglo[$x][10] = $row['productoid'];
				$arreglo[$x][12] = $row['MedidaPeso'];
				$arreglo[$x][13] = $row['PesoTotal'];
				$arreglo[$x][14] = $row['TOTALPESO'];
				$transferenciaid = $row['id'];
				// $x++;

				$peso = $arreglo[$x][12]; // Suponiendo que el peso se encuentra en $arreglo[$x][12]
				$totalPeso += $peso; // Sumar el peso al total

				$transferenciaid = $row['id'];
				$x++;
			}
			// hasta aqui tengo todo lo de la transferencia en ARREGLO. Ahora la paso a otro arreglos para los duplicados y unirlos
			$x = $x - 1; // tamaño del arreglo original
			$xao = 0;   // Para recorrer el arreglo original xao
			$xan = 0;   // Para recorrer el arreglo nuevo xan
			$xnew = 0;   // Para crear el arreglo1
			$igual = 0;
			$zz = 0;
			// el siguiente lazo es para unificar los repetidos
			for ($xao = 0; $xao <= $x; $xao++) {
				if (count($arreglo2) == 0) { // creo el primer elemento del arreglo2
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
					$arreglo2[0][12] = $arreglo[0][12];
					$arreglo2[0][13] = $arreglo[0][13];
					$arreglo2[0][14] = $arreglo[0][14];
					$arreglo2[$xnew][11] = "";
					$xnew++;
				} else { // no es el primer elemento de arreglo2, asi que lo debo comparar con todos del arreglo2
					$zz = 0;
					$cantidades = 0;
					$posicion = 0;
					while ($zz < count($arreglo2)) {
						if ($arreglo[$xao][4] == $arreglo2[$zz][4]) {
							$igual = 1;
							$cantidades = $cantidades + $arreglo[$xao][3];
							$posicion = $zz;
						}
						$zz++;
					}
					if ($igual == 0) {
						$arreglo2[$xnew][1] = $arreglo[$xao][1];
						$arreglo2[$xnew][2] = $arreglo[$xao][2];
						$arreglo2[$xnew][3] = $arreglo[$xao][3];
						$arreglo2[$xnew][4] = $arreglo[$xao][4];
						$arreglo2[$xnew][5] = $arreglo[$xao][5];
						$arreglo2[$xnew][6] = $arreglo[$xao][6];
						$arreglo2[$xnew][7] = $arreglo[$xao][7];
						$arreglo2[$xnew][8] = $arreglo[$xao][8];
						$arreglo2[$xnew][9] = $arreglo[$xao][9];
						$arreglo2[$xnew][10] = $arreglo[$xao][10];
						$arreglo2[$xnew][12] = $arreglo[$xao][12];
						$arreglo2[$xnew][13] = $arreglo[$xao][13];
						$arreglo2[$xnew][14] = $arreglo[$xao][14];
						$arreglo2[$xnew][11] = "";
						$xnew++;
					} else {
						$arreglo2[$posicion][3] = $arreglo2[$posicion][3] + $cantidades;
					}
					$igual = 0;
				} // fin del if count	 
			} // fin del for xao
			//echo '<pre>',print_r(($arreglo2)),'</pre>';
			$contadort = 0;
			$_SESSION['datosarreglos'] = $arreglo2;
			$_SESSION['contadort'] = $contadort;
			$usuario = $usuario1;
			$_SESSION['usuario'] = $usuario;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['codigo'] = $codigo;
			$_SESSION['nomsuc'] = $nomsuc;
			$_SESSION['transfer'] = $transferenciaid;
			//echo "Transferenciaid".$transferenciaid.$codigo.$nomsuc;
			//die();
			header("location: verificartransferencias2.php");
		} else {
			header("location: index.html");
		}
		?>
</body>

</html>