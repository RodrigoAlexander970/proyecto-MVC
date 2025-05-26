<?php
include_once(__DIR__.'/Controller.php');

include_once (__DIR__ . '/../Services/EspecialidadesService.php');

class EspecialidadesController extends Controller {
    
    // Almacena el servicio de especialidades
    private $especialidadesService;

    public function inicializarServicio() {
        $this->especialidadesService = new EspecialidadesService();
        $this->recursosValidos = ['medicos'];

        $this->service = $this->especialidadesService;
    }

    protected function manejarSubrecurso($id, $subrecurso) {
        switch($subrecurso) {
            case 'medicos':
                return 'medicos JKASJKJSKAJSAKJS';
        }
    }
 }