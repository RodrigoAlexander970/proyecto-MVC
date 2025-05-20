<?php
	
	include_once('Utilities/Response.php'); // Para definir los códigos de estado/respuesta

	require 'vistas/VistaXML.php';
	require 'vistas/VistaJson.php';
	require 'Utilities/ExcepcionApi.php';

	// Preparar manejo de excepciones
	$formato = isset($_GET['formato']) ? $_GET['formato'] : 'json';

	switch ($formato) {
		case 'xml':
			$vista = new VistaXML();
			break;
		case 'json':
		default:
			$vista = new VistaJson();
	}

	// Definir el encabezado de la respuesta
	/*set_exception_handler(function ($exception) use ($vista) {
		
	    $cuerpo = array(
	        "estado" => $exception->estado,
	        "mensaje" => $exception->getMessage()
	    );
	    if ($exception->getCode()) {
	        $vista->estado = $exception->getCode();
	    } else {
	        $vista->estado = 500;
	    }

	    $vista->imprimir($cuerpo);
		}
	);
	*/
	// Arreglo con los recursos existentes de la api
	$recursos_validos = array('medicos', 'pacientes', 'citas', 'especialidades', 'historiales');

	// Obtenemos los parametros de la URL
	$parameters = explode('/',$_GET['PATH_INFO']);

	// Obtenemos el recurso
	$recurso = $parameters[0];

	// Validamos que el recurso sea válido
	if (!in_array($recurso, $recursos_validos)) {
 		// Lanzamos una excepción
		throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Recurso no encontrado");
	}

	// Elimniamos el recurso de la lista de parámetros
	$parameters = array_slice($parameters, 1);

	// Obtenemos el método de la petición
	$request_method = strtolower($_SERVER['REQUEST_METHOD']);
	
	$nombre_clase = ucfirst($recurso) . 'Controller';  
	require_once __DIR__ . '/Controllers/' . ucfirst($recurso) . 'Controller.php';
	switch ($request_method){
		case 'get':
		case 'post':
		case 'put':
		case 'delete':{
			$respuesta = $nombre_clase::$request_method($parameters);
			//devolver la vista de la respuesta
			$vista->imprimir($respuesta);
			break;
		}
		/*default:
			// Método no aceptado
			$vista->estado = 405;
			$cuerpo = [
				"estado" => ESTADO_METODO_NO_PERMITIDO,
				"mensaje" => utf8_encode("Método no permitido")
			];
			$vista->imprimir($cuerpo);*/
	}
?> 