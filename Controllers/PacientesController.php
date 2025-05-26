<?php
include_once(__DIR__.'/Controller.php');
include_once (__DIR__ . '/../Services/PacientesService.php');
include_once (__DIR__.'/../Services/CitasService.php');

/**
 * Controlador para la gestión de pacientes
 */
class PacientesController extends Controller {
    // Almacena el servicio de médicos
    private $pacientesService;
    private $citasService;

    protected function inicializarServicio() {
        $this -> pacientesService = new PacientesService();
        $this -> citasService = new CitasService();

        $this -> service = $this->pacientesService;
        $this -> recursosValidos = ['citas'];
    }

    protected function manejarSubrecurso($id, $subrecurso)
    {
        switch($subrecurso) {
            case 'citas':
                return $this->citasService->porPaciente($id);
            break;

            default:
            return parent::manejarSubrecurso($id, $subrecurso);
        }
    }
}