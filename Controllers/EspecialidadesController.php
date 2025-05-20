<?php
include_once (__DIR__ . '/../Services/EspecialidadesService.php');
include_once(__DIR__.'/../Utilities/Response.php');
include_once(__DIR__.'/../Utilities/ExcepcionApi.php');
include_once(__DIR__.'/../Models/Especialidad/Especialidad.php');

/**
 * Clase que 
 */

 class EspecialidadesController {
    // Almacena el servicio de médicos
    private static $service;

    public static function init()
    {
        if (self::$service === null) {
            self::$service = new EspecialidadesService();
        }
    }

    /**
     * Procesa entradas get
     */

    public static function get($params){
        self::init();

        switch(count($params)) {
            case 0:
            case 1:
                return self::$service->obtener($params);
            break;
        }
    }
 }