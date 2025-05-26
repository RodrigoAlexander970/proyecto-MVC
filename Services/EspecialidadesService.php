<?php
include_once (__DIR__.'/Service.php');
include_once (__DIR__.'/../Models/Especialidad.php');
/**
 * Servicio para operaciones con especialidades
 * Interactua con el DAO de Especialidad
 */
class EspecialidadesService extends Service {
    private $especialidad;

    public function __construct() {
        $this -> especialidad =  new Especialidad();
        parent::__construct($this->especialidad);
     }
    
    /**
     * Obtiene especialidades según los parámetros
     * 
     * @param array $params Parámetros de la consulta
     * @return array|Especialidad Lista de especialidades o una especialidad específico
     * @throws ExcepcionApi Si los parámetros son inválidos
     */
    public function obtener($params) {
        // 0 parametros para todos
        // 1 parametro para subrecurso

        switch(count($params)) {
            case 0:
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Especialidades obtenidas correctamente',
                    $this->especialidad->todos()
                );
            break;

            case 1:
                $especialidad = $this->especialidad->porId($params[0]);
                
                if($especialidad === null) {
                    throw new ExcepcionApi(
                        Response::STATUS_NOT_FOUND,
                        "Especialidad no encontrada"
                    );
                }

                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Especialidad obtenida correctamente',
                    $especialidad
                );
            break;
        }
    }

    /**
     * Crea una nueva especialidad
     * @param Especialidad Especialidad a crear
     * @return array Respuesta
     */
    public function crear($especialidadData){
        $this->validarCamposObligatorios($especialidadData);

        $resultado = $this->especialidad->crear($especialidadData);

        // Verificamos si se creó correctamente
        if($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                'Especialidad creada correctamente'
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al crear la especialidad"
            );
        }
    }

    /**
     * Actualiza un registro de especialidad
     * @param Especialidad Registro a actualizar
     * @return Respuesta formateada
     * @throws ExcepcionApi
     */
    public function actualizar($id, $especialidadData) {

        $this->validarCamposObligatorios($especialidadData);

        if(!$this->existe($id)){
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                'La especialidad no existe'
            );
        }

        $resultado = $this->especialidad->actualizar($id, $especialidadData);

        if($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_OK,
                'Especialidad actualizada correctamente'
            );
        } else {
           throw new ExcepcionApi(
            Response::STATUS_INTERNAL_SERVER_ERROR,
            "Error al actualizar la especialidad");
        }
    }

    public function borrar($id) {
        // Revisamos si no existe el registro en la base
        if(!$this->existe($id)){
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                'La especialidad no existe'
            );
        }

        $seBorro = $this->especialidad->borrar($id);

        // Verificamos si se borró correctamente
        if ($seBorro) {
            return Response::formatearRespuesta(
                Response::STATUS_OK,
                "Especialidad borrada correctamente"
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al eliminar la especialidad"
            );
        }
    }
}