<?php
include_once(__DIR__ . '/DAO.php');

class Usuario extends DAO
{
    const NOMBRE_TABLA = 'usuarios';
    public function __construc()
    {
        parent::__construct();
        $this->NOMBRE_TABLA = self::NOMBRE_TABLA;
        $this->LLAVE_PRIMARIA = 'id_usuario';
        $this->camposTabla = ['username', 'email', 'password', 'fecha_registro'];
    }

    public function crear($userData) {
        $sql = "INSERT INTO " . self::NOMBRE_TABLA . " (username, email, password, claveAPI) VALUES (?,?,?,?)";
        $stmt = $this->conexion->prepare($sql);
        
        $stmt->bindValue(1, $userData['username'], PDO::PARAM_STR);
        $stmt->bindValue(2, $userData['email'], PDO::PARAM_STR);
        $stmt->bindValue(3, $userData['password'], PDO::PARAM_STR);
        $stmt->bindValue(4, $userData['apiKey'], PDO::PARAM_STR);
        try {
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new ExcepcionApi(Response::STATUS_INTERNAL_SERVER_ERROR, "Error al crear el registro:" . $e->getMessage());
        }
    }

    public function porEmail($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function porToken($token)
    {
        $sql = "SELECT * FROM usuarios WHERE claveAPI = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(1, $token);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
