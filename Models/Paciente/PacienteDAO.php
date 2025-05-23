<?php
include_once(__DIR__.'/../../Utilities/ExcepcionApi.php');
include_once(__DIR__.'/../../Utilities/Response.php');
include_once(__DIR__.'/../../Database/ConexionBD.php');

class PacienteDAO {
    // Constantes de la base de datos
    const NOMBRE_TABLA = 'pacientes';
    const ID_PACIENTE = 'id_paciente';
    const NOMBRE = 'nombre';
    const APELLIDOS = 'apellidos';
    const FECHA_NACIMIENTO = 'fecha_nacimiento';
    const GENERO = 'genero';
    const TELEFONO = 'telefono';
    const EMAIL = 'email';
    const DIRECCION = 'direccion';
    const FECHA_REGISTRO = 'fecha_registro';
    const ACTIVO = 'activo';

    // Variables para el objeto
    const campos_obligatorios = ['nombre', 'apellidos', 'fecha_nacimiento', 'genero', 'telefono', 'email', 'direccion'];
    const campos_opcionales = ['activo'];

    // Conexión a la base de datos
    private $conexion;

    public function __construct( PDO $conexion = null ) {
        $this->conexion = $conexion ?: ConexionBD::obtenerInstancia()->obtenerBD();
     }

     /**
      * Obtiene todos  los pacientes
      * @return array Arreglo de objetos Paciente
      */
      public function todos() {
        //Elaboramos la consulta
        $sql = "SELECT * FROM ". self::NOMBRE_TABLA;
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        // Obtenemos los resultados
        $resultados = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
      }

      /**
       * Obtiene un paciente en especifico
       * @param int ID del paciente a buscar
       * @return array Datos del paciente
       */
      public function porId($id_paciente){
        $sql = "SELECT * FROM " . self::NOMBRE_TABLA . " WHERE " . self::ID_PACIENTE . " = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(1, $id_paciente, PDO::PARAM_INT);
        $stmt -> execute();

        // Obtenemos el resultado
        $resultado = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        // Verificamos si se encontró al medico
        if( !$resultado ) {
           return null;
        }

        // Retornamos el objeto
        return $resultado;
      }




      /**
       * Devuelve los pacientes en especifico de un medico
       */
      public function porMedico($id_medico) {
        $sql = "SELECT DISTINCT p.*
                FROM " . self::NOMBRE_TABLA . " p
                INNER JOIN citas c ON p.id_paciente = c.id_paciente
                WHERE c.id_medico = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(1, $id_medico, PDO::PARAM_INT);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultados;
    }
}