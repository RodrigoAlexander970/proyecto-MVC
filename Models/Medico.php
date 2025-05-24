<?php
include_once(__DIR__.'/DAO.php');


/**
 * Clase que enlaza la base de datos con la tabla Medico.
 */
class Medico extends DAO
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

    public function __construct() {
       parent::__construct();
        $this->NOMBRE_TABLA = self::NOMBRE_TABLA;
        $this->LLAVE_PRIMARIA = self::ID_MEDICO;
        $this->camposRequeridos = ['id_especialidad', 'nombre', 'apellidos', 'cedula_profesional',
        'email', 'telefono', 'fecha_registro'];
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
        $idEspecialidad = $medico['id_especialidad'];
        $nombre = $medico['nombre'];
        $apellidos = $medico['apellidos'];
        $cedulaProfesional = $medico['cedula_profesional'];
        $email = $medico['email'];
        $telefono = $medico['telefono'];

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
        $medicoExistente = $this->porID($medico['id_medico']);
        
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
        $idMedico = $medico['id_medico'];
        $idEspecialidad = $medico['id_especialidad'];
        $nombre = $medico['nombre'];
        $apellidos = $medico['apellidos'];
        $cedulaProfesional = $medico['cedula_profesional'];
        $email = $medico['email'];
        $telefono = $medico['telefono'];

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
}