<?php 
include "../../config/conexion.php";

if (empty($_SESSION['id_usuario'])) {
    header("Location: salir.php?err=0");
    exit();
}

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
$datos = '';
foreach ($xmodulo as $xid_modulo => $xvalue) {
    foreach ($xvalue as $modulo => $xxvalue) {
        $datos .= "<li class='treeview'>
          <a href='#'>
            <i class='" . $mod_img[$xid_modulo][$modulo] . "'></i>
            <span>$modulo</span>
            <span class='pull-right-container'>
              <i class='fa fa-angle-left pull-right'></i>
            </span>
          </a>";
        $datos .= "<ul class='treeview-menu'>";
        foreach ($xxvalue as $subModulo) {
            $datos .= "<li>
            <a href='javascript:menu_principal(\"" . $smo_url[$xid_modulo][$modulo][$subModulo] . "\",\"$modulo\",\"$subModulo\")'>
            <i class='fa fa-circle-o'></i>$subModulo</a>
            </li>";
        }
        $datos .= "</ul></li>";
    }
}
?>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU NAVEGACION</li>
        <?php 
        echo $datos;         
        ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>