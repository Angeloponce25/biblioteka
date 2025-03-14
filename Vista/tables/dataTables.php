<?php
session_start();



class DataTables{

  // DATA_TABLE CLIENTES LISTAR CLIENTES
  public  function dtaClientes(){

 $action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
 if($action == 'ajax'){
    // escaping, additionally removing everything that could be (html/javascript-) code
    /*$perfilUsuario = $_REQUEST['perfilOcultoc'];*/
    $search = $_GET['search'];
    $selectnum = $_GET['selectnum'];
    $aColumns = array('num_documento', 'razon_social','contacto');//Columnas de busqueda
    $sTable = 'proovedor';
    $sWhere = "";
   if (isset($search))
   {
       $sWhere = "WHERE (";
       for ( $i=0 ; $i<count($aColumns) ; $i++ )
       {
           $sWhere .= $aColumns[$i]." LIKE '%".$search."%' OR ";
       }
       $sWhere = substr_replace( $sWhere, "", -3 );
       $sWhere .= ')';
   }
   
   	//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
   include_once 'pagination.php';
    //include pagination file
   $per_page = $selectnum; //how much records you want to show
   $adjacents  = 4; //gap between pages after number of adjacents
   $offset = ($page - 1) * $per_page;
  
   //Count the total number of row in your table*/
   include "../../config/conexionGmedic.php";
   $sql = "SELECT count(*) AS numrows FROM $sTable  $sWhere";
   $result = $conexion->query($sql);


    // Obtener el número de filas
   $row = $result->fetch_assoc();
   $totalRegistros = $row['numrows'];
   $tpages = ceil($totalRegistros/$per_page);
   $reload = './index.php';

    
   //main query to fetch the data
   
    // Construir la consulta SQL
    $sql2 = "SELECT * FROM $sTable $sWhere LIMIT $offset, $per_page";
    // Ejecutar la consulta
    $result2 = $conexion->query($sql2);
    // Verificar si la consulta fue exitosa
    $registros = $result2->fetch_all(MYSQLI_ASSOC);
       
         
             foreach($registros as $key => $value):
           echo  "<tr class='ida'>
               <td> ".(++$key)."</td>
               <td>". $value['num_documento']."</td>
               <td>". $value['razon_social']."</td>
               <td>". $value['direccion']."</td>
               <td>". $value['contacto']."</td>
               <td>". $value['telefono']."</td>
               <td>". $value['email_empresa']."</td>
               <td>". $value['email_empresa']."</td>               
             </tr>";
               
             endforeach;
             $conexion->close();
                   $paginador = new Paginacion();
                   $paginador = $paginador->paginarClientes($reload, $page, $tpages, $adjacents);  
            echo"<tr>
              <td colspan='10' style='text-align:center;'>".$paginador."</td>
             </tr>";
            }
}
public function dtainventarioProductos(){
  $action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';
  if ($action == 'ajax') {
      // escaping, additionally removing everything that could be (html/javascript-) code
      $search = $_GET['search'];
      $selectnum = $_GET['selectnum'];
      $aColumns = array('p.IdProducto', 'p.descripcion'); // Columnas de búsqueda con alias de tabla
      $sWhere = "";

      if (isset($search)) {
          $sWhere = "WHERE (";
          for ($i = 0; $i < count($aColumns); $i++) {
              $sWhere .= $aColumns[$i] . " LIKE '%" . $search . "%' OR ";
          }
          $sWhere = substr_replace($sWhere, "", -3);
          $sWhere .= ') AND dc.stock > 0';
      } else {
          $sWhere = "WHERE dc.stock > 0";
      }

      // pagination variables
      $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
      include_once 'pagination.php';
      $per_page = $selectnum; // cuántos registros quieres mostrar
      $adjacents = 4; // espacio entre páginas después del número de adjacents
      $offset = ($page - 1) * $per_page;

      // Contar el número total de filas en tu tabla
      include "../../config/conexionGmedic.php";
      $sql = "SELECT COUNT(*) AS total_filas
              FROM detalle_comprobante_compras dc
              LEFT JOIN productos p ON dc.id_producto = p.IdProducto
              LEFT JOIN laboratorio l ON p.IdLaboratorio = l.id_laboratorio
              LEFT JOIN tipo_presentacion tp ON p.IdPresentacion = tp.IdPresentacion
              WHERE dc.stock > 0";
      $result = $conexion->query($sql);

      // Obtener el número de filas
      $row = $result->fetch_assoc();
      $totalRegistros = $row['total_filas'];
      $tpages = ceil($totalRegistros / $per_page);
      $reload = './index.php';

      // Construir la consulta SQL
      $sql2 = "SELECT
                  dc.id_detalle,
                  tproducto.descripcion AS clasificacion,
                  p.codigo,
                  p.IdProducto,
                  p.descripcion AS producto,
                  IFNULL(tp.abreviatura, '--') AS presentacion,
                  IFNULL(l.nombre, 'Sin Laboratorio') AS laboratorio,
                  dc.lote,
                  dc.stock,
                  dc.precio_venta AS precioVenta,
                  dc.f_venc_pro AS fechaVencimiento,
                  ROUND((dc.precio_venta - dc.valor_unitario), 2) AS valor_monetario
               FROM
                  detalle_comprobante_compras dc
               LEFT JOIN
                  productos p ON dc.id_producto = p.IdProducto
               LEFT JOIN
                  laboratorio l ON p.IdLaboratorio = l.id_laboratorio
               LEFT JOIN
                  tipo_presentacion tp ON p.IdPresentacion = tp.IdPresentacion
               LEFT JOIN
                  tipo_producto tproducto ON p.TipoProducto = tproducto.id_tipo_producto
               $sWhere
               LIMIT $offset, $per_page";

      // Ejecutar la consulta
      $result2 = $conexion->query($sql2);

      // Verificar si la consulta fue exitosa
      $registros = $result2->fetch_all(MYSQLI_ASSOC);

      foreach ($registros as $key => $value) {
          echo "<tr class='ida'>
                  <td>" . (++$key) . "</td>
                  <td>" . $value['clasificacion'] . "</td>
                  <td>" . $value['codigo'] . "</td>
                  <td>" . $value['IdProducto'] . "</td>
                  <td>" . $value['producto'] . "</td>
                  <td>" . $value['laboratorio'] . "</td>
                  <td>" . $value['presentacion'] . "</td>
                  <td>" . $value['stock'] . "</td>               
               </tr>";
      }

      $conexion->close();
      $paginador = new Paginacion();
      $paginador = $paginador->paginarinventarioProductos($reload, $page, $tpages, $adjacents);  
      echo "<tr>
              <td colspan='10' style='text-align:center;'>" . $paginador . "</td>
           </tr>";
  }
}



    
  }
    


       if(isset($_REQUEST['dc'])){
         if($_REQUEST['dc'] == "dc"){
        $dataClientes = new DataTables();
        $dataClientes->dtaClientes();
       }
      }
       if(isset($_REQUEST['inventarioProductos'])){
         if($_REQUEST['inventarioProductos'] == "inventarioProductos"){
        $dataClientes = new DataTables();
        $dataClientes->dtainventarioProductos();
       }
      }
?>
<script>


</script>
