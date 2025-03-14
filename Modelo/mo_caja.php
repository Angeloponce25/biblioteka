<?php
session_start();
require('../asset/library/dompdf/autoload.inc.php');
require('../config/variablesGlobales.php');

use Dompdf\Dompdf;

function reporteCajaUnitario($r)
{
    // Verificar que las variables globales estén definidas
    global $titulo_empresa, $empresa;    
    // Crear una instancia de Dompdf
    $fecha_actual = new DateTime("now");
    $fecha_ahora = $fecha_actual->format('Y-m-d H:i:s');
    //Fecha
    include "../config/conexion.php";
    //DATOS CABECERA

    $queryCabecera = "SELECT fecha_apertura,fecha_cierre,monto_apertura,monto_cierre,estado FROM caja WHERE id=".$r['id_caja'];
    
    $query = mysqli_query($coni, $queryCabecera);
    $data = array();

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    foreach ($data as $dat) {
        $fecha_apertura = $dat['fecha_apertura'];
        $fecha_cierre = $dat['fecha_cierre'];
        $monto_apertura = $dat['monto_apertura'];
        $estado = $dat['estado'];
    }

    

    //DATOS DE VENTAS
    $queryDetalle = "SELECT 
    tp.descripcion AS tipo_pago,
    SUM(cv.total_vendido) AS total_vendido
FROM 
    comprobantes_venta cv
LEFT JOIN 
    caja c ON c.id = cv.id_caja
INNER JOIN 
    tipo_pago tp ON tp.id_tipo_pago = cv.tipo_pago
WHERE 
    cv.id_caja = '1'
GROUP BY 
    tp.descripcion";   

    $detalleCajaUnitarioTPago = mysqli_query($coni, $queryDetalle);
    // Variables para almacenar los datos del detalle
    $detalleCajaTPago = array();

    if ($detalleCajaUnitarioTPago) {
        while ($detalleCajaRowTPago = mysqli_fetch_assoc($detalleCajaUnitarioTPago)) {
            $detalleCajaTPago[] = $detalleCajaRowTPago;
        }
    } else {
        echo json_encode(array('error' => 'Error en la consulta de detalles: ' . mysqli_error($coni)));
    }


    //DATOS DE VENTAS
    $query = "SELECT cv.id_comprobante_venta,cv.fecha_creacion as fecha, CONCAT(cv.serie, '-', cv.numero) AS documento,c.id AS caja,tp.descripcion AS tipo_pago,cv.total_vendido,u.u_username,tc.descripcion AS comprobante
    FROM comprobantes_venta cv
    LEFT JOIN caja c ON c.id = cv.id_caja
    INNER JOIN tipo_pago tp ON tp.id_tipo_pago = cv.tipo_pago
    INNER JOIN usuario u ON u.id_usuario = cv.vendedor
    INNER JOIN tipo_comprobante tc ON tc.IdComprobante = cv.tipo_comprobante
    WHERE id_caja = ".$r['id_caja'];   

    $detalleCajaUnitario = mysqli_query($coni, $query);
    // Variables para almacenar los datos del detalle
    $detalleCaja = array();

    if ($detalleCajaUnitario) {
        while ($detalleCajaRow = mysqli_fetch_assoc($detalleCajaUnitario)) {
            $detalleCaja[] = $detalleCajaRow;
        }
    } else {
        echo json_encode(array('error' => 'Error en la consulta de detalles: ' . mysqli_error($coni)));
    }


    // Calcular el total vendido
    $totalVendido = 0;
    foreach ($detalleCaja as $row) {
        $totalVendido += $row['total_vendido'];
    }
    
    $totalCaja = $totalVendido+$monto_apertura;
    // Crear el contenido HTML
    $html = '
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #96adc4;
        }
        h1 {
            text-align: center;
        }
        .derecha {
            text-align: right;
        }
    </style>

    <h2><center>REPORTE VENTAS CAJA N° '.$r['id_caja'].'</center></h2>
    <table>
        <tr>
            <td class="borderless" style="vertical-align: top;text-transform:uppercase;">
                <table class="borderless">
                    <tr><td class="borderless">Empresa: '.$empresa.'</td></tr>                    
                    <tr><td class="borderless">Vendedor: '.$_SESSION["usuario"].'</td></tr>
                    <tr><td class="borderless">Estado de caja: '.($estado == 1 ? 'Aperturada' : 'Cerrada').'</td></tr>                    
                </table>
            </td>
            <td class="borderless" style="vertical-align: top;text-transform:uppercase;">
                <table class="borderless">
                    <tr><td class="borderless">Fecha reporte: '.$fecha_ahora.'</td></tr>                    
                    <tr><td class="borderless">Fecha y hora apertura: '.$fecha_apertura.'</td></tr>
                    <tr><td class="borderless">Monto Inicial: S/ '.number_format($monto_apertura,2).'</td></tr>
                </table>
            </td>
        </tr>
    </table>
<br>
<table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>#</th>                
                <th>DESCRIPCION</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>';
    
    foreach ($detalleCaja as $row) {
        $html .= '
            <tr>
                <td>' . $row['id_comprobante_venta'] . '</td>                
                <td>' . $row['fecha'] . '</td>
                <td>' . $row['comprobante'] . '</td>
            </tr>
            ';
    }

    $html .= '
        </tbody>
    </table>
<br>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>#</th>                
                <th>FECHA</th>
                <th>COMPROBANTE</th>
                <th>DOCUMENTO</th>                
                <th>T. VENDIDO</th>
                <th>T. PAGO</th>                
                <th>VENDEDOR</th>
            </tr>
        </thead>
        <tbody>';
    
    foreach ($detalleCaja as $row) {
        $html .= '
            <tr>
                <td>' . $row['id_comprobante_venta'] . '</td>                
                <td>' . $row['fecha'] . '</td>
                <td>' . $row['comprobante'] . '</td>                
                <td>' . $row['documento'] . '</td>
                <td>S/ ' . number_format($row['total_vendido'],2) . '</td>
                <td>' . $row['tipo_pago'] . '</td>                
                <td>' . strtoupper($row['u_username']) . '</td>
            </tr>
            ';
    }

    $html .= '
        </tbody>
    </table>
    <h3 class="derecha">TOTAL VENDIDO:  S/ ' . number_format($totalVendido,2) . '</h3>
    <h3 class="derecha">MONTO INICIAL:  S/ ' . number_format($monto_apertura,2) . '</h3>
    <h3 class="derecha">TOTAL CAJA:  S /' . number_format($totalCaja,2) . '</h3>';

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    // Nombrar el archivo PDF
    $dompdf->stream('reporte_ventas_caja_1.pdf', ['Attachment' => 0]);
}
function cerrarCaja($r)
{       
    include "../config/conexion.php";
    $fecha_actual = new DateTime("now");
    $fecha_cierre = $fecha_actual->format('Y-m-d H:i:s');

    $monto_cierre = consultarVentas($r['id']);
    

    $query = "UPDATE caja SET estado='2', fecha_cierre = '".$fecha_cierre."',monto_cierre='".$monto_cierre."' WHERE id=".$r['id'];
    $result = mysqli_query($coni, $query);
    if ($result) 
    {        
        echo json_encode(array('success' => 'Caja Cerrada con Éxito! '));
    } 
    else 
    {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
    }
}
function obtenerMovimiento($r)
{
    include '../config/conexion.php';
    $query = "SELECT m.id, m.tipo, m.empresa, m.usuario,u.u_username, m.fecha, m.descripcion, m.monto, m.detalle, m.id_caja 
    FROM movimientos m
    INNER JOIN usuario u ON u.id_usuario = m.usuario
    WHERE m.id=" . $r['id'];
    $result = mysqli_query($coni, $query);
    if ($result)
    {
        $movimientos = array();
        while ($row = mysqli_fetch_assoc($result)) 
        {
            $movimientos[] = $row;
        }
        echo json_encode($movimientos);
    }
    else
    {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
    }

}
function updateMovimiento($r)
{
    include "../config/conexion.php";
	$query="UPDATE movimientos SET descripcion='".htmlentities($r['descripcion'])."',monto='".$r['monto']."',detalle='".$r['detalle']."' WHERE id=".$r['id']."";
	$result = mysqli_query($coni,$query);
    if ($result) 
    {        
        echo json_encode(array('success' => 'Movimiento Actualizado con Exito!'));
    } 
    else 
    {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysql_error()));
    }
}
function deleteMovimiento($r)
{
    include "../config/conexion.php";
	$query="delete FROM movimientos WHERE id=".$r['id']."";
	$result = mysqli_query($coni,$query);
    if ($result) 
    {        
        echo json_encode(array('success' => 'Movimiento Eliminado con Exito!'));
    } 
    else 
    {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysql_error()));
    }
}
function consultarVentas($id_caja)
{
    include "../config/conexion.php";
    // Habilitar excepciones para errores de MySQLi
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        //Obtenemos el valor inicial

        // Ejecutar la consulta
        $checkMontoInicial = "SELECT monto_apertura FROM caja WHERE id=".$id_caja;
        $checkMonto = mysqli_query($coni, $checkMontoInicial);
        $rowMonto = mysqli_fetch_assoc($checkMonto);

        // Ejecutar la consulta
        $checkQuery = "SELECT SUM(total_vendido) AS total FROM comprobantes_venta WHERE id_caja=".$id_caja;
        $checkResult = mysqli_query($coni, $checkQuery);
        $row = mysqli_fetch_assoc($checkResult);

        // Verificar si se encontraron ventas
        $total = $rowMonto['monto_apertura']+$row['total'];
    } catch (mysqli_sql_exception $e) {
        // Manejar la excepción y registrar el error si es necesario
        error_log("Error en la consulta SQL: " . $e->getMessage());
        $total = 0;
    }

    return $total;
}
function cargarCajas($searchValue) {
    include "../config/conexion.php";
    // Construye la consulta SQL con búsqueda remota
    $query = "SELECT id,fecha_apertura,fecha_cierre,monto_apertura,monto_cierre,estado FROM caja";
    
    // Aplica el filtro de búsqueda si se proporciona
    if (!empty($searchValue)) {
        $searchValue = mysqli_real_escape_string($coni, $searchValue); // Escapar la cadena para evitar SQL Injection
        $query .= " WHERE fecha_apertura LIKE '%$searchValue%' OR monto_apertura = '$searchValue'";
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
function cargarMovimientos($searchValue) {
    include "../config/conexion.php";
    // Construye la consulta SQL con búsqueda remota
    $query = "SELECT id,tipo,empresa,usuario,fecha,descripcion,monto,detalle,id_caja FROM movimientos";
    
    // Aplica el filtro de búsqueda si se proporciona
    if (!empty($searchValue)) {
        $searchValue = mysqli_real_escape_string($coni, $searchValue); // Escapar la cadena para evitar SQL Injection
        $query .= " WHERE fecha LIKE '%$searchValue%' OR descripcion = '$searchValue'";
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

function crearCaja($r){
    include "../config/conexion.php";
    $fecha_actual = new DateTime("now");
    $fecha_apertura = $fecha_actual->format('Y-m-d H:i:s');
        // Verificar si ya existe una caja con estado 1
    $checkQuery = "SELECT COUNT(*) AS count FROM caja WHERE estado = 1";
    $checkResult = mysqli_query($coni, $checkQuery);
    $row = mysqli_fetch_assoc($checkResult);
    
    if ($row['count'] > 0) {
        // Si existe una caja con estado 1, no permitir la creación de una nueva
        echo json_encode(array('error' => 'No puede crear caja chica, por favor cierre caja chica para el usuario definido'));
    } 
    else 
    {
        // Preparar la consulta de inserción
        $query = "INSERT INTO caja (fecha_apertura, monto_apertura, estado) VALUES ('".$fecha_apertura."', '".$r['saldo_inicial']."', '1')";

        $result = mysqli_query($coni, $query);
        if ($result) 
        {        
            // Obtener el último id_producto insertado
            $ultimo_id = mysqli_insert_id($coni);
            echo json_encode(array('success' => 'Registro Exitoso!', 'id_caja' => $ultimo_id));
        } 
        else 
        {
            echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
        }
    }
}
function crearMovimiento($r){
    include "../config/conexion.php";
    $fecha_actual = new DateTime("now");
    $fecha= $fecha_actual->format('Y-m-d H:i:s');
    
    // Validar que los campos necesarios no estén vacíos
    if (empty($r['descripcion']) || empty($r['monto']) || empty($r['detalle'])) {
        echo json_encode(array('error' => 'Todos los campos (descripcion, monto, detalle) son obligatorios.'));
        return;
    }

    // Preparar la consulta para obtener el último id con estado 1
    $query = "SELECT id FROM caja WHERE estado = 1 ORDER BY id DESC LIMIT 1";
    
    $result = mysqli_query($coni, $query);

    if ($result) 
    {
        $row = mysqli_fetch_assoc($result);
        if ($row) 
        {
            $ultimo_id = $row['id'];
            // Preparar la consulta de inserción
            $query_movimientos = "INSERT INTO movimientos (tipo,empresa,usuario,fecha,descripcion,monto,detalle,id_caja) VALUES ('".$r['tipo']."','".$r['empresa']."','".$_SESSION["id_usuario"]."','".$fecha."','".$r['descripcion']."','".$r['monto']."','".$r['detalle']."', '".$ultimo_id."')";

            $result_movimientos = mysqli_query($coni, $query_movimientos);
            if ($result_movimientos) 
            {        
                // Obtener el último id_producto insertado
                $ultimo_id = mysqli_insert_id($coni);
                echo json_encode(array('success' => 'Registro Exitoso!', 'id_movimiento' => $ultimo_id));
            } 
            else 
            {
                echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
            }
        } 
        else 
        {
            echo json_encode(array('error' => 'No se encontró una caja Abierta.'));
        }
    } 
    else 
    {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
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
function cargarCantidadUltimaCaja($r)
{
    include "../config/conexion.php";

    // Preparar la consulta para obtener el último id con estado 1
    $query = "SELECT id,monto_cierre FROM caja WHERE estado = 2 ORDER BY id DESC LIMIT 1";
    
    $result = mysqli_query($coni, $query);

    if ($result) 
    {
        $row = mysqli_fetch_assoc($result);
        if ($row) 
        {
            $ultimo_monto = $row['monto_cierre'];
            echo json_encode(array('success' => 'Registro Exitoso!', 'monto' => $ultimo_monto));
        } 
        else 
        {
            echo json_encode(array('error' => 'No se encontró una caja Abierta.'));
        }
    } 
    else 
    {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
    }
}
?>