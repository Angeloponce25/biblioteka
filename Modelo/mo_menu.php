<?php
require_once "ConexionBD.php";
class mo_menu extends ConexionBD
{
    static public function cargarRutas()
    {
        $pdo = ConexionBD::conectarBD()->prepare("SELECT id_ruta, nombre_ruta, archivo_ruta, id_ruta_padre FROM sys_rutas");
        
        $pdo->execute();

        $resultados = $pdo->fetchAll(PDO::FETCH_ASSOC);

        // Cierra la conexión y devuelve los resultados
        $pdo = null;

        return $resultados;
    }

    static public function cargarMenu()
    {
        $pdo = ConexionBD::conectarBD()->prepare("SELECT id_modulo,descripcion,url,icono,estado,orden,url_padre FROM sys_modulos");
        $pdo->execute();
        $resultados = $pdo->fetchAll(PDO::FETCH_ASSOC);

        $pdo=null;

        return $resultados;
    }
}


?>