<?php
error_reporting(E_ALL ^ E_DEPRECATED);

ini_set("memory_limit", "10240M");
ini_set('max_execution_time', 6000);
ini_set('default_socket_timeout', 6000);
set_time_limit(6000);

$ruta_web = $_SERVER['DOCUMENT_ROOT'].'/gmedic/';


$bd_host = "localhost";
$bd_usuario = 'root';
$bd_pass = '';
$bd_base = "gmedic";

// Crear la conexión utilizando mysqli
$coni = new mysqli($bd_host, $bd_usuario, $bd_pass, $bd_base);

// Verificar la conexión
if ($coni->connect_error) {
    die("La conexión ha fallado: " . $coni->connect_error);
}

// Establecer el conjunto de caracteres
$coni->set_charset("utf8");

// Función para limpiar valores 'null'
if (!function_exists('clear_null')) {
    function clear_null($array) {
        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (!is_array($k)) {
                    $array[$k] = $v == 'null' ? '' : $v;
                }
            }
        }
        return $array;
    }
}

// Función para obtener la ruta base
if (!function_exists('getBasePath')) {
    function getBasePath() {
        return $_SERVER['DOCUMENT_ROOT'].'/gmedic/';
    }
}

// Función para obtener el alcance
if (!function_exists('getScope')) {
    function getScope() {
        $serv = explode('.', $_SERVER['SERVER_NAME']);
        return $serv[0];
    }
}

// Función para registrar una accin en un archivo de log
if (!function_exists('logAction')) {
    function logAction($action, $details) {
        $timestamp = date("Y-m-d H:i:s");
        $logEntry = "$timestamp - $action: $details" . PHP_EOL;
        $logFile = __DIR__ . "/../../log.txt"; // Ruta absoluta del archivo de registro
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
}

?>