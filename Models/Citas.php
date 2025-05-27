<?php
include_once(__DIR__ . '/DAO.php');
include_once(__DIR__ . '/../fpdf/fpdf.php');

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

    public function obtenerReporte(){
        $citas = $this->todosDetalle();

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();


        //Titulo del reporte
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,'Reporte de Citas',0,1,"C");

        $pdf->Ln();
        $pdf->SetFont('Arial','',9);

        // Cabecera de tabla
        $pdf->Cell(10,7,'ID',1,0,"C");
        $pdf->Cell(45,7,'Paciente',1,0,"C");
        $pdf->Cell(45,7,'Médico',1,0,"C");
        $pdf->Cell(20,7,'Fecha',1,0,"C");
        $pdf->Cell(30,7,'Hora Inicio',1,0,"C");
        $pdf->Cell(20,7,'Estado',1,0,"C");
        $pdf->Cell(55,7,'Motivo',1,0,"C");
        $pdf->cell(52,7,'Observaciones',1,0,"C");
        $pdf->Ln();

        // Datos de cada cita
        foreach ($citas as $cita) {
            $pdf->Cell(10,7,$cita['id_cita'],1,0,"C");
            $pdf->Cell(45,7,iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cita['paciente_nombre'] . ' ' . $cita['paciente_apellidos']),1,0,"C");
            $pdf->Cell(45,7,iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cita['medico_nombre'] . ' ' . $cita['medico_apellidos']),1,0,"C");
            $pdf->Cell(20,7,$cita['fecha'],1,0,"C");
            $pdf->Cell(30,7,$cita['hora_inicio'] . '-' . $cita['hora_fin'],1,0,"C");
            $pdf->Cell(20,7,$cita['estado'],1,0,"C");
            $pdf->Cell(55,7,iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cita['motivo']),1,0,"C");
            $pdf->Cell(52,7,iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cita['observaciones']),1,0,"C");
            $pdf->Ln();
        }

        // Salida del pdf
        $pdf->Output('I', 'reporte_citas.pdf');

        return [
            'status' => 200,
            'mensaje' => 'Reporte generado correctamente',
            'data' => null // No devolvemos datos, solo el PDF
        ];
    }
}
