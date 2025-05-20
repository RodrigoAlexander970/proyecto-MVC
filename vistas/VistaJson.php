<?php

require_once "VistaApi.php";

/**
 * Clase para imprimir en la salida respuestas con formato JSON
 */
class VistaJson extends VistaApi
{

    /**
     * Imprime el cuerpo de la respuesta y setea el código de respuesta
     * @param mixed $cuerpo de la respuesta a enviar
     */
    public function imprimir($cuerpo)
    {
        // Detecta si ya tiene la estructura estándar
        $isStandard = is_array($cuerpo)
            && array_key_exists('success', $cuerpo)
            && array_key_exists('status', $cuerpo)
            && array_key_exists('message', $cuerpo)
            && array_key_exists('data', $cuerpo);

        if (!$isStandard) {
            $status = $this->estado ?: 200;
            $success = $status >= 200 && $status < 300;
            $cuerpo = [
                "success" => $success,
                "status" => $status,
                "message" => $success ? "Operación exitosa" : "Error",
                "data" => $cuerpo
            ];
        }

        http_response_code($cuerpo['status']);
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($cuerpo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
















