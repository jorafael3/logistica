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
        $productoid = trim($_POST["productoid"]);

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
                    if ($productoid == $result[0]["productoid"]) {
                        echo json_encode([1, $result]);
                        exit();
                    } else {
                        echo json_encode([0, $result, "LA SERIE NO PERTENECE AL CODIGO INGRESADO"]);
                        exit();
                    }
                }
            } else {

                $ProductoID = buscar_codigo($serie);
                if ($ProductoID[0] == 1) {

                    if ($ProductoID[1] == $productoid) {
                        Inserta_Serie_RMA($ProductoID[1], $serie, $creado_por);
                    } else {
                        echo json_encode([0, "", "LA SERIE NO PERTENECE AL CODIGO INGRESADO"]);
                    }
                } else {
                    // Buscar_Por_Codigo($factura, $serie);
                    echo json_encode([0, "", "SERIE NO ENCONTRADA, VERIFIQUE EL NUMERO INGRESADO"]);
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


if (isset($_POST['Completar_Factura'])) {

    include('conexion_2.php');
    try {

        $DATOS = $_POST["DATOS"];
        $CLIENTE = trim($_POST["CLIENTE"]);
        $FACTURA = trim($_POST["FACTURA"]);
        $CREADO_POR = trim($_POST["CREADO_POR"]);
        $bodegaFAC = trim($_POST["BODEGA_FAC"]);
        $fecha = date('Ymd');
        $detalle = "Factura de Venta Nro: " . $FACTURA . " Cliente: " . $CLIENTE;
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);

        $pdo->beginTransaction();
        if (count($DATOS) > 0) {
            $query = $pdo->prepare("WEB_RMA_Ventas_Insert 
                @facturaid=:facturaid, 
                @fecha= :fecha,    
                @detalle= :detalle,   
                @creadopor=:creadopor
            ");
            $query->bindParam(':facturaid', $FACTURA, PDO::PARAM_STR);
            $query->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $query->bindParam(':detalle', $detalle, PDO::PARAM_STR);
            $query->bindParam(':creadopor', $CREADO_POR, PDO::PARAM_STR);

            if ($query->execute()) {
                do {
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                } while ($query->nextRowset());
                if (count($result) > 0) {
                    $DT = Guardar_Rma_Dt($DATOS, $result[0]["RMAID"], $CREADO_POR, $FACTURA);
                    if ($DT[0] == 1) {
                        $F = Actualizar_Facturas_listas($FACTURA, $CREADO_POR, $bodegaFAC);
                        if ($F[0] == 1) {
                            $pdo->commit();
                            echo json_encode([1, [1, $result], $DT, $F,]);
                        } else {
                            $pdo->rollBack();
                            echo json_encode([1, [0, $result], $DT, $F,]);
                        }
                    } else {
                        $pdo->rollBack();
                        echo json_encode([0, $result, $DT, []]);
                    }
                } else {
                    echo json_encode([0, $result, [], []]);
                }
            } else {
                $err = $query->errorInfo();
                $pdo->rollBack();
                echo json_encode([0, $err]);
            }
        } else {
            $F = Actualizar_Facturas_listas($FACTURA, $CREADO_POR, $bodegaFAC);
            if ($F[0] == 1) {
                $pdo->commit();
                echo json_encode([1, [1, $result], $DT, $F, "PRODUCTOS SIN SERIE"]);
            } else {
                $pdo->rollBack();
                echo json_encode([1, [0, $result], $DT, $F, "PRODUCTOS SIN SERIE"]);
            }
        }
    } catch (PDOException $e) {
        //return [];
        $e = $e->getMessage();
        $pdo->rollBack();
        echo json_encode($e);
        exit();
    }
}

function Guardar_Rma_Dt($DATOS, $RMAID, $CREADO_POR, $FACTURA)
{
    try {
        include('conexion_2.php');
        $VAL = 0;
        $ARRAY = [];
        foreach ($DATOS as $row) {
            $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
            $query = $pdo->prepare("WEB_RMA_Ventas_Insert_DT 
                @serie=:serie,
                @ProductoID=:productoid, 
                @RmaFacturaID= :rmafacturaid, 
                @creadopor=:creadopor
            ");
            $query->bindParam(':serie', $row["serie"], PDO::PARAM_STR);
            $query->bindParam(':productoid', $row["productoid"], PDO::PARAM_STR);
            $query->bindParam(':rmafacturaid', $RMAID, PDO::PARAM_STR);
            $query->bindParam(':creadopor', $CREADO_POR, PDO::PARAM_STR);

            if ($query->execute()) {
                do {
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                } while ($query->nextRowset());

                $RMA = Actualizar_Rma_Productos($FACTURA, $row["serie"], $row["productoid"], $result[0]["RMAIDDT"]);
                array_push($ARRAY, array("PRODUCTO" => $row["serie"], "RMA" => $RMA));
                $VAL++;
            } else {
                $err = $query->errorInfo();
                array_push($ARRAY, array("PRODUCTO" => $row["serie"], "RMA" => 0), $err);
                // echo json_encode($err);
            }
        }
        if (count($DATOS) == $VAL) {
            return [1, $ARRAY];
        } else {
            return [0, $ARRAY];
        }
    } catch (PDOException $e) {
        //return [];
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}

function Actualizar_Rma_Productos($FACTURA, $SERIE, $PRODUCTO, $RMADTID)
{

    include('conexion_2.php');

    try {

        $estado = 'VENDIDO';

        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("UPDATE RMA_PRODUCTOS 
            SET 
            FACTURAID=:facturaid, 
            ESTADO=:estado,
            RmaDtId = :RmaDtId
            WHERE PRODUCTOID=:productoid 
            and SERIE=:serie");
        $query->bindParam(':facturaid', $FACTURA, PDO::PARAM_STR);
        $query->bindParam(':estado', $estado, PDO::PARAM_STR);
        $query->bindParam(':serie', $SERIE, PDO::PARAM_STR);
        $query->bindParam(':productoid', $PRODUCTO, PDO::PARAM_STR);
        $query->bindParam(':RmaDtId', $RMADTID, PDO::PARAM_STR);
        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return 1;
        } else {
            $err = $query->errorInfo();
            return $err;
        }
    } catch (PDOException $e) {
        $e = $e->getMessage();
        return $e;
    }
}

function Actualizar_Facturas_listas($FACTURA, $CREADO_POR, $bodegaFAC)
{
    include('conexion_2.php');
    try {
        $tipo = 'VEN-FA';

        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("LOG_VERIFICAR_FACTURA_UPDATE  
            @verificado =:usuario, 
            @factura=:facturaid, 
            @tipo=:tipo, 
            @bodega=:bodega");
        $query->bindParam(':facturaid', $FACTURA, PDO::PARAM_STR);
        $query->bindParam(':usuario', $CREADO_POR, PDO::PARAM_STR);
        $query->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $query->bindParam(':bodega', $bodegaFAC, PDO::PARAM_STR);
        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return [1, "FACTURAS LISTAS"];
        } else {
            $err = $query->errorInfo();
            return [0, "FACTURAS LISTAS", $err];
        }
    } catch (PDOException $e) {
        //return [];
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}
