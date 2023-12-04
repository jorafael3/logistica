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
<link rel="stylesheet" type="text/css" href="css/tablas.css">

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
			$secu	= $_SESSION['secu'];
			$bodega	= $_SESSION['bodega'];
			$nomsuc = $_SESSION['nomsuc'];
			$bodegaFAC = $_SESSION['bodegaFAC'];
			// echo "aaaaaaaaaaa".$bodegaFAC;
			$usuario1 = trim($usuario);

			if ($base == 'CARTIMEX') {
				require 'headcarti.php';
			} else {
				require 'headcompu.php';
				$_SESSION['base'] = $base;
			}
			// $codigo= trim($codigo);
			require('conexion_mssql.php');

			//$pdo = new PDO($dsn, $sql_user, $sql_pwd);
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Select Query
			$result = $pdo->prepare('LOG_BUSQUEDA_FACTURA @secuencia=:secu');
			$result->bindParam(':secu', $secu, PDO::PARAM_STR);
			$result->execute();
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$Codbodega = $row['Codbodega'];
				$Secuencia = $row['Secuencia'];
				$Numero = $row['Numero'];
				$Id = $row['Id'];
				$ClienteId = $row['ClienteId'];
				$Ruc = $row['Ruc'];
				$Nombre = $row['Nombre'];
				$TipoCLi = $row['TipoCLi'];
				$Fecha = $row['Fecha'];
				$Direccion = $row['Direccion'];
				$Ciudad = $row['Ciudad'];
				$Telefono = $row['Telefono'];
				$Mail = $row['Email'];
				$Contacto = $row['Contacto'];
				$Vendedor = $row['Vendedor'];
				$Bloqueado = $row['Bloqueado'];
				$Nota = $row['Nota'];
				$BodegaId = $row['BodegaId'];
				$Bodeganame = $row['Bodeganame'];
				$Observacion = $row['Observacion'];
				$Medio = $row['Medio'];
				$Empmail = $row['Empmail'];
			}
			$Nota = " ";
		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Datos de Factura <?php echo substr($nomsuc, 10, 20);  ?></center>
				</a></div>
			<div id="derecha"> <a href="menu.php"><img src="assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">
			<div align="left">
				<table>
					<tr>
						<td><strong> BODEGA : <a> <?php echo $Codbodega ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<?php if ($Bloqueado == "1") {
										echo "Bloqueada!!";
									} elseif ($Nota <> " ") {
										echo "Desbloqueada!!";
									} else {
										echo "";
									} ?> </strong></td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Id: </strong> <a> <?php echo $Id ?> </a>
							<strong> Numero: </strong> <a> <?php echo $Numero ?> </a>
							<strong> Factura # : </strong> <a> <?php echo $Secuencia ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Cliente: </strong> <a> <?php echo $Nombre ?> </a>
							<strong> Tipo: </strong> <a> <?php echo $TipoCLi ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Direccion: </strong> <a> <?php echo $Direccion ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Ciudad: </strong> <a> <?php echo $Ciudad ?> </a>
							<strong> Telefono: </strong> <a> <?php echo $Telefono ?> </a>
							<strong> Mail: </strong> <a> <?php echo $Mail ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Contacto: </strong> <a> <?php echo $Contacto ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Vendedor: </strong> <a> <?php echo $Vendedor ?> </a>
							<strong> Email: </strong> <a> <?php echo $Empmail ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Fecha y Hora de Creacion: </strong> <a> <?php echo $Fecha ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Observaciones: </strong> <a> <?php echo $Observacion ?> </a>
							<br>
						</td>
					</tr>
					<tr>
						<td id="label2">
							<strong> Medio Transporte: </strong> <a> <?php echo $Medio  ?> </a>
							<br>
						</td>
					</tr>
				</table>
			</div>
			<?php
			$tipo = 'VEN-FA';
			//$pdo1 = new PDO($dsn, $sql_user, $sql_pwd);
			$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Select Query
			//echo $bodegaFAC;
			//die(); 
			$result1 = $pdo1->prepare('Log_facturaslistas_preparando_insert @id=:id , @usuario=:usuario, @tipo=:tipo, @bodegaid=:bodegaid');
			$result1->bindParam(':id', $Id, PDO::PARAM_STR);
			$result1->bindParam(':usuario', $usuario1, PDO::PARAM_STR);
			$result1->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result1->bindParam(':bodegaid', $bodegaFAC, PDO::PARAM_STR);
			$result1->execute();


			$pdo6 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//Select Query
			$result6 = $pdo6->prepare('Log_facturaslistas_preparando_select @id=:id, @tipo=:tipo, @bodegaid=:bodegaid');
			$result6->bindParam(':id', $Id, PDO::PARAM_STR);
			$result6->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result6->bindParam(':bodegaid', $bodegaFAC, PDO::PARAM_STR);
			$result6->execute();

			while ($row6 = $result6->fetch(PDO::FETCH_ASSOC)) {
				$Preparando = $row6['Preparando'];
				$Fechapre = $row6['
						'];
			}


			/*QUEDA PENDIENTE PONERLE EL TIPO DE DOCUMENTO PARA CARTIMEX  style="display:none"*/
			$pdo4 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			//new PDO($dsn, $sql_user, $sql_pwd);
			//Select Query
			$result4 = $pdo4->prepare('SELECT PREPARADOPOR, FECHAYHORA  FROM  facturaslistas WHERE TIPO =:tipo AND FACTURA=:Id and bodegaid=:bodegaid');
			$result4->bindParam(':Id', $Id, PDO::PARAM_STR);
			$result4->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result4->bindParam(':bodegaid', $bodegaFAC, PDO::PARAM_STR);
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
			$result2 = $pdo2->prepare('LOG_PREPARAR_FACTURA @FacturaID = :Id , @bodegaid=:bodegaid');
			$result2->bindParam(':Id', $Id, PDO::PARAM_STR);
			$result2->bindParam(':bodegaid', $bodegaFAC, PDO::PARAM_STR);
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
				$arreglo[$x][7] = $row2['Clase'];
				if (($arreglo[$x][7]) == '02') {
					$contaserv++;
				}
				$x++;
			}
			$count = count($arreglo);
			?>

			<div align="left" id="Preparado" class=\"table-responsive-xs\">
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
						$result3 = $pdo3->prepare('LOG_BUSCAR_MEJOR_UBICACION @ProductoId =:ProdId, @bodega =:bodegaid ');
						$result3->bindParam(':ProdId', $arreglo[$y][2], PDO::PARAM_STR);
						$result3->bindParam(':bodegaid', $bodegaFAC, PDO::PARAM_STR);
						$result3->execute();

						while ($row3 = $result3->fetch(PDO::FETCH_ASSOC)) {
							$ubi1 = $row3['ubi1'];
							$ubi2 = $row3['ubi2'];
						}
						$countubi = $result3->rowcount();
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
				<form name="formpreparar" action="prepararfacturas2.php" method="POST" width="75%">
					<table align="center">
						<tr>
							<td>
							<td id="box"> <input name="bodega" type="hidden" id="bodega" size="30" value="<?php echo $BodegaId ?>"> </td>
							<input name="submit" id="submit" value="Finalizar Preparacion" type="<?php echo $activar ?>">
							<?php
							if ($activar == "hidden") { ?><a href="prepararfacturas.php" style="text-decoration:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Regresar</a>
							<?php
							} else { ?>
								</td>
						</tr>
					</table>
				</form>
				<form name "interrumpir" action="interrumpirprepa.php" method="POST" width="75%">
					<td id="box"> <input name="tipo" type="hidden" id="tipo" size="30" value="VEN-FA"> </td>
					<input name="submit" id="submit" value="Interrumpir Preparacion" type="submit">
				</form>
				<?php
								if ($acceso == 7) { ?>
					<form name "interrumpir" action="verificarfacturas0.php" method="POST" width="75%">
						<td id="box"> <input name="secu" type="hidden" id="secu" size="30" value="<?php echo $Secuencia ?>"> </td>
						<input name="submit" id="submit" value="Pasar a verificar Factura " type="submit">
					</form>
				<?php						} ?>

			<?php				} ?>


			</div>
		</div>
	<?php
			$usuario = $usuario1;
			$_SESSION['usuario'] = $usuario;
			$_SESSION['id'] = $Id;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['codigo'] = $codigo;
			$_SESSION['bodega'] = $bodega;
			$_SESSION['nomsuc'] = $nomsuc;
			$_SESSION['serv'] = $contaserv;
			$_SESSION['carreglo'] = $count;
			$_SESSION['bodegaFAC'] = $bodegaFAC;
		} else {
			header("location: index.html");
		}
	?>

	</div>
</body>

</html>