<?php

include_once 'datos/ConexionBD.php';

class usuarios
{
    // Datos de la tabla "usuario"
    const NOMBRE_TABLA = "usuario";
    const ID_USUARIO = "idUsuario";
    const NOMBRE = "nombre";
    const CONTRASENA = "contrasena";
    const CORREO = "correo";
    const CLAVE_API = "claveApi";

    const ESTADO_CREACION_EXITOSA = 1;
    const ESTADO_CREACION_FALLIDA = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_AUSENCIA_CLAVE_API = 4;
    const ESTADO_CLAVE_NO_AUTORIZADA = 5;
    const ESTADO_URL_INCORRECTA = 6;
    const ESTADO_FALLA_DESCONOCIDA = 7;
    const ESTADO_PARAMETROS_INCORRECTOS = 8;

/**
 * peticion POST
 * /usuarios/registro
 * /usuarios/login
 * /usuarios/desencriptar  XXXXX
*/ 

    public static function post($peticion)
    {
        if ($peticion[0] == 'registro') {
            return self::registrar();
        } else if ($peticion[0] == 'login') {
            return self::loguear();
        } else {
            throw new ExcepcionApi(self::ESTADO_URL_INCORRECTA, "Url mal formada", 400);
        }
    }

    /**
     * Crea un nuevo usuario en la base de datos
     */
    private function registrar()
    {
        $cuerpo = file_get_contents('php://input');
        $usuario = json_decode($cuerpo);

        $resultado = self::crear($usuario);

        switch ($resultado) {
            case self::ESTADO_CREACION_EXITOSA:
                http_response_code(200);
                return
                    [
                        "estado" => self::ESTADO_CREACION_EXITOSA,
                        "mensaje" => utf8_encode("¡Registro con éxito!")
                    ];
                break;
            case self::ESTADO_CREACION_FALLIDA:
                throw new ExcepcionApi(self::ESTADO_CREACION_FALLIDA, "Ha ocurrido un error");
                break;
            default:
                throw new ExcepcionApi(self::ESTADO_FALLA_DESCONOCIDA, "Falla desconocida", 400);
        }
    }

    /**
     * Crea un nuevo usuario en la tabla "usuario"
     * @param mixed $datosUsuario columnas del registro
     * @return int codigo para determinar si la inserción fue exitosa
     */
    private function crear($datosUsuario)
    {
        $nombre = $datosUsuario->nombre;

        $contrasena = $datosUsuario->contrasena;
        $contrasenaEncriptada = self::encriptarContrasena($contrasena);

        $correo = $datosUsuario->correo;

        $claveApi = self::generarClaveApi();

        try {

            $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

            // Sentencia INSERT
            $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .
                self::NOMBRE . "," .
                self::CONTRASENA . "," .
                self::CLAVE_API . "," .
                self::CORREO . ")" .
                " VALUES(?,?,?,?)";

            $sentencia = $pdo->prepare($comando);

            $sentencia->bindParam(1, $nombre);
            $sentencia->bindParam(2, $contrasenaEncriptada);
            $sentencia->bindParam(3, $claveApi);
            $sentencia->bindParam(4, $correo);

            $resultado = $sentencia->execute();

            if ($resultado) {
                return self::ESTADO_CREACION_EXITOSA;
            } else {
                return self::ESTADO_CREACION_FALLIDA;
            }
        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }

    /**
     * Protege la contraseña con un algoritmo de encriptado
     * @param $contrasenaPlana
     * @return bool|null|string
     */
    private function encriptarContrasena($contrasenaPlana)
    {
        if ($contrasenaPlana)
            return password_hash($contrasenaPlana, PASSWORD_DEFAULT);
        else return null;
    }

    private function generarClaveApi()
    {
        return md5(microtime() . rand());
    }

    private function loguear()
    {
        $respuesta = array();

        $body = file_get_contents('php://input');
        $usuario = json_decode($body);

        $correo = $usuario->correo;
        $contrasena = $usuario->contrasena;

        if (self::autenticar($correo, $contrasena)) {
            $usuarioBD = self::obtenerUsuarioPorCorreo($correo);

            if ($usuarioBD != NULL) {
                http_response_code(200);
                $respuesta["nombre"] = $usuarioBD["nombre"];
                $respuesta["correo"] = $usuarioBD["correo"];
                $respuesta["claveApi"] = $usuarioBD["claveApi"];
                return ["estado" => 1, "usuario" => $respuesta];
            } else {
                throw new ExcepcionApi(self::ESTADO_FALLA_DESCONOCIDA,
                    "Ha ocurrido un error");
            }
        } else {
            throw new ExcepcionApi(self::ESTADO_PARAMETROS_INCORRECTOS,
                utf8_encode("Correo o contrase�a inv�lidos"));
        }
    }

    private function autenticar($correo, $contrasena)
    {
        $comando = "SELECT contrasena FROM " . self::NOMBRE_TABLA .
            " WHERE " . self::CORREO . "=?";

        try {

            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            $sentencia->bindParam(1, $correo);

            $sentencia->execute();

            if ($sentencia) {
                $resultado = $sentencia->fetch();

                if (self::validarContrasena($contrasena, $resultado['contrasena'])) {
                    return true;
                } else return false;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
        }
    }

    private function validarContrasena($contrasenaPlana, $contrasenaHash)
    {
        return password_verify($contrasenaPlana, $contrasenaHash);
    }


    
    /**
     * Otorga los permisos a un usuario para que acceda a los recursos
     * @return null o el id del usuario autorizado
     * @throws Exception
     */
    public static function autorizar()
    {
        $cabeceras = apache_request_headers();

        if (isset($cabeceras["Authorization"])) {

            $claveApi = $cabeceras["Authorization"];

            if (usuarios::validarClaveApi($claveApi)) {
                return usuarios::obtenerIdUsuario($claveApi);
            } else {
                throw new ExcepcionApi(
                    self::ESTADO_CLAVE_NO_AUTORIZADA, "Clave de API no autorizada", 401);
            }

        } else {
            throw new ExcepcionApi(
                self::ESTADO_AUSENCIA_CLAVE_API,
                utf8_encode("Se requiere Clave del API para autenticaci�n"));
        }
    }

    /**
     * Comprueba la existencia de la clave para la api
     * @param $claveApi
     * @return bool true si existe o false en caso contrario
     */
    private function validarClaveApi($claveApi)
    {
        $comando = "SELECT COUNT(" . self::ID_USUARIO . ")" .
            " FROM " . self::NOMBRE_TABLA .
            " WHERE " . self::CLAVE_API . "=?";

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

        $sentencia->bindParam(1, $claveApi);

        $sentencia->execute();

        return $sentencia->fetchColumn(0) > 0;
    }


/**
 * Ejemplo de método que proceso peticiones GET. El método se llama get para facilitar su identificación como controlador que atiende este tipo de peticiones.
 * 
 * /usuarios/      sin params devuelve todos los usuarios
 * /usuarios/1     con un param devuelve el usuario con ese valor como id
 * /usuarios/10/100    con dos params, devuelve los usuarios con su id en ese rango.
 * /usuarios/usuarioPorCorreo/carlos@mail.com
 * /usuarios/?nombre=carlos  ¿Como se evalua en Php? $_GET["nombre"]
 */
    public static function get($peticion) {

        $idUsuario = usuarios::autorizar();
        
        if ($peticion == null) {
            return self::listarTodos();
        } 
        else if (count($peticion) == 1 ) {
            return self::listarPorId($peticion[0]);
        }
          else if (count($peticion) == 2 ) {
            if ($peticion[0] == 'usuarioPorCorreo') {
                return self::obtenerUsuarioPorCorreo($peticion[1]);
            }
            else
                return self::listarPorRango($peticion[0], $peticion[1]);
        } 
        else{
                throw new ExcepcionApi(self::ESTADO_PARAMETROS_INCORRECTOS, "faltan parámetros", 400);
            }
    } 


    private function listarTodos() {
        $comando = "SELECT " .
            self::NOMBRE . "," .
            self::CONTRASENA . "," .
            self::CORREO . "," .
            self::CLAVE_API .
            " FROM " . self::NOMBRE_TABLA .
            " ORDER BY " . self::NOMBRE;

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

        if ($sentencia->execute())
            return $sentencia->fetchAll();
        else
            return null;
    }




    private function obtenerUsuarioPorCorreo($correo) {
        $comando = "SELECT " .
            self::NOMBRE . "," .
            self::CONTRASENA . "," .
            self::CORREO . "," .
            self::CLAVE_API .
            " FROM " . self::NOMBRE_TABLA .
            " WHERE " . self::CORREO . "=?";

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

        $sentencia->bindParam(1, $correo);

        if ($sentencia->execute())
            return $sentencia->fetch(PDO::FETCH_ASSOC);
        else
            return null;
    }


    /**
     * Obtiene el valor de la columna "idUsuario" basado en la clave de api
     * @param $claveApi
     * @return null si este no fue encontrado
     */
    private function obtenerIdUsuario($claveApi)
    {
        $comando = "SELECT " . self::ID_USUARIO .
            " FROM " . self::NOMBRE_TABLA .
            " WHERE " . self::CLAVE_API . "=?";

        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

        $sentencia->bindParam(1, $claveApi);

        if ($sentencia->execute()) {
            $resultado = $sentencia->fetch();
            return $resultado['idUsuario'];
        } else
            return null;
    }
}

