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
        if ($this->estado) {
            http_response_code($this->estado);
        }

        header('Content-Type: application/json; charset=utf8');
        echo json_encode($cuerpo, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Envía una respuesta al cliente
     * 
     * @param int $status Código de estado HTTP
     * @param string $mensaje Mensaje descriptivo
     * @param mixed $data Datos a incluir en la respuesta (opcional)
     */
    function responder($status, $mensaje, $data = null)
    {
        http_response_code($status);

        $response = [
            'status' => $status,
            'mensaje' => $mensaje
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }
        
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }
}
