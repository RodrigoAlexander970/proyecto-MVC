<?php
include_once(__DIR__.'/Controller.php');
include_once(__DIR__.'/../Services/MedicosService.php');
include_once (__DIR__ . '/../Services/EspecialidadesService.php');

class EspecialidadesController extends Controller {
    
    // Almacena el servicio de especialidades
    private $especialidadesService;
    private $medicosService;
    public function inicializarServicio() {
        $this->especialidadesService = new EspecialidadesService();
        $this-> medicosService = new MedicosService();
        $this->recursosValidos = ['medicos'];

        $this->service = $this->especialidadesService;
    }

    protected function manejarSubrecurso($id, $subrecurso) {
        switch($subrecurso) {
            case 'medicos':
                return $this->medicosService->porEspecialidad($id);
        }
    }
 }