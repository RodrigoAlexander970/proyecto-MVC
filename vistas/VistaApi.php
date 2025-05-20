<?php

/**
 * Clase base para la representaci�n de las vistas
 */
abstract class VistaApi{

    // Código de error
    public $estado;

    public abstract function imprimir($cuerpo);
}