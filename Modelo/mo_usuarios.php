<?php 
session_start();
function cargarUsuarios($r){

include "../config/conexion.php";
$query = "SELECT u.id_usuario,u.u_username,u.id_estado,ut.tpu_descripcion,ut.id_tipo_usuario
FROM usuario u
INNER JOIN usuario_tipo ut ON ut.id_tipo_usuario=u.id_tipo_usuario";   

$result = mysqli_query($coni,$query);
    if ($result) 
    {
        $productos = array();
        while ($row = mysqli_fetch_assoc($result)) 
        {
            $productos[] = $row;
        }
        echo json_encode($productos);
    } 
    else 
    {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysql_error()));
    }

}
function crearUsuario($r) {
    include "../config/conexion.php";

    // Verificar si ya existe un usuario con ese nombre
    $verificarQuery = "SELECT COUNT(*) as total FROM usuario WHERE u_username = '" . mysqli_real_escape_string($coni, $r['nombreUsuario']) . "'";
    $verificarResult = mysqli_query($coni, $verificarQuery);

    if ($verificarResult) {
        $verificarRow = mysqli_fetch_assoc($verificarResult);
        $totalUsuarios = $verificarRow['total'];

        // Si no existe otro usuario con el mismo nombre, realizar la inserción
        if ($totalUsuarios == 0) {
            // Preparar la consulta de inserción
            $query = "INSERT INTO usuario (u_username, u_password, id_estado, id_tipo_usuario) VALUES ('" . mysqli_real_escape_string($coni, $r['nombreUsuario']) . "', '" . md5($r['password']) . "', '" . $r['estado'] . "', '" . $r['tipoUsuario'] . "')";

            $result = mysqli_query($coni, $query);

            if ($result) {
                // Obtener el último id_usuario insertado
                $ultimo_id = mysqli_insert_id($coni);
                echo json_encode(array('success' => 'Registro Exitoso!', 'id_usuario' => $ultimo_id));
            } else {
                echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
            }
        } else {
            // Si ya existe un usuario con ese nombre, mostrar un mensaje de error
            echo json_encode(array('error' => 'Ya existe un usuario con ese nombre de usuario.'));
        }
    } else {
        // Manejar errores de consulta según tus necesidades
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
    }

    mysqli_close($coni);
}
function deleteUsuario($r)
{
    include "../config/conexion.php";
	$query="delete FROM usuario WHERE id_usuario=".$r['idUsuario']."";
	$result = mysqli_query($coni,$query);
    if ($result) 
    {        
        echo json_encode(array('success' => 'Usuario Eliminado con Exito!'));
    } 
    else 
    {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysql_error()));
    }
}
function updateUsuario($r)
{
    include "../config/conexion.php";
	$query="UPDATE usuario SET u_username='".htmlentities($r['nombreUsuario'])."',id_tipo_usuario='".$r['tipoUsuario']."',id_estado='".$r['estado']."' WHERE id_usuario=".$r['idUsuario']."";
	$result = mysqli_query($coni,$query);
    if ($result) 
    {        
        echo json_encode(array('success' => 'Usuario Actualizado con Exito!'));
    } 
    else 
    {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysql_error()));
    }
}
?>
