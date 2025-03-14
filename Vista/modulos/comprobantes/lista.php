<?php
$modulo=$_POST['modulo'];
$opcion=$_POST['opcion'];
include("../../../config/conexion.php");
/**Comprobante */    
$sqlTusuario="SELECT id_tipo_usuario,tpu_descripcion FROM usuario_tipo"; 
$queryTusuario=mysqli_query($coni,$sqlTusuario);
$dataComprobantes=array();
while($rowTusuario=mysqli_fetch_array($queryTusuario))
{ 
  $dataComprobantes[]=$rowTusuario;
}
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
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">
                <div class="input-group input-group-sm" style="width: 250px;">
                        <select id="search-field" class="form-control">
                            <option value="cliente">CLIENTE</option>
                        </select>
                    <div class="input-group-btn">
                        
                    </div>
                    <input type="text" id="search-input" autocomplete="off" name="search-input" class="form-control pull-right" placeholder="Buscar producto">
                    
                </div>
              </h3>
              <div class="box-tools">             
                <a class="btn btn-success pull-right" href="javascript:menu_principal('comprobantes/nota_venta.php','COMPROBANTES','EMITIR NOTA VENTA');"><i class="fa fa-credit-card"></i> EMITIR NOTA VENTA</a>                
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table id="data-table-ventas" class="table table-bordered table-hover" style="text-transform: uppercase;">
              <thead>
                <tr>
                  <th>CODIGO</th>                  
                  <th>COMPROBANTE</th>
                  <th>CORRELATIVO</th>
                  <th>CLIENTE</th>
                  <th>METODO PAGO</th>
                  <th>TOTAL</th>                  
                  <th>FECHA</th>
                  <th>VENDEDOR</th>
                  <th>ACCION</th>
                </tr>
              </thead>
              <tbody>
                            <!-- Los datos se cargarán aquí dinámicamente -->
              </tbody>
              </table>

                <div class="pagination pull-right">
                      <button onclick="prev_page()" id="prev-page">«</button>
                      <span>Página <span id="current-page">1</span> de <span id="total-pages">1</span></span>
                      <button onclick="next_page()" id="next-page">»</button>
                </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->
<script>
  var data = [];
  var filteredData = [];
  // Inicializar la tabla con los datos iniciales
  $(document).ready(function() {        
        $.ajax({
            url:'../../Controlador/co_comprobantes.php',
            data:{cargarVentas:1},
            type: 'GET',
            dataType: 'json',
            success: function (datosObtenidos) {
                if (datosObtenidos && datosObtenidos.length > 0) {
                Array.prototype.push.apply(data, datosObtenidos);
                loadTableVentas(datosObtenidos,1);
                } else {
                    console.error('Error al obtener Usuarios');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error en la solicitud AJAX:', error);
            }
        });
        
          // Evento keyup para el input de búsqueda
    $('#search-input').keyup(function() {
        var searchText = $(this).val().toLowerCase(); // Obtener el texto de búsqueda y convertirlo a minúsculas
        var filteredData = data.filter(function(item) {
            for (var key in item) 
            {
                if (item.hasOwnProperty(key)) {
                    if (item[key].toLowerCase().indexOf(searchText) !== -1) {
                        return true;
                    }
                }
            }
            return false;
        });
        loadTableVentas(filteredData,1); // Cargar la tabla con los datos filtrados*/
        
    });


    });
</script>