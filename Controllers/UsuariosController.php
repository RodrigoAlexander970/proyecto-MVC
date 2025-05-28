<?php
include_once(__DIR__.'/Controller.php');
include_once(__DIR__. '/../Services/UsuariosService.php');

class UsuariosController extends Controller {
    // Almacenamos el servicio de usuarios
    protected $usuariosService;

    protected function inicializarServicio()
    {
        $this->usuariosService = new UsuariosService();
        $this->service = $this->usuariosService;

        $this->recursosValidos = [];
    }

    public function post($params) {

        if(count($params) == 0) {
            $userData = $this->getRequestBody();
            return $this->usuariosService->registrar($userData);
        }

        switch ($params[0]) {
            case 'login':
                $credenciales = $this->getRequestBody();
                return $this->usuariosService->login($credenciales);

            case 'register':
                return ['message' => 'Register!'];

            case 'logout':
                return['message' => 'Logout!'];

            default:
                throw new ExcepcionApi(
                    Response::STATUS_BAD_REQUEST,
                    'Ruta no encontrada'
                );
        } 
    }
}