<?php

function crearProovedor($r) {
    include "../config/conexionGmedic.php";

    // Verificar si ya existe un usuario con ese nombre
    /*$verificarQuery = "SELECT COUNT(*) as total FROM usuario WHERE u_username = '" . mysqli_real_escape_string($coni, $r['nombreUsuario']) . "'";
    $verificarResult = mysqli_query($coni, $verificarQuery);

    if ($verificarResult) {
        $verificarRow = mysqli_fetch_assoc($verificarResult);
        $totalUsuarios = $verificarRow['total'];

        // Si no existe otro usuario con el mismo nombre, realizar la inserción
        if ($totalUsuarios == 0) {
            // Preparar la consulta de inserción*/
            $query = "INSERT INTO proovedor (id_tipo_documento,num_documento,razon_social,direccion,contacto,telefono,contacto2,telefono2,email_empresa,email_contacto) VALUES ('1', '" . $r['ruc'] . "', '" . $r['razonSocial'] . "', '" . $r['direccion'] . "', '" . $r['contacto'] . "', '" . $r['telefono'] . "', '" . $r['contacto2'] . "', '" . $r['telefono2'] . "', '" . $r['email'] . "', '" . $r['email2'] . "')";

            $result = mysqli_query($conexion, $query);

            if ($result) {
                // Obtener el último id_usuario insertado
                $ultimo_id = mysqli_insert_id($conexion);
                echo json_encode(array('success' => 'Registro Exitoso!', 'id_proovedor' => $ultimo_id));
            } else 
            {
                echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
            }
            /*
        } else {
            // Si ya existe un usuario con ese nombre, mostrar un mensaje de error
            echo json_encode(array('error' => 'Ya existe un usuario con ese nombre de usuario.'));
        }
    } else {
        // Manejar errores de consulta según tus necesidades
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
    }
    */        
    mysqli_close($conexion);
}


?>