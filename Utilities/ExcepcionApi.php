<?php

class ExcepcionApi extends Exception
{
    public $estado;
    public $success;
    public function __construct($status = 400, $message = "Error en la API")
    {
        $this->success = false;
        $this->estado = $status;
        $this->message = $message;
    }
}
?>