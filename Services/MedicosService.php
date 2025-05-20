<?php
include_once (__DIR__.'/../Models/Medico/Medico.php');
include_once (__DIR__.'/../Models/Medico/MedicoDAO.php');
include_once (__DIR__.'/../Utilities/Response.php');
include_once (__DIR__.'/../Utilities/ExcepcionApi.php');

/**
 * Clase que representa el servicio de médicos.
 * Esta clase contiene métodos para interactuar con el DAO de médicos.
 */
class MedicosService
{
    private static $dao;

    public static function init(){
        if (self::$dao === null) {
            self::$dao = new MedicoDAO();
        }
    }

    public function __construct() { }

    /**
     * Método para obtener médicos de la base de datos.
     * @param array $params Parámetros para la consulta.
     * @return array|Response Resultado de la consulta.
     */
    public static function obtener($params)
    {
        self::init();

        // Switch para determinar la acción en base al numero de parámetros
        switch( count($params) ) {
            // Se obtienen todos los médicos
            case 0:
                return self::$dao->todos();
            break;
            case 1:
                return self::$dao->porID($params[0]);
            break;
            // Se lanza error
            default:
                throw new ExcepcionApi(Response::STATUS_TOO_MANY_PARAMETERS, "Número de parámetros inválido");
        }
    }
}