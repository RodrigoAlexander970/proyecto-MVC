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
}