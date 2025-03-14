<?php

class co_menu{
	
	public function cargarRutas()
    {

        $ruta = mo_menu::cargarRutas();

        $rutaActual = isset($_GET["ruta"]) ? $_GET["ruta"] : "contenido";

        // Busca la ruta actual en las rutas cargadas
        $rutaEncontrada = null;
        foreach ($ruta as $ruta_controlador) {
            if ($ruta_controlador["archivo_ruta"] === $rutaActual) {
                $rutaEncontrada = $ruta_controlador;
                break;
            }
        }

        // Si se encuentra la ruta, incluye el archivo asociado
        if ($rutaEncontrada !== null) {
            include "Vista/modulos/" . $rutaEncontrada["archivo_ruta"] . ".php";
        } else {
            // Si la ruta no se encuentra, incluye un archivo predeterminado o muestra un mensaje de error
            include "Vista/modulos/contenido.php";
        }

	}
    public function cargarMenu()
    {
        $menus = mo_menu::cargarMenu();
        $html = '';
        foreach ($menus as $row) {
            $isActive = ($row['url'] == $url_actual); // Reemplaza $url_actual con la URL actual de tu aplicación            
            $html .= '<li class="treeview">';
            $html .= '<a href="' . ($row['url'] ? $row['url'] : '#') . '">';
            $html .= '<i class="fa ' . $row['icono'] . '"></i> <span>' . $row['descripcion'] . '</span>';
            $html .= '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
            $html .= '</a>';
            $html .= '<ul class="treeview-menu"><li><a href="../../index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li></ul>';
            $html .= '</li>';
        }


    
        echo $html;
    }

    public function submenus()
    {

    }
}