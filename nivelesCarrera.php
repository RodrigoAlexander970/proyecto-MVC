<?php

require_once './datos/ConexionBD.php';

class nivelesCarrera{
    const ESTADO_ERROR_PARAMETROS = 4;

    public static function get($parameters){
        if (count($parameters) == 0) {
            return self::getAll();
            /*
            $respuesta = $nombre_clase::getAll();
            $vista->imprimir($respuesta);
            */
        }
        else
            if (count($parameters) == 1)
                return self::getId($parameters[0]);
                //$nombre_clase::getId($parameters[0]);
            else
                if (count($parameters) == 2)
                    return self::getMany($parameters[0], $parameters[1]);
                    //$nombre_clase::getMany($parameters[0], $parameters[1]);
                else
                    throw new ExcepcionApi(self::ESTADO_ERROR_PARAMETROS, "Formato de parámetros incorrecto", 422);
                    //exit(1);
    }

    public static function getAll() {
            //Desglosar parámetros JSon

    	    /* select * from NivelesCarrera; */

            //Preparar comando SQL
    	    $comando = "select * from NivelesCarrera";

    	    $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            //Asigar valores a parámetros
		    // $sentencia->bindParam(1, $correo);

            //Ejecutar sentencia
		    if ($sentencia->execute())
                //echo "Se ejecutó getAll()";
		        return $sentencia->fetch(PDO::FETCH_ASSOC);
		    else
		        return null;
		}

        public static function getId($id) {
            //Preparar comando SQL
            $comando = "select * from NivelesCarrera where id = ?";
            // Esto es equivalente pero con comandos nombrados
            //$comando = "select * from NivelesCarrera where id = :id";

            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);

            //Asigar valores a parámetros por posición
            $sentencia->bindParam(1, $id, PDO::PARAM_INT);
            //Asignación de valores a parámetros nombrados
            //$sentencia->bindParam('id', $$id, PDO::PARAM_INT);

            //Ejecutar sentencia
            if ($sentencia->execute())
                return $sentencia->fetch(PDO::FETCH_ASSOC);
            else
                return null;
        }

    public static function post($peticion)
    {
        $idUsuario = usuarios::autorizar();

        $body = file_get_contents('php://input');
        $nivel = json_decode($body);

        $idNivel = nivelesCarrera::crear($nivel->nombre);

        http_response_code(201);
        return [
            "estado" => self::CODIGO_EXITO,
            "mensaje" => "nivel de Carrera creado",
            "id" => $idNivel
        ];

    }


    private function crear($nombre)
    {
        if ($nombre) {
            try {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();
                // Sentencia INSERT
                $comando = "INSERT INTO " . self::NOMBRE_TABLA . " ( " .
                    self::NOMBRE . ")" .
                    " VALUES(?)";

                // Preparar la sentencia
                $sentencia = $pdo->prepare($comando);
                $sentencia->bindParam(1, $nombre);
                $sentencia->execute();

                // Retornar en el último id insertado
                return $pdo->lastInsertId();

            } catch (PDOException $e) {
                throw new ExcepcionApi(self::ESTADO_ERROR_BD, $e->getMessage());
            }
        } else {
            throw new ExcepcionApi(
                self::ESTADO_ERROR_PARAMETROS,
                utf8_encode("Error en existencia o sintaxis de parámetros"));
        }

    }
}
