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

    /**
     * Método para crear un nuevo médico.
     * @param Medico $medico Objeto médico a crear.
     */
    public static function crear($medico)
    {
        self::init();

        // Llamamos a la función crear del DAO
        $resultado = self::$dao->crear($medico);

        // Verificamos si se creó correctamente
        if ($resultado) {
            
            $respuesta = [
                "status" => Response::STATUS_CREATED,
                "mensaje" => "Médico creado correctamente",
                "data" => $resultado
            ];
        } else {
            $respuesta = [
                "status" => Response::STATUS_INTERNAL_SERVER_ERROR,
                "mensaje" => "Error al crear el médico"
            ];
        }

        return $respuesta;
    }

    /**
     * Actualiza un médico existente.
     * @param Medico $medico Objeto médico con los datos actualizados.
     */

    public static function actualizar($medico) {
        self::init();

        // Llamamos a la función actualizar del DAO
        $resultado = self::$dao->actualizar($medico);

        if ($resultado) {
            $respuesta = [
                "status" => Response::STATUS_OK,
                "message" => "Médico actualizado correctamente"
            ];
        } else {
           throw new ExcepcionApi(Response::STATUS_INTERNAL_SERVER_ERROR, "Error al actualizar el médico");
        }

        return $respuesta;
    }
    /**
     * Método para borrar un médico.
     * @param int $id ID del médico a borrar.
     */
    public static function borrar($id){
        self::init();

        // Llamamos a la función borrar del DAO
        $resultado = self::$dao->borrar($id);

        // Verificamos si se borró correctamente
        if ($resultado) {
            $respuesta = [
                "status" => Response::STATUS_OK,
                "message" => "Médico borrado correctamente"
            ];
        } else {
            $respuesta = [
                "status" => Response::STATUS_INTERNAL_SERVER_ERROR,
                "mensaje" => "Error al borrar el médico"
            ];
        }

        return $respuesta;
    }
}