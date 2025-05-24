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
                e.descripcion as descripcion_especialidad
            FROM horarios_disponibles h
            INNER JOIN medicos m ON h.id_medico = m.id_medico
            INNER JOIN especialidades e ON m.id_especialidad = e.id_especialidad 
            ORDER BY FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')";
            
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

        foreach($resultados as $row){
            // Inicializamos el día si no existe
            if(!isset($reporte[$row['dia_semana']])){
                $reporte[$row['dia_semana']] = [];
            }

            //Inicializamos la especialidad
            if(!isset($reporte[$row['dia_semana']][$row['nombre_especialidad']])){
                $reporte[$row['dia_semana']][$row['nombre_especialidad']] = [];
            }

            $reporte[$row['dia_semana']][$row['nombre_especialidad']][] = [
                'hora_inicio' => $row['hora_inicio'],
                'hora_fin' => $row['hora_fin'],
                'nombre_medico' => $row['nombre_medico'],
                'apellidos_medico' => $row['apellidos_medico'],
            ];
        }
        /*
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
        */
        return $reporte;
    }
}