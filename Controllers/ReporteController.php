<?php
include_once(__DIR__.'/../Database/ConexionBD.php');
include_once (__DIR__.'/../Utilities/Response.php');

class ReporteController{

    // Conexión a la base de datos
    private $conexion;

    public function __construct( PDO $conexion = null ) {
        $this->conexion = $conexion ?: ConexionBD::obtenerInstancia()->obtenerBD();
    }
    
    public function get($params) {
        try {
            // Consulta con JOINs para obtener horarios, médicos y especialidades
            $sql = "SELECT 
                h.id_horario,
                h.id_medico,
                h.dia_semana,
                h.hora_inicio,
                h.hora_fin,
                m.nombre as nombre_medico,
                m.apellidos as apellidos_medico,
                m.id_especialidad,
                e.nombre as nombre_especialidad,
                e.descripcion as descripcion_especialidad,
                -- Calcular horas trabajadas por día
                TIME_TO_SEC(TIMEDIFF(h.hora_fin, h.hora_inicio)) / 3600 as horas_dia
            FROM horarios_disponibles h
            INNER JOIN medicos m ON h.id_medico = m.id_medico
            INNER JOIN especialidades e ON m.id_especialidad = e.id_especialidad 
            ORDER BY m.nombre, h.id_medico, 
                FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Procesar los datos y agrupar por especialidad
            $reporte = $this->procesarDatosReporte($resultados);
            
            return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Reporte generado correctamente',
                    $reporte
                );
            
        } catch (PDOException $e) {
            return Response::formatearRespuesta(
                Response::STATUS_BAD_REQUEST,
                'Error al Obtener el reporte',
                [$e]
            );
        }
    }
    
    private function procesarDatosReporte($resultados) {
        $reporte = [];
        $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        
        foreach ($resultados as $row) {
            $especialidad_id = $row['id_especialidad'];
            $especialidad_nombre = $row['nombre_especialidad'];
            $medico = $row['id_medico'];
            $especialidad_descripción = $row['descripcion_especialidad'];
            
            // Inicializar especialidad si no existe
            if (!isset($reporte[$especialidad_id])) {
                $reporte[$especialidad_id] = [
                    'id' => $especialidad_id,
                    'nombre' => $especialidad_nombre,
                    'descripcion' => $especialidad_descripción,
                    'medicos' => [],
                    'estadisticas' => [
                        'total_medicos' => 0,
                        'total_horas_semana' => 0,
                        'dias_con_cobertura' => [],
                        'horario_mas_temprano' => '23:59:59',
                        'horario_mas_tarde' => '00:00:00'
                    ]
                ];
            }
            
            // Inicializar médico si no existe
            if (!isset($reporte[$especialidad_id]['medicos'][$medico])) {
                $reporte[$especialidad_id]['medicos'][$medico] = [
                    'nombre' => $row['nombre_medico'],
                    'horarios' => [],
                    'total_horas_semana' => 0,
                    'dias_trabajo' => []
                ];
                $reporte[$especialidad_id]['estadisticas']['total_medicos']++;
            }
            
            // Agregar horario
            $horario = [
                'dia' => $row['dia_semana'],
                'inicio' => $row['hora_inicio'],
                'fin' => $row['hora_fin'],
                'horas' => round($row['horas_dia'], 2)
            ];
            
            $reporte[$especialidad_id]['medicos'][$medico]['horarios'][] = $horario;
            $reporte[$especialidad_id]['medicos'][$medico]['total_horas_semana'] += $row['horas_dia'];
            $reporte[$especialidad_id]['medicos'][$medico]['dias_trabajo'][] = $row['dia_semana'];
            
            // Actualizar estadísticas de la especialidad
            $reporte[$especialidad_id]['estadisticas']['total_horas_semana'] += $row['horas_dia'];
            
            // Días con cobertura
            if (!in_array($row['dia'], $reporte[$especialidad_id]['estadisticas']['dias_con_cobertura'])) {
                $reporte[$especialidad_id]['estadisticas']['dias_con_cobertura'][] = $row['dia_semana'];
            }
            
            // Horarios más temprano y más tarde
            if ($row['hora_inicio'] < $reporte[$especialidad_id]['estadisticas']['horario_mas_temprano']) {
                $reporte[$especialidad_id]['estadisticas']['horario_mas_temprano'] = $row['hora_inicio'];
            }
            if ($row['hora_fin'] > $reporte[$especialidad_id]['estadisticas']['horario_mas_tarde']) {
                $reporte[$especialidad_id]['estadisticas']['horario_mas_tarde'] = $row['hora_fin'];
            }
        }
        
        // Convertir asociativo a numérico y agregar estadísticas finales
        $reporte_final = [];
        foreach ($reporte as $especialidad) {
            // Convertir médicos de asociativo a numérico
            $especialidad['medicos'] = array_values($especialidad['medicos']);
            
            // Redondear total de horas
            $especialidad['estadisticas']['total_horas_semana'] = round($especialidad['estadisticas']['total_horas_semana'], 2);
            
            // Calcular porcentaje de cobertura semanal
            $dias_con_cobertura = count($especialidad['estadisticas']['dias_con_cobertura']);
            $especialidad['estadisticas']['porcentaje_cobertura_semanal'] = round(($dias_con_cobertura / 7) * 100, 2);
            
            $reporte_final[] = $especialidad;
        }
        
        return $reporte_final;
    }
    
    // Método adicional para obtener resumen general
    public function obtenerResumenGeneral() {
        try {
            $sql = "SELECT 
                COUNT(DISTINCT e.id) as total_especialidades,
                COUNT(DISTINCT h.medico) as total_medicos,
                COUNT(h.id_horario) as total_horarios,
                SUM(TIME_TO_SEC(TIMEDIFF(h.fecha_fin, h.fecha_inicio)) / 3600) as total_horas_semana
            FROM horarios_disponibles h
            INNER JOIN medicos_especialidades me ON h.medico = me.medico_nombre
            INNER JOIN especialidades e ON me.especialidad_id = e.id";
            
            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();
            $resumen = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'data' => [
                    'total_especialidades' => (int)$resumen['total_especialidades'],
                    'total_medicos' => (int)$resumen['total_medicos'],
                    'total_horarios' => (int)$resumen['total_horarios'],
                    'total_horas_semana' => round($resumen['total_horas_semana'], 2),
                    'promedio_horas_por_medico' => round($resumen['total_horas_semana'] / $resumen['total_medicos'], 2)
                ]
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'error' => 'Error al obtener resumen: ' . $e->getMessage()
            ];
        }
    }
}

// Ejemplo de uso:
/*
$reporte = new ReporteDisponibilidad($conexion);
$resultado = $reporte->obtenerReporteDisponibilidadPorEspecialidad();

if ($resultado['success']) {
    header('Content-Type: application/json');
    echo json_encode($resultado['data']);
} else {
    http_response_code(500);
    echo json_encode(['error' => $resultado['error']]);
}
*/