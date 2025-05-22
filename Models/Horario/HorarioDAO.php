<?php
include_once(__DIR__.'/../../Utilities/Response.php');
include_once(__DIR__.'/../../Database/ConexionBD.php');
include_once(__DIR__.'/Horario.php');

/**
 * Clase que enlaza la base de datos con la tabla Horario.
 */
class HorarioDAO {
    // Constantes de la base de datos
    const NOMBRE_TABLA = 'horarios_disponibles';
    const ID_HORARIO = 'id_horario';
    const ID_MEDICO = 'id_medico';
    const DIA_SEMANA = 'dia_semana';
    const HORA_INICIO = 'hora_inicio';
    const HORA_FIN = 'hora_fin';
    const ACTIVO = 'activo';

    // Conexión a la base de datos
    private $conexion;

    public function __construct(PDO $conexion = null) {
        // Inicializamos la conexión a la base de datos
        $this->conexion = $conexion ?: ConexionBD::obtenerInstancia()->obtenerBD();
     }

     /**
      * Obtiene todos los horarios
      * @return array Los horarios
      */
      public function todos(){
        $sql = "SELECT * FROM ". self::NOMBRE_TABLA;
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        $resultados = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        return $this -> procesarResultados($resultados);
      }

      /**
       * Obtiene un horario en especifico
       * @param int ID del horario
       * @return array El horario
       */
      public function porId($id){
        $sql = "SELECT * FROM ". self::NOMBRE_TABLA . " WHERE ". self::ID_HORARIO . " = ?";

        $stmt = $this ->conexion->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt -> execute();

        $resultado = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        // Verificamos si se encontró al medico
        if( !$resultado ) {
           return null;
        }

        return $this -> procesarResultados($resultado);
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
        $id_medico = $horario->getIdMedico();
        $dia_semana = $horario->getDiaSemana();
        $hora_inicio = $horario->getHoraInicio();
        $hora_fin = $horario->getHoraFin();
        
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
      public function actualizar($horario) {
        // Elaboramos la consulta
        $sql = "UPDATE ". self::NOMBRE_TABLA . " SET "
        . self::ID_MEDICO . " = ?,"
        . self::DIA_SEMANA . " = ?,"
        . self::HORA_INICIO . " = ?,"
        . self::HORA_FIN . " = ?,"
        . self::ACTIVO . " = ? WHERE " . self::ID_HORARIO . " = ?";

        $stmt = $this -> conexion -> prepare($sql);

        // Recuperamos las variables del objeto horario
        $id_horario = $horario->getIdHorario();
        $id_medico = $horario->getIdMedico();
        $dia_semana = $horario->getDiaSemana();
        $hora_inicio = $horario->getHoraInicio();
        $hora_fin = $horario->getHoraFin();
        $activo = $horario->getActivo();
        
        $stmt->bindParam(1, $id_medico, PDO::PARAM_INT);
        $stmt->bindParam(2, $dia_semana, PDO::PARAM_STR);
        $stmt->bindParam(3, $hora_inicio, PDO::PARAM_STR);
        $stmt->bindParam(4, $hora_fin, PDO::PARAM_STR);
        $stmt->bindParam(5, $activo, PDO::PARAM_BOOL);
        $stmt->bindParam(6, $id_horario, PDO::PARAM_INT);

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
       * Elimina un horario
       * @param int Id del horario
       * @return bool true si se elimino correctamente, false en caso contrario
       */
      public function borrar($id_horario) {
        $sql = "DELETE FROM ". self::NOMBRE_TABLA . " WHERE ". self::ID_HORARIO . " = ?";
        
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(1, $id_horario, PDO::PARAM_INT);
        $stmt->execute();

        // Verificamos si se eliminó correctamente
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

        return $this -> procesarResultados($resultados);
    }

    /**
     * Procesa múltiples resultados de la base de datos
     * @param array Resultados a procesar
     * @return array Lista de objetos Horario
     */
    private function procesarResultados($resultados) {
        $horarios = [];

        foreach($resultados as $resultado) {
            $horarios[] = $this->mapearHorario($resultado);
        }

        return $horarios;
    }

    /**
     * Mapea los resultados de la base de datos a un objeto Horario
     * @param array Filas de la base de datos
     * @return Horario Objeto horario
     */
    private function mapearHorario($resultado)
    {
        $horario = new Horario();
        $horario->setIdHorario($resultado[self::ID_HORARIO]);
        $horario->setIdMedico($resultado[self::ID_MEDICO]);
        $horario->setDiaSemana($resultado[self::DIA_SEMANA]);
        $horario->setHoraInicio($resultado[self::HORA_INICIO]);
        $horario->setHoraFin($resultado[self::HORA_FIN]);
        $horario->setActivo($resultado[self::ACTIVO]);

        return $horario;
    }
}