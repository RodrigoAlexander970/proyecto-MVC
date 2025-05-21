<?php
include_once (__DIR__.'/../Models/Paciente/Paciente.php');
include_once (__DIR__.'/../Models/Paciente/PacienteDAO.php');
include_once (__DIR__.'/../Utilities/Response.php');
include_once (__DIR__.'/../Utilities/ExcepcionApi.php');

/**
 * Servicio para operaciones con pacientes
 * Interactua con el DAO de Paciente
 */
class PacientesService {
    private $pacienteDAO;

    public function __construct(PacienteDAO $pacienteDAO = null) {
        $this -> pacienteDAO = $pacienteDAO ?: new PacienteDao();
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
                    $this->pacienteDAO->todos()
                );
            break;

            case 1:
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Especialidad obtenida correctamente',
                    'Por ID'
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
    