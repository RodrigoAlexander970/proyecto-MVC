<?php
include_once(__DIR__.'/Controller.php');
include_once (__DIR__ . '/../Services/HorariosService.php');

class HorariosController extends Controller {
    private $horariosService;

    public function inicializarServicio() {
        $this->horariosService = new HorariosService();
        $this -> service = $this->horariosService;
        $this -> recursosValidos = [];
    }
}