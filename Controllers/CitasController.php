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
}