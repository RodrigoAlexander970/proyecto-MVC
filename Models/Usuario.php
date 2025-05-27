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
        $this->camposTabla = ['nombre', 'email', 'password', 'rol', 'fecha_registro'];
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
