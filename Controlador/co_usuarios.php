<?php
include "../Modelo/mo_usuarios.php";
if(isset($_GET['cargarUsuarios'])) 
{
	$sql=cargarUsuarios($_GET);
    return  $sql;
}
if(isset($_POST['crearUsuario'])) 
{
	$sql=crearUsuario($_POST);
    return  $sql;
}
if(isset($_POST['deleteUsuario'])) 
{
	$sql=deleteUsuario($_POST);
    return  $sql;
}
if(isset($_POST['updateUsuario'])) 
{
	$sql=updateUsuario($_POST);
    return  $sql;
}
	

?>