<?php
include_once (__DIR__.'/../Models/Citas.php');
include_once (__DIR__.'/../Utilities/Response.php');
include_once (__DIR__.'/../Utilities/ExcepcionApi.php');

class CitasService {
    private $citas;

    public function __construct(Citas $citas = null) {
        $this -> citas = $citas ?: new Citas();
     }

     public function obtener($params) {
        switch(count($params)) {
            case 0:
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Citas obtenidas correctamente',
                    $this->citas->todos()
                );
            break;
            case 1:
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'AAAAAAAAAA',
                    $this->citas->porID($params[0])
                );
            break;
        }
     }
}