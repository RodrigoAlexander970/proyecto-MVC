<?php
include_once(__DIR__ . '/DAO.php');

class Paciente extends DAO
{

  // Constantes de la base de datos
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

  public function __construct()
  {
    parent::__construct();
    $this->NOMBRE_TABLA = "pacientes";
    $this->LLAVE_PRIMARIA = "id_paciente";
    $this->camposRequeridos = ['nombre', 'apellidos', 'fecha_nacimiento', 'genero', 'telefono', 'email', 'direccion'];
  }

  public function actualizar($data) {}

  public function crear($data) {}

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
