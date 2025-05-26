<?php
	
	include_once('Utilities/Response.php'); // Para definir los códigos de estado/respuesta
	
	// Definimos los encabezados de la respuesta
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
	header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
	header("Content-Type: application/json; charset=UTF-8");
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

	// Definimos el manejador de excepciones
	//definirManejadorExcepciones($vista);

	// Arreglo con los recursos existentes de la api
	$recursos_validos = array('medicos', 'pacientes', 'citas', 'especialidades', 'historiales', 'especialidades', 'horarios', 'reporte');

	// Obtenemos la URL solucitada
	$url = $_GET['PATH_INFO'];
	// Obtenemos los parametros de la URL y el recurso solicitado
	$parameters = explode('/',$url);
	$recurso = $parameters[0];

	// Elimniamos el recurso de la lista de parámetros
	$parameters = array_slice($parameters, 1);

	// Obtenemos el método de la petición
	$request_method = strtolower($_SERVER['REQUEST_METHOD']);
	
	// Validamos que el recurso sea válido
	if (!in_array($recurso, $recursos_validos)) {
 		// Lanzamos una excepción
		throw new ExcepcionApi(Response::STATUS_NOT_FOUND, "Recurso no encontrado");
	}

	$nombre_clase = ucfirst($recurso) . 'Controller';  
	require_once __DIR__ . '/Controllers/' . ucfirst($recurso) . 'Controller.php';
	
	switch ($request_method){
		case 'get':
		case 'post':
		case 'put':
		case 'delete':{
			$controller = new $nombre_clase();
			$respuesta = $controller->$request_method($parameters);
			//devolver la vista de la respuesta
			$vista->responder(
				$respuesta['status'] ?? null,
				$respuesta['mensaje'] ?? null,
				$respuesta['data'] ?? null
			);
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



	/**
	 * Definición de la función de manejo de excepciones
	 */
	function definirManejadorExcepciones($vista) {
		set_exception_handler(function ($exception) use ($vista) {
		
			$cuerpo = array(
				"success" => false,
				"status" => $exception->status ?? '500',
				"message" => $exception->getMessage()
			);

			$vista->imprimir($cuerpo);
			}
		);
	}
?> 