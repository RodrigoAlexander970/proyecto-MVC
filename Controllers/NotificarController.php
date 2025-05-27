<?php

include_once(__DIR__.'/../Services/NotificarService.php');
include_once(__DIR__.'/Controller.php');

class NotificarController extends Controller
{
    private $notificarService;

    public function inicializarServicio()
    {
        $this->notificarService = new NotificarService();

    }

    public function post($params)
    {
        try {
            $data = $this->getRequestBody();
            $resultado = $this->notificarService->enviarNotificacion($data);
            return $resultado;
        } catch (Exception $e) {
            return $e;
        }
    }
}