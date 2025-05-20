<?php

//Dentro de index.php

require_once 'ConexionBD.php';

print ConexionBD::obtenerInstancia()->obtenerBD()->errorCode();
print "Conexión exitosa";
