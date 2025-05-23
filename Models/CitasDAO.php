<?php
include_once(__DIR__.'/DAO.php');
include_once(__DIR__.'/../Utilities/ExcepcionApi.php');
include_once(__DIR__.'/../Utilities/Response.php');
include_once(__DIR__.'/../Database/ConexionBD.php');

class CitasDAO extends DAO {
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

    public function __construct (PDO $conexion = null) {
        parent::__construct($conexion);
        $this->NOMBRE_TABLA = 'citas';
        $this->LLAVE_PRIMARIA = 'id_cita';
        $this->campos = ['hola'];
    }

    public function actualizar($data)
    {
        
    }

    public function crear($data)
    {
        
    }
}
