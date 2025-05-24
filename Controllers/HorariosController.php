<?php
include_once (__DIR__ . '/../Services/HorariosService.php');
include_once(__DIR__.'/../Utilities/Response.php');
include_once(__DIR__.'/../Utilities/ExcepcionApi.php');

class HorariosController {
    private $horariosService;

    public function __construct() {
        $this->horariosService = new HorariosService();
    }

    public function get($params) {
        switch(count($params)) {
            case 0:
            case 1:
                return $this->horariosService->obtener($params);
            break;
        }
    }

    public function post() {
        $horarioData = $this->getRequestBody();
        $this -> validarDatosHorario($horarioData);

        $horario = [
            'id_medico'   => $horarioData->id_medico,
            'dia_semana'  => $horarioData->dia_semana,
            'hora_inicio' => $horarioData->hora_inicio,
            'hora_fin'    => $horarioData->hora_fin
        ];


        return $this->horariosService->crear($horario);
    }


    public function put($params) {
        if(count($params) != 1) {
            throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Se requiere el ID de la especialidad");
        }

        $horarioData = $this->getRequestBody();
        $this -> validarDatosHorario($horarioData);

        $horario = [
            'id_horario'  => $params[0],
            'id_medico'   => $horarioData->id_medico,
            'dia_semana'  => $horarioData->dia_semana,
            'hora_inicio' => $horarioData->hora_inicio,
            'hora_fin'    => $horarioData->hora_fin
        ];

        return $this->horariosService->actualizar($horario);
    }

    public function delete($params) {
        if(count($params) != 1){
            throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Se requiere el ID del horario");
        }

        return $this->horariosService->borrar($params[0]);
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
    private function validarDatosHorario($data)
    {
        $camposRequeridos = ['id_medico', 'dia_semana', 'hora_inicio', 'hora_fin'];
        
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