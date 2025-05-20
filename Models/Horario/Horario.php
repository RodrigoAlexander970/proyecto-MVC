<?php
class Horario {
    public $id_horario;
    public $id_medico;
    public $dia_semana;
    public $hora_inicio;
    public $hora_fin;
    public $activo;

    public function getIdHorario() {
        return $this->id_horario;
    }

    public function setIdHorario($id_horario) {
        $this->id_horario = $id_horario;
    }

    public function getIdMedico() {
        return $this->id_medico;
    }

    public function setIdMedico($id_medico) {
        $this->id_medico = $id_medico;
    }

    public function getDiaSemana() {
        return $this->dia_semana;
    }

    public function setDiaSemana($dia_semana) {
        $this->dia_semana = $dia_semana;
    }

    public function getHoraInicio() {
        return $this->hora_inicio;
    }

    public function setHoraInicio($hora_inicio) {
        $this->hora_inicio = $hora_inicio;
    }

    public function getHoraFin() {
        return $this->hora_fin;
    }

    public function setHoraFin($hora_fin) {
        $this->hora_fin = $hora_fin;
    }

    public function getActivo() {
        return $this->activo;
    }

    public function setActivo($activo) {
        $this->activo = $activo;
    }


}