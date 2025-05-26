<?php
include_once(__DIR__ . '/DAO.php');

class Paciente extends DAO
{

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

  public function __construct()
  {
    parent::__construct();
    $this->NOMBRE_TABLA = self::NOMBRE_TABLA;
    $this->LLAVE_PRIMARIA = self::ID_PACIENTE;
    $this->camposTabla = ['nombre', 'apellidos', 'fecha_nacimiento', 'genero', 'email', 'telefono', 'direccion'];
  }

  /**
   * Devuelve los pacientes en especifico de un medico
   */
  public function porMedico($id_medico)
  {
    $sql = "SELECT DISTINCT p.*
                FROM " . $this->NOMBRE_TABLA . " p
                INNER JOIN citas c ON p.id_paciente = c.id_paciente
                WHERE c.id_medico = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bindParam(1, $id_medico, PDO::PARAM_INT);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $resultados;
  }
}
