<?php



if (isset($_POST['Cargar_Documentos'])) {


	try {
        include('conexion_2.php');
        $FACTID = $_POST["ID"];
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $sql = "SELECT * FROM SGL_DROPSHIPING_DOCUMENTOS
			where factura_id = :factura_id";
        $query = $pdo->prepare($sql);
		$query->bindParam(':factura_id', $FACTID, PDO::PARAM_STR);

        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
           
        } else {
            $err = $query->errorInfo();
            echo json_encode($err);
        }
    } catch (PDOException $e) {
        //return [];
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}

if (isset($_POST['Borrar'])) {


	try {
        include('conexion_2.php');
        $FACTID = $_POST["ID"];
        $archivo = $_POST["archivo"];
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $sql = "DELETE FROM SGL_DROPSHIPING_DOCUMENTOS
			where factura_id = :factura_id and archivo = :archivo";
        $query = $pdo->prepare($sql);
		$query->bindParam(':factura_id', $FACTID, PDO::PARAM_STR);
		$query->bindParam(':archivo', $archivo, PDO::PARAM_STR);

        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
           
        } else {
            $err = $query->errorInfo();
            echo json_encode($err);
        }
    } catch (PDOException $e) {
        //return [];
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}




