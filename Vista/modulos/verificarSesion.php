<?php
session_start();
///////////////////////////////////////////////////
logearse($_REQUEST['id_usuario']);
///////////////////////////////////////////////////
function logearse($idUsuario){
	include "../../config/conexion.php";

	$idUsuario = $coni->real_escape_string($idUsuario);

	$sql = "SELECT * FROM usuario u 
            INNER JOIN usuario_tipo ut ON ut.id_tipo_usuario = u.id_tipo_usuario 
            WHERE u.id_usuario = '$idUsuario' AND u.id_estado = 1";

	$query = $coni->query($sql);

	if (!$query) 
	{
		die("Error en la consulta: " . $coni->error);
	}
	
	if ($query->num_rows > 0) {
		$row = $query->fetch_assoc();
		$_SESSION["id_usuario"] = $row['id_usuario'];
		$_SESSION["usuario"] = $row['u_username'];
		$_SESSION["tipo_usuario"] = $row['tpu_descripcion'];
		$_SESSION["id_tipo_usuario"] = $row['id_tipo_usuario'];
	
		header("Location: home.php");
	} else 
	{
		header("Location: salir.php?err=0");
	}
	
	$query->close();
	$coni->close();   
}
?>