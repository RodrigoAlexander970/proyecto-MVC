<?php
include_once (__DIR__ . '/../Services/PacientesService.php');
include_once(__DIR__.'/../Models/Paciente/Paciente.php');
include_once(__DIR__.'/../Utilities/Response.php');
include_once(__DIR__.'/../Utilities/ExcepcionApi.php');

class PacientesController {
    // Almacena el servicio de médicos
    private $pacientesService;

    public function __construct() {
        $this->pacientesService = new PacientesService();
    }

    /**
     * Procesa la solicitud GET
     * 
     * @param array $params Parámetros de la solicitud
     * @return array|object Respuesta para el cliente
     * @throws ExcepcionApi Si el recurso no es válido
     */
    public function get($params){
        switch(count($params)) {
            case 0:
            case 1:
                return $this->pacientesService->obtener($params);
            break;
        }
    }
 }