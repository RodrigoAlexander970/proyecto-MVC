<?php

/**
 * Clase que representa a un médico.
 */

 class Medico {
   public $id_medico;
   public $id_especialidad;
   public $nombre;
   public $apellidos;
   public $cedula_profesional;
   public $email;
   public $telefono;
   public $password;
   public $fecha_registro;
   public $activo;

   public function getIdMedico() {
      return $this->id_medico;
   }

   public function setIdMedico($id_medico) {
      $this->id_medico = $id_medico;
   }

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

   public function getApellidos() {
      return $this->apellidos;
   }

   public function setApellidos($apellidos) {
      $this->apellidos = $apellidos;
   }

   public function getCedulaProfesional() {
      return $this->cedula_profesional;
   }

   public function setCedulaProfesional($cedula_profesional) {
      $this->cedula_profesional = $cedula_profesional;
   }

   public function getEmail() {
      return $this->email;
   }

   public function setEmail($email) {
      $this->email = $email;
   }

   public function getTelefono() {
      return $this->telefono;
   }

   public function setTelefono($telefono) {
      $this->telefono = $telefono;
   }

   public function getPassword() {
      return $this->password;
   }

   public function setPassword($password) {
      $this->password = $password;
   }

   public function getFechaRegistro() {
      return $this->fecha_registro;
   }

   public function setFechaRegistro($fecha_registro) {
      $this->fecha_registro = $fecha_registro;
   }

   public function getActivo() {
      return $this->activo;
   }

   public function setActivo($activo) {
      $this->activo = $activo;
   }
 }