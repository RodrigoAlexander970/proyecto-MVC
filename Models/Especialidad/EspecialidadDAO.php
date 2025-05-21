<?php
include_once(__DIR__.'/../../Utilities/ExcepcionApi.php');
include_once(__DIR__.'/../../Utilities/Response.php');
include_once(__DIR__.'/../../Database/ConexionBD.php');
include_once(__DIR__.'/Especialidad.php');


class EspecialidadDAO {
    // Constantes de la base de datos
    const NOMBRE_TABLA = 'especialidades';
    const ID_ESPECIALIDAD = 'id_especialidad';
    const NOMBRE = 'nombre';
    const DESCRIPCION = 'descripcion';
    const ACTIVO = 'activo';

    // Conexión a la base de datos
    private $conexion;

    public function __construct( PDO $conexion = null ) {
        $this->conexion = $conexion ?: ConexionBD::obtenerInstancia()->obtenerBD();
     }


    /**
     * Obtiene todas las especialidades
     * @return array Arreglo de objetos Especialidad
     */
    public function todos() {
        // Elaboramos la consulta
        $sql = "SELECT * FROM ". self::NOMBRE_TABLA;
        $stmt = self::$conexion->prepare($sql);
        $stmt->execute();

        // Obtenemos los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        return $this -> procesarResultados($resultados);
    }

    /**
     * Obtiene por ID
     */
    public function porId($id) {
        // Elaboramos la consulta
        $sql = "SELECT * FROM ". self::NOMBRE_TABLA . " WHERE ". self::ID_ESPECIALIDAD . " = ?";

        // Preparamos la consulta
        $stmt = self::$conexion->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        // Obtenemos el resultado
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $this -> mapearEspecialidad($resultado);
    }

    /**
     * Procesa múltiples resultados de la base de datos
     * @param array $resultados Resultados a procesar
     * @return array Lista de objetos Especialidad
     */
    private function procesarResultados($resultados) {
        $especialidades = [];
        
        foreach ($resultados as $resultado) {
            $especialidades[] = $this -> mapearEspecialidad($resultado);
        }

        return $especialidades;
    }

    /**
     * Mapea un resultado de base de datos a un objeto Especialidad
     * 
     * @param array $resultado Fila de la base de datos
     * @return Especialidad Objeto especialidad
     */
    private function mapearEspecialidad($resultado)
    {
        $especialidad = new Especialidad();
        $especialidad->setIdEspecialidad($resultado[self::ID_ESPECIALIDAD]);
        $especialidad->setNombre($resultado[self::NOMBRE]);
        $especialidad->setDescripcion($resultado[self::DESCRIPCION]);
        $especialidad->setActivo($resultado[self::ACTIVO]);
        
        return $especialidad;
    }
}