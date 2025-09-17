<?php

  

    require '../libs/PHPMailer-master/src/PHPMailer.php';
    require '../libs/PHPMailer-master/src/SMTP.php';
    require '../libs/PHPMailer-master/src/Exception.php';
    require '../config/config.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    // Validación de entrada
    if (!validarDatos($_POST)) {
        echo json_encode(['status' => false, 'mensaje' => 'Campos obligatorios faltantes']);
        return;
    }

    $plantilla = adecuarPlantilla($_POST);
    envioCorreo($plantilla, $_POST);

    function validarDatos($datos){
        return !(empty($datos['nombre']) || empty($datos['telefono']) || empty($datos['email']) || empty($datos['mensaje']));
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
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true; // Habilita la autenticación SMTP
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port = SMTP_PORT; // Usar el puerto 587 para STARTTLS
            $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
            $mail->addAddress(SMTP_ADDRESS);
            
            $mail->isHTML(true);
            $mail->Subject = 'Nuevo mensaje de ' . $datos['nombre'];
            $mail->Body = $plantilla;
    
            $mail->send();
            echo json_encode(['status' => true, 'mensaje' => 'Solicitud enviada correctamente']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'mensaje' => 'Fallo al enviar la solicitud', 'error' => $e->getMessage()]);
        }
    }

    
?>
