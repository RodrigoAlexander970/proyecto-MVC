<?php
include_once(__DIR__ . '/../Utilities/Response.php');
include_once(__DIR__ . '/../Utilities/ExcepcionApi.php');

/**
 * Clase abstracta base para controladores REST
 */
abstract class Controller
{
    protected $service; // Servicio principal del controlador
    protected $recursosValidos = []; // Subrecursos válidos para GET anidados

    public function __construct()
    {
        $this->inicializarServicio();
    }

    /**
     * Método abstracto para inicializar el servicio principal
     * Debe ser implementado por cada controlador específico
     */
    abstract protected function inicializarServicio();

    /**
     * Procesa solicitudes GET
     * @param array $params Parámetros de la URL
     * @return array|object Respuesta
     */
    public function get($params)
    {
        switch (count($params)) {
            case 0:
            case 1:
                return $this->service->obtener($params);
            
            case 2:
                return $this->procesarSubrecurso($params);
            
            default:
                throw new ExcepcionApi(Response::STATUS_TOO_MANY_PARAMETERS, "Demasiados parámetros");
        }
    }

    /**
     * Procesa solicitudes POST
     * @param array $params Parámetros de la URL
     * @return array Respuesta
     */
    public function post($params = [])
    {
        $data = $this->getRequestBody();
        return $this->service->crear($data);
    }

    /**
     * Procesa solicitudes PUT
     * @param array $params Parámetros de la URL
     * @return array Respuesta
     */
    public function put($params)
    {
        if (count($params) != 1) {
            throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Se requiere el ID del recurso");
        }

        $data = $this->getRequestBody();
        return $this->service->actualizar($params[0], $data);
    }

    /**
     * Procesa solicitudes DELETE
     * @param array $params Parámetros de la URL
     * @return array Respuesta
     */
    public function delete($params)
    {
        if (count($params) != 1) {
            throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Se requiere únicamente el ID del recurso");
        }
        
        return $this->service->borrar($params[0]);
    }

    /**
     * Procesa subrecursos en solicitudes GET anidadas
     * @param array $params Parámetros de la URL
     * @return array|object Respuesta
     */
    protected function procesarSubrecurso($params)
    {
        $subrecurso = $params[1];
        
        if (!in_array($subrecurso, $this->recursosValidos)) {
            throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Subrecurso no encontrado");
        }
        
        return $this->manejarSubrecurso($params[0], $subrecurso);
    }

    /**
     * Maneja subrecursos específicos - puede ser sobrescrito por clases hijas
     * @param string $id ID del recurso principal
     * @param string $subrecurso Nombre del subrecurso
     * @return array|object Respuesta
     */
    protected function manejarSubrecurso($id, $subrecurso)
    {
        throw new ExcepcionApi(Response::STATUS_NOT_IMPLEMENTED, "Subrecurso '$subrecurso' no implementado");
    }

    /**
     * Obtiene el cuerpo de la solicitud como objeto JSON
     * @return object Datos de la solicitud
     * @throws ExcepcionApi Si los datos no son JSON válido
     */
    protected function getRequestBody()
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body,true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Datos JSON inválidos");
        }
        
        return $data;
    }
}