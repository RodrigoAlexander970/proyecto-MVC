<?php
include_once(__DIR__ . '/DAO.php');

class Citas extends DAO
{
    // Constante de la base de datos
    const NOMBRE_TABLA = 'citas';
    const ID_CITA = 'id_cita';
    const ID_PACIENTE = "id_paciente";
    const ID_MEDICO = "id_medico";
    const FECHA = "fecha";
    const HORA_INICIO = "hora_inicio";
    const HORA_FIN = "hora_fin";
    const MOTIVO = "motivo";
    const ESTADO = "estado";
    const OBSERVACIONES = "observaciones";
    const FECHA_REGISTRO = "fecha_registro";

    public function __construct()
    {
        parent::__construct();
        $this->NOMBRE_TABLA = self::NOMBRE_TABLA;
        $this->LLAVE_PRIMARIA = self::ID_CITA;
        $this->camposTabla = ['id_paciente',
                            'id_medico',
                            'fecha',
                            'hora_inicio',
                            'hora_fin',
                            'motivo',
                            'estado',
                            'observaciones'];
    }

    public function porMedico($id_medico) {
        $sql = 'SELECT * FROM ' . self::NOMBRE_TABLA . ' WHERE ' . self::ID_MEDICO . ' = ?';

        // Preparamos la consulta
        $stmt = $this->conexion->prepare($sql);
        $stmt -> bindParam(1,$id_medico, PDO::PARAM_INT);
        $stmt -> execute();

        // Obtenemos los resultados
        $resultados = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    }

    public function porPaciente($idPaciente) {
        $sql = 'SELECT * FROM ' . self::NOMBRE_TABLA . ' WHERE ' . self::ID_PACIENTE . ' = ?';

        // Preparamos la consulta
        $stmt = $this->conexion->prepare($sql);
        $stmt -> bindParam(1,$idPaciente, PDO::PARAM_INT);
        $stmt -> execute();

        // Obtenemos los resultados
        $resultados = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        return $resultados;
    }

    public function todosDetalle() {
        $sql = "SELECT c.*, 
                   p.nombre AS paciente_nombre, p.apellidos AS paciente_apellidos, 
                   m.nombre AS medico_nombre, m.apellidos AS medico_apellidos
            FROM citas c
            INNER JOIN pacientes p ON c.id_paciente = p.id_paciente
            INNER JOIN medicos m ON c.id_medico = m.id_medico";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
