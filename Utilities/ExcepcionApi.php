<?php

class ExcepcionApi extends Exception
{
    public $status;
    public $success;
    public function __construct($status = 400, $message = "Error en la API")
    {
        $this->success = false;
        $this->status = $status;
        $this->message = $message;
    }
}
?>