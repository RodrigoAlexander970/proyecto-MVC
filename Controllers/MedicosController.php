<?php
include_once(__DIR__.'/Controller.php');

include_once(__DIR__ . '/../Services/MedicosService.php');
include_once(__DIR__ . '/../Services/HorariosService.php');
include_once(__DIR__. '/../Services/PacientesService.php');

/**
 * Controlador para la gestión de médicos.
 */
class MedicosController extends Controller
{
    private $medicosService; // Servicio de médicos
    private $horariosService; // Servicio de horarios
    private $pacientesService; // Servicio de pacientes

    public function inicializarServicio() {
        $this->medicosService = new MedicosService();
        $this->horariosService = new HorariosService();
        $this->pacientesService = new PacientesService();
        $this->recursosValidos = ['horarios', 'citas', 'pacientes'];

        $this->service = $this->medicosService;
    }

    protected function manejarSubrecurso($id, $subrecurso)
    {
        switch($subrecurso) {
            case 'horarios':
                return $this->horariosService->porMedico($id);
            
            case 'citas':
                return 'Getting by cites';

            case 'pacientes':
                return $this->pacientesService->porMedico($id);
        }
    }
}
