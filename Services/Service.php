<?php

include_once (__DIR__.'/../Utilities/Response.php');
include_once (__DIR__.'/../Utilities/ExcepcionApi.php');

/**
 * Clase abstracta ServicioBase
 *
 * Proporciona una estructura base para los servicios, incluyendo validación de campos obligatorios,
 * verificación de existencia y definición de métodos CRUD abstractos.
 *
 * @property mixed $dao Instancia del objeto de acceso a datos (DAO).
 * @property array $camposObligatorios Lista de campos obligatorios definidos por el DAO.
 */
abstract class Service {
    protected $dao;
    protected $camposObligatorios;

    /**
     * Constructor de la clase ServicioBase.
     *
     * @param mixed $dao Objeto de acceso a datos que debe implementar el método getRequiredFields().
     */
    public function __construct($dao) {
        $this->dao = $dao;
        $this->camposObligatorios = $dao->getRequiredFields();
    }

    /**
     * Valida que todos los campos obligatorios estén presentes en los datos proporcionados.
     *
     * @param array $data Datos a validar.
     * @throws ExcepcionApi Si falta algún campo obligatorio.
     */
    protected function validarCamposObligatorios($data) {
        foreach ($this->camposObligatorios as $campo) {
            if (empty($data[$campo])) {
                throw new ExcepcionApi(
                    Response::STATUS_BAD_REQUEST,
                    "Falta el campo obligatorio: $campo");
            }
        }
    }

    /**
     * Verifica si existe un registro con el identificador proporcionado.
     *
     * @param mixed $id Identificador del registro a buscar.
     * @return bool True si existe, False en caso contrario.
     */
    protected function existe($id) {
        return $this->dao->porID($id) !== null;
    }


    /**
     * Método abstracto para obtener registros.
     * Debe ser implementado por las clases hijas.
     */
    abstract public function obtener($params);

    /**
     * Método abstracto para crear un nuevo registro.
     * Debe ser implementado por las clases hijas.
     */
    abstract public function crear($params);

    /**
     * Método abstracto para actualizar un registro existente.
     * Debe ser implementado por las clases hijas.
     */
    abstract public function actualizar($id, $data);

    /**
     * Método abstracto para borrar un registro.
     * Debe ser implementado por las clases hijas.
     */
    abstract public function borrar($params);
}