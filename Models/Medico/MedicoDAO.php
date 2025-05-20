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

    // Método para obtener un médico por su ID
    public static function porID($id) {
        self::init();

        // Elaboramos la consulta
        $sql = "SELECT * FROM ". self::$NOMBRE_TABLA . " WHERE ". self::$ID_MEDICO . " = ?";

        // Preparamos la consulta
        $stmt = self::$conexion->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        // Ejecutamos la consulta
        $stmt -> execute();

        // Obtenemos el resultado
        $resultado = $stmt -> fetch(PDO::FETCH_ASSOC);

        // Verificamos si se encontró al medico
        if( !$resultado ) {
            throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "No se encontró el médico con ID: $id");
        }

        // Convertimos a objeto Medico
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

        return $medico;
    }

    /**
     * Crea un medico en la base de datos
     *  @param Medico $medico Objeto Medico a registrar
     */

     public static function crear($medico) {
        self::init();

        // Elaboramos la consulta
        $sql = "INSERT INTO "
        . self::$NOMBRE_TABLA . " ("
        . self::$ID_ESPECIALIDAD . ", "
        . self::$NOMBRE . ", "
        . self::$APELLIDOS . ", "
        . self::$CEDULA_PROFESIONAL . ", "
        . self::$EMAIL . ", "
        . self::$TELEFONO . ", "
        . self::$PASSWORD 
        . ") VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        // Preparamos la consulta
        $stmt = self::$conexion->prepare($sql);
        
        // Recuperamos variables del objeto Medico
        $idEspecialidad = $medico->getIdEspecialidad();
        $nombre = $medico->getNombre();
        $apellidos = $medico->getApellidos();
        $cedulaProfesional = $medico->getCedulaProfesional();
        $email = $medico->getEmail();
        $telefono = $medico->getTelefono();
        $password = $medico->getPassword();

        // Vinculamos los parámetros a la consulta
        $stmt->bindParam(1, $idEspecialidad, PDO::PARAM_INT);
        $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(3, $apellidos, PDO::PARAM_STR);
        $stmt->bindParam(4, $cedulaProfesional, PDO::PARAM_STR);
        $stmt->bindParam(5, $email, PDO::PARAM_STR);
        $stmt->bindParam(6, $telefono, PDO::PARAM_STR);
        $stmt->bindParam(7, $password, PDO::PARAM_STR);
        
        // Ejecutamos la consulta
        $stmt->execute();

        // Revisamos que se haya ejecutado con exito
        if ($stmt->rowCount() > 0) {
            // Obtenemos el ID del médico creado
            $medico->setIdMedico(self::$conexion->lastInsertId());
            return $medico;
        } else {
            return null;
        }
     }

     /**
      * Actualiza un médico en la base de datos
      * @param Medico $medico Objeto Medico a actualizar
      * @return bool true si se actualizó correctamente, false en caso contrario
      */
      public static function actualizar($medico) {
        self::init();

        // Elaboramos la consulta
        $sql = "UPDATE ". self::$NOMBRE_TABLA . " SET "
        . self::$ID_ESPECIALIDAD . " = ?, "
        . self::$NOMBRE . " = ?, "
        . self::$APELLIDOS . " = ?, "
        . self::$CEDULA_PROFESIONAL . " = ?, "
        . self::$EMAIL . " = ?, "
        . self::$TELEFONO . " = ?, "
        . self::$PASSWORD . " = ? WHERE ". self::$ID_MEDICO . " = ?";

        // Preparamos la consulta
        $stmt = self::$conexion->prepare($sql);

        // Recuperamos variables del objeto Medico
        $idMedico = $medico->getIdMedico();
        $idEspecialidad = $medico->getIdEspecialidad();
        $nombre = $medico->getNombre();
        $apellidos = $medico->getApellidos();
        $cedulaProfesional = $medico->getCedulaProfesional();
        $email = $medico->getEmail();
        $telefono = $medico->getTelefono();
        $password = $medico->getPassword();

        // Vinculamos los parámetros a la consulta
        $stmt->bindParam(1, $idEspecialidad, PDO::PARAM_INT);
        $stmt->bindParam(2, $nombre, PDO::PARAM_STR);
        $stmt->bindParam(3, $apellidos, PDO::PARAM_STR);
        $stmt->bindParam(4, $cedulaProfesional, PDO::PARAM_STR);
        $stmt->bindParam(5, $email, PDO::PARAM_STR);
        $stmt->bindParam(6, $telefono, PDO::PARAM_STR);
        $stmt->bindParam(7, $password, PDO::PARAM_STR);
        $stmt->bindParam(8, $idMedico, PDO::PARAM_INT);

        
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
      * Elimina un médico de la base de datos
      * @param int $id ID del médico a eliminar
      * @return bool true si se eliminó correctamente, false en caso contrario
      */
      public static function borrar($id_medico){
        self::init();

        // Elaboramos la consulta
        $sql = "DELETE FROM ". self::$NOMBRE_TABLA . " WHERE ". self::$ID_MEDICO . " = ?";
        // Preparamos la consulta
        $stmt = self::$conexion->prepare($sql);
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
}