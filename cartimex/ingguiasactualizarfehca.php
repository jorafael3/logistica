<?php
include('../conexion_mssql.php');

session_start();
$usuario = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (isset($_POST['fechaRetiro']) && isset($_POST['facturaId'])) {
		// Bloque para actualizar la fecha de envío
		$fechaRetiro = $_POST['fechaRetiro'];
		$facturaId = $_POST['facturaId'];

		try {
			$pdo = new PDO("sqlsrv:server=$sql_serverName;Database=$sql_database", $sql_user, $sql_pwd);
			$query = "UPDATE FACTURASLISTAS SET Fechaenviar = :Fechaenviar, EnvioPor = :usuario WHERE Factura = :FacturaID";
			$stmt = $pdo->prepare($query);
			$stmt->bindParam(':Fechaenviar', $fechaRetiro, PDO::PARAM_STR);
			$stmt->bindParam(':FacturaID', $facturaId, PDO::PARAM_STR);
			$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
			$stmt->execute();

			echo json_encode(['status' => 'success']);
		} catch (PDOException $e) {
			echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
		}
		exit;

		
	} elseif (isset($_POST['TipoPedido']) && isset($_POST['facturaId'])) {
		
		$TipoPedido = $_POST['TipoPedido'];
		$facturaId = $_POST['facturaId'];

		try {
			$pdo = new PDO("sqlsrv:server=$sql_serverName;Database=$sql_database", $sql_user, $sql_pwd);
			$query = "UPDATE FACTURASLISTAS SET TipoPedido = :TipoPedido WHERE Factura = :facturaId";
			$stmt = $pdo->prepare($query);

			$stmt->bindParam(':TipoPedido', $TipoPedido, PDO::PARAM_STR);
			$stmt->bindParam(':facturaId', $facturaId, PDO::PARAM_STR);
			$stmt->execute();

			echo json_encode(['status' => 'success']);
		} catch (PDOException $e) {
			echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
		}
		exit;
	}
}
