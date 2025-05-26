<?php
include_once(__DIR__ . '/DAO.php');


/**
 * Clase que enlaza la base de datos con la tabla Medico.
 */
class Medico extends DAO
{
    // Constantes de la base de datos
    const NOMBRE_TABLA = 'medicos';
    const ID_MEDICO = 'id_medico';
    const ID_ESPECIALIDAD = 'id_especialidad';
    const NOMBRE = 'nombre';
    const APELLIDOS = 'apellidos';
    const CEDULA_PROFESIONAL = 'cedula_profesional';
    const EMAIL = 'email';
    const TELEFONO = 'telefono';
    const FECHA_REGISTRO = 'fecha_registro';
    const ACTIVO = 'activo';

    public function __construct()
    {
        parent::__construct();
        $this->NOMBRE_TABLA = self::NOMBRE_TABLA;
        $this->LLAVE_PRIMARIA = self::ID_MEDICO;
        $this->camposTabla = [
            'id_especialidad',
            'nombre',
            'apellidos',
            'cedula_profesional',
            'email',
            'telefono'
        ];
    }

    public function porEspecialidad($idEspecialidad) {
        $sql = 'SELECT * FROM ' . self::NOMBRE_TABLA . ' WHERE ' . self::ID_MEDICO . ' = ?';

        // Preparamos la consulta
        $stmt = $this->conexion->prepare($sql);
        $stmt -> bindParam(1,$idEspecialidad, PDO::PARAM_INT);
        $stmt -> execute();

        // Obtenemos los resultados
        $resultados = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    }
}
