<?php
include_once(__DIR__.'/Controller.php');
include_once (__DIR__ . '/../Services/CitasService.php');

class CitasController extends Controller {
    // Almacenamos el serviico de horarios
    private $citasService;

    protected function inicializarServicio()
    {
        $this -> citasService = new CitasService();
        $this -> service = $this -> citasService;

        $this->recursosValidos = [];
    }

    public function get($params)
    {
        // Solo para /citas?detalle=1
        if (count($params) === 0 && isset($_GET['detalle']) && $_GET['detalle'] == 'true') {
            return $this->citasService->obtenerCitasDetalladas();
        }

        // Solo para /citas?reporte=true
        if (count($params) === 0 && isset($_GET['reporte']) && $_GET['reporte'] == 'true') {
            return $this->citasService->obtenerReporte();
        }

        // Si la URL es /citas
        return parent::get($params); // O tu lógica base
    }
}