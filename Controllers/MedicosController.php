<?php
include_once (__DIR__ . '/../Services/MedicosService.php');
include_once(__DIR__.'/../Utilities/Response.php');
include_once(__DIR__.'/../Utilities/ExcepcionApi.php');
include_once(__DIR__.'/../Services/HorariosService.php');
include_once(__DIR__.'/../Models/Medico/Medico.php');
class MedicosController
{
    private $recursos_validos = ['horarios'];

    // Almacena el servicio de médicos
    private static $service;
    private static $horariosService;

    public static function init()
    {
        if (self::$service === null) {
            self::$service = new MedicosService();
        }

        if (self::$horariosService === null) {
            self::$horariosService = new HorariosService();
        }
    }
    /**
     * Constructor de la clase MedicosController.
     * Inicializa el servicio de médicos.
     */
    public function __construct() { }

    /**
     * Prosesa la solicitud GET
     * @param array $params Parámetros de la solicitud [0] => ID del medico, [1] => subrecurso
     * 
     * @return array Respuesta de la API
     */
    public static function get($params)
    {
        // $params[0] Para ids
        // $params[1] Para subrecursos

        // Inicializamos los servicios
        self::init();

        // Contamos la cantidad de parametros
        switch(count($params)) {

            // Se obtienen los médicos
            case 0:
            case 1:
                // Llamamos a la función obtener del servicio
                return self::$service->obtener($params);
            break;

            case 2:
                // Conseguimos el subrecurso
                $subrecurso = $params[1];

                // Decidimos que hacer en base al recurso
                switch($subrecurso) {
                    case 'horarios':
                        return self::$horariosService->porMedico($params[0]);
                    break;

                    case 'citas':
                        // return self::$citasService->porMedico($params);
                        return 'citas';
                    break;

                    case 'pacientes':
                        // return self::$pacientesService->porMedico($params);
                        return 'pacientes';
                    default:
                        throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Subrecurso no encontrado");
                }
        }
    }

    /**
     * Procesa la solicitud POST
     */
    public static function post($params)
    {
        self::init();

        // Body recibido
        $body = file_get_contents('php://input');
        $medicoJSON = json_decode($body);

        $medico = new Medico();
        $medico -> setIdEspecialidad($medicoJSON->id_especialidad);
        $medico -> setNombre($medicoJSON->nombre);
        $medico -> setApellidos($medicoJSON->apellidos);
        $medico -> setCedulaProfesional($medicoJSON->cedula_profesional);
        $medico -> setEmail($medicoJSON->email);
        $medico -> setTelefono($medicoJSON->telefono);
        $medico -> setPassword($medicoJSON->password);
        
        // Obtenemos los datos del body
        return self::$service->crear($medico);
    }

    /**
     * Procesa la solicitud PUT
     */
    public static function put($params){
        self::init();
        
        if(count($params) != 1){
            throw new ExcepcionApi(Response::STATUS_TOO_MANY_PARAMETERS, "Ruta invalida");
        }

        // Body recibido
        $body = file_get_contents('php://input');
        $medicoJSON = json_decode($body);

        $medico = new Medico();
        $medico -> setIdMedico($params[0]);
        $medico -> setIdEspecialidad($medicoJSON->id_especialidad);
        $medico -> setNombre($medicoJSON->nombre);
        $medico -> setApellidos($medicoJSON->apellidos);
        $medico -> setCedulaProfesional($medicoJSON->cedula_profesional);
        $medico -> setEmail($medicoJSON->email);
        $medico -> setTelefono($medicoJSON->telefono);
        $medico -> setPassword($medicoJSON->password);

        $response = self::$service->actualizar($medico);

        return $response;
    }

    /**
     * Procesa la solicitud DELETE
     */
    public static function delete($params) {
        // Inicializamos los servicios
        self::init();

        // Comprobamos que solo se haya recibido un parámetro (id)
        if(count($params) == 1 ){
            $response = self::$service->borrar($params[0]);
            return $response;
        }
        else {
            throw new ExcepcionApi(Response::STATUS_TOO_MANY_PARAMETERS, "Ruta invalida");
        }
    }
}