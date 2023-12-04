<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
	function visible() {
		var div1 = document.getElementById('Preparando');
		div1.style.display = 'none';
	}
</script>
<link href="estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="estilos/estilos.css" rel="stylesheet" type="text/css">

<body>
	<div id="header" align="center">
		<?php
		//error_reporting(E_ALL);
		//ini_set('display_errors','On');
		session_start();
		if (isset($_SESSION['loggedin'])) {
			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso	= $_SESSION['acceso'];
			$numero	= $_SESSION['numero'];
			$bodega	= $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$usuario1 = trim($usuario);

			// $codigo= trim($codigo);
			require('conexion_mssql.php');

			//$pdo = new PDO($dsn, $sql_user, $sql_pwd);
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare('LOG_BUSQUEDA_TRANSFERENCIA @numero=:numero');
			$result->bindParam(':numero', $numero, PDO::PARAM_STR);
			$result->execute();
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$Numtransfer = $row['Numtransfer'];
				$Idtransfer = $row['Idtransfer'];
				$Detalle = $row['Detalle'];
				$Fecha = $row['Fecha'];
				$Nota = $row['Nota'];
				$Descodigo = $row['Descodigo'];
				$Destino = $row['Destino'];
				$Oricodigo = $row['Oricodigo'];
				$Origen = $row['Origen'];
				$BodegaId = $row['BodegaId'];
			}
			if ($base == 'CARTIMEX') {
				require 'headcarti.php';
			} else {
				require 'headcompu.php';
				$_SESSION['base'] = $base;
				$Nota = " ";
			}
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Datos de Transferencia <?php echo substr($nomsuc, 10, 20);  ?></center>
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">
			<div align="left">
				<table>
					<tr>
						<td><strong> BODEGA ORIGEN: <a> <?php echo $Oricodigo ?>&nbsp;&nbsp;&nbsp; <?php echo $Origen ?> </strong></td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Transferencia Id: </strong> <a> <?php echo $Idtransfer ?> </a>
							<strong> Numero: </strong> <a> <?php echo $Numtransfer ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Fecha: </strong> <a> <?php echo $Fecha ?> </a>
							<strong> Destino: </strong> <a> <?php echo $Descodigo ?> <?php echo $Destino ?></a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Detalle: </strong> <a> <?php echo $Detalle ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Nota: </strong> <a> <?php echo $Nota ?> </a>
							<br>
						</td>
					</tr>
				</table>
			</div>
			<?php

			$tipo = 'INV-TR';
			//$pdo1 = new PDO($dsn, $sql_user, $sql_pwd);
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Select Query
			$result1 = $pdo1->prepare('Log_facturaslistas_preparando_insert @id=:id , @usuario=:usuario, @tipo=:tipo');
			$result1->bindParam(':id', $Idtransfer, PDO::PARAM_STR);
			$result1->bindParam(':usuario', $usuario1, PDO::PARAM_STR);
			$result1->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			//Executes the query
			$result1->execute();

			$pdo6 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Select Query
			$result6 = $pdo6->prepare('Log_facturaslistas_preparando_select @id=:id, @tipo=:tipo');
			$result6->bindParam(':id', $Idtransfer, PDO::PARAM_STR);
			$result6->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result6->execute();

			while ($row6 = $result6->fetch(PDO::FETCH_ASSOC)) {
				$Preparando = $row6['Preparando'];
				$Fechapre = $row6['fechaPreparando'];
			}


			/*QUEDA PENDIENTE PONERLE EL TIPO DE DOCUMENTO PARA CARTIMEX  style="display:none"*/
			$pdo4 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result4 = $pdo4->prepare('SELECT PREPARADOPOR, FECHAYHORA  FROM  facturaslistas WHERE TIPO =:tipo AND FACTURA=:Id');
			$result4->bindParam(':Id', $Idtransfer, PDO::PARAM_STR);
			$result4->bindParam(':tipo', $tipo, PDO::PARAM_STR);

			$result4->execute();
			while ($row4 = $result4->fetch(PDO::FETCH_ASSOC)) {
				$PreparadoPor = $row4['PREPARADOPOR'];
				$Fechaprepa = $row4['FECHAYHORA'];
			}
			if ($Preparando <> '' and $PreparadoPor == '.') {
				$lprepa = $Preparando;
				$lfecha = $Fechapre;
				$ltitulo = "Preparando";
				$activar = "submit";
			} else {
				$lprepa = $PreparadoPor;
				$lfecha = $Fechaprepa;
				$ltitulo = "Preparado";
				$activar = "hidden";
			}
			$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result2 = $pdo2->prepare('LOG_PREPARAR_TRANSFERENCIA @TransferenciaID = :Id ');
			$result2->bindParam(':Id', $Idtransfer, PDO::PARAM_STR);
			$result2->execute();
			$arreglo = array();
			$x = 0;
			while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
				$arreglo[$x][1] = $row2['DTID'];
				$arreglo[$x][2] = $row2['ProductoId'];
				$arreglo[$x][3] = $row2['CopProducto'];
				$arreglo[$x][4] = $row2['Cantidad'];
				$arreglo[$x][5] = $row2['Detalle'];
				$arreglo[$x][6] = $row2['RegistaSerie'];
				$x++;
			}
			$count = count($arreglo);
			?>

			<div align="left" id="Preparado">
				<table>
					<tr>
						<td id="label3">
							<strong> <?php echo $ltitulo ?> </strong> <a> <?php echo $lprepa ?> </a>
							<strong> Fecha: </strong> <a> <?php echo $lfecha ?> </a>
							<br>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<?php

		?>
		<div id="cuerpo2" align="center">
			<div>
				<table id="listado2" align="center">
					<tr>
						<th> UBICACION1 </th>
						<th> UBICACION2 </th>
						<th> CODIGO </th>
						<th> ARTICULO </th>
						<th> CANT. </th>
					</tr>
					<?php

					$y = 0;
					while ($y <= $count - 1) {
						$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						//new PDO($dsn, $sql_user, $sql_pwd);
						//Select Query
						$result3 = $pdo3->prepare('LOG_BUSCAR_MEJOR_UBICACION @ProductoId =:ProdId , @bodega=:bodega');
						$result3->bindParam(':ProdId', $arreglo[$y][2], PDO::PARAM_STR);
						$result3->bindParam(':bodega', $bodega, PDO::PARAM_STR);
						$result3->execute();

						while ($row3 = $result3->fetch(PDO::FETCH_ASSOC)) {
							$ubi1 = $row3['ubi1'];
							$ubi2 = $row3['ubi2'];
						}
						//echo "trae".$countubi = $result3->rowcount();	
						if ($countubi == 0) {
							$ubi1 = '';
							$ubi2 = '';
						}
					?>
						<tr>
							<td id="fila2" align=left> <?php echo $ubi1 ?></td>
							<td id="fila2" align=left> <?php echo $ubi2 ?></td>
							<td id="fila2" align=left> <?php echo $arreglo[$y][3] ?></td>
							<td id="fila2" align=left> <?php echo $arreglo[$y][5] ?></td>
							<td id="fila" align=left> <?php echo $arreglo[$y][4] ?></td>
						</tr>
					<?php
						$y = $y + 1;
					}
					?>
				</table>
			</div>
			<div>
				<form name="formpreparar" action="preparartransferencias2.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<td>
							<td id="box"> <input name="bodega" type="hidden" id="bodega" size="30" value="<?php echo $BodegaId ?>"> </td>
							<input name="submit" id="submit" value="Finalizar Preparacion" type="<?php echo $activar ?>">
							<?php
							if ($activar == "hidden") { ?><a href="preparartransferencias.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar </a>
							<?php
							} else { ?>

								</td>
						</tr>
					</table>
				</form>
				<form name "interrumpir" action="interrumpirprepa.php" method="POST" width="75%">
					<td id="box"> <input name="tipo" type="hidden" id="tipo" size="30" value="INV-TR"> </td>
					<input name="submit" id="submit" value="Interrumpir Preparacion" type="submit">
				</form>
			<?php				} ?>
			</div>
		</div>
	<?php
			$usuario = $usuario1;
			$_SESSION['usuario'] = $usuario;
			$_SESSION['id'] = $Idtransfer;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['codigo'] = $codigo;
			$_SESSION['nomsuc'] = $nomsuc;
		} else {
			header("location: index.html");
		}
	?>

	</div>
</body>

</html>