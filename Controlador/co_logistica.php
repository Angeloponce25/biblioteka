<?php
include "../Modelo/mo_logistica.php";

///////////////////////////////////////////////////////////////////////////////////////////////

	if(isset($_POST['crearProovedor'])) {

		$sql=crearProovedor($_POST);

		return  $sql;

	}
	

?>