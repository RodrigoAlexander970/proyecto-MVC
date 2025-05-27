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

    // Header del documento con diseño moderno
    $pdf->SetFillColor(102, 126, 234); // Color azul moderno
    $pdf->Rect(0, 0, 297, 25, 'F');
    
    // Logo o icono (simulado con texto)
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('Arial','B',20);
    $pdf->SetXY(15, 8);
    $pdf->Cell(0, 10, 'Medicos', 0, 1, "L");
    
    // Título principal
    $pdf->SetFont('Arial','B',18);
    $pdf->SetXY(0, 30);
    $pdf->SetTextColor(51, 51, 51);
    $pdf->Cell(0, 12, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Reporte de Citas Médicas'), 0, 1, "C");
    
    // Información adicional
    $pdf->SetFont('Arial','',10);
    $pdf->SetTextColor(108, 117, 125);
    $pdf->SetXY(0, 42);
    $pdf->Cell(0, 6, 'Generado el: ' . date('d/m/Y H:i:s') . ' | Total de registros: ' . count($citas), 0, 1, "C");
    
    // Línea decorativa
    $pdf->SetDrawColor(102, 126, 234);
    $pdf->SetLineWidth(0.8);
    $pdf->Line(20, 52, 277, 52);
    
    $pdf->Ln(8);
    
    // Encabezado de la tabla con diseño moderno
    $pdf->SetFont('Arial','B',9);
    $pdf->SetFillColor(248, 249, 250); // Gris claro para el header
    $pdf->SetTextColor(73, 80, 87);
    $pdf->SetDrawColor(222, 226, 230);
    $pdf->SetLineWidth(0.3);
    
    // Headers con iconos simulados
    $pdf->Cell(10, 10, 'ID', 1, 0, "C", true);
    $pdf->Cell(45, 10, 'Paciente', 1, 0, "C", true);
    $pdf->Cell(45, 10, 'Médico', 1, 0, "C", true);
    $pdf->Cell(20, 10, 'Fecha', 1, 0, "C", true);
    $pdf->Cell(30, 10, 'Horario', 1, 0, "C", true);
    $pdf->Cell(20, 10, 'Estado', 1, 0, "C", true);
    $pdf->Cell(55, 10, 'Motivo', 1, 0, "C", true);
    $pdf->Cell(52, 10, 'Observaciones', 1, 0, "C", true);
    $pdf->Ln();

    // Datos de cada cita con filas alternadas
    $pdf->SetFont('Arial','',8);
    $pdf->SetTextColor(33, 37, 41);
    
    $fill = false;
    foreach ($citas as $index => $cita) {
        // Colores alternados para las filas
        if ($fill) {
            $pdf->SetFillColor(255, 248, 240); // Naranja muy claro
        } else {
            $pdf->SetFillColor(255, 255, 255); // Blanco
        }
        
        // Color especial para el estado
        $estadoColor = $this->getEstadoColor($cita['estado']);
        
        $pdf->Cell(10, 8, $cita['id_cita'], 1, 0, "C", $fill);
        $pdf->Cell(45, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cita['paciente_nombre'] . ' ' . $cita['paciente_apellidos']), 1, 0, "L", $fill);
        $pdf->Cell(45, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cita['medico_nombre'] . ' ' . $cita['medico_apellidos']), 1, 0, "L", $fill);
        $pdf->Cell(20, 8, $cita['fecha'], 1, 0, "C", $fill);
        $pdf->Cell(30, 8, $cita['hora_inicio'] . '-' . $cita['hora_fin'], 1, 0, "C", $fill);
        
        // Celda especial para el estado con color
        $pdf->SetFillColor($estadoColor['r'], $estadoColor['g'], $estadoColor['b']);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(20, 8, $cita['estado'], 1, 0, "C", true);
        
        // Restaurar colores normales
        $pdf->SetTextColor(33, 37, 41);
        if ($fill) {
            $pdf->SetFillColor(255, 248, 240);
        } else {
            $pdf->SetFillColor(255, 255, 255);
        }
        
        $pdf->Cell(55, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cita['motivo'], 0, 35) . (strlen($cita['motivo']) > 35 ? '...' : '')), 1, 0, "L", $fill);
        $pdf->Cell(52, 8, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', substr($cita['observaciones'], 0, 30) . (strlen($cita['observaciones']) > 30 ? '...' : '')), 1, 0, "L", $fill);
        $pdf->Ln();
        
        $fill = !$fill; // Alternar color de fila
    }
    
    // Footer del reporte
    $pdf->Ln(5);
    $pdf->SetDrawColor(102, 126, 234);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(20, $pdf->GetY(), 277, $pdf->GetY());
    
    $pdf->Ln(3);
    $pdf->SetFont('Arial','I',8);
    $pdf->SetTextColor(108, 117, 125);
    $pdf->Cell(0, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Este reporte fue generado por el sistema de gestión de citas médicas.'), 0, 1, "C");
    $pdf->Cell(0, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Página') . ' ' . $pdf->PageNo() . ' de {nb}', 0, 1, "C");

    // Salida del pdf
    $pdf->Output('I', 'reporte_citas_' . date('Y-m-d_H-i-s') . '.pdf');

    return [
        'status' => 200,
        'mensaje' => 'Reporte generado correctamente',
        'data' => null // No devolvemos datos, solo el PDF
    ];
}

    // Método auxiliar para obtener colores según el estado
    private function getEstadoColor($estado) {
        switch (strtolower($estado)) {
            case 'confirmada':
            case 'confirmado':
                return ['r' => 40, 'g' => 167, 'b' => 69]; // Verde
            case 'pendiente':
                return ['r' => 255, 'g' => 193, 'b' => 7]; // Amarillo
            case 'cancelada':
            case 'cancelado':
                return ['r' => 220, 'g' => 53, 'b' => 69]; // Rojo
            case 'completada':
            case 'completado':
                return ['r' => 102, 'g' => 126, 'b' => 234]; // Azul
            default:
                return ['r' => 108, 'g' => 117, 'b' => 125]; // Gris
        }
    }
}
