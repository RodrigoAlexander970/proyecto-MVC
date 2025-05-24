<?php
// Incluye la conexion a la base de datos
include_once(__DIR__.'/../Database/ConexionBD.php');
include_once(__DIR__.'/../../Utilities/ExcepcionApi.php');
/**
 * Clase abstracta DAO (Data Access Object).
 *
 * Proporciona una estructura base para los objetos de acceso a datos,
 * permitiendo la implementación de métodos comunes para interactuar con la base de datos.
 * 
 * Incluye la conexión a la base de datos a través de ConexionBD.
 *
 */
abstract class DAO
{
    protected $conexion;
    protected $NOMBRE_TABLA;
    protected $LLAVE_PRIMARIA;
    protected $camposRequeridos; 

    /**
     * Constructor de la clase DAO.
     *
     * Inicializa la conexión a la base de datos utilizando una instancia de PDO.
     * Si no se proporciona una conexión PDO, obtiene una instancia predeterminada
     * a través del método ConexionBD::obtenerInstancia()->obtenerBD().
     *
     * @param PDO|null $conexion Instancia opcional de PDO para la conexión a la base de datos.
     */
    public function __construct(PDO $conexion = null) {
        $this->conexion = $conexion ?: ConexionBD::obtenerInstancia()->obtenerBD();
    }

    /**
     * Obtiene todos los registros de la tabla especificada.
     *
     * Ejecuta una consulta SELECT * sobre la tabla definida en la propiedad NOMBRE_TABLA
     * y retorna todos los resultados como un arreglo asociativo.
     *
     * @return array Arreglo asociativo con todos los registros de la tabla.
     */
    public function todos(){
        $sql = "SELECT * FROM {$this->NOMBRE_TABLA}";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un registro de la base de datos por su ID.
     *
     * @param int $id El identificador único del registro a buscar.
     * @return array|null Retorna un arreglo asociativo con los datos del registro si existe, o null si no se encuentra.
     */
    public function porID($id) {
        $sql = "SELECT * FROM {$this->NOMBRE_TABLA} WHERE {$this->LLAVE_PRIMARIA} = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    /**
     * Elimina un registro de la base de datos según el ID proporcionado.
     *
     * @param int $id El identificador único del registro a eliminar.
     * @return bool Devuelve true si se eliminó al menos un registro, false en caso contrario.
     */
    public function borrar($id) {
        $sql = "DELETE FROM {$this->NOMBRE_TABLA} WHERE {$this->LLAVE_PRIMARIA} = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Métodos abstractos que deben ser implementados por las clases hijas para crear y actualizar registros.
     *
     * @param array $data Datos necesarios para crear o actualizar el registro.
     * @return mixed El resultado de la operación, dependiendo de la implementación.
     */
    // Métodos abstractos para crear y actualizar, porque dependen de los campos
    abstract public function crear($data);
    abstract public function actualizar($data);

    /**
     * Obtiene los campos requeridos para la entidad.
     *
     * @return array Lista de campos que son obligatorios.
     */
    public function getRequiredFields() {
        return $this->camposRequeridos;
    }
}