<?php
include_once (__DIR__ . '/../Services/MedicosService.php');
if (!class_exists('MedicosService')) {
    echo "La clase MedicosService no existe después de incluir el archivo";
    // También puedes imprimir cuáles clases están disponibles
    print_r(get_declared_classes());
}
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

        if (count($params) == 0) {
            // Se obtienen todos los médicos
            
            return self::$service->obtener($params);
        }
    }
}