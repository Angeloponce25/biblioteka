<?php
$servidor = "DESKTOP-I6TQGIL"; // Cambia a la dirección de tu servidor SQL
$baseDatos = "Ventas"; // Cambia al nombre de tu base de datos
$usuario = "usuario"; // Cambia al nombre de usuario de la base de datos
$contrasena = "usuario123"; // Cambia a la contraseña de tu base de datos

try {
    // Crear la conexión utilizando PDO con el conjunto de caracteres UTF-8
    $conSQL = new PDO(
        "sqlsrv:Server=$servidor;Database=$baseDatos;TrustServerCertificate=true",
        $usuario,
        $contrasena,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 2 // Tiempo de espera en segundos
        )
    );
    echo "Conexión exitosa a SQL Server con UTF-8.<br>";
} catch (PDOException $e) {
    echo "Error en la conexión a SQL Server: " . $e->getMessage();
    $conSQL = null;
}

if ($conSQL) {
    // La conexión se estableció correctamente
    echo "La conexión a la base de datos se verificó exitosamente.<br>";
} else {
    // La conexión falló
    echo "No se pudo establecer la conexión a la base de datos.<br>";
}
?>