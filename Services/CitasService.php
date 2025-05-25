<?php
include_once(__DIR__ . '/../Models/Citas.php');
include_once(__DIR__ . '/Service.php');
include_once(__DIR__ . '/../Utilities/Response.php');
include_once(__DIR__ . '/../Utilities/ExcepcionApi.php');

class CitasService extends Service
{
    private $citas;

    public function __construct()
    {
        $this->citas = new Citas();
        parent::__construct($this->citas);
    }

    public function obtener($params)
    {
        switch (count($params)) {
            case 0:
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Citas obtenidas correctamente',
                    $this->citas->todos()
                );
                break;
            case 1:

                // Revisamos que el objeto no exista
                if(!$this->existe($params[0])) {
                    throw new ExcepcionApi(
                        Response::STATUS_NOT_FOUND,
                        'La cita no existe'
                    );
                }

                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Cita obtenida correctamente',
                    $this->citas->porID($params[0])
                );
                break;
        }
    }

    public function crear($params) {
        // Llamamos a la función de creado
        $resultado = $this->citas->crear($params);
        return 'creando';
    }

    public function actualizar($params)
    {
        return 'actualizando';
    }

    public function borrar($params)
    {
        return 'borrando';
    }
}
