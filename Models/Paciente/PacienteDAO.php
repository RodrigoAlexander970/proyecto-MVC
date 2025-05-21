<?php
include_once(__DIR__.'/../../Utilities/ExcepcionApi.php');
include_once(__DIR__.'/../../Utilities/Response.php');
include_once(__DIR__.'/../../Database/ConexionBD.php');
include_once(__DIR__.'/Paciente.php');

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

    // Conexión a la base de datos
    private $conexion;

    public function __construct( PDO $conexion = null ) {
        $this->conexion = $conexion ?: ConexionBD::obtenerInstancia()->obtenerBD();
     }

     /**
      * Obtiene todos  los pacientes
      * @return array|Paciente Arreglo de objetos Paciente
      */
      public function todos() {
        //Elaboramos la consulta
        $sql = "SELECT * FROM ". self::NOMBRE_TABLA;
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        // Obtenemos los resultados
        $resultados = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        return $this -> procesarResultados($resultados);
      }














      /**
     * Procesa múltiples resultados de la base de datos
     * @param array $resultados Resultados a procesar
     * @return array Lista de objetos Paciente
     */
    private function procesarResultados($resultados) {
        $pacientes = [];
        
        foreach ($resultados as $resultado) {
            $pacientes[] = $this -> mapearPaciente($resultado);
        }

        return $pacientes;
    }

    /**
     * Mapea un resultado de base de datos a un objeto Especialidad
     * 
     * @param array $resultado Fila de la base de datos
     * @return Especialidad Objeto especialidad
     */
    private function mapearPaciente($resultado)
    {
        $paciente = new Paciente();
        $paciente->setIdPaciente($resultado[self::ID_PACIENTE]);
        $paciente->setNombre($resultado[self::NOMBRE]);
        $paciente->setApellidos($resultado[self::APELLIDOS]);
        $paciente->setFechaNacimiento($resultado[self::FECHA_NACIMIENTO]);
        $paciente->setGenero($resultado[self::GENERO]);
        $paciente->setEmail($resultado[self::EMAIL]);
        $paciente->setTelefono($resultado[self::TELEFONO]);
        $paciente->setDireccion($resultado[self::DIRECCION]);
        $paciente->setFechaRegistro($resultado[self::FECHA_REGISTRO]);
        $paciente->setActivo($resultado[self::ACTIVO]);
        
        return $paciente;
    }
}