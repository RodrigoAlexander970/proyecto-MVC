<?php
include_once(__DIR__ . '/DAO.php');


class Especialidad extends DAO {
    // Constantes de la base de datos
    const ID_ESPECIALIDAD = 'id_especialidad';
    const NOMBRE = 'nombre';
    const DESCRIPCION = 'descripcion';
    const ACTIVO = 'activo';

    public function __construct() {
        parent::__construct();
        $this->NOMBRE_TABLA = 'especialidades';
        $this->LLAVE_PRIMARIA = 'id_especialidad';
        $this->camposRequeridos = ['nombre', 'descripcion'];
    }

    /** Registra una especilidad en la base de datos
     * @param Especialidad
     * @return boolean
     */
    public function crear($especialidad) {
        // Elaboramos la consulta
        $sql = "INSERT INTO ". $this->NOMBRE_TABLA . " ("
        . self::NOMBRE . ", "
        . self::DESCRIPCION . ") VALUES (?, ?)";

        $stmt = $this->conexion->prepare($sql);

        $nombre = $especialidad['nombre'];
        $descripcion = $especialidad['descripcion'];

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
        $especialidadExiste = $this->porID($especialidad['id_especialidad']);
        
        if (!$especialidadExiste) {
            throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Especialidad no encontrada");
        }

        // Elaboramos la consulta
        $sql = "UPDATE ". $this->NOMBRE_TABLA . " SET "
        . self::NOMBRE . " = ?, "
        . self::DESCRIPCION . " = ? WHERE ". self::ID_ESPECIALIDAD . " = ?";

        $stmt = $this->conexion->prepare($sql);

        $idEspecialidad = $especialidad['id_especialidad'];
        $nombre = $especialidad['nombre'];
        $descripcion = $especialidad['descripcion'];

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
}