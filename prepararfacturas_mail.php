<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


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
            inv.Código as codigo, 
            inv.Nombre as producto,
            dt.Cantidad,
            inv.ProveedorID,
            RTRIM(LTRIM(acr.Nombre)) as Nombre,
            RTRIM(LTRIM(acr.Email)) as Email 
            from VEN_FACTURAS_DT dt
            left join INV_PRODUCTOS inv
            on dt.ProductoID = inv.ID
            left join ACR_ACREEDORES acr
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
        foreach ($DATOS as $row) {

            $VAL = validarEmail($row["EMAIL"]);
            if ($VAL == 1) {
                enviar_correo($row);
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
        echo json_encode($CORREOS_NO);
    } catch (PDOException $e) {
        //return [];
        $e = $e->getMessage();
        echo json_encode($e);
        exit();
    }
}


function enviar_correo($ARRAY)
{


    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    // Configurar las opciones del correo electrónico
    $val = 0;
    $error = 0;

    try {
        $correoDestino = trim($ARRAY["EMAIL"]);
        $message = "";
        foreach ($ARRAY["DATOS"] as $row) {
            $message .= '
            <tr>
                <td style="padding: 10px;">' . $row["codigo"] . '</td>
                <td style="padding: 10px;">' . $row["producto"] . '</td>
                <td style="padding: 10px;">' . $row["Cantidad"] . '</td>
            </tr>
            ';
        }

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host       = 'mail.cartimex.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sgo';
        $mail->Password   = 'sistema2021*';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->addAddress($correoDestino);
        // $mail->addAddress('jalvarado@cartimex.com');
        $mail->setFrom('sgo@cartimex.com', 'Computron');
        // $mail->AddCC($usuario_email);
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
                            background-color: #F9E79F;
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

                            <h2>ORDEN DE COMPRA</h2>
                        </div>
                        <div class="content">
                            <span>Numero :  </span><br>
                            <span>Proveedor :  </span><br>
                            <span>Transporte :  </span><br>
                            <span>Guia :  </span><br>
                            <span>Detalle Despacho :  </span><br>
                            
                        </div>
                        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
                            <tr>
                                <th style="padding: 10px;">Codigo</th>
                                <th style="padding: 10px;">Producto</th>
                                <th style="padding: 10px;">Cantidad</th>
                            </tr>
                            ' . $message . '
                           
                        </table>
                    </div>
                </body>
                </html>';

        $mail->AltBody = 'Transferencia';
        $mail->isHTML(true);
        if (!$mail->Send()) {
            $error = 'Mail error: ' . $mail->ErrorInfo;
        } else {
            $val++;
        }
        return [$val, $error];
    } catch (Exception $u) {
        return ($mail->ErrorInfo);
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
