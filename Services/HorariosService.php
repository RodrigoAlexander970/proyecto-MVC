<?php
include_once (__DIR__.'/../Models/Horario/Horario.php');
include_once (__DIR__.'/../Models/Horario/HorarioDAO.php');
include_once (__DIR__.'/../Utilities/Response.php');
include_once (__DIR__.'/../Utilities/ExcepcionApi.php');

/**
 * Clase que representa el servicio de horarios.
 * Esta clase contiene métodos para interactuar con el DAO de horarios.
 */
class HorariosService
{
    private static $dao;

    public static function init(){
        if (self::$dao === null) {
            self::$dao = new HorarioDAO();
        }
    }

    public function __construct() { }

    public static function porMedico($id_medico)
    {
        self::init();
        
        return self::$dao->porMedico($id_medico);
    }
}