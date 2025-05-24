<?php
include_once (__DIR__ . '/../Services/CitasService.php');
include_once(__DIR__.'/../Utilities/Response.php');
include_once(__DIR__.'/../Utilities/ExcepcionApi.php');

class CitasController {
    // Almacenamos el serviico de horarios
    private $citasService;

    public function __construct() {
        $this->citasService = new CitasService();
    }

    /**
     * Procesa la solicitud GET
     * 
     * @param array $params Parámetros de la solicitud
     * @return array|object Respuesta para el cliente
     * @throws ExcepcionApi Si el recurso no es válido
     */
    public function get($params) {
        switch(count($params)) {
            case 0:
            case 1:
                return $this->citasService->obtener($params);
        }
    }
}