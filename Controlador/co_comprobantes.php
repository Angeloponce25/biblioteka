<?php
include "../Modelo/mo_comprobantes.php";
if(isset($_GET['correlativoDocumento'])){
    $sql=correlativoDocumento($_GET);
    return $sql;
}
if(isset($_GET['correlativoCaja'])){
    $sql=correlativoCaja($_GET);
    return $sql;
}
if(isset($_POST['cargarProductosVender'])) 
{
	$searchValue = $_POST['searchValue'];
	$sql = cargarProductosVender($searchValue);
    echo json_encode($sql);
}
if(isset($_GET['cargarVentas'])) 
{
	$sql=cargarVentas($_GET);
    return  $sql;
}
if(isset($_GET['ObtenerProductos'])) 
{
	$sql=ObtenerProductos($_GET);
    echo json_encode($sql);
}
if(isset($_POST['completarVenta'])){
    $sql=completarVenta($_POST);
    return $sql;
}
if(isset($_REQUEST['printPdfComprobante'])){	
	$sql=printPdfComprobante($_REQUEST);
	return $sql;
}	
if(isset($_GET['verPdfComprobante'])){	
	$sql=verPdfComprobante($_GET);
	return $sql;
}	
if(isset($_POST['deleteVenta'])) 
{
	$sql=deleteVenta($_POST);
    return  $sql;
}
if(isset($_POST['ConsultarDoc'])){
    $sql=ConsultarDoc($_POST);
    return $sql;
}
if(isset($_POST['findProducto'])){
   
    $id_tabla = $_POST['id_tabla'];
    $producto = $_POST['producto'];
    $sql = findProducto($id_tabla,$producto);
    echo $sql; // Imprimir los resultados
}

?>