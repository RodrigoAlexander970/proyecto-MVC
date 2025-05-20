<?php
	include_once('Producto.php');
	include_once('nivelesCarrera.php');
	include_once('contactos.php');
	include_once('usuarios.php');
	include_once('Response.php');

	require 'vistas/VistaXML.php';
	require 'vistas/VistaJson.php';
	require 'utilidades/ExcepcionApi.php';

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
	set_exception_handler(function ($exception) use ($vista) {
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

	
	/*
	echo "<hr><br/><br/>"; 

	function getproducto($id){
		return "<br/>Se ejecutó getproducto: {$id} <br/>";
	}

	function postproducto($obj){
		return "<br/>Se ejecutó postproducto <br/>";
	}

	function deleteproducto($id){
		return "<br/>Se ejecutó deleteproducto <br/>";
	}

	function putproducto($obj){
		return "<br/>Se ejecutó putproducto <br/>";
	}
	*/

	/*
	$resultado = call_user_func(strtolower($request_method . $recurso), $parameters[0]);

	echo $resultado . "<br />";
*/
/*
	
	if (method_exists($recurso, $request_method)) {
		$resultado = call_user_func(array($recurso, $request_method), $parameters[0]);

		
		//	GET localhost:8080/producto/2/3
	
		$nombre_clase = $recurso;   //$recurso='producto'
		$nombre_clase::$request_method($parameters[0]);
		//    producto::get(2);

	}
*/

/*
	echo "<br /> parameters[0]" . $parameters[0];
	echo "<br /> isset: " . isset($parameters); 
	echo "<br /> isnull: " . is_null($parameters);
	echo "<br /> empty: " . empty($parameters);
	echo "<br /> count: " . count($parameters)  . "<br />";

	print "validar con empty: ";
	print empty($parameters) ? "verdadero" : "falso";
	print "<br />";

	print "validar con isnull: ";
	print is_null($parameters) ? "verdadero" : "falso";
	print "<br />";
*/

/*
get localhost/producto/par1/par2
pathinfo = producto/par1/par2
$recurso = producto
$parameters [par1, par2]
*/

	$nombre_clase = $recurso;  

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
		default:
			// Método no aceptado
			$vista->estado = 405;
			$cuerpo = [
				"estado" => ESTADO_METODO_NO_PERMITIDO,
				"mensaje" => utf8_encode("Método no permitido")
			];
			$vista->imprimir($cuerpo);
	}
?> 