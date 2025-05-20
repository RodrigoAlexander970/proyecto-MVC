<?php

class ExcepcionApi extends Exception
{
    public $estado;

    public function __construct($status = 400, $message)
    {
        $this->estado = $status;
        $this->message = $message;
    }
}
?>