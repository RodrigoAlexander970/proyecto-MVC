<?php
include_once(__DIR__ . '/../Services/MedicosService.php');
include_once(__DIR__ . '/../Utilities/Response.php');
include_once(__DIR__ . '/../Utilities/ExcepcionApi.php');
include_once(__DIR__ . '/../Services/HorariosService.php');
include_once(__DIR__ . '/../Models/Medico/Medico.php');

/**
 * Controlador para la gestión de médicos.
 */
class MedicosController
{
    private $medicosService; // Servicio de médicos
    private $horariosService; // Servicio de horarios
    private $recursosValidos = ['horarios', 'citas', 'pacientes'];
    /**
     * Constructor de la clase MedicosController.
     * Inicializa el servicio de médicos.
     */
    public function __construct(
        MedicosService $medicosService = null,
        HorariosService $horariosService = null
    ) {
        $this->medicosService = $medicosService ?: new MedicosService();
        $this->horariosService = $horariosService ?: new HorariosService();
    }

    /**
     * Procesa la solicitud GET
     * 
     * @param array $params Parámetros de la solicitud
     * @return array|object Respuesta para el cliente
     * @throws ExcepcionApi Si el recurso no es válido
     */
    public function get($params)
    {
        // Contamos la cantidad de parametros
        switch (count($params)) {
            // Se obtienen los médicos
            case 0:
            case 1:
                // Llamamos a la función obtener del servicio
                return $this->medicosService->obtener($params);

            case 2:
                // Verificamos que el subrecurso sea válido
                $subrecurso = $params[1];
                if (!in_array($subrecurso, $this->recursosValidos)) {
                    throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Subrecurso no encontrado");
                }
                
                // Procesamos según el subrecurso
                switch($subrecurso) {
                    case 'horarios':
                        return $this->horariosService->porMedico($params[0]);
                    
                    case 'citas':
                        // Implementación pendiente
                        return ['message' => 'Funcionalidad de citas no implementada'];
                    
                    case 'pacientes':
                        // Implementación pendiente
                        return ['message' => 'Funcionalidad de pacientes no implementada'];
                }
            
            default:
                throw new ExcepcionApi(Response::STATUS_TOO_MANY_PARAMETERS, "Demasiados parámetros");
        }
    }

    /**
     * Procesa la solicitud POST
     * 
     * @param array $params Parámetros de la solicitud
     * @return array Respuesta para el cliente
     * @throws ExcepcionApi Si los datos son inválidos
     */
    public function post($params)
    {
        // Obtenemos y validamos los datos del cuerpo
        $medicoData = $this->getRequestBody();
        $this->validarDatosMedico($medicoData);
        
        // Creamos el objeto médico
        $medico = new Medico();
        $medico->setIdEspecialidad($medicoData->id_especialidad);
        $medico->setNombre($medicoData->nombre);
        $medico->setApellidos($medicoData->apellidos);
        $medico->setCedulaProfesional($medicoData->cedula_profesional);
        $medico->setEmail($medicoData->email);
        $medico->setTelefono($medicoData->telefono);

        // Creamos el médico
        return $this->medicosService->crear($medico);
    }

    /**
     * Procesa la solicitud PUT
     * 
     * @param array $params Parámetros de la solicitud
     * @return array Respuesta para el cliente
     * @throws ExcepcionApi Si los datos son inválidos o faltan parámetros
     */
    public function put($params)
    {
        if (count($params) != 1) {
            throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Se requiere el ID del médico");
        }

        // Obtenemos y validamos los datos del cuerpo
        $medicoData = $this->getRequestBody();
        $this->validarDatosMedico($medicoData);
        
        // Creamos el objeto médico
        $medico = new Medico();
        $medico->setIdMedico($params[0]);
        $medico->setIdEspecialidad($medicoData->id_especialidad);
        $medico->setNombre($medicoData->nombre);
        $medico->setApellidos($medicoData->apellidos);
        $medico->setCedulaProfesional($medicoData->cedula_profesional);
        $medico->setEmail($medicoData->email);
        $medico->setTelefono($medicoData->telefono);

        return $this->medicosService->actualizar($medico);
    }

    /**
     * Procesa la solicitud DELETE
     * 
     * @param array $params Parámetros de la solicitud
     * @return array Respuesta para el cliente
     * @throws ExcepcionApi Si faltan o sobran parámetros
     */
    public function delete($params)
    {
        if (count($params) != 1) {
            throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Se requiere únicamente el ID del médico");
        }
        
        return $this->medicosService->borrar($params[0]);
    }

    /**
     * Obtiene el cuerpo de la solicitud como objeto JSON
     * 
     * @return object Datos de la solicitud
     * @throws ExcepcionApi Si los datos no son un JSON válido
     */
    private function getRequestBody()
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ExcepcionApi(Response::STATUS_BAD_REQUEST, "Datos JSON inválidos");
        }
        
        return $data;
    }
    
    /**
     * Valida los datos de un médico
     * 
     * @param object $data Datos a validar
     * @throws ExcepcionApi Si los datos son inválidos
     */
    private function validarDatosMedico($data)
    {
        $camposRequeridos = [
            'id_especialidad', 'nombre', 'apellidos', 
            'cedula_profesional', 'email', 'telefono'
        ];
        
        foreach ($camposRequeridos as $campo) {
            if (!isset($data->$campo)) {
                throw new ExcepcionApi(
                    Response::STATUS_BAD_REQUEST, 
                    "El campo '$campo' es requerido"
                );
            }
        }
        
        // Validar email
        if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            throw new ExcepcionApi(
                Response::STATUS_BAD_REQUEST,
                "El email proporcionado no es válido"
            );
        }
    }
}
