<?php
include_once (__DIR__.'/../Models/Horario.php');
include_once (__DIR__.'/../Utilities/Response.php');
include_once (__DIR__.'/../Utilities/ExcepcionApi.php');
include_once (__DIR__.'/MedicosService.php');

/**
 * Servicio para operaciones con horarios.
 */
class HorariosService
{
    private $horario;
    private $medicosService;
    public function __construct(Horario $horario = null, MedicosService $medicosService = null) {
        $this -> horario = $horario ?: new Horario();
        $this -> medicosService = $medicosService ?: new MedicosService();
     }

     /**
      * Obtiene horarios según los parametros
      */
      public function obtener($params) {
        switch(count($params)) {
            case 0:
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Horarios obtenidos correctamente',
                    $this->horario->todos()
                );
            break;

            case 1:
                if(!self::existe($params[0])) {
                    return Response::formatearRespuesta(
                        Response::STATUS_NOT_FOUND,
                        "Horario no encontrado"
                    );
                }

                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    "Horario obtenido correctamente",
                    $this->horario->porId($params[0])
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
     * Crea un nuevo horario
     */
    public function crear($horario) {
        $resultado = $this->horario->crear($horario);

        // Verificamos si se creó correctamente
        if ($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                "Horario creado correctamente",
                $resultado
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al crear el horario"
            );
        }
    }


    public function actualizar($horario) {

        if(!self::existe($horario->getIdHorario())){
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                "Horario no existente"
            );
        }
        
        // Llamamos a la función actualizar del DAO
        $resultado = $this->horario->actualizar($horario);

        if ($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_OK,
                "Horario actualizado correctamente"
            );
        } else {
           throw new ExcepcionApi(
            Response::STATUS_INTERNAL_SERVER_ERROR,
            "Error al actualizar el horario");
        }

        return $respuesta;
    }

    public function borrar($id) {
        
        if(!self::existe($id)){
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                "Horario no existente"
            );
        }

        $seBorro = $this->horario->borrar($id);

        if ($seBorro === 'constraint_violation') {
            throw new ExcepcionApi(
                Response::STATUS_CONFLICT,
                "No se puede eliminar el horario porque tiene elementos asociados."
            );
        } elseif ($seBorro) {
            return Response::formatearRespuesta(
                Response::STATUS_OK,
                'Horario eliminado correctamente'
            );
        } 
    }

     /**
      * Obtiene horarios segun el ID del médico.
      * @param int ID del médico
      * @return array Lista de horarios del médico
      * @throws ExcepcionApi Si el ID del médico es inválido
      */
    public function porMedico($id_medico)
    {
        // Comprobamos que exista el  medico
        $medicoExiste = $this->medicosService->obtener([$id_medico]);

        if (!$medicoExiste) {
            throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Médico no encontrado");
        }

        $horarios = $this->horario->porMedico($id_medico);
        if ($horarios === null) {
            return Response::formatearRespuesta(
                Response::STATUS_NOT_FOUND,
                "Horarios no encontrados"
            );
        }

        return Response::formatearRespuesta(
            Response::STATUS_OK,
            "Horarios obtenidos correctamente",
            $horarios
        );
    }

    private function existe($id) {
        $horario = $this->horario->porId($id);
        if($horario){
            return true;
        } else {
            return false;
        }
    }
}