<?php 
session_start();

function iniciarSesion($r){
    include "../config/conexion.php";

    $usuario = $coni->real_escape_string($r['usuario']);
    $clave = md5($coni->real_escape_string($r['clave']));

    $sql = "SELECT * FROM usuario WHERE u_username='$usuario' AND u_password='$clave' AND id_estado=1";
    $query = $coni->query($sql);

    $data = array();
    $date2 = new DateTime('2025-03-20');
    $date1 = new DateTime("now");

    if ($date1 >= $date2) {
        echo "Licencia de Uso Expirado.";
        return;
    }

    if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode("error");
    }

    $query->close();
    $coni->close();
}

function mensaje()
{
    include "../../config/conexionSQL.php";

    try {
        // Prepara y ejecuta la consulta
        $query = "SELECT TOP 100 * FROM Cliente";
        $stmt = $conSQL->query($query);
        
        // Almacena los resultados en un array
        $clientes = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientes[] = $fila;
        }
        
        // Convierte el array a JSON
        $jsonClientes = json_encode($clientes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        echo "<pre>" . $jsonClientes . "</pre>";
    } catch (PDOException $e) {
        echo "Error al consultar los datos: " . $e->getMessage();
    }

    $conSQL = null;
}





?>
