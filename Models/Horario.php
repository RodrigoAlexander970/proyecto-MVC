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
        $this->camposTabla = ['id_medico', 'dia_semana', 'hora_inicio', 'hora_fin'];
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