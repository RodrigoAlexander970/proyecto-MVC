<?php
include_once(__DIR__ . '/DAO.php');


class Especialidad extends DAO {
    // Constantes de la base de datos
    Const NOMBRE_TABLA = 'especialidades';
    const ID_ESPECIALIDAD = 'id_especialidad';
    const NOMBRE = 'nombre';
    const DESCRIPCION = 'descripcion';

    public function __construct() {
        parent::__construct();
        $this->NOMBRE_TABLA = self::NOMBRE_TABLA;
        $this->LLAVE_PRIMARIA = self::ID_ESPECIALIDAD;
        $this->camposTabla = ['nombre', 'descripcion'];
    }
}