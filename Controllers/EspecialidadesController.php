<?php
include_once (__DIR__ . '/../Services/EspecialidadesService.php');
include_once(__DIR__.'/../Models/Especialidad/Especialidad.php');
include_once(__DIR__.'/../Utilities/Response.php');
include_once(__DIR__.'/../Utilities/ExcepcionApi.php');

/**
 * Clase que 
 */

 class EspecialidadesController {
    // Almacena el servicio de médicos
    private $especialidadesService;

    public function __construct(EspecialidadesService $especialidadesService = null) {
        $this->especialidadesService = $especialidadesService ?: new EspecialidadesService();
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
                return $this->especialidadesService->obtener($params);
            break;
        }
    }
 }