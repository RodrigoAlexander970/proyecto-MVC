<?php

	/***
	 * Modificar el código para que las funciones sean métodos de una clase llamada Producto.
	 * Usar una función php para llamar al método correspondiente de la clase Producto,
	 * dependiendo del método http usado en la solicitud. Ejemplo:
	 * 
	 *     Petición				|		Método a ejecutar
	 * -------------------------------------------------------------
	 * GET localhost/producto/1       	Producto::get(10) 
	 * POST localhost/producto/		  	Producto::post({"id":2, "nombre":"laptop", "precio":10000})
	 *  body: 
	 * 		{"id":2, 
	 * 		 "nombre":"laptop", 
	 * 		 "precio":10000}
	 * 
	 * PUT localhost/producto/		  	Producto::post({"id":2, "nombre":"Computadora de escritorio", "precio":15000})
	 *  body: 
	 * 		{"id":2, 
	 * 		 "nombre":"Computadora de escritorio", 
	 * 		 "precio":15000}
	 * 
	 * DELETE localhost/producto/2    	Producto::delete(2) 
	 */

	include_once('Producto.php');
	include_once('nivelesCarrera.php');
	include_once('contactos.php');
	include_once('usuarios.php');

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

	$recursos_validos = array('producto', 'persona', 'nivelesCarrera', 'contactos', 'usuarios');
/*
	echo "Hola mundo<br/>";
	echo $_GET['PATH_INFO'];
	echo "<br/> {$_SERVER['REQUEST_METHOD'] } ";
*/

	$parameters = explode('/',$_GET['PATH_INFO']);
	$recurso = $parameters[0];

	//valido si el recurso es válido
	if (!in_array($recurso, $recursos_validos)) {
 		//generar exception
		//throw new ExcepcionApi(404, $e->getMessage());
 		exit(0);
	}

	$parameters = array_slice($parameters, 1);

	//$recurso = array_shift($parameters);
	//echo "<br/><br/>" . $parameters . '<br/>';
	//print_r($parameters);

/*
	echo "<br/><br/>";
    print_r($parameters);
	echo "<br/><br/>";
*/

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