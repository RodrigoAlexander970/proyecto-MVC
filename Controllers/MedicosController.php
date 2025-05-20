<?php
include_once (__DIR__ . '/../Services/MedicosService.php');
include_once(__DIR__.'/../Utilities/Response.php');
include_once(__DIR__.'/../Utilities/ExcepcionApi.php');

class MedicosController
{


    // Almacena el servicio de médicos
    private static $service;


    public static function init()
    {
        if (self::$service === null) {
            self::$service = new MedicosService();
        }
    }
    /**
     * Constructor de la clase MedicosController.
     * Inicializa el servicio de médicos.
     */
    public function __construct() { }

    // Método para manejar la solicitud GET.
    public static function get($params)
    {
        self::init();

        // Depende de la cantidad de parámetros, se obtienen diferentes resultados
        switch(count($params)) {
            // Se obtienen los médicos
            case 0:
            case 1:
                return self::$service->obtener($params);
            break;

            default:
                throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Número de parámetros inválido");
        }
    }
}