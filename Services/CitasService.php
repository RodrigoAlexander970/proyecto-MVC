<?php
include_once(__DIR__ . '/Service.php');
include_once (__DIR__.'/MedicosService.php');
include_once(__DIR__.'/PacientesService.php');
include_once(__DIR__ . '/../Models/Citas.php');

class CitasService extends Service
{
    private $citas;
    private $medicosService;
    private $pacienteService;
    public function __construct()
    {
        $this->citas = new Citas();
        $this->medicosService = new MedicosService();
        $this->pacienteService = new PacientesService();
        parent::__construct($this->citas);
    }

    public function obtener($params)
    {

        switch (count($params)) {
            case 0:
                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Citas obtenidas correctamente',
                    $this->citas->todos()
                );
                break;
            case 1:
                // Intentamos obtener la cita
                $cita = $this->citas->porId($params[0]);

                // Revisamos que el objeto no exista
                if($cita == null) {
                    throw new ExcepcionApi(
                        Response::STATUS_NOT_FOUND,
                        'La cita no existe'
                    );
                }

                return Response::formatearRespuesta(
                    Response::STATUS_OK,
                    'Cita obtenida correctamente',
                    $cita
                );
                break;
        }
    }

    public function crear($datosCita) {
        $this->validarCamposObligatorios($datosCita);

        $resultado = $this->citas->crear($datosCita);
        
        // Verificamos si se creó correctamente
        if ($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                "Cita creado correctamente",
                $resultado
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al crear la cita"
            );
        }
    }

    public function actualizar($id, $datosCita)
    {
        $this->validarCamposObligatorios($datosCita);

        // Revisamos si existe el registro en la base
               // Revisamos si existe el registro en la base
        if(!$this->existe($id)){
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                'La cita no existe'
            );
        }

        $resultado = $this->citas->actualizar($id, $datosCita);

        if($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                "Cita actualizada correctamente",
                $resultado
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al actualizar la cita"
            );
        }
    }

    public function borrar($id)
    {
        // Revisamos si no existe el registro en la base
        if(!$this->existe($id)){
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                'La cita no existe'
            );
        }

        $resultado = $this->citas->borrar($id);

        if($resultado) {
            return Response::formatearRespuesta(
                Response::STATUS_CREATED,
                "Cita borrada correctamente",
                $resultado
            );
        } else {
            throw new ExcepcionApi(
                Response::STATUS_INTERNAL_SERVER_ERROR,
                "Error al borrar la cita"
            );
        }
    }

    public function porMedico($idMedico) {
        // Comprobamos que exista el médico
        $medicoExiste = $this->medicosService->obtener([$idMedico]);

        if (!$medicoExiste) {
            throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Médico no encontrado");
        }

        $citas = $this->citas->porMedico($idMedico);
        
        if($citas === null) {
            return Response::formatearRespuesta(
                Response::STATUS_NOT_FOUND,
                "Citas no encontradas"
            );
        }

        return Response::formatearRespuesta(
            Response::STATUS_OK,
            "Citas obtenidas correctamente",
            $citas
        );
    }

    public function porPaciente($idPaciente) {
        if(!$this->pacienteService->existe($idPaciente)) {
            throw new ExcepcionApi(
                Response::STATUS_NOT_FOUND,
                'El paciente no existe'
            );
        }

        return Response::formatearRespuesta(
            Response::STATUS_OK,
            "Citas conseguidas por paciente",
            $this->citas->porPaciente($idPaciente)
        );
    }

    public function obtenerCitasDetalladas() {
        return Response::formatearRespuesta(
            Response::STATUS_OK,
            'Citas obtenidas correctamente',
            $this->citas->todosDetalle()
        );
    }

    public function obtenerReporte(){
        return Response::formatearRespuesta(
            Response::STATUS_OK,
            'Reporte generado correctamente',
            $this->citas->obtenerReporte()
        );
    }
}
