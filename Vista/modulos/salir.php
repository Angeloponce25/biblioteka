<?php

@session_start();

session_destroy();

if(isset($_REQUEST['err']))

header("Location: ../../index.php");

else

header("Location: ../../index.php");

?>