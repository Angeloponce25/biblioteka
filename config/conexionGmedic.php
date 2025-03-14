<?php

$servidor = "192.168.1.151";
$usuario = 'webmaster';
$contrasena = 'super';
$base_de_datos = "gperu_cmdn";

// Crear la conexión utilizando mysqli
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("La conexión ha fallado: " . $coni->connect_error);
}

// Establecer el conjunto de caracteres
$conexion->set_charset("utf8");

?>