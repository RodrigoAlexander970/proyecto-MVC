<?php

include_once(__DIR__.'/Especialidad.php');
include_once(__DIR__.'/../../Utilities/Response.php');
include_once(__DIR__.'/../../Database/ConexionBD.php');

class EspecialidadDAO {
    //Nombre de la tabla
    private static $NOMBRE_TABLA = 'especialidades';

    // Nombre de los campos
    private static $ID_ESPECIALIDAD = 'id_especialidad';
    private static $NOMBRE = 'nombre';
    private static $DESCRIPCION = 'descripcion';
    private static $ACTIVO = 'activo';

    // Conexión a la base de datos
    private static $conexion;

    /**
     * Instanciamos la base de datos
     */
    public static function init(){
        if (self::$conexion === null) {
            self::$conexion = ConexionBD::obtenerInstancia()->obtenerBD();
        }
    }

    public function __construct() { }


    /**
     * Consigue todos los medicos
     * @return array Arreglo de objetos Especialidad
     */
    public static function todos() {
        // Inicializamos la conexion
        self::init();

        // Elaboramos la consulta
        $sql = "SELECT * FROM ". self::$NOMBRE_TABLA;
        
        // Preparamos la consulta
        $stmt = self::$conexion->prepare($sql);
        
        // Ejecutamos la consulta
        $stmt->execute();

        // Obtenemos los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Creamos un arreglo para guardar los resultados
        $especialidades = array();

        // Iteramos sobre los resultados para hacer objetos Especialidad
        foreach ($resultados as $resultado) {
            // Creamos el objeto especialidad
            $especialidad = new Especialidad();

            // Llenamos el objeto
            $especialidad -> setIdEspecialidad($resultado[self::$ID_ESPECIALIDAD]);
            $especialidad -> setNombre($resultado[self::$NOMBRE]);
            $especialidad -> setDescripcion($resultado[self::$DESCRIPCION]);
            $especialidad -> setActivo($resultado[self::$ACTIVO]);

            // Guardamos el objeto en el arreglo
            array_push($especialidades, $especialidad);
        }

        // Retornamos el arreglo de objetos
        return $especialidades;
    }

    /**
     * Obtiene por ID
     */
    public static function porId($id) {
        // Inicializamos la conexion
        self::init();
        
        // Elaboramos la consulta
        $sql = "SELECT * FROM ". self::$NOMBRE_TABLA . " WHERE ". self::$ID_ESPECIALIDAD . " = ?";

        // Preparamos la consulta
        $stmt = self::$conexion->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // Ejecutamos la consulta
        $stmt->execute();
        // Obtenemos el resultado
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        // Creamos el objeto especialidad
        $especialidad = new Especialidad();
        // Llenamos el objeto
        $especialidad -> setIdEspecialidad($resultado[self::$ID_ESPECIALIDAD]);
        $especialidad -> setNombre($resultado[self::$NOMBRE]);
        $especialidad -> setDescripcion($resultado[self::$DESCRIPCION]);
        $especialidad -> setActivo($resultado[self::$ACTIVO]);
        // Retornamos el objeto
        return $especialidad;
    }
}