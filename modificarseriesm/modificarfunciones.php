<?php
require('conexion.php');

if (isset($_POST['consultarFactura'])) {

    $secu = $_POST['secu'];
    $bodega = $_POST['bodega'];
    $acceso = $_POST['acceso'];
    $query = $pdo->prepare('{CALL LOG_BUSQUEDA_FACTURA (?) }');
    $query->bindParam(1, $secu, PDO::PARAM_STR);
    if ($query->execute()) {
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) > 0) {
            $fa = VALIDAR_RMA_($result[0]["Id"]);
            $orden = json_encode([$result, $fa]);
            echo $orden;
        } else {
            $orden = json_encode([$result, 0]);
            echo $orden;
        }
    } else {
        $err = $query->errorInfo();
        $orden = json_encode($err);
        echo $orden;
    }
}

function VALIDAR_RMA_($ID)
{
    require('conexion.php');
    $query = $pdo->prepare('SELECT * FROM RMA_FACTURAS with(NOLOCK) WHERE facturaid = :facturaid');
    $query->bindParam(":facturaid", $ID, PDO::PARAM_STR);
    if ($query->execute()) {
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) > 0) {
            $f = RMA_FACTURAS($ID);
            return [$f, "RMA-OK"];
        } else {
            // $f = VEN_FACTURAS($ID);
            $f = [];
            return [$f, "RMA-NO"];
        }
    } else {
        $err = $query->errorInfo();
        return $err;
    }
}

function VEN_FACTURAS($secu)
{
    require('conexion.php');
    $bo = "";
    $query = $pdo->prepare('{CALL LOG_BUSQUEDA_FACTURA_DT_SERIES (?) }');
    $query->bindParam(1, $secu, PDO::PARAM_STR);
    if ($query->execute()) {
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } else {
        $err = $query->errorInfo();
        return $err;
    }
}

function RMA_FACTURAS($secu)
{
    require('conexion.php');
    $bo = "";
    $query = $pdo->prepare('{CALL LOG_BUSQUEDA_FACTURA_DT_SERIES (?) }');
    $query->bindParam(1, $secu, PDO::PARAM_STR);
    if ($query->execute()) {
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } else {
        $err = $query->errorInfo();
        return $err;
    }
}

// if (isset($_POST['validar_serie'])) {
//     $serie = $_POST['serie'];
//     $ProductoId = $_POST['ProductoId'];
//     $query = $pdo->prepare('SELECT Serie,estado from CARTIMEX..RMA_PRODUCTOS
//         WHERE Serie = :serie AND ProductoID = :ProductoID');
//     $query->bindParam(":serie", $serie, PDO::PARAM_STR);
//     $query->bindParam(":ProductoID", $ProductoId, PDO::PARAM_STR);
//     if ($query->execute()) {
//         $result = $query->fetchAll(PDO::FETCH_ASSOC);
//         if (count($result) > 0) {
//             $result[0]["base"] = "CARTIMEX";
//             $orden = json_encode($result);
//             echo $orden;
//         } else {
//             $cp =  VAlidar_Serie_bd_Computron($serie, $ProductoId);
//             $orden = json_encode($cp);
//             echo $orden;
//         }
//     } else {
//         $err = $query->errorInfo();
//         $orden = json_encode($err);
//         echo $orden;
//     }
// }

if (isset($_POST['validar_serie'])) {
    $serie = $_POST['serie'];
    $ProductoId = $_POST['ProductoId'];
    $query = $pdo->prepare("SELECT bd = 'COMPUTRONSA',se.*,
    Estado = isnull(r.Estado,'NO-RMA'),FacturaID = isnull(r.FacturaID,'') 
    from CARTIMEX..INV_PRODUCTOS_SERIES_COMPRAS se with(NOLOCK)
    left outer join COMPUTRONSA.dbo.RMA_PRODUCTOS r with(NOLOCK)
    on r.Serie = se.serie and se.ProductoID = r.ProductoID
    where se.ProductoID = :ProductoID
    and se.serie = :serie");

    $query->bindParam(":serie", $serie, PDO::PARAM_STR);
    $query->bindParam(":ProductoID", $ProductoId, PDO::PARAM_STR);
    if ($query->execute()) {
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) > 0) {
            $orden = json_encode($result);
            echo $orden;
            // $cp =  VAlidar_Serie_bd_Computron($serie, $ProductoId);
            // $orden = json_encode($cp);
            // echo $orden;
        } else {
            $cp =  BUSCAR_RMA_FACTURSA_DT($serie, $ProductoId);
            $orden = json_encode($cp);
            echo $orden;
        }
    } else {
        $err = $query->errorInfo();
        $orden = json_encode($err);
        echo $orden;
    }
}

function BUSCAR_RMA_FACTURSA_DT($serie, $ProductoId)
{
    require('conexion.php');
    $query = $pdo->prepare('SELECT * from COMPUTRONSA..RMA_FACTURAS_DT
    where Serie = :serie and ProductoID = :ProductoID');
    $query->bindParam(":serie", $serie, PDO::PARAM_STR);
    $query->bindParam(":ProductoID", $ProductoId, PDO::PARAM_STR);
    if ($query->execute()) {
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) > 0) {
            $result[0]["base"] = "COMPUTRONSA";
            $result[0]["Estado"] = "VENDIDO";
            $orden = ($result);
            return $orden;
        } else {
            $orden = BUSCAR_RMA_PRODUCTOS($serie, $ProductoId);
            return [$orden];
        }
    } else {
        $err = $query->errorInfo();
        $orden = json_encode($err);
        echo $orden;
    }
}

function BUSCAR_RMA_PRODUCTOS($serie, $ProductoId)
{
    require('conexion.php');
    $query = $pdo->prepare('SELECT * from COMPUTRONSA..RMA_PRODUCTOS with(NOLOCK)
    where Serie = :serie and ProductoID = :ProductoID');
    $query->bindParam(":serie", $serie, PDO::PARAM_STR);
    $query->bindParam(":ProductoID", $ProductoId, PDO::PARAM_STR);
    if ($query->execute()) {
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result) > 0) {
            $result[0]["base"] = "COMPUTRONSA";
            // $result[0]["Estado"] = "VENDIDO";
            $orden = $result[0];
            return $orden;
        } else {
            $orden = (array("serie" => "", "bd" => "COMPUTRON", "Estado" => "NO-ENCONTRADA"));
            return $orden;
        }
    } else {
        $err = $query->errorInfo();
        $orden = json_encode($err);
        echo $orden;
    }
}

// function VAlidar_Serie_bd_Computron($serie, $ProductoId)
// {
//     require('conexion.php');
//     $query = $pdo->prepare('SELECT Serie,estado from COMPUTRONSA..RMA_PRODUCTOS
//     WHERE Serie = :serie AND ProductoID = :ProductoID');
//     $query->bindParam(":serie", $serie, PDO::PARAM_STR);
//     $query->bindParam(":ProductoID", $ProductoId, PDO::PARAM_STR);
//     if ($query->execute()) {
//         $result = $query->fetchAll(PDO::FETCH_ASSOC);
//         if (count($result) > 0) {
//             // if (trim($result[0]["Estado"]) == "VENDIDO") {
//             //     $orden = (array("serie" => $result[0]["Serie"], "bd" => "COMPUTRON", "estado" => "VENDIDA"));
//             //     return $orden;
//             // } else {re
//             //     $orden = (array("serie" => $result[0]["Serie"], "bd" => "COMPUTRON", "estado" => true));
//             //     return $orden;
//             // }
//             $result[0]["base"] = "COMPUTRONSA";
//             $orden = ($result);
//             return $orden;
//         } else {
//             $orden = (array("serie" => "", "bd" => "COMPUTRON", "estado" => false));
//             return [$orden];
//         }
//     } else {
//         $err = $query->errorInfo();
//         $orden = json_encode($err);
//         echo $orden;
//     }
// }


if (isset($_POST['guardar_serie'])) {
    $serie = $_POST['serie'];
    $ProductoId = $_POST['ProductoId'];
    $RMAID = $_POST['RMAID'];
    $Creado_por = $_POST['Creado_por'];
    $estado = (int)$_POST['estado'];
    $Factura_id = $_POST['Factura_id'];
    $RMADT_ID = $_POST['RMADT_ID'];
    $base = $_POST['base'];
    $VAL_RMA = $_POST['RMA'];
    // 1 ACTUALIZAR
    if ($estado == 1) {
        $serie_anterior =  $_POST['serie_anterior'];
        $gu = ACTUALIZAR_RMA_FACTURAS_DT($serie, $ProductoId, $RMAID, $Creado_por, $Factura_id, $RMADT_ID, $base, $serie_anterior, $VAL_RMA);
        $orden = json_encode($gu);
        echo $orden;
        // 0 CREAR
    } else if ($estado == 0) {
        $gu = GUARDAR_NUEVO_RMA_FACTURA_DT($serie, $ProductoId, $RMAID, $Creado_por, $Factura_id, $RMADT_ID, $base, $VAL_RMA);
        $orden = json_encode($gu);
        echo $orden;
    }
}
//********************************************* */
function GUARDAR_NUEVO_RMA_FACTURA_DT($serie, $ProductoId, $RMAID, $Creado_por, $Factura_id, $RMADT_ID, $base, $VAL_RMA)
{
    require('conexion.php');
    $pdo->beginTransaction();
    $query = $pdo->prepare('{CALL WEB_RMA_Ventas_Insert_DT (?,?,?,?)}');
    $query->bindParam(1, $serie, PDO::PARAM_STR);
    $query->bindParam(2, $ProductoId, PDO::PARAM_STR);
    $query->bindParam(3, $RMAID, PDO::PARAM_STR);
    $query->bindParam(4, $Creado_por, PDO::PARAM_STR);
    if ($query->execute()) {
        if ($VAL_RMA == "NO-RMA") {
            $VAL = NUEVO_RMA_PRODUCTO($serie, $Factura_id, $ProductoId, $Creado_por, $RMADT_ID, $serie_anterior);
            if ($VAL["NUEVO_RMA_PRODUCTO"] == "OK") {
                $pdo->commit();
            } else {
                $pdo->rollBack();
            }
        } else {
            $VAL = ACTUALIZAR_RMA_PRODUCTO($Factura_id, $RMADTID, $ProductoId, $serie, $base);
            if ($VAL["ACTUALIZAR_RMA_PRODUCTO"] == "OK") {
                $pdo->commit();
            } else {
                $pdo->rollBack();
            }
        }
        return array("GUARDAR_NUEVO_RMA_FACTURA_DT" => "OK", "RMA_PRODUCTO" => $VAL);
    } else {
        $pdo->rollBack();
        $err = $query->errorInfo();
        return array("GUARDAR_NUEVO_RMA_FACTURA_DT" => $err);
    }
}

function ACTUALIZAR_RMA_FACTURAS_DT($serie, $ProductoId, $RMAID, $Creado_por, $Factura_id, $RMADT_ID, $base, $serie_anterior, $VAL_RMA)
{
    require('conexion.php');
    $pdo->beginTransaction();
    $query = $pdo->prepare('UPDATE RMA_FACTURAS_DT
        SET
            Serie = :Serie
        WHERE
            ID = :ID');
    $query->bindParam(":Serie", $serie, PDO::PARAM_STR);
    $query->bindParam(":ID", $RMADT_ID, PDO::PARAM_STR);
    if ($query->execute()) {
        if ($VAL_RMA == "NO-RMA") {
            $VAL = NUEVO_RMA_PRODUCTO($serie, $Factura_id, $ProductoId, $Creado_por, $RMADT_I, $serie_anterior);
            if ($VAL["NUEVO_RMA_PRODUCTO"] == "OK") {
                $pdo->commit();
            } else {
                $pdo->rollBack();
            }
        } else {
            $VAL = ACTUALIZAR_RMA_PRODUCTO($Factura_id, $RMADTID, $ProductoId, $serie, $base, $serie_anterior);
            //$VAL = ACTUALIZAR_RMA_PRODUCTO_INVENTARIO($Factura_id, $RMADTID, $ProductoId, $serie, $base, $serie_anterior);
            if ($VAL["ACTUALIZAR_RMA_PRODUCTO"] == "OK") {
                $pdo->commit();
                $VAL2 = ACTUALIZAR_RMA_PRODUCTO_INVENTARIO($Factura_id, $RMADTID, $ProductoId, $serie, $base, $serie_anterior);
            } else {
                $pdo->rollBack();
            }
        }
        return array("ACTUALIZAR_RMA_FACTURAS_DT" => "OK", "VAL_RMA" => [$VAL, $VAL2]);
    } else {
        $pdo->rollBack();
        $err = $query->errorInfo();
        return array("GUARDADO_DT" => $err);
    }
}
//**********************************************/


function NUEVO_RMA_PRODUCTO($serie, $Factura_id, $ProductoId, $Creado_por, $RMADT_ID, $serie_anterior)
{
    require('conexion.php');
    $pdo->beginTransaction();
    $estado = 'VENDIDO';
    $fecha = date('Ymd');
    $query = $pdo->prepare('INSERT INTO COMPUTRONSA..RMA_PRODUCTOS
    ( Serie , FacturaID, Estado , ProductoID, CREADOPOR, CREADODATE, RMADTID) 
        VALUES
    (:serie,:facturaid,:estado,:productoid,:creadopor,:fechacreado,:rmadtid)');
    $query->bindParam(':serie', $serie, PDO::PARAM_STR);
    $query->bindParam(':facturaid', $Factura_id, PDO::PARAM_STR);
    $query->bindParam(':estado', $estado, PDO::PARAM_STR);
    $query->bindParam(':productoid', $ProductoId, PDO::PARAM_STR);
    $query->bindParam(':creadopor', $Creado_por, PDO::PARAM_STR);
    $query->bindParam(':fechacreado', $fecha, PDO::PARAM_STR);
    $query->bindParam(':rmadtid', $RMADT_ID, PDO::PARAM_STR);
    if ($query->execute()) {
        $VAL = ACTUALIZAR_RMA_PRODUCTO_INVENTARIO($Factura_id, $RMADTID, $ProductoId, $serie, $base, $serie_anterior);
        if ($VAL["ACTUALIZAR_RMA_PRODUCTO_INVENTARIO"] == "OK") {
            $pdo->commit();
            return array("NUEVO_RMA_PRODUCTO" => "OK", "VAL_RMA" => $VAL);
        } else {
            $pdo->rollBack();
            return array("NUEVO_RMA_PRODUCTO" => "ERR", "VAL_RMA" => $VAL);
        }
    } else {
        $pdo->rollBack();
        $err = $query->errorInfo();
        return array("NUEVO_RMA_PRODUCTO" => $err);
    }
}

function ACTUALIZAR_RMA_PRODUCTO($Factura_id, $RMADTID, $ProductoId, $serie, $base)
{
    require('conexion.php');
    $estado = "VENDIDO";
    $pdo->beginTransaction();
    $vacio = '';
    // if ($base == "CARTIMEX") {
    //     $sql = 'UPDATE CARTIMEX..RMA_PRODUCTOS 
    //     SET 
    //         FACTURAID=:facturaid, 
    //         ESTADO=:estado,
    //         RMADTID = :RMADTID
    //         WHERE PRODUCTOID=:productoid 
    //             and SERIE=:serie and FacturaID = :FacturaID';
    // } else {
    $sql = 'UPDATE COMPUTRONSA..RMA_PRODUCTOS 
        SET 
            FACTURAID=:facturaid, 
            ESTADO=:estado,
            RMADTID = :RMADTID
            WHERE PRODUCTOID=:productoid 
                and SERIE=:serie';
    // }
    // print_r($sql);

    $query = $pdo->prepare($sql);
    $query->bindParam(":facturaid", $Factura_id, PDO::PARAM_STR);
    $query->bindParam(":estado", $estado, PDO::PARAM_STR);
    $query->bindParam(":RMADTID", $RMADTID, PDO::PARAM_STR);
    $query->bindParam(":productoid", $ProductoId, PDO::PARAM_STR);
    $query->bindParam(":serie", $serie, PDO::PARAM_STR);
    $query->bindParam(":FacturaID", $vacio, PDO::PARAM_STR);
    if ($query->execute()) {
        // if ($VAL["ACTUALIZAR_RMA_PRODUCTO_INVENTARIO"] == "OK") {
        $pdo->commit();
        return array("ACTUALIZAR_RMA_PRODUCTO" => "OK", "VAL_RMA" => $VAL);
        // } else {
        //     $pdo->rollBack();
        //     return array("ACTUALIZAR_RMA_PRODUCTO" => "ERR", "VAL_RMA" => $VAL);
        // }
        // $pdo->commit();
        // return array("ACTUALIZAR_RMA_PRODUCTO" => "OK");
    } else {
        $pdo->rollBack();
        $err = $query->errorInfo();
        return array("ACTUALIZAR_RMA_PRODUCTO" => $err);
    }
}
//********************************************* */

function ACTUALIZAR_RMA_PRODUCTO_INVENTARIO($Factura_id, $RMADTID, $ProductoId, $serie, $base, $serie_anterior)
{
    require('conexion.php');
    $estado = "INVENTARIO";
    $Factura = "";
    $RMADTID = "";
    $pdo->beginTransaction();
    // if ($base == "CARTIMEX") {
    //     $sql = 'UPDATE CARTIMEX..RMA_PRODUCTOS 
    //     SET 
    //         FACTURAID=:facturaid, 
    //         ESTADO=:estado,
    //         RMADTID = :RMADTID
    //         WHERE PRODUCTOID=:productoid 
    //             and SERIE=:serie';
    // } else {
    $sql = 'UPDATE COMPUTRONSA..RMA_PRODUCTOS 
        SET 
            FACTURAID=:facturaid, 
            ESTADO=:estado,
            RMADTID = :RMADTID
            WHERE PRODUCTOID=:productoid
                and SERIE=:serie';
    // }
    // print_r($sql);

    $query = $pdo->prepare($sql);
    $query->bindParam(":facturaid", $Factura, PDO::PARAM_STR);
    $query->bindParam(":estado", $estado, PDO::PARAM_STR);
    $query->bindParam(":RMADTID", $RMADTID, PDO::PARAM_STR);
    $query->bindParam(":productoid", $ProductoId, PDO::PARAM_STR);
    $query->bindParam(":serie", $serie_anterior, PDO::PARAM_STR);
    // $query->bindParam(":FACTURAID", $Factura_id, PDO::PARAM_STR);
    if ($query->execute()) {
        $pdo->commit();
        return array("ACTUALIZAR_RMA_PRODUCTO_INVENTARIO" => "OK");
    } else {
        $pdo->rollBack();
        $err = $query->errorInfo();
        return array("ACTUALIZAR_RMA_PRODUCTO_INVENTARIO" => $err);
    }
}
