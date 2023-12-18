<?php



function Archivo()
{
	include('conexion_2.php');

	if (($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/png")
		|| ($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "application/pdf")
	) {

		$SO = PHP_OS;
		if ($SO  == "Linux") {
			$destination_folder = '/var/www/html/sgo_docs/SGL/dropshiping/puerta_puerta/';
		} else {
			$destination_folder = 'C:\xampp\htdocs\logistica\puerta/';
		}

		// $destination_folder = '/var/www/html/cdn/samsung/';
		$tipo = $_FILES['file']['type'];
		$tipo = explode("/", $tipo);
		$tipo = $tipo[1];
		$fileName = $_FILES['file']['name'];
		$orden = explode("_", $fileName)[0];
		// echo $orden;
		// echo $destination_folder;
		// var_dump($_FILES['file']);
		// echo $orden[0];
		// chmod($destination_folder, 0755);
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $destination_folder . $fileName)) {
			// $log = $this->model->Guardar_adjuntos($fileName, $orden[0], $TIPO_IMG);
			$pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
			$sql_nc = "INSERT into SGL_DROPSHIPING_DOCUMENTOS
				(
					factura_id,
					archivo
				)VALUES(
					:factura_id,
					:archivo
				)";
			$query = $pdo->prepare($sql_nc);
			$query->bindParam(':factura_id', $orden, PDO::PARAM_STR);
			$query->bindParam(':archivo', $fileName, PDO::PARAM_STR);
			if ($query->execute()) {
				echo json_encode([1, "Archivo guardado"]);
			} else {
				echo json_encode([0, "Error al guardar"]);
			}
		} else {
			echo 0;
		}
	}
}




Archivo();
