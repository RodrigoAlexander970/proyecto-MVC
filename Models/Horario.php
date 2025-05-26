<?php
include_once(__DIR__ . '/DAO.php');

/**
 * Clase que enlaza la base de datos con la tabla Horario.
 */
class Horario extends DAO {
    // Constantes de la base de datos
    const NOMBRE_TABLA = 'horarios_disponibles';
    const ID_HORARIO = 'id_horario';
    const ID_MEDICO = 'id_medico';
    const DIA_SEMANA = 'dia_semana';
    const HORA_INICIO = 'hora_inicio';
    const HORA_FIN = 'hora_fin';

    public function __construct() {
        parent::__construct();
        $this->NOMBRE_TABLA = self::NOMBRE_TABLA;
        $this->LLAVE_PRIMARIA = self::ID_HORARIO;
        $this->camposRequeridos = ['id_medico', 'dia_semana', 'hora_inicio', 'hora_fin'];
     }

      /**
       * Crea un horario en la base de datos
       * @param Horario Objeto HOrario a registrar
       * @return boolean
       */
      public function crear($horario) {
        // Elaboramos la consulta
        $sql = "INSERT INTO ". self::NOMBRE_TABLA. " ("
        . self::ID_MEDICO . ", "
        . self::DIA_SEMANA . ", "
        . self::HORA_INICIO . ", "
        . self::HORA_FIN . ") VALUES (?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);

        // Recuperamos las variables del objeto horario
        $id_medico = $horario['id_medico'];
        $dia_semana = $horario['dia_semana'];
        $hora_inicio = $horario['hora_inicio'];
        $hora_fin = $horario['hora_fin'];
        
        $stmt->bindParam(1, $id_medico, PDO::PARAM_INT);
        $stmt->bindParam(2, $dia_semana, PDO::PARAM_STR);
        $stmt->bindParam(3, $hora_inicio, PDO::PARAM_STR);
        $stmt->bindParam(4, $hora_fin, PDO::PARAM_STR);

        try {
            $stmt->execute();

            if ($stmt->rowCount() > 0){
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new ExcepcionApi(Response::STATUS_INTERNAL_SERVER_ERROR, "Error al crear el médico");
        }
      }

      /**
       * Actualiza un horario en la base de datos
       * @param Horario Horario a actualizar
       * @return bool true si se actualizo correctamente, false en caso contrario
       */
      public function actualizar($id,$horario) {
        // Elaboramos la consulta
        $sql = "UPDATE ". self::NOMBRE_TABLA . " SET "
        . self::ID_MEDICO . " = ?,"
        . self::DIA_SEMANA . " = ?,"
        . self::HORA_INICIO . " = ?,"
        . self::HORA_FIN . " = ? WHERE " . self::ID_HORARIO . " = ?";

        $stmt = $this -> conexion -> prepare($sql);

        // Recuperamos las variables del objeto horario
        $id_horario = $horario['id_horario'];
        $id_medico = $horario['id_medico'];
        $dia_semana = $horario['dia_semana'];
        $hora_inicio = $horario['hora_inicio'];
        $hora_fin = $horario['hora_fin'];
        
        $stmt->bindParam(1, $id_medico, PDO::PARAM_INT);
        $stmt->bindParam(2, $dia_semana, PDO::PARAM_STR);
        $stmt->bindParam(3, $hora_inicio, PDO::PARAM_STR);
        $stmt->bindParam(4, $hora_fin, PDO::PARAM_STR);
        $stmt->bindParam(5, $id_horario, PDO::PARAM_INT);

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
     * Obtiene todos los horarios de un medico en especifico
     * @param $id_medico La ID del medico seleccionado
     */
    public function porMedico($id_medico) {
        // Elaboramos la consulta
        $sql = "SELECT * FROM ". self::NOMBRE_TABLA . " WHERE " . self::ID_MEDICO ." = ?";

        // Preparamos la consulta
        $stmt = $this->conexion->prepare($sql);
        $stmt -> bindParam(1,$id_medico, PDO::PARAM_INT);
        $stmt -> execute();

        // Obtenemos los resultados
        $resultados = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    }
}