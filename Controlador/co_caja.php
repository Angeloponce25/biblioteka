<?php
include "../Modelo/mo_caja.php";
if(isset($_POST['crearCaja'])){
    $sql=crearCaja($_POST);
    return $sql;
}
if(isset($_GET['cargarCantidadUltimaCaja'])){
    $sql=cargarCantidadUltimaCaja($_GET);
    return $sql;
}
if(isset($_POST['crearMovimiento'])){
    $sql=crearMovimiento($_POST);
    return $sql;
}
if(isset($_POST['obtenerMovimiento'])){
    $sql=obtenerMovimiento($_POST);
    return $sql;
}
if(isset($_POST['deleteMovimiento'])){
    $sql=deleteMovimiento($_POST);
    return $sql;
}
if(isset($_POST['updateMovimiento'])){
    $sql=updateMovimiento($_POST);
    return $sql;
}
if(isset($_GET['reporteCajaUnitario'])){
    $sql=reporteCajaUnitario($_GET);
    return $sql;
}
if(isset($_POST['cerrarCaja'])){
    $sql=cerrarCaja($_POST);
    return $sql;
}
if(isset($_POST['cargarCajas'])) 
{
    $searchValue = $_POST['searchValue'];
	$sql = cargarCajas($searchValue);
    echo json_encode($sql);
}
if(isset($_POST['cargarMovimientos'])) 
{
    $searchValue = $_POST['searchValue'];
	$sql = cargarMovimientos($searchValue);
    echo json_encode($sql);
}
?>