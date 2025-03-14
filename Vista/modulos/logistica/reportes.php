<?php
$modulo = $_POST['modulo'];
$opcion = $_POST['opcion'];
include("../../../config/conexion.php");
// Consulta para obtener tipos de usuario
$sqlTusuario = "SELECT id_tipo_usuario, tpu_descripcion FROM usuario_tipo";
$queryTusuario = mysqli_query($coni, $sqlTusuario);

// Inicialización del array para almacenar los resultados
$dataTusuario = array();

// Procesamiento de los resultados de la consulta
while ($rowTusuario = mysqli_fetch_array($queryTusuario)) {
    $dataTusuario[] = $rowTusuario;
}

session_start();
?>

<section class="content-header">
      <h1>
        <?php echo $modulo; ?>
        <small><?php echo $opcion; ?></small>
      </h1>
</section>
    <!-- Main content -->
    <section class="content">
      <!-- /.row -->
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
            <div class="contenedor-busqueda">
                        <div class="input-group-search">
                            <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadInventarioReportes(1)">                                
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                                <div class="input-search"> 
                                <input type="search" class="search" id="search" name="search" placeholder="Buscar..." onkeyup="loadInventarioReportes(1)" style="width: 100%;">
                                <span class="input-group-addo icon-search"><i class="fa fa-search"></i></span> 
                                </div>
                        </div>
                    </div>
            
            <h3 class="box-title">
            <button class="btn btn-success  pull-right btn-radius" onclick="descargarReporte();"><i class="fas fa-download"></i>Descargar Reporte           
            </button>
            </h3>
            <div class="box-tools">                
            </div>
            <!-- /.box-header -->
             <input type="hidden" id="perfilOcultoc" value="Administrador">
             <div class="table-responsive">
                    <table class="table  dt-responsive tabla-clientes tbl-t" width="100%" >

                    <thead>
                    <tr>
                        <th style="width:10px;">#</th>
                        <th>Clasificacion</th>
                        <th>Codigo</th>
                        <th>Id</th>
                        <th>Producto</th>
                        <th>Laboratorio</th>
                        <th>Presentación</th>
                        <th width="100px">Stock</th>
                        </tr>
                    </thead>                    
                    
                        <?php
                        echo "<tbody class='body-clientes'></tbody>";

                        ?>
                
                </table>
                
            </div>


            <div class="box-body table-responsive no-padding">
                <!-- los table iban aqui -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->
<script>
function loadInventarioReportes(page){
        let search= $("#search").val();
        let selectnum= $("#selectnum").val();
        let parametros={"action":"ajax","page":page,"search":search,"selectnum":selectnum, "inventarioProductos":"inventarioProductos"};
   
    $.ajax({
        url: '../tables/dataTables.php',        
        data: parametros,  
        beforeSend: function(){
            //  $("body").append(loadcl);
        },   
        success:function(data){           
          
                /*$(".reloadcl").hide();*/
                $('.body-clientes').html(data); 
                /*console.log(data);*/
             
             
        }
    })
};

loadInventarioReportes(1);


function descargarReporte()
{     
    window.open('../../Reportes/demo.php', '_blank');
}
</script>
