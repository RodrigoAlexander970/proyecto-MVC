<?php

include_once(__DIR__.'/../../Utilities/Response.php');
include_once(__DIR__.'/../../Database/ConexionBD.php');
include_once(__DIR__.'/Medico.php');

/**
 * Clase que enlaza la base de datos con la tabla Medico.
 */
class MedicoDAO
{
    // Nombre de la tabla
    private static $NOMBRE_TABLA = 'medicos';

    // Nombre de los campos
    private static $ID_MEDICO = 'id_medico';
    private static $ID_ESPECIALIDAD = 'id_especialidad';
    private static $NOMBRE = 'nombre';
    private static $APELLIDOS = 'apellidos';
    private static $CEDULA_PROFESIONAL = 'cedula_profesional';
    private static $EMAIL = 'email';
    private static $TELEFONO = 'telefono';
    private static $PASSWORD = 'password';
    private static $FECHA_REGISTRO = 'fecha_registro';
    private static $ACTIVO = 'activo';

    // Conexión a la base de datos
    private static $conexion;
    // Instanciamos la conexión a la base de datos
    
    public static function init(){
        if (self::$conexion === null) {
            self::$conexion = ConexionBD::obtenerInstancia()->obtenerBD();
        }
    }
    public function __construct()
    {
    }
    /**
     * Obtiene todos los medicos de la base de datos.
     * @return array Arreglo de objetos Medico.
     */
    public static function todos(){

        self::init();

        // Elaboramos la consulta
        $sql = "SELECT * FROM ". self::$NOMBRE_TABLA;

        // Preparamos la consulta
        $stmt = self::$conexion->prepare($sql);

        // Ejecutamos la consulta
        $stmt->execute();

        // Obtenemos los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Creamos un arreglo de objetos Medico
        $medicos = array();

        foreach ($resultados as $resultado) {
            $medico = new Medico();
            $medico->setIdMedico($resultado[self::$ID_MEDICO]);
            $medico->setIdEspecialidad($resultado[self::$ID_ESPECIALIDAD]);
            $medico->setNombre($resultado[self::$NOMBRE]);
            $medico->setApellidos($resultado[self::$APELLIDOS]);
            $medico->setCedulaProfesional($resultado[self::$CEDULA_PROFESIONAL]);
            $medico->setEmail($resultado[self::$EMAIL]);
            $medico->setTelefono($resultado[self::$TELEFONO]);
            $medico->setPassword($resultado[self::$PASSWORD]);
            $medico->setFechaRegistro($resultado[self::$FECHA_REGISTRO]);
            $medico->setActivo($resultado[self::$ACTIVO]);

            // Agregamos el objeto Medico al arreglo
            array_push($medicos, $medico);
        }

        // Retornamos el arreglo de objetos Medico
        return $medicos;
    }
}