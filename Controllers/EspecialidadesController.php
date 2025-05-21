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

    /**
     * Procesa la solicitud POST
     * 
     * @param array $params Parámetros de la solicitud
     * @return array Respuesta para el cliente
     * @throws ExcepcionApi Si los datos son inválidos
     */
    public function post($params) {
        // Obtenemos y validamos el body
        $especialidadData = $this->getRequestBody();
        $this -> validarDatosEspecialidad($especialidadData);

        // Creamos el objeto Especialidad
        $especialidad = new Especialidad();
        $especialidad->setNombre($especialidadData->nombre);
        $especialidad->setDescripcion($especialidadData->descripcion);

        // Registramos la especialidad
        return $this->especialidadesService->crear($especialidad);
    }

    /**
     * Procesa la solicitud PUT
     * 
     * @param array $params Parámetros de la solicitud
     * @return array Respuesta para el cliente
     * @throws ExcepcionApi Si los datos son inválidos o faltan parámetros
     */
    public function put($params) {
        if(count($params) != 1) {
            throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Se requiere el ID de la especialidad");
        }

        // Obtenemos y validamos el body
        $especialidadData = $this->getRequestBody();
        $this -> validarDatosEspecialidad($especialidadData);

        // Creamos el objeto Especialidad
        $especialidad = new Especialidad();
        $especialidad->setIdEspecialidad($params[0]);
        $especialidad->setNombre($especialidadData->nombre);
        $especialidad->setDescripcion($especialidadData->descripcion);

        return $this->especialidadesService->actualizar($especialidad);
    }

    /**
     * Procesa la solicitud DELETE
     * 
     * @param array $params Parámetros de la solicitud
     * @return array Respuesta para el cliente
     * @throws ExcepcionApi Si faltan o sobran parámetros
     */
    public function delete($params) {
        if(count($params) != 1) {
            throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Se requiere el ID de la especialidad");
        }

        return $this->especialidadesService->borrar($params[0]);
    }

    /**
     * Obtiene el cuerpo de la solicitud como objeto JSON
     * 
     * @return object Datos de la solicitud
     * @throws ExcepcionApi Si los datos no son un JSON válido
     */
    private function getRequestBody()
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Datos JSON inválidos");
        }
        
        return $data;
    }
    
    /**
     * Valida los datos de una especialidad
     * 
     * @param object $data Datos a validar
     * @throws ExcepcionApi Si los datos son inválidos
     */
    private function validarDatosEspecialidad($data)
    {
        $camposRequeridos = ['nombre', 'descripcion'];
        
        foreach ($camposRequeridos as $campo) {
            if (!isset($data->$campo)) {
                throw new ExcepcionApi(
                    Response::STATUS_BAD_REQUEST, 
                    "El campo '$campo' es requerido"
                );
            }
        }
    }
 }