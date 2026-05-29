<?php

header('Content-Type: application/json');

require '../libs/PHPMailer-master/src/PHPMailer.php';
require '../libs/PHPMailer-master/src/SMTP.php';
require '../libs/PHPMailer-master/src/Exception.php';
require '../config/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => false, 'mensaje' => 'Método no permitido']);
    exit;
}

// Validación de entrada
if (!validarDatos($_POST)) {
    http_response_code(400);
    echo json_encode(['status' => false, 'mensaje' => 'Campos obligatorios faltantes']);
    exit;
}

$plantilla = adecuarPlantilla($_POST);
envioCorreo($plantilla, $_POST);

function validarDatos($datos) {
    if (empty($datos['nombre']) || empty($datos['telefono']) || empty($datos['email']) || empty($datos['mensaje'])) {
        return false;
    }

    // Validate email format
    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Validate phone format (basic validation)
    if (!preg_match('/^[0-9\+\-\s\(\)]{7,20}$/', $datos['telefono'])) {
        return false;
    }

    // Validate message length (min 10 chars)
    if (strlen($datos['mensaje']) < 10) {
        return false;
    }

    return true;
}

function crearDatosPlantilla($datos) {
    return [
        'nombre' => htmlspecialchars($datos['nombre'], ENT_QUOTES, 'UTF-8'),
        'telefono' => htmlspecialchars($datos['telefono'], ENT_QUOTES, 'UTF-8'),
        'email' => htmlspecialchars($datos['email'], ENT_QUOTES, 'UTF-8'),
        'mensaje' => nl2br(htmlspecialchars($datos['mensaje'], ENT_QUOTES, 'UTF-8'))
    ];
}

function adecuarPlantilla($datos) {
    $datosPlantilla = crearDatosPlantilla($datos);
    ob_start();
    include 'plantillaCorreo.php';
    $plantilla = ob_get_clean();
    return $plantilla;
}

function envioCorreo($plantilla, $datos) {
    try {
        $mail = new PHPMailer(true);

        // Production: Disable debug output
        if (!PRODUCTION) {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        } else {
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
        }

        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = SMTP_PORT;

        // Production: Enable proper SSL verification
        if (PRODUCTION) {
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => true,
                    'verify_peer_name' => true,
                    'allow_self_signed' => false
                ]
            ];
        }

        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress(SMTP_ADDRESS);

        $mail->isHTML(true);
        $mail->Subject = 'Nuevo mensaje de ' . $datos['nombre'];
        $mail->Body = $plantilla;
        $mail->AltBody = strip_tags($plantilla);

        $mail->send();

        http_response_code(200);
        echo json_encode([
            'status' => true,
            'mensaje' => 'Solicitud enviada correctamente'
        ]);

    } catch (Exception $e) {
        http_response_code(500);

        $errorMessage = PRODUCTION ? 'Fallo al enviar la solicitud' : $e->getMessage();

        echo json_encode([
            'status' => false,
            'mensaje' => $errorMessage
        ]);
    }
}

?>
