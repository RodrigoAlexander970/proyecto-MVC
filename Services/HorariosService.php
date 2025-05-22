<?php
include_once (__DIR__.'/../Models/Horario/Horario.php');
include_once (__DIR__.'/../Models/Horario/HorarioDAO.php');
include_once (__DIR__.'/../Utilities/Response.php');
include_once (__DIR__.'/../Utilities/ExcepcionApi.php');
include_once (__DIR__.'/MedicosService.php');

/**
 * Servicio para operaciones con horarios.
 */
class HorariosService
{
    private $horarioDAO;
    private $medicosService;
    public function __construct(HorarioDAO $horarioDAO = null, MedicosService $medicosService = null) {
        $this -> horarioDAO = $horarioDAO ?: new HorarioDAO();
        $this -> medicosService = $medicosService ?: new MedicosService();
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

        $horarios = $this->horarioDAO->porMedico($id_medico);
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