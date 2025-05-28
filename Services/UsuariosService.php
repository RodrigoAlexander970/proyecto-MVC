<?php
include_once(__DIR__ . '/Service.php');
include_once(__DIR__ . '/../Models/Usuario.php');

class UsuariosService extends Service
{
    private $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
        parent::__construct($this->usuario);
    }

    public function obtener($params) {}
    public function crear($usuarioData) {}
    public function actualizar($id, $usuarioData) {}
    public function borrar($id) {}

    public function login($credenciales)
    {

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
            $credenciales
        );
    }

    public function registrar($userData) {
        $this->validarDatosUsuarios($userData);

        // Hashear la contraseña antes de crear el usuario
        if (isset($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }

        // Creamos la api key
        $userData['apiKey'] = md5(microtime() . rand());

        $resultado = $this->usuario->crear($userData);

        if($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                "Usuario registrado correctamente"
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al crear al usuario"
            );
        }
    }

    public function validarCredenciales($credenciales)
    {
        if (empty($credenciales['email'])) {
            throw new ExcepcionApi(
                Response::STATUS_BAD_REQUEST,
                'El email es obligatorio para el login'
            );
        }

        if (empty($credenciales['password'])) {
            throw new ExcepcionApi(
                Response::STATUS_BAD_REQUEST,
                'La contraseña es obligatoria para el login'
            );
        }
    }

    public function validarToken($token)
    {
        $usuario = $this->usuario->porToken($token);
        
        if (!$usuario) {
            throw new ExcepcionApi(Response::STATUS_UNAUTHORIZED, 'Token inválido');
        }
        return $usuario;
    }

    public function validarDatosUsuarios($userData) {
        $camposRequeridos = ['username', 'password', 'email'];

        foreach ($camposRequeridos as $campo) {
            if (empty($userData[$campo])) {
                throw new ExcepcionApi(
                    Response::STATUS_BAD_REQUEST,
                    "Falta el campo obligatorio: $campo");
            }
        }
    }
}
