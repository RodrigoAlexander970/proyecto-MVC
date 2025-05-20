<?php
include_once(__DIR__.'/../../Utilities/Response.php');
include_once(__DIR__.'/../../Database/ConexionBD.php');
include_once(__DIR__.'/Horario.php');

class HorarioDAO {
    // Nombre de la tabla
    private static $NOMBRE_TABLA = 'horarios_disponibles';

    // Campos de la tabla
    private static $ID_HORARIO = 'id_horario';
    private static $ID_MEDICO = 'id_medico';
    private static $DIA_SEMANA = 'dia_semana';
    private static $HORA_INICIO = 'hora_inicio';
    private static $HORA_FIN = 'hora_fin';
    private static $ACTIVO = 'activo';

    // Conexión a la base de datos
    private static $conexion;

    /**
     * Instancia la base de datos
     */
    public static function init(){
        if (self::$conexion === null) {
            self::$conexion = ConexionBD::obtenerInstancia()->obtenerBD();
        }
    }

    public function __construct() { }

    /**
     * Obtiene todos los horarios de un medico en especifico
     * @param $id_medico La ID del medico seleccionado
     */
    public static function porMedico($id_medico) {
        // Inicializamos la conexion
        self::init();

        // Elaboramos la consulta
        $sql = "SELECT * FROM ". self::$NOMBRE_TABLA . " WHERE " . self::$ID_MEDICO ." = ?";

        // Preparamos la consulta
        $stmt = self::$conexion->prepare($sql);
        $stmt -> bindParam(1,$id_medico, PDO::PARAM_INT);

        // Ejecutamos la consulta
        $stmt -> execute();

        // Obtenemos los resultados
        $resultados = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        // // Creamos un arreglo de objetos Medico
        $horarios = array();

        foreach ($resultados as $resultado) {
            $horario = new Horario();
            $horario->setIdHorario($resultado[self::$ID_HORARIO]);
            $horario->setIdMedico($resultado[self::$ID_MEDICO]);
            $horario->setDiaSemana($resultado[self::$DIA_SEMANA]);
            $horario->setHoraInicio($resultado[self::$HORA_INICIO]);
            $horario->setHoraFin($resultado[self::$HORA_FIN]);
            $horario->setActivo($resultado[self::$ACTIVO]);

            // Agregamos el objeto Horario al arreglo
            array_push($horarios, $horario);
        }

        return $horarios;
    }
}