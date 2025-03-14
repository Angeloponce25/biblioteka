<?php 
session_start();
require('../config/variablesGlobales.php');
function findProducto($id, $searchValue)
{
    include "../config/conexion.php";

    // Consulta SQL base para obtener los productos
    $sql = "SELECT id_producto, descripcion FROM productos";

    // Aplica el filtro de búsqueda si se proporciona
    if (!empty($searchValue)) {
        $searchValue = mysqli_real_escape_string($coni, $searchValue); // Escapar la cadena para prevenir SQL injection
        $sql .= " WHERE descripcion LIKE '%" . $searchValue . "%' OR id_producto LIKE '%" . $searchValue . "%' LIMIT 5";
    } else {
        // Limitar los resultados a 5 cuando no hay ninguna cadena de búsqueda
        $sql .= " LIMIT 5";
    }

    $query = mysqli_query($coni, $sql);
    $datos = ''; // Inicializar la variable que contendrá los datos

    if (!$query || mysqli_num_rows($query) == 0) {
        $datos .= "No se encontraron registros...";
    } else {
        $datos .= "<ul>";
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            // Generar el HTML para cada resultado
            $datos .= "<li class='pointer' onclick=takeProducto('$id', '" . $row['id_producto'] . "', '" . str_replace(' ', '_', $row['descripcion']) . "');>" . $row['descripcion'] . "</li>";
        }
        $datos .= "</ul>";
    }

    // Cierra la conexión
    mysqli_close($coni);

    return $datos;
}
function ObtenerProductos($searchValue) {
    include "../config/conexion.php";
    // Construye la consulta SQL con búsqueda remota
    $query = "SELECT id_producto, descripcion, precio_venta, unidad_medida, stock FROM productos WHERE stock > 0";

    mysqli_set_charset($coni, 'utf8');	
    $result = mysqli_query($coni, $query);

    if ($result) {
        $productos = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
        mysqli_free_result($result); // Liberar el resultado
        mysqli_close($coni); // Cerrar la conexión

        return $productos;
    } else {
        $error = 'Error en la consulta SQL: ' . mysqli_error($coni);
        mysqli_close($coni); // Cerrar la conexión

        return array('error' => $error);
    }
}
function ConsultarDoc($r)
{
	$token = 'apis-token-6713.UtnY0HTzEhxQBhEZ4Po3Zrbt3h34GMfs';	
	$nro_documento = $r['nro_documento'];

	// Iniciar llamada a API
	$curl = curl_init();



	switch ($r['tipoDoc']) {
		case 1://DNI			
		curl_setopt_array($curl, array(
			// para user api versión 2
			CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $nro_documento,
			// para user api versión 1
			// CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero=' . $dni,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 2,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
			'Referer: https://apis.net.pe/consulta-dni-api',
			'Authorization: Bearer ' . $token
			),
		));
						
			break;
		case 3://RUC
		// Buscar ruc sunat
		curl_setopt_array($curl, array(
			// para usar la versión 2
			CURLOPT_URL => 'https://api.apis.net.pe/v2/sunat/ruc?numero=' . $nro_documento,
			// para usar la versión 1
			// CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero=' . $ruc,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
			'Referer: http://apis.net.pe/api-ruc',
			'Authorization: Bearer ' . $token
			),
		));
			break;

	}	

	$response = curl_exec($curl);

	curl_close($curl);
	// Datos de empresas según padron reducido
	$empresa = json_decode($response);
	echo $response;
}
function deleteVenta($r)
{
    include "../config/conexion.php";	
    $consulta_productos = "SELECT dcv.id_detalle_venta, dcv.id_producto, dcv.cantidad
    FROM detalle_comprobante_ventas dcv
    WHERE id_comprobante_venta =" . $r['idVenta'];

    $result_consulta = mysqli_query($coni, $consulta_productos);

    $eliminarData = array();
    if (mysqli_num_rows($result_consulta) > 0)
    {		
        while ($productosEliminar = mysqli_fetch_array($result_consulta)) 
        {
            $eliminarData[] = $productosEliminar;
        }

        foreach ($eliminarData as $dataDelete) 
        {
            $idproducto = $dataDelete['id_producto'];				
            $cantidad = $dataDelete['cantidad'];
            // Restar la cantidad del stock en la tabla detalle_comprobante_compra
            $sqlSumarStock = "UPDATE productos SET stock = stock + $cantidad WHERE id_producto =$idproducto";
            $querySumarStock = mysqli_query($coni, $sqlSumarStock);
            if (!$querySumarStock) {
                echo json_encode(array('error' => 'Error al actualizar el stock: ' . mysqli_error($coni)));
                return;
            }
        }

        $queryDCV = "DELETE FROM detalle_comprobante_ventas WHERE id_comprobante_venta=" . $r['idVenta'];
        $resultDCV = mysqli_query($coni, $queryDCV);

        $query = "DELETE FROM comprobantes_venta WHERE id_comprobante_venta=" . $r['idVenta'];
        $result = mysqli_query($coni, $query);

        if ($result) 
        {        
            echo json_encode(array('success' => 'Eliminado con éxito'));
        } 
        else 
        {
            echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
        }
    }

    mysqli_close($coni);
}
function printPdfComprobante($r)
{	

    include "../config/conexion.php";
    require_once('mo_cantidad_en_letras.php');	
    $id = $r["id_venta"];
    $usuario = $_SESSION["usuario"];
    $fecha = date('d/m/Y');
    $hora = date('H:i:s');
    $sql = "SELECT cv.id_comprobante_venta, tc.descripcion AS comprobante, cv.ndocumento, cv.cliente, cv.serie, cv.numero, tp.descripcion AS tipo_pago, cv.fecha_creacion, cv.total_vendido, u.u_username
    FROM comprobantes_venta cv
    INNER JOIN tipo_comprobante tc ON tc.IdComprobante = cv.tipo_comprobante
    INNER JOIN tipo_pago tp ON tp.id_tipo_pago = cv.tipo_pago
    INNER JOIN usuario u ON u.id_usuario = cv.vendedor
    WHERE id_comprobante_venta='" . $id . "'";
    
    $query = mysqli_query($coni, $sql);
    $data = array();

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    foreach ($data as $dat) {
        $serie = $dat['serie'];
        $comprobante = $dat['comprobante'];
        $numero = html_entity_decode($dat['numero']);
        $ndocumento = html_entity_decode($dat['ndocumento']);
        $cliente = html_entity_decode($dat['cliente']);
        $tipo_pago = html_entity_decode($dat['tipo_pago']);
        $total_vendido = html_entity_decode($dat['total_vendido']);
    }

    // Consulta para obtener los detalles del comprobante de venta
    $detalleSql = "SELECT dcv.cantidad, dcv.id_producto, p.descripcion AS nombre_producto, dcv.precio_venta 
                    FROM detalle_comprobante_ventas dcv 
                    INNER JOIN productos p ON dcv.id_producto = p.id_producto 
                    WHERE dcv.id_comprobante_venta='" . $id . "'";

    $detalleQuery = mysqli_query($coni, $detalleSql);

    // Variables para almacenar los datos del detalle
    $detalleData = array();

    if ($detalleQuery) {
        while ($detalleRow = mysqli_fetch_assoc($detalleQuery)) {
            $detalleData[] = $detalleRow;
        }
    } else {
        echo json_encode(array('error' => 'Error en la consulta de detalles: ' . mysqli_error($coni)));
    }

    $subtotal = number_format($total_vendido / 1.18, 2);
    $igv = number_format($total_vendido - $subtotal, 2);
    $total_vendido = number_format($total_vendido, 2);

echo '<div style="width: 400px; text-align: center; color: #000;">';

echo 'CLAUDIFIESTAS<br>';
echo 'CALLE COMERCIO 545<br>';
echo 'MOLLENDO - AREQUIPA<br><br>';
echo '<b>NOTA DE VENTA '.$serie.' - '.$numero.'</b><br>';
echo '</div><br>';
echo 'FECHA EMISIÓN '.$fecha.'  /  '.$hora.'<br>';
echo 'CLIENTE : '.strtoupper($cliente).'<br>';
echo 'DOC. IDENTIDAD : '.$ndocumento.'<br>';
echo 'DIRECCIÓN : <br><br>';
echo '<table width="400PX" cellpadding="1" cellspacing="1" border=1" >';

echo '<tr>';
echo '<td style="border:none;"><center>CANT</center></td>';
echo '<td style="border:none;"><center>DESCRIPCION</center></td>';
echo '<td style="border:none;"><center>PRECIO</center></td>';
echo '<td style="border:none;"><center>TOTAL</center></td>';
echo '</tr>';


foreach ($detalleData as $detalle) {
    $precio_venta = $detalle['precio_venta'];
    $precio_formateado = number_format($precio_venta, 2);

    $precio_total = $detalle['precio_venta'] * $detalle['cantidad'];
    $total_formateado = number_format($precio_total, 2);

    echo '<tr>';				    
    echo '<td style="border:none;">' . $detalle['cantidad'] . '</td>';
    echo '<td style="border:none;">' . strtoupper($detalle['nombre_producto']) . '</td>';
    echo '<td style="border:none;">' . $precio_formateado. '</td>';		
    echo '<td style="border:none;">' . $total_formateado . '</td>';
    echo '</tr>';
}

echo '<tr>';
echo '<td style="border:none;"></td>';
echo '<td style="border:none;"></td>';

echo '</tr>';
echo '<tr>';
echo '<td style="border:none;"></td>';
echo '<td style="border:none;"></td>';

echo '</tr>';

echo '</table>';
echo '<table width="400PX" cellpadding="1" cellspacing="1" border=0" >';
echo '<tr>';
echo '<td style="border:none; width="180px";>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td style="border:none; width="180px"">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td style="border:none;">TOTAL</td>';



echo '<td style="border:none;">'.$total_vendido.'</td>';

echo '</tr>';
echo '</table>';
echo '<br>';



echo 'SON: '.CantidadEnLetra($total_vendido).'<br>';
echo 'TIPO PAGO: EFECTIVO<br>';
echo 'CAJERO: CAJA<br>';

echo '<div style="width: 400px; text-align: center; color: #000;">';
echo '<p style="font-size:13px;">NO SE ACEPTAN CAMBIOS NI DEVOLUCIONES</p>';
echo '</div>';


echo '<div style="width: 400px; text-align: center; color: #000;">';
echo '<center><img src="../Vista/dist/img/logo_yape.jpg" width="150px"></center>';
echo '</div>';



echo '<script type="text/javascript">location.href ="javascript:print()";</script>';
}

/*
Funciona con PDF hay que revisar la libreria

function printPdfComprobante($r)
{	
    $codigo=$r['id_venta'];
	$date=date('dmY');
	$time = time();
	$datos = Comprobante($r['id_venta']);
	include_once("../asset/library/mpdf/mpdf.php");
    set_time_limit(0);
    $mpdf=new mPDF('c',array(56, 150),'','',1,1,1,1,1,1);
    $mpdf->useOddEven = 1;
	$stylesheet = file_get_contents('../Vista/css/estilo_comprobante.css');
    $mpdf->WriteHTML($stylesheet,1);
	$mpdf->SetAutoPageBreak(false);
    $mpdf->mirrorMargins = 1;
			$pref='Comprobante';
			$mpdf->WriteHTML($datos, 2);
    $vz=($zip==1)?"F":"D";
    $mpdf->Output($pref.$codigo.'.pdf', $vz);
    if($zip==1){
        echo "<script language='javascript'>window.open('./temporal/".$pref.".pdf"."','_self','');</script>";//para ver el archivo pdf generado
    }
		
}*/
function verPdfComprobante_OLD($r)
{   
    $codigo = $r['id_venta'];
    $date = date('dmY');
    $time = time();
    $datos = Comprobante($r['id_venta']);
    include_once("../asset/library/mpdf/mpdf.php");
    set_time_limit(0);
    $mpdf = new mPDF('c', array(80, 150), '', '', 2, 2, 3, 3, 4, 4);
    $mpdf->useOddEven = 1;
    $stylesheet = file_get_contents('../Vista/css/estilo_comprobante.css');
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->SetAutoPageBreak(false);
    $mpdf->mirrorMargins = 1;
    $pref = 'Comprobante';
    $mpdf->WriteHTML($datos, 2);

    // Modificar el título del PDF
    $mpdf->SetTitle("COMPROBANTE - $codigo");
    
    // Obtener el contenido del PDF en formato binario
    $output = $mpdf->Output('', 'S');
    $pdfContent = base64_encode($output);

    // Imprimir el contenido del PDF como una URL de datos
    echo "<embed src='data:application/pdf;base64," . $pdfContent . "' type='application/pdf' width='100%' height='800px' />";
}
function verPdfComprobante($r)
{   
    $codigo = $r['id_venta'];
    $date = date('dmY');
    $time = time();
    $datos = Comprobante($r['id_venta']);

    include_once("../asset/library/mpdf/mpdf.php");

    // Configurar mPDF con un tamaño de página personalizado
    $mpdf = new mPDF('', [58, 600]);
    $mpdf->useOddEven = 1;
    $stylesheet = file_get_contents('../Vista/css/estilo_comprobante.css');
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->SetAutoPageBreak(false);
    $mpdf->mirrorMargins = 1;

    // Agregar el texto al PDF
    $mpdf->WriteHTML($datos, 2);

    // Modificar el título del PDF
    $mpdf->SetTitle("COMPROBANTE- $codigo");

    // Obtener el contenido del PDF en formato binario
    $output = $mpdf->Output('', 'S');
    $pdfContent = base64_encode($output);

    // Imprimir el contenido del PDF como una URL de datos
    echo "<embed src='data:application/pdf;base64," . $pdfContent . "' type='application/pdf' width='100%' height='auto' />";
}
function Comprobante($id)
{
	include "../config/conexion.php";
$usuario = $_SESSION["usuario"];
$fecha = date('d/m/Y');
$hora = date('H:i:s');

$sql = "SELECT cv.id_comprobante_venta, tc.descripcion AS comprobante, cv.ndocumento, cv.cliente, cv.serie, cv.numero, tp.descripcion AS tipo_pago, cv.fecha_creacion, cv.total_vendido, u.u_username
        FROM comprobantes_venta cv
        INNER JOIN tipo_comprobante tc ON tc.IdComprobante = cv.tipo_comprobante
        INNER JOIN tipo_pago tp ON tp.id_tipo_pago = cv.tipo_pago
        INNER JOIN usuario u ON u.id_usuario = cv.vendedor
        WHERE id_comprobante_venta='" . $id . "'";
        
$query = mysqli_query($coni, $sql);
$data = array();

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    foreach ($data as $dat) {
        $serie = $dat['serie'];
        $comprobante = $dat['comprobante'];
        $numero = html_entity_decode($dat['numero']);
        $ndocumento = html_entity_decode($dat['ndocumento']);
        $cliente = html_entity_decode($dat['cliente']);
        $tipo_pago = html_entity_decode($dat['tipo_pago']);
        $total_vendido = html_entity_decode($dat['total_vendido']);
    }

    // Consulta para obtener los detalles del comprobante de venta
    $detalleSql = "SELECT dcv.cantidad, dcv.id_producto, p.descripcion AS nombre_producto, dcv.precio_venta 
                    FROM detalle_comprobante_ventas dcv 
                    INNER JOIN productos p ON dcv.id_producto = p.id_producto 
                    WHERE dcv.id_comprobante_venta='" . $id . "'";
    
    $detalleQuery = mysqli_query($coni, $detalleSql);

    // Variables para almacenar los datos del detalle
    $detalleData = array();

    if ($detalleQuery) {
        while ($detalleRow = mysqli_fetch_assoc($detalleQuery)) {
            $detalleData[] = $detalleRow;
        }
    } else {
        echo json_encode(array('error' => 'Error en la consulta de detalles: ' . mysqli_error($coni)));
    }

    $subtotal = number_format($total_vendido / 1.18, 2);
    $igv = number_format($total_vendido - $subtotal, 2);
    $total_vendido = number_format($total_vendido, 2);
	$datos.="
    <body style='text-align: center; margin: 0; padding: 0;'>


    <table class='ticket' style='text-align:center; width: 100%; margin: 0; padding: 0;'>
    <tr>
      <td colspan='2' style='text-align:center;'>
        <p>CLAUDIFIESTAS</p>
        <p> Calle Comercio 545</p>
        <p> Mollendo,Islay,Arequipa</p>
        <p>TELF: +51942506363</p>        
      </td>
    </tr>	
    <tr>
      <td colspan='2'>
	  	<hr class='dashed-line'>
      </td>
    </tr>
    <tr>
      <td colspan='2' style='text-align: center'>
        <p><strong>NOTA DE VENTA $serie-$numero</strong></p>
      </td>
    </tr>

        <table style='width: 100%; font-size: 9px !important; margin-bottom: 0;'>
            <tr>
                <td class='left-align column-1'>
                    <p><strong>F. IMPR.</strong></p>
                    <p><strong>HORA IMPR.</strong></p>
                    <p><strong>TIPO DOC</strong></p>
                    <p><strong>N° DOC</strong></p>
                    <p><strong>CLIENTE</strong></p>
                    <p><strong>FORMA DE PAGO</strong></p>				
                </td>
                <td class='left-align column-2'>
                    <p>: $fecha</p>
                    <p>: $hora</p>
                    <p>: DNI</p>
                    <p>: $ndocumento</p>
                    <p style='text-transform: uppercase;'>: $cliente</p>
                    <p style='text-transform: uppercase;'>: $tipo_pago</p>				
                </td>
            </tr>
        </table>
    </table>
	<table style='width: 100%; border-collapse: collapse; font-size: 9px !important;margin-top: 8px;'>
		<thead>
			<tr>				
				<th class='table-line' style='text-align: center;'>DESCRIPCION</th>
				<th class='table-line' style='text-align: center;'>CANT.</th>
				<th class='table-line' style='text-align: center;'>P.U</th>				
				<th class='table-line' style='text-align: center;'>IMP.</th>
			</tr>
		</thead>
		<tbody>";

	foreach ($detalleData as $detalle) {
        $precio_venta = $detalle['precio_venta'];
        $precio_formateado = number_format($precio_venta, 2);

        $precio_total = $detalle['precio_venta'] * $detalle['cantidad'];
        $total_formateado = number_format($precio_total, 2);

		$datos .= "
			<tr >				
				<td style='text-align: center;text-transform: uppercase;'>" . $detalle['nombre_producto'] . "</td>
				<td style='text-align: center;'>" . $detalle['cantidad'] . "</td>
				<td style='text-align: center;'>" . $precio_formateado. "</td>				
				<td style='text-align: center;'>" . $total_formateado . "</td>
			</tr>";
	}

	$datos .= "
		</tbody>
	</table>
    <table>
	<tr>
		<td colspan='2'>
			<hr class='dashed-line'>
		</td>
		</tr>

		<tr>
			<td class='left-align column-3'>
            <p></p>					
			</td>
			<td class='right-align column-4'>
				<p class='total'><strong>TOTAL: S/ $total_vendido</strong></p>
			</td>
		</tr>
    <tr>
      <td colspan='2' style='text-align:center;font-size: 10px !important;'>
        <img src='../Vista/dist/img/yape.jpg' width='100%' class='center-align' width='60%'>
        <p style='text-transform: uppercase;'><strong>VENDEDOR: $usuario<strong></p>        
        <p><strong>GRACIAS POR SU COMPRA<strong></p>
        <p><strong>NO SE ACEPTAN DEVOLUCIONES<strong></p>        
      </td>
    </tr>
  </table>
	
	</body>";

	return $datos;
}
function cargarProductosVender($searchValue) {
    include "../config/conexion.php";

    // Construye la consulta SQL con búsqueda remota
    $query = "SELECT id_producto, descripcion, stock, stock_min, unidad_medida, precio_compra, precio_venta, precio_venta_minimo, fecha_vencimiento, almacen FROM productos ";

    // Aplica el filtro de búsqueda si se proporciona
    if (!empty($searchValue)) {
        $searchValue = mysqli_real_escape_string($coni, $searchValue);
        $query .= " WHERE (descripcion LIKE '%$searchValue%' OR id_producto = '$searchValue') AND stock > 0";
    } else {
        // Si no se proporciona un valor de búsqueda, solo selecciona los productos con stock > 0
        $query .= " WHERE stock > 0";
    }

    mysqli_set_charset($coni, 'utf8');
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

    logAction("Carga de Productos por parte de ", "USUARIO: $usuario");
    mysqli_close($coni);
}

function cargarVentas($r) {
    include "../config/conexion.php";
    $query = "SELECT cv.id_comprobante_venta, tc.descripcion AS comprobante, cv.cliente, cv.serie, cv.numero, tp.descripcion AS tipo_pago, cv.fecha_creacion, cv.total_vendido, u.u_username
            FROM comprobantes_venta cv
            INNER JOIN tipo_comprobante tc ON tc.IdComprobante = cv.tipo_comprobante
            INNER JOIN tipo_pago tp ON tp.id_tipo_pago = cv.tipo_pago 
            INNER JOIN usuario u ON u.id_usuario = cv.vendedor
            ORDER BY fecha_creacion DESC";

    $result = mysqli_query($coni, $query);
    
    if ($result) {
        $productos = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $row['editar'] = '<button class="btn btn-primary btn-small" onclick="imprimirVenta(' . $row['id_comprobante_venta'] . ')"><i class="fa fa-print"></i></button>';
            $row['eliminar'] = $_SESSION["id_tipo_usuario"] == '1' ? '<button class="btn btn-danger btn-small" onclick="eliminarVenta(' . $row['id_comprobante_venta'] . ')"><i class="fa fa-trash"></i></button>' : '';
            $productos[] = $row;
        }
        echo json_encode($productos);
    } else {
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
    }
    
    $usuario = $_SESSION["usuario"];
    logAction("Carga de Ventas por parte de ", "USUARIO: $usuario");

    mysqli_close($coni);
}

function correlativoDocumento($r)
{
    include "../config/conexion.php";

    // Consulta SQL para obtener el último número registrado para el tipo de documento
    $sql = "SELECT MAX(Actual) AS ultimoNumero, Serie
            FROM contadorfacturacion
            WHERE IdDocumento = '".$r['tipoDocumento']."'
            GROUP BY Serie";

    $result = mysqli_query($coni, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Calcular el siguiente número sumando 1 al último número registrado
        $siguienteNumero = $row["ultimoNumero"] + 1;
        $serie = $row["Serie"];

        echo json_encode(array('ultimoNumero' => $siguienteNumero, 'serie' => $serie ));
    } else {
        // Manejar errores de consulta según tus necesidades
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
    }

    mysqli_close($coni);
}

function correlativoCaja($r)
{
    include "../config/conexion.php";

    // Consulta SQL para obtener el último número registrado para el tipo de documento
    $sql = "SELECT MAX(id) AS ultimoID FROM caja where estado ='1'";

    $result = mysqli_query($coni, $sql);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Calcular el siguiente número sumando 1 al último número registrado
        $idCaja = $row["ultimoID"];        

        echo json_encode(array('idCaja' => $idCaja));
    } else {
        // Manejar errores de consulta según tus necesidades
        echo json_encode(array('error' => 'Error en la consulta SQL: ' . mysqli_error($coni)));
    }

    mysqli_close($coni);
}

function completarVenta($r) {
    include "../config/conexion.php";
    $id_usuario = $_SESSION["id_usuario"];
    $fecha_actual = date("Y-m-d");

    $sqlVenta = "INSERT INTO comprobantes_venta(tipo_comprobante, serie, numero, ndocumento, cliente, fecha_creacion, tipo_pago, total_vendido, vendedor,id_caja) 
                 VALUES (" . $r['tipo_comprobante'] . ",'" . $r['serie'] . "','" . $r['numero'] . "','" . $r['ndocumento'] . "','" . $r['cliente'] . "',CURRENT_TIMESTAMP,'" . $r['tpago'] . "','" . $r['total_vendido'] . "','" . $id_usuario . "','" . $r['caja'] . "')";

    $queryVenta = mysqli_query($coni, $sqlVenta);

    if ($queryVenta) {
        $ultimo_id = mysqli_insert_id($coni);
        
        foreach ($r['productos'] as $producto) {
            $idproducto = $producto['id_producto'];
            $cantidad = $producto['cantidad'];
            $precio_venta = $producto['precio_venta'];
            $total = $producto['total'];
            
            $sqlDetalleVenta = "INSERT INTO detalle_comprobante_ventas(id_comprobante_venta, id_producto, cantidad, precio_venta, total) 
                                VALUES ($ultimo_id, $idproducto, $cantidad, $precio_venta, $total)";
            $queryDetalleVenta = mysqli_query($coni, $sqlDetalleVenta);

            // Restar la cantidad del stock en la tabla productos
            $sqlRestarStock = "UPDATE productos SET stock = stock - $cantidad WHERE id_producto = $idproducto";
            $queryRestarStock = mysqli_query($coni, $sqlRestarStock);
        }

        // Actualizar el contador en la tabla contadorfacturacion
        $sqlActualizarContador = "UPDATE contadorfacturacion SET Actual = Actual + 1 WHERE Serie = '" . $r['serie'] . "'";
        $queryActualizarContador = mysqli_query($coni, $sqlActualizarContador);

        mysqli_close($coni);
        echo $ultimo_id;
    } else {
        echo "Error en la consulta de venta: " . mysqli_error($coni);
    }
}
?>
