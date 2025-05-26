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
    $this->camposRequeridos = ['nombre', 'apellidos', 'fecha_nacimiento', 'genero', 'telefono', 'email', 'direccion'];
  }

  public function crear($paciente)
  {
    // Elaboramos la consulta
    $sql = "INSERT INTO " . self::NOMBRE_TABLA . " ("
      . self::NOMBRE . ", "
      . self::APELLIDOS . ", "
      . self::FECHA_NACIMIENTO . ", "
      . self::GENERO . ", "
      . self::TELEFONO . ", "
      . self::EMAIL . ", "
      . self::DIRECCION . ") VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conexion->prepare($sql);
    $stmt->bindParam(1, $paciente['nombre']);
    $stmt->bindParam(2, $paciente['apellidos']);
    $stmt->bindParam(3, $paciente['fecha_nacimiento']);
    $stmt->bindParam(4, $paciente['genero']);
    $stmt->bindParam(5, $paciente['telefono']);
    $stmt->bindParam(6, $paciente['email']);
    $stmt->bindParam(7, $paciente['direccion']);

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
      throw new ExcepcionApi(Response::STATUS_INTERNAL_SERVER_ERROR, "Error al crear el médico {$e}");
    }
  }

  public function actualizar($id, $paciente)
  {
    // Verificar primero si el médico existe
    $pacienteExiste = $this->porID($id);

    if (!$pacienteExiste) {
      throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Paciente no encontrado");
    }

    $sql = "UPDATE " . self::NOMBRE_TABLA . " SET "
      . self::NOMBRE . " = ?, "
      . self::APELLIDOS . " = ?, "
      . self::FECHA_NACIMIENTO . " = ?, "
      . self::GENERO . " = ?, "
      . self::TELEFONO . " = ?, "
      . self::EMAIL . " = ?, "
      . self::DIRECCION . " = ? "
      . " WHERE " . self::ID_PACIENTE . " = ?";

    $stmt = $this->conexion->prepare($sql);
    $stmt->bindParam(1, $paciente['nombre']);
    $stmt->bindParam(2, $paciente['apellidos']);
    $stmt->bindParam(3, $paciente['fecha_nacimiento']);
    $stmt->bindParam(4, $paciente['genero']);
    $stmt->bindParam(5, $paciente['telefono']);
    $stmt->bindParam(6, $paciente['email']);
    $stmt->bindParam(7, $paciente['direccion']);
    $stmt->bindParam(8, $id, PDO::PARAM_INT);

    try {
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        return true;
      } else {
        return false;
      }
    } catch (PDOException $e) {
      throw new ExcepcionApi(Response::STATUS_INTERNAL_SERVER_ERROR, "Error al actualizar el paciente; {$e}");
    }
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
