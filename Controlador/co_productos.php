<?php
include "../Modelo/mo_productos.php";
if(isset($_POST['cargarProductos'])) 
{
    $searchValue = $_POST['searchValue'];
	$sql = cargarProductos($searchValue);
    echo json_encode($sql);
}
if(isset($_POST['crearProducto'])) 
{
	$sql=crearProducto($_POST);
    return  $sql;
}
if(isset($_POST['deleteProducto'])) 
{
	$sql=deleteProducto($_POST);
    return  $sql;
}
if(isset($_POST['updateProductos'])) 
{
	$sql=updateProductos($_POST);
    return  $sql;
}
if(isset($_POST['editarProducto'])) 
{
	$sql=editarProducto($_POST);
    return  $sql;
}
if(isset($_GET['ObtenerProductos'])) 
{
	$sql=ObtenerProductos($_GET);
    echo json_encode($sql);
}
if(isset($_POST['uploadArchivoProducto'])) 
{
	$sql=uploadArchivoProducto($_POST);
    return $sql;
}	

?>