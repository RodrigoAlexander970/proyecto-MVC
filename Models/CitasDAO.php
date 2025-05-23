<?php
include_once(__DIR__.'/../Utilities/ExcepcionApi.php');
include_once(__DIR__.'/../Utilities/Response.php');
include_once(__DIR__.'/../Database/ConexionBD.php');

class CitasDAO {
    // Constante de la base de datos
    const NOMBRE_TABLA = "citas";
    const ID_PACIENTE = "id_paciente";
    const ID_MEDICO = "id_medico";
    const FECHA = "fecha";
    const HORA_INICIO = "hora_inicio";
    const HORA_FIN = "hora_fin";
    const MOTIVO = "motivo";
    const ESTADO = "estado";
    const OBSERVACIONES = "observaciones";
    const FECHA_REGISTRO = "fecha_registro";

    private $conexion;
    public function __construct (PDO $conexion = null) {
        $this->conexion = $conexion ?: ConexionBD::obtenerInstancia()->obtenerBD();
    }

    public function todos() {
        $sql = "SELECT * FROM ". self::NOMBRE_TABLA;
        $stmt = $this->conexion->prepare($sql);
        $stmt -> execute();

        // Obtenemos los resultados
        $resultados = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    }
}
