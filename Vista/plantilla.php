<?php
session_start();
require "../../config/variablesGlobales.php";
include "../../config/conexion.php";


if (empty($_SESSION["id_usuario"])) {
    header("Location: salir.php?err=0");
    exit();
}

header('Content-Type: text/html; charset=UTF-8'); 

$codigo_usuario = $_SESSION['id_usuario'];

// Consulta principal para obtener los módulos y opciones del usuario
$sql = "SELECT 
          u.id_usuario,
          ut.id_tipo_usuario,
          mods.id_modulo,
          mods.mod_descripcion,
          mods.mod_url,
          mods.mod_img,
          modsop.id_modulo_opcion,
          modsop.smo_descripcion,
          modsop.smo_url,
          modsop.smo_img
        FROM usuario u
        INNER JOIN usuario_tipo ut ON ut.id_tipo_usuario = u.id_tipo_usuario
        INNER JOIN usuario_tipo_modulos utm ON utm.id_tipo_usuario = ut.id_tipo_usuario
        INNER JOIN modulos mods ON mods.id_modulo = utm.id_modulo
        LEFT JOIN modulos_opciones modsop ON modsop.id_modulo_opcion = utm.id_modulo_opcion
        WHERE u.id_usuario = '$codigo_usuario'
        GROUP BY mods.id_modulo, modsop.id_modulo_opcion
        ORDER BY mods.ordenM, modsop.ordenMO, modsop.smo_descripcion";

$query = $coni->query($sql);
if (!$query) {
    die("Error en la consulta: " . $coni->error);
}

// Consulta para contar los comprobantes de venta
$sql_comprobantes = "SELECT COUNT(*) AS total_comprobantes FROM comprobantes_venta";
$query_comprobantes = $coni->query($sql_comprobantes);
$comprobantes = $query_comprobantes->fetch_assoc();
$totalComprobantes = $comprobantes['total_comprobantes'];

// Consulta para sumar el total vendido
$sql_ventas = "SELECT SUM(total_vendido) AS total_vendido FROM comprobantes_venta";
$query_ventas = $coni->query($sql_ventas);
$ventas = $query_ventas->fetch_assoc();
$totalVentas = $ventas['total_vendido'];

// Consulta para contar los productos
$sql_productos = "SELECT COUNT(*) AS total_productos FROM productos";
$query_productos = $coni->query($sql_productos);
$productos = $query_productos->fetch_assoc();
$totalProductos = $productos['total_productos'];

// Consulta para sumar el stock de productos
$sql_stock = "SELECT SUM(stock) AS total_stock FROM productos";
$query_stock = $coni->query($sql_stock);
$stock = $query_stock->fetch_assoc();
$totalStock = $stock['total_stock'];

// Inicialización de arrays para almacenar los datos
$xmodulo = $mod_img = $mod_url = $smo_url = $smo_img = array();

// Procesamiento de los resultados de la consulta principal
while ($row = $query->fetch_assoc()) {
    $xmodulo[$row['id_modulo']][$row['mod_descripcion']][$row['smo_descripcion']] = $row['smo_descripcion'];
    $mod_img[$row['id_modulo']][$row['mod_descripcion']] = $row['mod_img'];
    $mod_url[$row['id_modulo']][$row['mod_descripcion']] = $row['mod_url'];
    $smo_url[$row['id_modulo']][$row['mod_descripcion']][$row['smo_descripcion']] = $row['smo_url'];
    $smo_img[$row['id_modulo']][$row['mod_descripcion']][$row['smo_descripcion']] = $row['smo_img'];
}

// Generación del HTML para los elementos del menú
$accesos_directos = '';
foreach ($xmodulo as $xid_modulo => $xvalue) {
    foreach ($xvalue as $modulo => $xxvalue) {
        foreach ($xxvalue as $subModulo) {
            $accesos_directos .= "<div class='col-md-3 col-sm-6' onclick='javascript:menu_principal(\"" . $smo_url[$xid_modulo][$modulo][$subModulo] . "\",\"$modulo\",\"$subModulo\")' style='cursor: pointer;'> 
                                    <div class='small-box bg-aqua'>
                                        <div class='inner'>
                                            <h4>$modulo</h4>
                                            <p>$subModulo</p>
                                        </div>
                                        <div class='icon'>
                                            <i class='" . $mod_img[$xid_modulo][$modulo] . "'></i>
                                        </div>
                                        <a href='javascript:menu_principal(\"" . $smo_url[$xid_modulo][$modulo][$subModulo] . "\",\"$modulo\",\"$subModulo\")' class='small-box-footer'>$subModulo <i class='fa fa-arrow-circle-right'></i></a>
                                    </div>
                                  </div>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $empresa; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Agrega los estilos CSS de DataTables -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../dist/css/custom.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/responsive.bootstrap.min.css">
  <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

  <!-- FullCalendar -->
  <link rel="stylesheet" href="../bower_components/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="../bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">

   <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include "modulos/cabecera.php"; ?>
  <?php include "modulos/menu.php"; ?>
  


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <div id='home'>
      <section class="content-header">
        <h1>
          Inicio
          <small>Accesos Directos</small>
        </h1>
    </section>
    <section class="content">
    <div class="row">    





    <?php 

        echo $accesos_directos;         
        ?>
    </div>
    <div class="row">
        <h2><center>RESUMEN</center></h2>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa  fa-floppy-o"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">VENTAS</span>
              <span class="info-box-number"><?php echo $totalComprobantes;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">TOTAL VENDIDO</span>
              <span class="info-box-number">S/ <?php echo $totalVentas;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Productos</span>
              <span class="info-box-number"><?php echo $totalProductos;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Stock</span>
              <span class="info-box-number"><?php echo $totalStock;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
    </section>

    </div>
  </div>
  <?php include "modulos/footer.php"; ?>
  <!-- /.content-wrapper -->  
  
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Agrega la biblioteca DataTables -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../js/global.js"></script>
<script src="../js/sesion.js"></script>
<script src="../js/usuarios.js"></script>
<script src="../js/productos.js"></script>
<script src="../js/nota_venta.js"></script>
<script src="../js/comprobantes.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
</script>
</body>
</html>
