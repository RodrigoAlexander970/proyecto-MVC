<?php
include_once (__DIR__.'/../Models/Paciente.php');
include_once (__DIR__.'/MedicosService.php');
include_once (__DIR__.'/Service.php');

/**
 * Servicio para operaciones con pacientes
 * Interactua con el DAO de Paciente
 */
class PacientesService extends Service{
    private $paciente;
    private $medicosService;
    
    public function __construct() {
        $this -> paciente = new Paciente();
        $this -> medicosService = new MedicosService();
        parent::__construct($this->paciente);
     }

     /**
     * Obtiene pacientes según los parámetros
     * 
     * @param array $params Parámetros de la consulta
     * @return array|Especialidad Lista de pacientes o un paciente específico
     * @throws ExcepcionApi Si los parámetros son inválidos
     */
    public function obtener($params) {
        // 0 parametros para todos
        // 1 parametro para subrecurso

        switch(count($params)) {
            case 0:
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Pacientes obtenidos correctamente',
                    $this->paciente->todos()
                );
            break;

            case 1:
                $paciente = $this->paciente->porId($params[0]);
                 
                // Revisamos si el paciente no existe
                if($paciente == null) {
                    throw new ExcepcionApi(
                        Response::STATUS_NOT_FOUND,
                        "Paciente no encontrado"
                    );
                }

                // Devolvemos el paciente
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Paciente obtenido correctamente',
                    $paciente
                );
            break;

            default:
                throw new ExcepcionApi(
                    Response::STATUS_TOO_MANY_PARAMETERS,
                    'Ruta no reconocida'
                );
        }
    }

    public function crear($paciente)
    {
        $this->validarCamposObligatorios($paciente);

        $resultado = $this->paciente->crear($paciente);

        // Verificamos si se creó correctamente
        if ($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                "Paciente creado correctamente",
                $resultado
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al crear el Paciente"
            );
        }
    }

    public function actualizar($id, $paciente) {
        $this->validarCamposObligatorios($paciente);
        
        // Revisamos si existe el registro en la base
        if(!$this->existe($id)){
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                'El paciente no existe'
            );
        }

        $resultado = $this->paciente->actualizar($id, $paciente);

        if($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                "Paciente actualizado correctamente",
                $resultado
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al actualizar el Paciente"
            );
        }
    }

    public function borrar($id) {
        // Revisamos si no existe el registro en la base
        if(!$this->existe($id)){
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                'El paciente no existe'
            );
        }

        $resultado = $this->paciente->borrar($id);

        if($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                "Paciente borrado correctamente",
                $resultado
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al borrar el Paciente"
            );
        }
    }

    /**
     * Obtiene los pacientes de un medico en especifico
     * @param int ID del medico
     * @return array Respuesta
     * @throws ExcepcionApi
     */
    public function porMedico($id_medico) {
        // Buscamos si existe el medico
        $medico = $this->medicosService->obtener([$id_medico]);

        return Response::formatearRespuesta(
            Response::STATUS_OK,
            "Pacientes conseguidos",
            $this->paciente->porMedico($id_medico)
        );
    }
}
    