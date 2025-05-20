<?php
/**
 * Clase que define los códigos de estado de la respuesta de la API.
 */
class Response
{
    const STATUS_OK = 200;
    const STATUS_CREATED = 201;

    const STATUS_NOT_FOUND = 404;
    const STATUS_TOO_MANY_PARAMETERS = 422;

    const STATUS_INTERNAL_SERVER_ERROR = 500;
}