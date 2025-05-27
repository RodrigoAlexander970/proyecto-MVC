<?php
include_once (__DIR__.'/Service.php');
include_once (__DIR__.'/../Models/Usuario.php');

class UsuariosService extends Service {
    private $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
        parent::__construct($this->usuario);
    }

    public function obtener($params) {}
    public function crear($usuarioData) {}
    public function actualizar($id, $usuarioData) {}
    public function borrar($id) {}

    public function login($credenciales) {

        $this->validarCredenciales($credenciales);

        $usuario = $this->usuario->porEmail($credenciales['email']);
        if (!$usuario) {
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                'Credenciales inválidas'
            );
        }

        // 2. Verificar contraseña
        if (!password_verify($credenciales['password'], $usuario['password'])) {
            throw new ExcepcionApi(
                Response::STATUS_UNAUTHORIZED,
                'Credenciales inválidas'
            );
        }

        // 3. Si todo bien, devuelve los datos del usuario (sin la contraseña)
        unset($usuario['password']);
        unset($usuario['fecha_registro']);
        unset($usuario['id_medico']);
        unset($usuario['id_paciente']);
        unset($usuario['ultimo_acceso']);
        return Response::formatearRespuesta(
            Response::STATUS_OK,
            'Login exitoso',
            $usuario
        );
        
        return Response::formatearRespuesta(
            Response::STATUS_OK,
            'Login exitoso',
        $credenciales);
    }

    public function validarCredenciales($credenciales) {
        if(empty($credenciales['email'])) {
            throw new ExcepcionApi(
                Response::STATUS_BAD_REQUEST,
                'El email es obligatorio para el login'
            );
        }

        if(empty($credenciales['password'])) {
            throw new ExcepcionApi(
                Response::STATUS_BAD_REQUEST,
                'La contraseña es obligatoria para el login'
            );
        }
    }
}