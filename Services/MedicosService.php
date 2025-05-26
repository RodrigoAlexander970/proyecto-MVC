<?php
include_once (__DIR__.'/../Models/Medico.php');
include_once (__DIR__.'/Service.php');

/**
 * Servicio para operaciones con médicos
 */
class MedicosService extends Service
{
    private $medico;

    public function __construct() {
        $this -> medico = new Medico();
        parent::__construct($this->medico);
     }

/**
     * Obtiene médicos según los parámetros
     * 
     * @param array $params Parámetros de la consulta
     * @return array|Medico Lista de médicos o un médico específico
     * @throws ExcepcionApi Si los parámetros son inválidos
     */
    public function obtener($params)
    {
        /* Switch para determinar la acción en base al numero de parámetros
        * 0 parametros: devuelve todos los médicos
        * 1 parámetro: devuelve un médico específico
        * 2 o más parámetros: lanza un error
        */
        switch( count($params) ) {
            case 0:
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Médicos obtenidos correctamente',
                    $this->medico->todos()
                );
            break;
            case 1:
                $medico = $this->medico->porID($params[0]);

                if ($medico === null) {
                    throw new ExcepcionApi(
                        Response::STATUS_NOT_FOUND,
                        "Médico no encontrado"
                    );
                }

                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    "Médico obtenido correctamente",
                    $medico
                );
            break;
            default:
                throw new ExcepcionApi(
                    Response::STATUS_BAD_REQUEST, 
                    "Número de parámetros inválido"
                );
        }
    }

    /**
     * Método para crear un nuevo médico.
     * @param Medico $medico Médico a crear.
     * @return array Respuesta formateada
     */
    public function crear($medico)
    {
        // Llamamos a la función crear del DAO
        $resultado = $this->medico->crear($medico);

        // Verificamos si se creó correctamente
        if ($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                "Médico creado correctamente",
                $resultado
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al crear el médico"
            );
        }
    }

    /**
     * Actualiza un médico existente
     * 
     * @param Medico $medico Médico con datos actualizados
     * @return array Respuesta formateada
     */
    public function actualizar($id, $medico) {
        // Llamamos a la función actualizar del DAO
        $resultado = $this->medico->actualizar($id, $medico);

        if ($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_OK,
                "Médico actualizado correctamente"
            );
        } else {
           throw new ExcepcionApi(
            Response::STATUS_INTERNAL_SERVER_ERROR,
            "Error al actualizar el médico");
        }

        return $respuesta;
    }

    /**
     * Elimina un médico
     * 
     * @param int $id ID del médico a eliminar
     * @return array Respuesta formateada
     */
    public function borrar($id){
        // Llamamos a la función borrar del DAO
        $resultado = $this->medico->borrar($id);

        // Verificamos si se borró correctamente
        if ($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_OK,
                "Médico borrado correctamente"
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al eliminar el médico"
            );
        }
    }
}