<?php
include_once (__DIR__.'/../Models/Especialidad/Especialidad.php');
include_once (__DIR__.'/../Models/Especialidad/EspecialidadDAO.php');
include_once (__DIR__.'/../Utilities/Response.php');
include_once (__DIR__.'/../Utilities/ExcepcionApi.php');

/**
 * Servicio para operaciones con especialidades
 * Interactua con el DAO de Especialidad
 */
class EspecialidadesService {
    private $especialidadDAO;

    public function __construct(EspecialidadDAO $especialidadDAO = null) {
        $this -> especialidadDAO = $especialidadDAO ?: new EspecialidadDAO();
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
                    $this->especialidadDAO->todos()
                );
            break;

            case 1:
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Especialidad obtenida correctamente',
                    $this->especialidadDAO->porId($params[0])
                );
            break;

            default:
                throw new ExcepcionApi(
                    Response::STATUS_TOO_MANY_PARAMETERS,
                    'Ruta no reconocida'
                );
        }
    }

    /**
     * Crea una nueva especialidad
     * @param Especialidad Especialidad a crear
     * @return array Respuesta
     */
    public function crear($especialidad){
        $resultado = $this->especialidadDAO->crear($especialidad);

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
    public function actualizar($especialidad) {

        // Revisamos si no existe
        if(!self::existe($especialidad->getIdEspecialidad())){
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                "Especialidad no existente"
            );
        }

        $resultado = $this->especialidadDAO->actualizar($especialidad);

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
        $seBorro = $this->especialidadDAO->borrar($id);

        if ($seBorro === 'constraint_violation') {
            throw new ExcepcionApi(
                Response::STATUS_CONFLICT,
                "No se puede eliminar la especialidad porque tiene elementos asociados."
            );
        } elseif ($seBorro) {
            return Response::formatearRespuesta(
                Response::STATUS_OK,
                'Especialidad eliminada correctamente'
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                "Especialidad no encontrada"
            );
        }
    }

    /**
     * Comprueba si existe un registro en la base de datos
     * @param int ID del registro
     * @return bool true si existe | false si no existe
     */
    private function existe($id){
        $especialidad = $this->especialidadDAO->porId($id);
        if($especialidad){
            return true;
        } else {
            return false;
        }
    }
}