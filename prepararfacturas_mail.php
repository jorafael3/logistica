<?php

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;


if (isset($_POST['Fatura_Detalle'])) {

    include('conexion_2.php');
    try {

        $bodegaFAC = $_POST["BODEGAFAC"];
        $factura = $_POST["SECUENCIA"];
        $tipo = 'VEN-FA';


        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        $query = $pdo->prepare("
        declare 
            @factura_id varchar(10)
            select @factura_id = ID from VEN_FACTURAS where Secuencia = :secuencia

            select 
            inv.ID as producto_id,
            inv.Código as codigo, 
            inv.Nombre as producto,
            dt.Cantidad,
            inv.ProveedorID,
            isnull(dt.PEDIDO_DROP,0) as PEDIDO_DROP,
            RTRIM(LTRIM(acr.Nombre)) as Nombre,
            RTRIM(LTRIM(acr.Email)) as Email
            from VEN_FACTURAS_DT dt
            left join INV_PRODUCTOS inv
            on dt.ProductoID = inv.ID
            left join CARTIMEX..ACR_ACREEDORES acr
            on acr.ID = inv.ProveedorID
            where FacturaID = @factura_id
            and BodegaID = :bodega_fac
        ");
        $query->bindParam(':secuencia', $factura, PDO::PARAM_STR);
        $query->bindParam(':bodega_fac', $bodegaFAC, PDO::PARAM_STR);
        $query->execute();
        if ($query->execute()) {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            // $CORREOS = ["jalvarado@cartimex.com"];
            // enviar_correo($CORREOS);

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

if (isset($_POST['Mail_Drop'])) {
    include('conexion_2.php');
    try {
        $DATOS = $_POST["DATOS"];
        $CORREOS_NO = [];
        $CORREOS_SI = [];
        foreach ($DATOS as $row) {

            $VAL = validarEmail($row["EMAIL"]);
            if ($VAL == 1) {
                $ENV = enviar_correo($row);
                array_push($CORREOS_SI, $ENV);
            } else {
                array_push(
                    $CORREOS_NO,
                    array(
                        "PROVEEDOR" => $row["PROVEEDOR"],
                        "EMAIL" => $row["EMAIL"],
                    )
                );
            }
            // enviar_correo($row)
        }
        echo json_encode([1, $CORREOS_NO, $ENV]);
    } catch (PDOException $e) {
        //return [];
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}


function enviar_correo($ARRAY)
{


    // require 'PHPMailer/src/Exception.php';
    // require 'PHPMailer/src/PHPMailer.php';
    // require 'PHPMailer/src/SMTP.php';
    // Configurar las opciones del correo electrónico
    $val = 0;
    $error = 0;

    try {
        $correoDestino = trim($ARRAY["EMAIL"]);
        $message = "";
        $LISTA_CODIGOS = [];
        foreach ($ARRAY["DATOS"] as $row) {
            array_push($LISTA_CODIGOS, $row["producto_id"]);

            $message .= '
            <tr>
                <td style="padding: 10px;font-weight:bold;">' . $row["codigo"] . '</td>
                <td style="padding: 10px;">' . $row["producto"] . '</td>
                <td style="padding: 10px;">' . $row["Cantidad"] . '</td>
            </tr>
            ';
        }
        include 'vendor/autoload.php';
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host       = 'mail.cartimex.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sgo';
        $mail->Password   = 'sistema2021*';
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->addAddress($correoDestino);
        // $mail->addAddress('gcassis@cartimex.com');
        // $mail->addAddress('jalvarado@cartimex.com');
        $mail->setFrom('sgo@cartimex.com', 'CARTIMEX');
        $mail->AddCC("gcassis@cartimex.com");
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'ORDEN DE COMPRA';
        $mail->Body = '
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f2f2f2;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            padding: 20px;
                            background-color: #fff;
                        }
                        .header {
                            background-color: #fff;
                            color: #000;
                            padding: 20px;
                            text-align: center;
                        }
                        .content {
                            padding: 20px;
                            text-align: center;
                        }
                        .button {
                            display: inline-block;
                            padding: 10px 20px;
                            background-color: #000;
                            color: #fff;
                            text-decoration: none;
                            border-radius: 5px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <img src="https://www.cartimex.com/assets/img/logo200.png"></img>
                            <h2>ORDEN DE COMPRA</h2>
                        </div>
                        <div class="content">
                            <span style="font-size:20px">Numero :  ' . $ARRAY["SECUENCIA"] . '</span><br>
                            <span style="font-size:20px">Proveedor :  ' . $ARRAY["PROVEEDOR"] . '</span><br>
                            
                            <div style="margin-top:20px" class="header">

                                <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
                                    <tr>
                                        <th style="padding: 10px;">Codigo</th>
                                        <th style="padding: 10px;">Producto</th>
                                        <th style="padding: 10px;">Cantidad</th>
                                    </tr>
                                    ' . $message . '
                                </table>

                            </div>
                        </div>            
                    </div>
                </body>
                </html>';

        $mail->AltBody = 'Transferencia';
        $mail->isHTML(true);
        if (!$mail->Send()) {
            $error = 'Mail error: ' . $mail->ErrorInfo;
        } else {
            $val++;
            // $ACT = Actualizar_Pedido($ARRAY["SECUENCIA"], $LISTA_CODIGOS);
            $ACT = [1, "PEDIDO_ACTUALIZADO"];
        }
        return [$ACT, $error];
    } catch (Exception $u) {
        return ($mail->ErrorInfo);
    }
}

function Actualizar_Pedido($SECUENCIA, $PRODUCTO)
{
    include('conexion_2.php');
    try {
        $val = 0;
        $pdo = new PDO("sqlsrv:server=$sql_serverName ; Database = $sql_database", $sql_user, $sql_pwd);
        foreach ($PRODUCTO as $row) {
            $query = $pdo->prepare("
        declare 
        @factura_id varchar(10)
        select @factura_id = ID from VEN_FACTURAS where Secuencia = :secuencia
        
        UPDATE VEN_FACTURAS_DT
            SET PEDIDO_DROP = 1
            WHERE FacturaID = @factura_id
            and ProductoID = :ProductoID
        ");
            $query->bindParam(':secuencia', $SECUENCIA, PDO::PARAM_STR);
            $query->bindParam(':ProductoID', $row, PDO::PARAM_STR);
            $query->execute();
            if ($query->execute()) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $val++;
            } else {
                $err = $query->errorInfo();
            }
        }
        if ($val == count($PRODUCTO)) {
            return [1, "PEDIDO_ACTUALIZADO"];
        } else {
            return [0, "ERROR AL ACTUALIZAR"];
        }
    } catch (PDOException $e) {
        //return [];
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}

function validarEmail($email)
{
    // Define la expresión regular para validar el formato del correo electrónico
    $patron = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    // Utiliza la función preg_match para verificar si el correo electrónico coincide con el patrón
    if (preg_match($patron, $email)) {
        return 1;
    } else {
        return 0;
    }
}
