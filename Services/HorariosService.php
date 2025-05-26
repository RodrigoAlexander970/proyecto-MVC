<?php
include_once(__DIR__.'/Service.php');
include_once (__DIR__.'/MedicosService.php');
include_once (__DIR__.'/../Models/Horario.php');


/**
 * Servicio para operaciones con horarios.
 */
class HorariosService extends Service
{
    private $horario;
    private $medicosService;
    public function __construct() {
        $this -> horario = new Horario();
        $this -> medicosService = new MedicosService();

        parent::__construct($this->horario);
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
                $horario = $this->horario->porId($params[0]);

                if($horario == null) {
                    throw new ExcepcionApi(
                        Response::STATUS_NOT_FOUND,
                        "Horario no encontrado"
                    );
                }

                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    "Horario obtenido correctamente",
                    $horario
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
    public function crear($datosHorario) {
        $this->validarCamposObligatorios($datosHorario);

        $resultado = $this->horario->crear($datosHorario);

        // Verificamos si se creó correctamente
        if ($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                "Horario creado correctamente"
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al crear el horario"
            );
        }
    }


    public function actualizar($id, $datosHorario) {
        $this->validarCamposObligatorios($datosHorario);
        
        if(!$this->existe($id)) {
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                "Horario no existente"
            );
        }

        // Llamamos a la función actualizar del DAO
        $resultado = $this->horario->actualizar($id, $datosHorario);

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
        
        if(!$this->existe($id)){
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                "Horario no existente"
            );
        }

        $seBorro = $this->horario->borrar($id);

        if ($seBorro) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                "Horario eliminado correctamente"
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al eliminar el horario"
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
}