<?php

    require_once './datos/ConexionBD.php';
	class producto{
		// property declaration
	    public $var = 'a default value';

	    	    // method declaration

	    //-------------------------------
	    //No se puede sobrecargar métodos en php

	    /*
	    public static function get($id) {
	        echo "Ejecutaste persona::get({$id})";
	    }
	    
	    public static function get($idIni, $idFin) {
	        echo "Ejecutaste persona::get({$id})";
	    }

	    public static function get() {
	        echo "Ejecutaste persona::get({$id})";
	    }
		*/

	    //-----------------

	    public static function getId($id) {
	        echo "Ejecutaste producto::getId({$id})";
			/*
	        select * 
	        from productos
	        where productos.id = :id
			*/
	    }
	    
	    public static function getMany($idIni, $idFin) {
	        echo "Ejecutaste producto::getMany({$idIni} {$idFin})";
			/*
	        select * 
	        from productos
	        where (productos.id >= :idIni) and (productos.id >= :idFin)
			*/

	    }

	    public static function getAll() {
	        echo "Ejecutaste producto::getAll()";
            /*
	        select * 
	        from productos
           */
	    }

	    public static function post($datosProducto){
	    	//extraer datos del JSon

	    	 $comando = "insert into Alumnos(nombreCompleto, numeroControl, carreraId, semestre)
			//values('Pedro Pérez', '20390000', 1);
	    	 	values(?,?,?)";
	    	 	// values(:name, :numControl, :carrera, :semestre);

			$sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
/*
        $sentencia->bindParam(1, $nombre);
        $sentencia->bindParam(2, $contrasenaEncriptada);
        $sentencia->bindParam(3, $claveApi);
        $sentencia->bindParam(4, $correo);
*/
        $resultado = $sentencia->execute();
		print $resultado;

	    }
	    // Otro escenario: Un método único get que recibe un solo parámetro de tipo array

	    /*
	    public static function get($arrayparams) {

	    	if (count($arrayparams) == 0)
					self::getAll();
				else  
					if (count($arrayparams) == 1)
						self::getId($parameters[0]);
					else
						if (count($arrayparams) == 2)
							self::getMany($parameters[0], $parameters[1]);
						//else return;
							
	        echo "Ejecutaste persona::get({$id})";
	    }
	    */

	}
?>

















