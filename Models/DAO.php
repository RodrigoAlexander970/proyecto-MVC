<?php
// Incluye la conexion a la base de datos
include_once(__DIR__.'/../Database/ConexionBD.php');
include_once(__DIR__.'/../Utilities/ExcepcionApi.php');
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
    protected $camposTabla; 

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
        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { 
                throw new ExcepcionApi(409, "No se puede eliminar el registro porque existen registros relacionados.", $e->getCode());
            }
            throw $e;
        }
    }

        /**
     * Crea un nuevo registro en la base de datos
     * @param array $data Datos del registro
     * @return bool true si se creó correctamente, false en caso contrario
     */
    public function crear($data)
    {
        $placeholders = str_repeat('?,', count($this->camposTabla) - 1) . '?';
        
        $sql = "INSERT INTO {$this->NOMBRE_TABLA} (" 
             . implode(', ', $this->camposTabla) 
             . ") VALUES ($placeholders)";

        $stmt = $this->conexion->prepare($sql);
        
        $this->bindearParametros($stmt, $data, $this->camposTabla);

        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Código SQLSTATE para violación de restricción de integridad (duplicado)
            throw new ExcepcionApi(409, "Ya existe un registro con los mismos datos únicos.", $e->getCode());
            }
            throw new ExcepcionApi(Response::STATUS_INTERNAL_SERVER_ERROR, "Error al crear el registro: " . $e->getMessage());
        }
    }

    /**
     * Actualiza un registro existente en la base de datos
     * @param mixed $id ID del registro a actualizar
     * @param array $data Datos a actualizar
     * @return bool true si se actualizó correctamente, false en caso contrario
     */
    public function actualizar($id, $data)
    {
        // Verificar si el registro existe
        if (!$this->porID($id)) {
            throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Registro no encontrado");
        }

        $setClause = implode(' = ?, ', $this->camposTabla) . ' = ?';
        
        $sql = "UPDATE {$this->NOMBRE_TABLA} SET $setClause WHERE {$this->LLAVE_PRIMARIA} = ?";
        
        $stmt = $this->conexion->prepare($sql);
        
        // Bindear los campos de datos
        $this->bindearParametros($stmt, $data, $this->camposTabla);
        
        // Bindear el ID para el WHERE
        $stmt->bindParam(count($this->camposTabla) + 1, $id, PDO::PARAM_INT);
        
        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
        }  catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Código SQLSTATE para violación de restricción de integridad (duplicado)
            throw new ExcepcionApi(409, "Ya existe un registro con los mismos datos únicos.", $e->getCode());
            }
            throw new ExcepcionApi(Response::STATUS_INTERNAL_SERVER_ERROR, "Error al crear el registro: " . $e->getMessage());
        }
    }

    /**
     * Bindea los parámetros a la consulta preparada
     * @param PDOStatement $stmt Consulta preparada
     * @param array $data Datos a bindear
     * @param array $campos Campos en el orden correcto
     */
    protected function bindearParametros($stmt, $data, $campos)
    {
        foreach ($campos as $index => $campo) {
            $valor = $data[$campo] ?? null;
            $tipo = $this->obtenerTipoPDO($campo, $valor);
            $stmt->bindValue($index + 1, $valor, $tipo);
        }
    }

    /**
     * Determina el tipo de datos PDO para un campo
     * @param string $campo Nombre del campo
     * @param mixed $valor Valor del campo
     * @return int Constante PDO::PARAM_*
     */
    protected function obtenerTipoPDO($campo, $valor)
    {
        if (is_int($valor)) {
            return PDO::PARAM_INT;
        } elseif (is_bool($valor)) {
            return PDO::PARAM_BOOL;
        } elseif (is_null($valor)) {
            return PDO::PARAM_NULL;
        } else {
            return PDO::PARAM_STR;
        }
    }


    /**
     * Obtiene los campos requeridos para la entidad.
     *
     * @return array Lista de campos que son obligatorios.
     */
    public function getRequiredFields() {
        return $this->camposTabla;
    }
}