<?php
session_start();
session_destroy();
// Obtén el directorio actual del script
$directorio_actual = dirname($_SERVER['PHP_SELF']);

// Redirige a la ubicación deseada
header('Location: ' . $directorio_actual);// Redirigir a la raíz del sitio o a donde desees

exit();
?>