<?php


if (isset($_POST['Cargar_detalle_factura'])) {

    include('conexion_2.php');
    try {

        $secuencia = trim($_POST["secuencia"]);
        $bodega = trim($_POST["bodega"]);
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("{CALL LOG_VERIFICAR_FACTURA1 (?,?)}
            ");
        $query->bindParam(1, $secuencia, PDO::PARAM_STR);
        $query->bindParam(2, $bodega, PDO::PARAM_STR);
        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
            exit();
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

if (isset($_POST['Ingreso_serie'])) {
    include('conexion_2.php');
    try {

        //*** BUSCAR POR SERIE */
        $factura = trim($_POST["factura"]);
        $serie = trim($_POST["serie"]);
        $creado_por = trim($_POST["creado_por"]);
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("{CALL LOG_VERIFICAR_FACTURA3 (?,?)}");
        $query->bindParam(1, $factura, PDO::PARAM_STR);
        $query->bindParam(2, $serie, PDO::PARAM_STR);
        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $ESTADO = trim($result[0]["estado"]);
                if ($ESTADO == "VENDIDO") {
                    Buscar_Factura_NC($serie);
                } else {
                    echo json_encode([1, $result]);
                    exit();
                }
            } else {

                $ProductoID = buscar_codigo($serie);
                if ($ProductoID[0] == 1) {
                    Inserta_Serie_RMA($ProductoID[1], $serie, $creado_por);
                } else {
                    Buscar_Por_Codigo($factura, $serie);
                }
            }
        } else {
            $err = $query->errorInfo();
            echo json_encode($err);
        }
    } catch (PDOException $e) {
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}

function Buscar_Por_Codigo($factura, $serie)
{
    include('conexion_2.php');
    try {

        //*** BUSCAR POR CODIGO */
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("{CALL LOG_VERIFICAR_FACTURA4 (?,?)}");
        $query->bindParam(1, $serie, PDO::PARAM_STR);
        $query->bindParam(2, $factura, PDO::PARAM_STR);
        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $SERIE_ENT = $result[0]["RSeriesEnt"];
                if ($SERIE_ENT == 1) {
                    echo json_encode([1, $result, "PRODUCTO NECESITA SERIE"]);
                } else {
                    echo json_encode([1, $result]);
                }
            } else {
                echo json_encode([0, $result, "NO SE ENCONTRO LA SERIE O CODIGO INGRESADO"]);
            }
        } else {
            $err = $query->errorInfo();
            return [0, $err];
        }
    } catch (PDOException $e) {
        //return [];
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}

function Buscar_Factura_NC($serie)
{
    include('conexion_2.php');
    try {

        //*** BUSCAR POR CODIGO */
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("{CALL SGO_Inv_buscar_series_productos (?)}");
        $query->bindParam(1, $serie, PDO::PARAM_STR);
        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                echo json_encode([1, $result, "VENDIDA"]);
            } else {
                echo json_encode([1, $result, ""]);
            }
        } else {
            $err = $query->errorInfo();
            return [0, $err];
        }
    } catch (PDOException $e) {
        //return [];
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}

function buscar_codigo($Serie)
{
    try {
        include('conexion_2.php');
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("SELECT 
        Código as codigo, 
        ID 
        FROM CARTIMEX..SERIESSGLCARTIMEX s 
        INNER JOIN INV_PRODUCTOS P WITH (NOLOCK) 
        ON S.PRODUCTO = P.ID 
        WHERE s.SERIE = :Serie");
        $query->bindParam(":Serie", $Serie, PDO::PARAM_STR);
        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                return  [1, $result[0]["ID"]];
            } else {
                return  [0, 0, "SERIE NO ENCONTRADA SGLCARTIMEX"];
            }
        } else {
            $err = $query->errorInfo();
            return  [0, $err, "SERIE NO ENCONTRADA SGLCARTIMEX"];
        }
    } catch (PDOException $e) {
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}

function Inserta_Serie_RMA($ProductoID, $Serie, $CreadoPor)
{
    try {
        include('conexion_2.php');
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("EXEC COMPUTRONSA..SGO_INV_INSERT_SERIES_RMA 
            @Serie = :Serie,
            @ProductoID = :ProductoID, 
            @CreadoPor = :CreadoPor
        ");
        $query->bindParam(":ProductoID", $ProductoID, PDO::PARAM_STR);
        $query->bindParam(":Serie", $Serie, PDO::PARAM_STR);
        $query->bindParam(":CreadoPor", $CreadoPor, PDO::PARAM_STR);
        if ($query->execute()) {
            $result = [
                array("serie" => $Serie, "productoid" => $ProductoID)
            ];

            echo json_encode([1, $result]);
            exit();
        } else {
            $err = $query->errorInfo();
            echo json_encode($err);
            exit();
        }
    } catch (PDOException $e) {
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}


function Validar_Serie_Entrada()
{
    include('conexion_2.php');
    try {

        $secuencia = trim($_POST["secuencia"]);
        $bodega = trim($_POST["bodega"]);
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("SELECT RSeriesEnt FROM INV_PRODUCTOS WHERE ID = :ID");
        $query->bindParam(1, $secuencia, PDO::PARAM_STR);
        $query->bindParam(2, $bodega, PDO::PARAM_STR);
        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($result);
            exit();
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
