<?php

// include("conexion.php");

if (isset($_POST['Cargar_guias'])) {
    include('conexion_2.php');

    try {

        $bodega = $_POST["bodega"];
        $acceso = $_POST["acceso"];
        $drop = $_POST["drop"];
        $drop_gye = $_POST["drop_gye"];
        $drop_uio = $_POST["drop_uio"];
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);

        if ($drop == 1) {
            $sql = "LOG_FACTURAS_PENDIENTES_GUIAS_SELECT_2_DROPSHIPPING
            @gye = :gye,
            @uio = :uio";
            $query = $pdo->prepare($sql);
            $query->bindParam(':gye', $drop_gye, PDO::PARAM_STR);
            $query->bindParam(':uio', $drop_uio, PDO::PARAM_STR);
        } else {
            $sql = "LOG_FACTURAS_PENDIENTES_GUIAS_SELECT_2
            @BODEGA=:bodega,
            @acceso=:acceso
            ";
            $query = $pdo->prepare($sql);
            $query->bindParam(':bodega', $bodega, PDO::PARAM_STR);
            $query->bindParam(':acceso', $acceso, PDO::PARAM_STR);
        }

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

if (isset($_POST['Cargar_guias_sisco'])) {
    include("conexion_2sisco.php");
    try {
        // NC();
        $secuencia = $_POST["secuencia"];
        $pdo = new PDO("mysql:host=10.5.1.245;dbname=" . $sql_database, $sql_user, $sql_pwd);
        $query = $pdo->prepare("SELECT 
        a.*,
        p.bodega as bodegaret, 
        date_format(a.paymentez,'%d/%m/%Y') as fechapay,
        date_format(a.tcfecha,'%d/%m/%Y') as tcfecha,
        date_format(a.l2pfecha,'%d/%m/%Y') as l2pfecha,
        c.sucursalid as sucursal  
        FROM covidsales a
        left outer join covidpickup p on p.orden= a.secuencia
        left outer join sisco.covidciudades c on p.bodega= c.almacen
        where a.factura = :factura and a.anulada<> '1'  
        ");
        $query->bindParam(':factura', $secuencia, PDO::PARAM_STR);
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

if (isset($_POST['Cargar_Dropshiping'])) {
    include('conexion_2.php');

    try {

        $bodega = $_POST["bodega"];
        $acceso = $_POST["acceso"];
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("LOG_FACTURAS_PENDIENTES_GUIAS_SELECT 
            @BODEGA=:bodega,
            @acceso=:acceso
        ");
        $query->bindParam(':bodega', $bodega, PDO::PARAM_STR);
        $query->bindParam(':acceso', $acceso, PDO::PARAM_STR);
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

if (isset($_POST['Cargar_Multi'])) {
    include('conexion_2.php');

    try {

        $secuencia = $_POST["secuencia"];
        $bodega = $_POST["bodega"];
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("PER_Detalle_Facturas2 
            @Secuencia=:secuencia,
            @bodegaFAC=:bodega
        ");
        $query->bindParam(':secuencia', $secuencia, PDO::PARAM_STR);
        $query->bindParam(':bodega', $bodega, PDO::PARAM_STR);
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

function NC()
{
    try {
        include('conexion_2.php');
        include("conexion_2sisco.php");
        $fecha = date("y-m-d", time());
        $hora = date("H:i:s", time());
        $fh = $fecha . " " . $hora;

        $pdo_s = new PDO("mysql:host=10.5.1.245;dbname=" . $sql_database, $sql_user, $sql_pwd);
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $sql = "{CALL LOG_FACTURAS_PENDIENTES_DEVUELTAS}";
        $query = $pdo->prepare($sql);
        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $sql2 = "{CALL [LOG_FACTURAS_DEVUELTA_UPDATE] (?)}";
                $query2 = $pdo->prepare($sql2);
                $query2->bindParam(1, $row["secuencia"], PDO::PARAM_STR);
                if ($query2->execute()) {
                    $query3 = $pdo_s->prepare("UPDATE covidsales 
                    set Anulada= '1' , 
                        anuladapor= '', 
                        fechaanulada= :fh
                    where factura = :factura
                    ");
                    $query3->bindParam(":factura", $row["secuencia"], PDO::PARAM_STR);
                    $query3->bindParam(":fh", $fh, PDO::PARAM_STR);
                    $query3->execute();
                }
            }
            //echo json_encode($result);
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
