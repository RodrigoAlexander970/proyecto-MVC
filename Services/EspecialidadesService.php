<?php
include_once (__DIR__.'/../Models/Especialidad/Especialidad.php');
include_once (__DIR__.'/../Models/Especialidad/EspecialidadDAO.php');
include_once (__DIR__.'/../Utilities/Response.php');
include_once (__DIR__.'/../Utilities/ExcepcionApi.php');

/**
 * Clase que representa el servicio de las especialidades
 * Interactua con el DAO de Especialidad
 */
class EspecialidadesService {
    private static $dao;

    public static function init(){
        if (self::$dao === null) {
            self::$dao = new EspecialidadDAO();
        }
    }

    public function __construct() { }
    
    /**
     * Obtiene los registros de Especialidad
     */
    public static function obtener($params) {
        // Inicializamos 
        self::init();

        // 0 parametros para todos
        // 1 parametro para subrecurso

        switch(count($params)) {
            case 0:
                return self::$dao->todos();
            break;

            case 1:
                return self::$dao->porId($params[0]);
            break;

            default:
                throw new ExcepcionApi(Response::STATUS_TOO_MANY_PARAMETERS, 'Ruta no reconocida');
        }
    }
}