<?php
include_once(__DIR__.'/../../Utilities/ExcepcionApi.php');
include_once(__DIR__.'/../../Utilities/Response.php');
include_once(__DIR__.'/../../Database/ConexionBD.php');
include_once(__DIR__.'/Medico.php');

/**
 * Clase que enlaza la base de datos con la tabla Medico.
 */
class MedicoDAO
{
    // Constantes de la base de datos
    const NOMBRE_TABLA = 'medicos';
    const ID_MEDICO = 'id_medico';
    const ID_ESPECIALIDAD = 'id_especialidad';
    const NOMBRE = 'nombre';
    const APELLIDOS = 'apellidos';
    const CEDULA_PROFESIONAL = 'cedula_profesional';
    const EMAIL = 'email';
    const TELEFONO = 'telefono';
    const FECHA_REGISTRO = 'fecha_registro';
    const ACTIVO = 'activo';

    // Conexión a la base de datos
    private $conexion;

    public function __construct(PDO $conexion = null) {
        // Inicializamos la conexión a la base de datos
        $this->conexion = $conexion ?: ConexionBD::obtenerInstancia()->obtenerBD();
     }

    /**
     * Obtiene todos los medicos de la base de datos.
     * @return array Arreglo de objetos Medico.
     */
    public function todos(){
        // Elaboramos la consulta
        $sql = "SELECT * FROM ". self::NOMBRE_TABLA;
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        // Obtenemos los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $this -> procesarResultados($resultados);
    }

    // Método para obtener un médico por su ID
    public function porID($id) {
        // Elaboramos la consulta
        $sql = "SELECT * FROM ". self::NOMBRE_TABLA . " WHERE ". self::ID_MEDICO . " = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt -> execute();

        // Obtenemos el resultado
        $resultado = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        // Verificamos si se encontró al medico
        if( !$resultado ) {
           return null;
        }

        return $this -> procesarResultados($resultado);
    }

    /**
     * Crea un medico en la base de datos
     * @param Medico $medico Objeto Medico a registrar
     * @return boolean 
     */
     public function crear($medico) {
        // Elaboramos la consulta
        $sql = "INSERT INTO ". self::NOMBRE_TABLA . " ("
        . self::ID_ESPECIALIDAD . ", "
        . self::NOMBRE . ", "
        . self::APELLIDOS . ", "
        . self::CEDULA_PROFESIONAL . ", "
        . self::EMAIL . ", "
        . self::TELEFONO . ") VALUES (?, ?, ?, ?, ?, ?)";
        
        // Preparamos la consulta
        $stmt = $this->conexion->prepare($sql);
        
        // Recuperamos variables del objeto Medico
        $idEspecialidad = $medico->getIdEspecialidad();
        $nombre = $medico->getNombre();
        $apellidos = $medico->getApellidos();
        $cedulaProfesional = $medico->getCedulaProfesional();
        $email = $medico->getEmail();
        $telefono = $medico->getTelefono();

        // Vinculamos los parámetros a la consulta
        $stmt->bindParam(1, $idEspecialidad, PDO::PARAM_INT);
        $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(3, $apellidos, PDO::PARAM_STR);
        $stmt->bindParam(4, $cedulaProfesional, PDO::PARAM_STR);
        $stmt->bindParam(5, $email, PDO::PARAM_STR);
        $stmt->bindParam(6, $telefono, PDO::PARAM_STR);
        
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
            throw new ExcepcionApi(Response::STATUS_INTERNAL_SERVER_ERROR, "Error al crear el médico");
        }
     }

     /**
      * Actualiza un médico en la base de datos
      * @param Medico $medico Objeto Medico a actualizar
      * @return bool true si se actualizó correctamente, false en caso contrario
      */
      public function actualizar($medico) {

        // Verificar primero si el médico existe
        $medicoExistente = $this->porID($medico->getIdMedico());
        
        if (!$medicoExistente) {
            throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Médico no encontrado");
        }
    
        // Elaboramos la consulta
        $sql = "UPDATE ". self::NOMBRE_TABLA . " SET "
        . self::ID_ESPECIALIDAD . " = ?, "
        . self::NOMBRE . " = ?, "
        . self::APELLIDOS . " = ?, "
        . self::CEDULA_PROFESIONAL . " = ?, "
        . self::EMAIL . " = ?, "
        . self::TELEFONO . " = ? WHERE ". self::ID_MEDICO . " = ?";

        // Preparamos la consulta
        $stmt = $this->conexion->prepare($sql);

        // Recuperamos variables del objeto Medico
        $idMedico = $medico->getIdMedico();
        $idEspecialidad = $medico->getIdEspecialidad();
        $nombre = $medico->getNombre();
        $apellidos = $medico->getApellidos();
        $cedulaProfesional = $medico->getCedulaProfesional();
        $email = $medico->getEmail();
        $telefono = $medico->getTelefono();

        // Vinculamos los parámetros a la consulta
        $stmt->bindParam(1, $idEspecialidad, PDO::PARAM_INT);
        $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(3, $apellidos, PDO::PARAM_STR);
        $stmt->bindParam(4, $cedulaProfesional, PDO::PARAM_STR);
        $stmt->bindParam(5, $email, PDO::PARAM_STR);
        $stmt->bindParam(6, $telefono, PDO::PARAM_STR);
        $stmt->bindParam(7, $idMedico, PDO::PARAM_INT);

        // Ejecutamos la consulta
        $stmt->execute();

        // Verificamos si se actualizó correctamente
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
      }

     /**
      * Elimina un médico
      * @param int $id ID del médico a eliminar
      * @return bool true si se eliminó correctamente, false en caso contrario
      */
      public function borrar($id_medico){
        // Elaboramos la consulta
        $sql = "DELETE FROM ". self::NOMBRE_TABLA . " WHERE ". self::ID_MEDICO . " = ?";
        // Preparamos la consulta
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(1, $id_medico, PDO::PARAM_INT);

        // Ejecutamos la consulta
        $stmt->execute();

        // Verificamos si se eliminó correctamente
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
      }

    /**
     * Procesa múltiples resultados de la base de datos
     * 
     * @param array $resultados Resultados a procesar
     * @return array Lista de objetos Medico
     */
    private function procesarResultados($resultados)
    {
        $medicos = [];
        
        foreach ($resultados as $resultado) {
            $medicos[] = $this->mapearMedico($resultado);
        }
        
        return $medicos;
    }
    
    /**
     * Mapea un resultado de base de datos a un objeto Medico
     * 
     * @param array $resultado Fila de la base de datos
     * @return Medico Objeto médico
     */
    private function mapearMedico($resultado)
    {

        $medico = new Medico();
        $medico->setIdMedico($resultado[self::ID_MEDICO]);
        $medico->setIdEspecialidad($resultado[self::ID_ESPECIALIDAD]);
        $medico->setNombre($resultado[self::NOMBRE]);
        $medico->setApellidos($resultado[self::APELLIDOS]);
        $medico->setCedulaProfesional($resultado[self::CEDULA_PROFESIONAL]);
        $medico->setEmail($resultado[self::EMAIL]);
        $medico->setTelefono($resultado[self::TELEFONO]);
        
        if (isset($resultado[self::FECHA_REGISTRO])) {
            $medico->setFechaRegistro($resultado[self::FECHA_REGISTRO]);
        }
        
        if (isset($resultado[self::ACTIVO])) {
            $medico->setActivo($resultado[self::ACTIVO]);
        }
        
        return $medico;
    }
}