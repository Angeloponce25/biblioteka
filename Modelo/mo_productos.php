<?php
session_start();
require_once '../asset/library/PhpSpreadsheet/Spreadsheet.php'; // Ajusta la ruta según la ubicación real de PhpSpreadsheet
/*require_once '../asset/library/PhpSpreadsheet/Writer/Xlsx.php'; // Ajusta la ruta según la ubicación real de PhpSpreadsheet*/
require_once '../asset/library/PhpSpreadsheet/IOFactory.php'; // Ajusta la ruta según la ubicación real de PhpSpreadsheet

function uploadArchivoProducto($r)
{
    include "../config/conexion.php";
    // Ruta donde se guardará el archivo subido
    $uploadDir = '../uploads/';

    // Nombre del archivo subido
    $uploadedFile = $_FILES['file']['tmp_name'];

    // Verificar si se recibió el archivo correctamente
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(array('error' => 'NO funciono'));
        return;
    }
    
    echo json_encode(array('success' => 'funciono'));
}

function ObtenerProductos($searchValue) 
{
    include "../config/conexion.php";
    // Construye la consulta SQL con búsqueda remota
    $query = "SELECT id_producto, descripcion, precio_venta, unidad_medida, stock FROM productos";
    
    // Aplica el filtro de búsqueda si se proporciona
    if (!empty($searchValue)) {
        $searchValue = mysqli_real_escape_string($coni, $searchValue); // Escapar la cadena para evitar SQL Injection
        $query .= " WHERE descripcion LIKE '%$searchValue%' OR id_producto = '$searchValue'";
    }
    
    mysqli_set_charset($coni, 'utf8'); // Establecer la codificación UTF-8
    
    $result = mysqli_query($coni, $query);

    if ($result) {
        $productos = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
        return $productos;
    } else {
        return array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni));
    }

    // Cierra la conexión
    mysqli_close($coni);
}

function cargarProductos($searchValue) {
    include "../config/conexion.php";
    // Construye la consulta SQL con búsqueda remota
    $query = "SELECT id_producto, descripcion, stock, stock_min, unidad_medida, precio_compra, precio_venta, precio_venta_minimo, fecha_vencimiento, almacen FROM productos";
    
    // Aplica el filtro de búsqueda si se proporciona
    if (!empty($searchValue)) {
        $searchValue = mysqli_real_escape_string($coni, $searchValue); // Escapar la cadena para evitar SQL Injection
        $query .= " WHERE descripcion LIKE '%$searchValue%' OR id_producto = '$searchValue'";
    }
    
    mysqli_set_charset($coni, 'utf8'); // Establecer la codificación UTF-8
    
    $result = mysqli_query($coni, $query);

    if ($result) {
        $productos = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
        return $productos;
    } else {
        return array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni));
    }

    // Cierra la conexión
    mysqli_close($coni);
}

function crearProducto($r){
    include "../config/conexion.php";
    // Verificar si ya existe un producto con esa descripción
    $verificarQuery = "SELECT COUNT(*) as total FROM productos WHERE descripcion = '".mysqli_real_escape_string($coni, $r['descripcion'])."'";
    $verificarResult = mysqli_query($coni, $verificarQuery);
    $verificarRow = mysqli_fetch_assoc($verificarResult);
    $totalProductos = $verificarRow['total'];

    // Si no existe otro producto con la misma descripción, realizar la inserción
    if ($totalProductos == 0)
    {
        // Preparar la consulta de inserción
        $query = "INSERT INTO productos (descripcion, stock, stock_min, unidad_medida, precio_compra, precio_venta, precio_venta_minimo, fecha_vencimiento, almacen) VALUES ('".mysqli_real_escape_string($coni, $r['descripcion'])."', '".$r['stock']."', '".$r['stock_min']."', '".$r['unidad_medida']."', '".$r['precio_compra']."', '".$r['precio_venta']."', '".$r['precio_venta_min']."', '".$r['fecha_vencimiento']."', '".$r['almacen']."')";

        $result = mysqli_query($coni, $query);
        if ($result) 
        {        
            // Obtener el último id_producto insertado
            $ultimo_id = mysqli_insert_id($coni);
            echo json_encode(array('success' => 'Registro Exitoso!', 'id_producto' => $ultimo_id));
            $usuario = $_SESSION["usuario"];
            // logAction("Producto Creado", "USUARIO: $usuario - ID PRODUCTO: $ultimo_id - PRODUCTO: ".mysqli_real_escape_string($coni, $r['descripcion']));
        } 
        else 
        {
            echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
        }
    } 
    else 
    {
        // Si ya existe un producto con esa descripción, mostrar un mensaje de error
        echo json_encode(array('error' => 'Ya existe un producto con esta descripcion.'));
    }
}

function deleteProducto($r)
{
    include "../config/conexion.php";
    $query = "DELETE FROM productos WHERE id_producto=" . $r['idProducto'];
    $result = mysqli_query($coni, $query);
    if ($result) 
    {        
        echo json_encode(array('success' => 'Producto Eliminado con Éxito!'));
    } 
    else 
    {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
    }
}

function updateProductos($r)
{
    include "../config/conexion.php";
    $query = "UPDATE productos SET descripcion='".mysqli_real_escape_string($coni, $r['descripcion'])."', stock='".$r['stock']."', stock_min='".$r['stockmin']."', unidad_medida='".$r['unidad_medida']."', precio_compra='".$r['precio_compra']."', precio_venta='".$r['precio_venta']."', precio_venta_minimo='".$r['precio_venta_min']."', fecha_vencimiento='".$r['fecha_vencimiento']."', almacen='".$r['almacen']."' WHERE id_producto=".$r['idProducto'];
    $result = mysqli_query($coni, $query);
    if ($result) 
    {        
        echo json_encode(array('success' => 'Producto Actualizado con Éxito!'));
    } 
    else 
    {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
    }
}

function editarProducto($r)
{
    include '../config/conexion.php';
    $query = "SELECT id_producto, descripcion, stock, stock_min, unidad_medida, precio_compra, precio_venta, precio_venta_minimo, fecha_vencimiento, almacen FROM productos WHERE id_producto=" . $r['idProducto'];
    $result = mysqli_query($coni, $query);
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
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
    }
}
?>