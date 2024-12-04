<?php

if (isset($_POST['REVERSAR_TR'])) {
    include('conexion_2.php');

    try {

        $ID = trim($_POST["ID"]);
        $usuario = $_POST["usuario"];


        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $SQL = "{CALL LOG_PREPARARTRANSFERENCIAS_ELIMINAR (?,?)}";
        $pdo->beginTransaction();

        $query = $pdo->prepare($SQL);

        $query->bindParam(1, $ID, PDO::PARAM_STR);
        $query->bindParam(2, $usuario, PDO::PARAM_STR);

        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            $rowsAffected = $query->rowCount();
            $res = array(
                "success" => true,
                "SQL" => $SQL,
                "rowsAffected" => $rowsAffected,
                "ID" => $ID
            );
            $pdo->commit();
            echo json_encode($res);
        } else {
            $err = $query->errorInfo();
            $res = array(
                "success" => false,
                "SQL" => $query,
                "mensaje" => $err
            );
            $pdo->rollBack();
            echo json_encode($res);
        }
    } catch (PDOException $e) {
        //return [];
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}
