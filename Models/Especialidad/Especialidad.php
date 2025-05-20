<?php

/**
 * Clase que representa una especialidad médica.
 */
class Especialidad {
    public $id_especialidad;
    public $nombre;
    public $descripcion;
    public $activo;

    public function getIdEspecialidad() {
        return $this->id_especialidad;
    }

    public function setIdEspecialidad($id_especialidad) {
        $this->id_especialidad = $id_especialidad;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getActivo() {
        return $this->activo;
    }

    public function setActivo($activo) {
        $this->activo = $activo;
    }
}