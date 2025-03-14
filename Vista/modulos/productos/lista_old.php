<?php
$modulo=$_POST['modulo'];
$opcion=$_POST['opcion'];
include("../../../config/conexion.php");
/**Comprobante */    
$sqlTusuario="select id_tipo_usuario,tpu_descripcion from usuario_tipo"; 
$queryTusuario=mysql_query($sqlTusuario);
mysql_query('SET CHARACTER SET utf8'); 
while($rowTusuario=mysql_fetch_array($queryTusuario))
{ 
  $dataTusuario[]=$rowTusuario;
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
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" id="search-input" autocomplete="off" name="search-input" class="form-control pull-right" placeholder="Buscar producto">                    
                    <input type="hidden" class="form-control" id="tipoUsuario" name="tipoUsuario" value="<?php echo $_SESSION["id_tipo_usuario"]; ?>">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
              </h3>
              <div class="box-tools">                
                <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-agregar-producto"><i class="fa fa-credit-card"></i> Agregar
                </button>                
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table id="data-table-productos-inventario" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>CODIGO</th>
                  <th>DESCRIPCION</th>
                  <th>STOCK</th>
                  <th>STOCK MIN</th>
                  <th>UNIDAD MEDIDA</th>
                  <th>P. COMPRA</th>
                  <th>P. VENTA</th>
                  <th>P. MIN</th>
                  <th>F. VENCIMIENTO</th>
                  <th>ALMACEN</th>
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





<!-- /.MODALS -->
<div class="modal fade" id="modal-agregar-producto">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Producto</h4> <!-- Icono para el título del modal -->
            </div>
            <div class="modal-body">
                <form id="formAgregarProducto">
                    <div class="form-group">
                        <label for="descripcion"><i class="fa fa-pencil"></i> Descripción:</label> <!-- Icono para la etiqueta de descripción -->
                        <input type="text" class="form-control" id="descripcion" name="descripcion" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="stock"><i class="fa fa-cubes"></i> Stock:</label> <!-- Icono para la etiqueta de stock -->
                        <input type="number" class="form-control" id="stock" name="stock" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="stock_min"><i class="fa fa-level-down"></i> Stock Mínimo:</label> <!-- Icono para la etiqueta de stock mínimo -->
                        <input type="number" class="form-control" id="stock_min" name="stock_min" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="unidad_medida"><i class="fa fa-balance-scale"></i> Unidad de Medida:</label> <!-- Icono para la etiqueta de unidad de medida -->
                        <select class="form-control" id="unidad_medida" name="unidad_medida" required>
                            <option value="UNIDAD">Unidad</option>                            
                            <option value="PAQUETE">Paquete</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_compra"><i class="fa fa-money"></i> Precio de Compra:</label> <!-- Icono para la etiqueta de precio de compra -->
                        <input type="number" class="form-control" id="precio_compra" name="precio_compra" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="precio_venta"><i class="fa fa-money"></i> Precio de Venta:</label> <!-- Icono para la etiqueta de precio de venta -->
                        <input type="number" class="form-control" id="precio_venta" name="precio_venta" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="precio_venta_min"><i class="fa fa-money"></i> Precio de Venta Mínimo:</label> <!-- Icono para la etiqueta de precio de venta mínimo -->
                        <input type="number" class="form-control" id="precio_venta_min" name="precio_venta_min" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_vencimiento"><i class="fa fa-calendar"></i> Fecha de Vencimiento:</label> <!-- Icono para la etiqueta de fecha de vencimiento -->
                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" >
                    </div>
                    <div class="form-group">
                        <label for="almacen"><i class="fa fa-building"></i> Almacén:</label> <!-- Icono para la etiqueta de almacén -->
                        <select class="form-control" id="almacen" name="almacen">
                            <option value="1">Tienda</option>
                            <option value="2">Detalles</option>
                            <option value="3">Almacén</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button> <!-- Icono para el botón de Cerrar -->
                <button type="button" class="btn btn-primary" onclick="crearProducto();"><i class="fa fa-save"></i> Guardar</button> <!-- Icono para el botón de Guardar -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

    <!-- /.MODALS EDICION-->
    <div class="modal fade" id="modal-editar-producto">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i> Editar Producto</h4> <!-- Icono para el título del modal -->
            </div>
            <div class="modal-body">
                <form id="formEditarProducto">
                    <div class="form-group">      
                        <!-- Icono para la etiqueta de nombre de usuario -->                  
                        <input type="hidden" class="form-control" id="idEditarProducto" name="idEditarProducto">
                        <input type="hidden" class="form-control" id="indexProducto" name="indexProducto">
                    </div>
                    <div class="form-group">
                        <label for="nombreProducto"><i class="fa fa-user"></i> Descripcion:</label> <!-- Icono para la etiqueta de nombre de usuario -->
                        <input type="text" class="form-control" id="EditarnombreProducto" name="EditarnombreProducto" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="stock"><i class="fa fa-cubes"></i> Stock:</label> <!-- Icono para la etiqueta de stock -->
                        <input type="number" class="form-control" id="Editarstock" name="Editarstock" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="stock_min"><i class="fa fa-level-down"></i> Stock Mínimo:</label> <!-- Icono para la etiqueta de stock mínimo -->
                        <input type="number" class="form-control" id="Editarstock_min" name="Editarstock_min" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="unidad_medida"><i class="fa fa-balance-scale"></i> Unidad de Medida:</label> <!-- Icono para la etiqueta de unidad de medida -->
                        <select class="form-control" id="Editarunidad_medida" name="Editarunidad_medida" required>
                            <option value="UNIDAD">Unidad</option>                            
                            <option value="PAQUETE">Paquete</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_compra"><i class="fa fa-money"></i> Precio de Compra:</label> <!-- Icono para la etiqueta de precio de compra -->
                        <input type="number" class="form-control" id="Editarprecio_compra" name="Editarprecio_compra" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="precio_venta"><i class="fa fa-money"></i> Precio de Venta:</label> <!-- Icono para la etiqueta de precio de venta -->
                        <input type="number" class="form-control" id="Editarprecio_venta" name="Editarprecio_venta" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="precio_venta_min"><i class="fa fa-money"></i> Precio de Venta Mínimo:</label> <!-- Icono para la etiqueta de precio de venta mínimo -->
                        <input type="number" class="form-control" id="Editarprecio_venta_min" name="Editarprecio_venta_min" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_vencimiento"><i class="fa fa-calendar"></i> Fecha de Vencimiento:</label> <!-- Icono para la etiqueta de fecha de vencimiento -->
                        <input type="date" class="form-control" id="Editarfecha_vencimiento" name="Editarfecha_vencimiento" >
                    </div>
                    <div class="form-group">
                        <label for="almacen"><i class="fa fa-building"></i> Almacén:</label> <!-- Icono para la etiqueta de almacén -->
                        <select class="form-control" id="Editaralmacen" name="Editaralmacen">
                            <option value="1">Tienda</option>
                            <option value="2">Detalles</option>
                            <option value="3">Almacén</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button> <!-- Icono para el botón de Cerrar -->
                <button type="button" class="btn btn-primary" onclick="actualizarProducto();"><i class="fa fa-save"></i> Guardar</button> <!-- Icono para el botón de Guardar -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->




<script>
  var data = [];
  var filteredData = [];
   // Obtener el elemento de entrada de fecha por su ID
   var fechaVencimientoInput = document.getElementById('fecha_vencimiento');
   // Obtener el elemento de entrada de fecha por su ID
   var tipoUsuario = document.getElementById('tipoUsuario');

// Obtener la fecha actual
var fechaActual = new Date();

// Formatear la fecha actual en el formato YYYY-MM-DD (que es el formato esperado por el campo de entrada de fecha)
var formattedFechaActual = fechaActual.getFullYear() + '-' + ('0' + (fechaActual.getMonth() + 1)).slice(-2) + '-' + ('0' + fechaActual.getDate()).slice(-2);

// Establecer el valor del campo de entrada de fecha como la fecha actual
fechaVencimientoInput.value = formattedFechaActual;

  // Inicializar la tabla con los datos iniciales
  $(document).ready(function() {        
        $.ajax({
            url:'../../Controlador/co_productos.php',
            data:{cargarProductos:1},
            type: 'GET',
            dataType: 'json',
            success: function (datosObtenidos) {
                if (datosObtenidos && datosObtenidos.length > 0) {
                Array.prototype.push.apply(data, datosObtenidos);
                loadTableProductos(datosObtenidos,1);
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
            return item.descripcion.toLowerCase().indexOf(searchText) !== -1; // Filtrar los datos en base al nombre de usuario
        });
        loadTableProductos(filteredData,1); // Cargar la tabla con los datos filtrados*/
        
    });
        
    });
</script>