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
include_once(__DIR__.'/../Models/Paciente.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class NotificarService
{
    private $paciente;

    public function __construct() {
        $this -> paciente = new Paciente();
    }
    /**
     * Envía una notificación al paciente
     * 
     * @param array $datos Datos del paciente y mensaje
     * @return string Mensaje de éxito
     * @throws Exception Si el paciente no existe o hay un error al enviar la notificación
     */
    public function enviarNotificacion($datos)
    {
        //Buscar al paciente por ID
        $paciente = $this->paciente->porId($datos['id_paciente']);
        if (!$paciente) {
            throw new Exception("Paciente no encontrado");
        }

        // Obtener el correo electronico y nombre del paciente
        $email = $paciente['email'];
        $nombre = $paciente['nombre'] . ' ' .$paciente['apellidos'];

        $email_remitente = 'losfifisreal@gmail.com';
        $contrasena_remitente = 'nyljpuotqjgzrpnl';
        $nombre_remitente = 'Hospital';

        try {
            $mail = new PHPMailer(true);

            // Configuración del servidor SMTP (Mailersend)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $email_remitente;
            $mail->Password   = $contrasena_remitente;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Remitente
            $mail->setFrom($email_remitente, $nombre_remitente);

            // Destinatario
            $mail->addAddress($email, $nombre);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Recordatorio de cita';
            $mail->Body    = '<h1>Recordatorio</h1><p>' . $datos['mensaje'] . '</p>';
            $mail->AltBody = $datos['mensaje'];

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
