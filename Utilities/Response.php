<?php
/**
 * Clase que define los códigos de estado de la respuesta de la API.
 */
class Response
{
    const STATUS_OK = 200;
    const STATUS_CREATED = 201;

    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_NOT_FOUND = 404;
    const STATUS_DUPLICATED_ENTRY = 409;
    const STATUS_CONFLICT = 409;
    const STATUS_TOO_MANY_PARAMETERS = 422;

    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_NOT_IMPLEMENTED = 501;
    /**
     * Formatea la respuesta para mantener consistencia
     * 
     * @param int $status Código de estado
     * @param string $mensaje Mensaje descriptivo
     * @param mixed $data Datos a incluir (opcional)
     * @return array Respuesta formateada
     */
    public static function formatearRespuesta($status, $mensaje, $data = null)
    {
        $respuesta = [
            'success' => true,
            'status' => $status,
            'mensaje' => $mensaje
        ];
        
        if ($data !== null) {
            $respuesta['data'] = $data;
        }
        
        return $respuesta;
    }
}