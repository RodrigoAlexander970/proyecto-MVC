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
        $horario->setIdHorario($resultado[self::ID_MEDICO]);
        $horario->setIdMedico($resultado[self::ID_MEDICO]);
        $horario->setDiaSemana($resultado[self::DIA_SEMANA]);
        $horario->setHoraInicio($resultado[self::HORA_INICIO]);
        $horario->setHoraFin($resultado[self::HORA_FIN]);
        $horario->setActivo($resultado[self::ACTIVO]);

        return $horario;
    }
}