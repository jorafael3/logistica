<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!DOCTYPE html>
<html>
<script type="text/javascript">
</script>
<link href="../estilos/estilos2.css" rel="stylesheet" type="text/css">
<link href="../estilos/estilos.css" rel="stylesheet" type="text/css">

<body>
	<div id="header" align="center">
		<?PHP
		session_start();
		if (isset($_SESSION['loggedin'])) {

			$usuario = $_SESSION['usuario'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$nomsuc = $_SESSION['nomsuc'];
			$iddestino = $_SESSION['IDDESTINO'];
			$idorigen = $_SESSION['IDORIGEN'];
			$detalle = $_SESSION['DETALLE'];
			$tipo = 'INV-TR';
			$numerorecibido = $_SESSION['transfer'];
			$usuario1 = $usuario;
			$Transnum = $_SESSION['Transnum'];
			//echo "Transferencia # ".$numerorecibido.$usuario.$base.$acceso."Destino".$iddestino."Origen".$idorigen.$detalle;

			date_default_timezone_set('America/Guayaquil');
			$fecha = date("Y-m-d", time());
			$fecha = date("Ymd");
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;
			if ($base == 'CARTIMEX') {
				require '../headcarti.php';
			} else {
				require '../headcompu.php';
				$_SESSION['base'] = $base;
				$Nota = " ";
			}
			$arreglo2 = $_SESSION['arregloseriest']; // Cargo el arreglo de la memoria
			$xx = count($arreglo2);
			$x = 0;
			//si vienen productos que registran series deberian venir en este arreglo 
			//echo '<pre>', print_r($arreglo2),'</pre>';

			require('../conexion_mssql.php');


			$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);

			$LISTA_BODEGAS = [];
			$SQL_B = "SELECT ID from COMPUTRONSA..INV_BODEGAS
			where Anulado = 0
			and venta = 1
			and Sucursal not in ('00')
			and Código not in ('00.01','00.08','03.51','13.02','05.04','45.01','46.01','05.07','75.01','13.01','05.13')";
			$result_B = $pdo2->prepare($SQL_B);
			if ($result_B->execute()) {
				$result = $result_B->fetchAll(PDO::FETCH_ASSOC);

				foreach ($result as $row) {
					array_push($LISTA_BODEGAS, $row["ID"]);
				}
				// echo "<pre>";
				// var_dump($LISTA_BODEGAS);
				// echo "</pre>";
			}

			while ($x < count($arreglo2)) {


				$TRANSFERENCIA_ID = $Transnum;
				$PRODUCTOID = $arreglo2[$x][1];
				$SERIE = $arreglo2[$x][2];
				$EGRESO = 1;
				$BODEGA_ORIGEN = $idorigen;
				$BODEGA_DESTINO = $iddestino;
				$USUARIO = $usuario1;
				$BODEGA = $idorigen;
				$EMPRESA = 'CARTIMEX';
				// echo $Transnum;

				if (in_array($iddestino, $LISTA_BODEGAS)) {
					// echo $iddestino;

					$SQL_SERIES = "INSERT INTO COMPUTRONSA..SGO_TRASFERENCIAS_SERIES_COMPUTRON(
							ProductoID,
							Serie_inventario,
							creado_por,
							TrasferenciaID,
							:EMPRESA
						)VALUES(
							:ProductoID,
							:Serie_inventario,
							:creado_por,
							:TrasferenciaID,
							:EMPRESA
					)";
					$result_SERIES_ = $pdo2->prepare($SQL_SERIES);
					$result_SERIES_->bindParam(':ProductoID', $PRODUCTOID, PDO::PARAM_STR);
					$result_SERIES_->bindParam(':Serie_inventario', $SERIE, PDO::PARAM_STR);
					$result_SERIES_->bindParam(':creado_por', $USUARIO, PDO::PARAM_STR);
					$result_SERIES_->bindParam(':TrasferenciaID', $TRANSFERENCIA_ID, PDO::PARAM_STR);
					$result_SERIES_->bindParam(':EMPRESA', $EMPRESA, PDO::PARAM_STR);

					if ($result_SERIES_->execute()) {
						echo "SERIES GUARDADAS";
						$SQL = "INSERT INTO COMPUTRONSA..SGO_TRANSFERENCIAS_SERIES_CARDEX
							(
								Transferencia_id,
								producto_id,
								Serie,
								Egreso,
								bodega_origen,
								bodega_destino,
								usuario,
								bodega,
								EMPRESA
							)VALUES(
								:Transferencia_id,
								:producto_id,
								:Serie,
								:Egreso,
								:bodega_origen,
								:bodega_destino,
								:usuario,
								:bodega,
								:EMPRESA
							)";

						$result_SERIES_CARDEX = $pdo2->prepare($SQL);
						$result_SERIES_CARDEX->bindParam(':Transferencia_id', $TRANSFERENCIA_ID, PDO::PARAM_STR);
						$result_SERIES_CARDEX->bindParam(':producto_id', $PRODUCTOID, PDO::PARAM_STR);
						$result_SERIES_CARDEX->bindParam(':Serie', $SERIE, PDO::PARAM_STR);
						$result_SERIES_CARDEX->bindParam(':Egreso', $EGRESO, PDO::PARAM_STR);
						$result_SERIES_CARDEX->bindParam(':bodega_origen', $BODEGA_ORIGEN, PDO::PARAM_STR);
						$result_SERIES_CARDEX->bindParam(':bodega_destino', $BODEGA_DESTINO, PDO::PARAM_STR);
						$result_SERIES_CARDEX->bindParam(':usuario', $USUARIO, PDO::PARAM_STR);
						$result_SERIES_CARDEX->bindParam(':bodega', $BODEGA, PDO::PARAM_STR);
						$result_SERIES_CARDEX->bindParam(':EMPRESA', $EMPRESA, PDO::PARAM_STR);
						if ($result_SERIES_CARDEX->execute()) {
							echo "CARDEX GUARDADAS";
						}
					} else {
						$err = $result_SERIES_->errorInfo();
						var_dump($err);
					}
				}

				// echo "Transferencia # ".$numerorecibido.$usuario1.$base.$acceso.$nomsuc.$iddestino.$idorigen.$detalle;
				//die(); 					
				if (($arreglo2[$x][4]) == 1) {
					$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result = $pdo->prepare('rma_productos_insert_series_nuevas @serie=:serie, @productoid=:productoid, @BodegaActual=:iddestino, @transferenciaID=:transferenciaid, @CreadoPor=:usuario');
					$result->bindParam(':serie', $arreglo2[$x][2], PDO::PARAM_STR);
					$result->bindParam(':productoid', $arreglo2[$x][1], PDO::PARAM_STR);
					$result->bindParam(':iddestino', $iddestino, PDO::PARAM_STR);
					$result->bindParam(':transferenciaid', $numerorecibido, PDO::PARAM_STR);
					$result->bindParam(':usuario', $usuario1, PDO::PARAM_STR);
					$result->execute();
				} else {

					$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					//Update Bodega Actual de RMA_Productos tanto en CARTIMEX Y COMPUTRONSA
					$result1 = $pdo1->prepare('rma_productos_update_series @iddestino=:iddestino, @productoid=:productoid, @serie=:serie, @transferenciaid=:transferenciaid');
					$result1->bindParam(':iddestino', $iddestino, PDO::PARAM_STR);
					$result1->bindParam(':productoid', $arreglo2[$x][1], PDO::PARAM_STR);
					$result1->bindParam(':serie', $arreglo2[$x][2], PDO::PARAM_STR);
					$result1->bindParam(':transferenciaid', $numerorecibido, PDO::PARAM_STR);
					$result1->bindParam(':usuario', $usuario1, PDO::PARAM_STR);
					//Ejecutar Store 
					$result1->execute();
				}

				$productoid = ($arreglo2[$x][1]);
				$serie = ($arreglo2[$x][2]);
				$anulado = 0;
				$egreso = 0;
				//Inserta el movimiento de ingreso del cardex de series *********************************
				$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result2 = $pdo2->prepare('rma_productos_cardex_series_insert @serie=:serie, @ProductoID=:productoid, @BodegaID=:iddestino, @DocumentoID=:numerorecibido,
										    	@Fecha=:fecha, @Tipo=:tipo, @Detalle=:detalle, @Egreso=:egreso, @Anulado=:anulado, @CreadoPor=:usuario');
				$result2->bindParam(':serie', $serie, PDO::PARAM_STR);
				$result2->bindParam(':productoid', $productoid, PDO::PARAM_STR);
				$result2->bindParam(':iddestino', $iddestino, PDO::PARAM_STR);
				$result2->bindParam(':numerorecibido', $numerorecibido, PDO::PARAM_STR);
				$result2->bindParam(':fecha', $fh, PDO::PARAM_STR);
				$result2->bindParam(':tipo', $tipo, PDO::PARAM_STR);
				$result2->bindParam(':detalle', $detalle, PDO::PARAM_STR);
				$result2->bindParam(':egreso', $egreso, PDO::PARAM_STR);
				$result2->bindParam(':anulado', $anulado, PDO::PARAM_STR);
				$result2->bindParam(':usuario', $usuario1, PDO::PARAM_STR);

				//$result2->execute();

				$egreso = 1;
				$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result3 = $pdo3->prepare('rma_productos_cardex_series_insert @serie=:serie, @ProductoID=:productoid, @BodegaID=:idorigen, @DocumentoID=:numerorecibido,
										    	@Fecha=:fecha, @Tipo=:tipo, @Detalle=:detalle, @Egreso=:egreso, @Anulado=:anulado, @CreadoPor=:usuario');
				$result3->bindParam(':serie', $serie, PDO::PARAM_STR);
				$result3->bindParam(':productoid', $productoid, PDO::PARAM_STR);
				$result3->bindParam(':idorigen', $idorigen, PDO::PARAM_STR);
				$result3->bindParam(':numerorecibido', $numerorecibido, PDO::PARAM_STR);
				$result3->bindParam(':fecha', $fh, PDO::PARAM_STR);
				$result3->bindParam(':tipo', $tipo, PDO::PARAM_STR);
				$result3->bindParam(':detalle', $detalle, PDO::PARAM_STR);
				$result3->bindParam(':egreso', $egreso, PDO::PARAM_STR);
				$result3->bindParam(':anulado', $anulado, PDO::PARAM_STR);
				$result3->bindParam(':usuario', $usuario1, PDO::PARAM_STR);
				//$result3->execute();
				$x++;
			}
			$estado = 'VERIFICADA';
			$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result5 = $pdo5->prepare('UPDATE facturaslistas SET verificado =:usuario , fechaVerificado=:fecha , estado=:estado WHERE factura=:facturaid AND Tipo=:tipo');
			$result5->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
			$result5->bindParam(':usuario', $usuario1, PDO::PARAM_STR);
			$result5->bindParam(':fecha', $fh, PDO::PARAM_STR);
			$result5->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result5->bindParam(':estado', $estado, PDO::PARAM_STR);
			$result5->execute();

			$bultoIngresado = $_POST['bulto'];

			// Actualizar el campo BULTOS en tu base de datos
			$pdo6 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result6 = $pdo6->prepare('UPDATE facturaslistas SET BULTOS = :bulto WHERE factura = :facturaid AND Tipo = :tipo');
			$result6->bindParam(':bulto', $bultoIngresado, PDO::PARAM_STR);
			$result6->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
			$result6->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result6->execute();



		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Verificar Transferencias <?php echo $nomsuc  ?></center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>

		</div>
		<hr>
		<div id="cuerpo2" align="center">
			<p style="font-weight: bold" align="center">
				<font size="6"> Transferencia # <?php echo $numerorecibido ?> completa! </font><br>
			</p>
		</div>
	<?php
			$_SESSION['usuario'] = $usuario1;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['nomsuc'] = $nomsuc;
			// header("Refresh: 1 ; verificartransferencias.php");
		} else {
			// header("location: index.html");
		}
	?>
	</div>
</body>

</html>