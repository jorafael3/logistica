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
			$bloqueado = $_SESSION['bloqueo'];
			$nota = $_SESSION['nota'];
			$usuario = $_SESSION['usuario'];
			$Id = $_SESSION['id'];
			$base = $_SESSION['base'];
			$acceso = $_SESSION['acceso'];
			$codigo = $_SESSION['codigo'];
			$nomsuc = $_SESSION['nomsuc'];
			$cliente = $_SESSION['cliente'];
			$numerorecibido = $_SESSION['factura'];
			$pedido = $_SESSION['tipotrans'];
			//echo $usuario;
			$usuario1 = $usuario;
			date_default_timezone_set('America/Guayaquil');
			$fecha = date("Ymd", time());
			$hora = date("H:i:s", time());
			$fh = $fecha . " " . $hora;

			//echo "envio  # ".  $pedido."<br>";
			//die();
			//$arreglo2 = $_SESSION['arregloseries'];

			if ($base == 'CARTIMEX') {
				require '../headcarti.php';
			} else {
				require '../headcompu.php';
				$_SESSION['base'] = $base;
				$Nota = " ";
			}
			require('../conexion_mssql.php');
			$detalle = "Factura de Venta Nro:" . $numerorecibido . " Cliente:" . $cliente;
			$arreglo2 = $_SESSION['arregloseries']; // Cargo el arreglo de la memoria
			$xx = count($arreglo2);
			//echo '<pre>', print_r($arreglo2),'</pre>';
			//die();
			/* ************ Si lleva series graba rma_facturas cabecera y detalle y el rma_productos************** */
			if ($xx > 0) {


				//***Grabar cabecera de RMA_facturas
				$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result = $pdo->prepare('WEB_RMA_Ventas_Insert @facturaid=:facturaid, @detalle= :detalle, @creadopor=:creadopor');
				$result->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
				$result->bindParam(':detalle', $detalle, PDO::PARAM_STR);
				$result->bindParam(':creadopor', $usuario1, PDO::PARAM_STR);
				$result->execute();

				//Consulto el rmaid q se grabo en la cabecera
				$pdo1 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
				$result1 = $pdo1->prepare('select id from rma_facturas where facturaid=:facturaid');
				$result1->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
				$result1->execute();
				$row1 = $result1->fetch(PDO::FETCH_ASSOC);
				$rmafacturaid = $row1['id'];

				//***Grabar detalle de RMA_facturas_dt
				$x = 0;
				while ($x < count($arreglo2)) {
					$pdo2 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result2 = $pdo2->prepare('WEB_RMA_Ventas_Insert_DT @serie=:serie, @ProductoID=:productoid, @RmaFacturaID= :rmafacturaid, @creadopor=:creadopor');
					$result2->bindParam(':serie', $arreglo2[$x][2], PDO::PARAM_STR);
					$result2->bindParam(':productoid', $arreglo2[$x][1], PDO::PARAM_STR);
					$result2->bindParam(':rmafacturaid', $rmafacturaid, PDO::PARAM_STR);
					$result2->bindParam(':creadopor', $usuario1, PDO::PARAM_STR);
					$result2->execute();

					//Consulto el rmaDTID q se grabo en la DETALLE
					$pdo7 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
					$result7 = $pdo7->prepare('select id from rma_facturas_dt where facturaid=:facturaid and productoid=:productoid and serie=:serie ');
					$result7->bindParam(':facturaid', $rmafacturaid, PDO::PARAM_STR);
					$result7->bindParam(':productoid', $arreglo2[$x][1], PDO::PARAM_STR);
					$result7->bindParam(':serie', $arreglo2[$x][2], PDO::PARAM_STR);
					$result7->execute();
					$row7 = $result7->fetch(PDO::FETCH_ASSOC);
					$rmadtid = $row7['id'];

					$estado = 'VENDIDO';
					if ($arreglo2[$x][4] == 0) {
						//echo "Entra aqui si hay q actualizar" . $arreglo2[$x][4];
						$pdo3 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						$result3 = $pdo3->prepare('UPDATE RMA_PRODUCTOS SET FACTURAID=:facturaid , ESTADO=:estado WHERE PRODUCTOID=:productoid and SERIE=:serie and RMADTID =:rmadtid ');
						$result3->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
						$result3->bindParam(':estado', $estado, PDO::PARAM_STR);
						$result3->bindParam(':serie', ltrim(rtrim($arreglo2[$x][2])), PDO::PARAM_STR);
						$result3->bindParam(':productoid', $arreglo2[$x][1], PDO::PARAM_STR);
						$result3->bindParam(':rmadtid', $rmadtid, PDO::PARAM_STR);
						$result3->execute();
					} else {
						//echo "Entra aqui si hay q insertar " . $arreglo2[$x][2]."-".$numerorecibido."-".$estado."-".$arreglo2[$x][1]."-".$usuario1."-".$fecha."-".$rmadtid;
						$pdo4 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						$result4 = $pdo4->prepare('INSERT INTO RMA_PRODUCTOS ( SERIE , FACTURAID, ESTADO , PRODUCTOID, CREADOPOR, CREADODATE, RMADTID) VALUES(:serie,:facturaid,:estado,:productoid,:creadopor,:fechacreado,:rmadtid)');
						$result4->bindParam(':serie', ltrim(rtrim($arreglo2[$x][2])), PDO::PARAM_STR);
						$result4->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
						$result4->bindParam(':estado', $estado, PDO::PARAM_STR);
						$result4->bindParam(':productoid', $arreglo2[$x][1], PDO::PARAM_STR);
						$result4->bindParam(':creadopor', $usuario1, PDO::PARAM_STR);
						$result4->bindParam(':fechacreado', $fecha, PDO::PARAM_STR);
						$result4->bindParam(':rmadtid', $rmadtid, PDO::PARAM_STR);
						$result4->execute();
					}

					/*$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
						$result5 = $pdo5->prepare('UPDATE INV_PRODUCTOS_SERIES_COMPRAS SET FVentaId =:facturaid  WHERE PRODUCTOID=:productoid and SERIE=:serie');
						$result5->bindParam(':facturaid',$numerorecibido,PDO::PARAM_STR);
						$result5->bindParam(':productoid',$arreglo2[$x][1],PDO::PARAM_STR);
						$result5->bindParam(':serie',ltrim(rtrim($arreglo2[$x][2])),PDO::PARAM_STR);
						$result5->execute();
						*/
					$x++;
				}
			}


			$tipo = 'VEN-FA';
			$bultoIngresado = $_POST['bulto'];
			$FIRMA = $_POST['firma'];
			// echo $FIRMA;

			// Actualizar el campo BULTOS en tu base de datos
			$pdo6 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result6 = $pdo6->prepare('UPDATE facturaslistas 
			SET 
				BULTOS = :bulto,
				FIRMA = :firma 
			WHERE factura = :facturaid AND Tipo = :tipo');
			$result6->bindParam(':bulto', $bultoIngresado, PDO::PARAM_STR);
			$result6->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
			$result6->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result6->bindParam(':firma', $FIRMA, PDO::PARAM_STR);
			if ($result6->execute()) {
				// echo "asdasd";
			} else {
				$err = $result6->errorInfo();
				var_dump($err);
			}

			$pdo5 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result5 = $pdo5->prepare('LOG_VERIFICAR_FACTURA_UPDATE  @verificado =:usuario, @factura=:facturaid, @tipo=:tipo, @tipotrans=:tipotrans');
			$result5->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
			$result5->bindParam(':usuario', $usuario1, PDO::PARAM_STR);
			$result5->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$result5->bindParam(':tipotrans', $pedido, PDO::PARAM_STR);
			$result5->execute();

			//para borrar la tabla temporal de series SEERIESSGLCARTIMEX

			$pdo8 = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$result8 = $pdo8->prepare('DELETE FROM SERIESSGLCARTIMEX WHERE FACTURAID =:facturaid');
			$result8->bindParam(':facturaid', $numerorecibido, PDO::PARAM_STR);
			$result8->execute();

		?>
	</div>
	<div id="Cuerpo">
		<div id="cuerpo2">

			<div id="izq"> </div>
			<div id="centro"> <a class="titulo">
					<center> Verificar Facturas <?php echo substr($nomsuc, 10, 20);  ?></center>
				</a></div>
			<div id="derecha"> <a href="../menu.php"><img src="..\assets\img\home.png"></a> </div>
		</div>
	</div>
	<hr>
	<div id="cuerpo2" align="center">

		<p style="font-weight: bold" align="center">
			<font size="6"> Factura # <?php echo $numerorecibido ?> completa! </font><br>
		</p>
	</div>
<?php
			if ($pedido == 'DESPACHAR') {
				$_SESSION['cliente'] = $cliente;
				$_SESSION['usuario'] = $usuario1;
				$_SESSION['id'] = $Id;
				$_SESSION['base'] = $base;
				$_SESSION['acceso'] = $acceso;
				$_SESSION['codigo'] = $codigo;
				$_SESSION['nomsuc'] = $nomsuc;
				$_SESSION['numfac'] = $numerorecibido;
				header("Refresh: 0 ; maildespachoyseries.php");
			} else {
				header("Refresh: 0 ; verificarfacturas.php");
			}
			//echo "<h1>Factura completa!</h1>";
			$_SESSION['cliente'] = $cliente;
			$_SESSION['usuario'] = $usuario1;
			$_SESSION['id'] = $Id;
			$_SESSION['base'] = $base;
			$_SESSION['acceso'] = $acceso;
			$_SESSION['codigo'] = $codigo;
			$_SESSION['nomsuc'] = $nomsuc;
			$_SESSION['numfac'] = $numerorecibido;
		} else {
			header("location: index.html");
		}

?>
</div>
</body>

</html>