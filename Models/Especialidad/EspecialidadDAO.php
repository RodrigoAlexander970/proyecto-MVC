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
        $stmt = $this->conexion->prepare($sql);
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
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();

        // Obtenemos el resultado
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($resultado) {
            return $this -> mapearEspecialidad($resultado);
        } else {
            return null;
        }
        
    }

    /** Registra una especilidad en la base de datos
     * @param Especialidad
     * @return boolean
     */
    public function crear($especialidad) {
        // Elaboramos la consulta
        $sql = "INSERT INTO ". self::NOMBRE_TABLA . " ("
        . self::NOMBRE . ", "
        . self::DESCRIPCION . ") VALUES (?, ?)";

        $stmt = $this->conexion->prepare($sql);

        $nombre = $especialidad->getNombre();
        $descripcion = $especialidad->getDescripcion();

        $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(2, $descripcion, PDO::PARAM_STR);
        try {
            // Ejecutamos la consulta
            $stmt->execute();
            // Revisamos que se haya ejecutado con exito
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new ExcepcionApi(Response::STATUS_INTERNAL_SERVER_ERROR, "Error al crear la especialidad");
        }
    }

    /**
     * Actualiza una especialidad en la base de datos
     * @param Especialidad
     * @return boolean
     */
    public function actualizar($especialidad) {

        // Verificar primero si la especialidad existe
        $especialidadExiste = $this->porID($especialidad->getIdEspecialidad());
        
        if (!$especialidadExiste) {
            throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Especialidad no encontrada");
        }

        // Elaboramos la consulta
        $sql = "UPDATE ". self::NOMBRE_TABLA . " SET "
        . self::NOMBRE . " = ?, "
        . self::DESCRIPCION . " = ? WHERE ". self::ID_ESPECIALIDAD . " = ?";

        $stmt = $this->conexion->prepare($sql);

        $idEspecialidad = $especialidad->getIdEspecialidad();
        $nombre = $especialidad->getNombre();
        $descripcion = $especialidad->getDescripcion();

        $stmt->bindParam(1, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(2, $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(3, $idEspecialidad, PDO::PARAM_INT);

        // Ejecutamos la consulta
        $stmt->execute();
        // Revisamos que se actualizado correctamente
        if($stmt->rowCount() > 0){
            return true;
        } else{
            return false;
        }
    }

    /**
     * Elimina una especialidad
     * @param int
     * @return boolean
     */
    public function borrar($id_especialidad) {
        // Elaboramos la consulta
        $sql = "DELETE FROM ". self::NOMBRE_TABLA . " WHERE ". self::ID_ESPECIALIDAD . " = ?";

        // Preparamos la consulta
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(1, $id_especialidad, PDO::PARAM_INT);

        try{
            $stmt->execute();

            // Revisamos que se haya eliminado correctamente
            if($stmt->rowCount() > 0){
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
        // Verifica si es un error de integridad referencial (código 23000)
        if ($e->getCode() == '23000') {
            return 'constraint_violation';
        }
            // Otros errores, relanza la excepción
            throw $e;
        }
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