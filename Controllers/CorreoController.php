<?php

// Mostrar errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar PHPMailer manualmente (sin Composer)
include_once (__DIR__.'/../PHPMailer/Exception.php');
include_once (__DIR__.'/../PHPMailer/PHPMailer.php');
include_once (__DIR__.'/../PHPMailer/SMTP.php');
include_once (__DIR__.'/../Utilities/Response.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class CorreoController {

    public function get($params) {
        try {
            $mail = new PHPMailer(true);

            // Configuración del servidor SMTP (Mailersend)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'losfifisreal@gmail.com';
            $mail->Password   = 'nyljpuotqjgzrpnl';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Remitente
            $mail->setFrom('losfifisreal@gmail.com', 'Los Fifis Real');

            // Destinatario
            $mail->addAddress('migueltbg2@gmail.com', 'Miguel');

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Correo desde XAMPP con PHPMailer y Mailersend';
            $mail->Body    = '<h1>Hola Mundo</h1><p>Este es un correo enviado desde mi entorno local de XAMPP.</p>';
            $mail->AltBody = 'Este es el cuerpo en texto plano.';

            // Enviar correo
            $mail->send();

            return Response::formatearRespuesta(
                Response::STATUS_OK,
                'Correo enviado correctamente'
            );

        } catch (Exception $e) {
            return Response::formatearRespuesta(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al enviar el correo: {$mail->ErrorInfo}"
            );
        }
    }
}