<?php
include_once (__DIR__.'/../Models/CitasDAO.php');
include_once (__DIR__.'/../Utilities/Response.php');
include_once (__DIR__.'/../Utilities/ExcepcionApi.php');

class CitasService {
    private $citasDAO;

    public function __construct(CitasDAO $citasDAO = null) {
        $this -> citasDAO = $citasDAO ?: new CitasDAO();
     }

     public function obtener($params) {
        switch(count($params)) {
            case 0:
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Citas obtenidas correctamente',
                    $this->citasDAO->todos()
                );
            break;
        }
     }
}