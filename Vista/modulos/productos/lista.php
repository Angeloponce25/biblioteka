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
              <h3 class="box-title">
              <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" id="search-input" autocomplete="off" name="search-input" class="form-control pull-right" placeholder="Buscar producto" onblur="buscarProducto()" onChange="buscarProducto()">                    
                    <input type="hidden" class="form-control" id="tipoUsuario" name="tipoUsuario" value="<?php echo $_SESSION["id_tipo_usuario"]; ?>">
                    <div class="input-group-btn">
                        <button id="filtrar" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
              </h3>
              <div class="box-tools">                
                <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-agregar-producto"><i class="fa fa-credit-card"></i> Agregar
                </button>
                <span></span>                
                <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-cargar-productos"><i class="fa fa-upload"></i> Subir Masivamente
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
                            <th>P. COMPRA</th>
                            <th>P. VENTA</th>
                            <th>P. MIN</th>
                            <th>ALMACEN</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Los datos se cargarán aquí dinámicamente -->
                    </tbody>
                </table>
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
                        <input type="number" class="form-control" id="stock" name="stock" autocomplete="off" required value="1">
                    </div>
                    <div class="form-group">
                        <label for="stock_min"><i class="fa fa-level-down"></i> Stock Mínimo:</label> <!-- Icono para la etiqueta de stock mínimo -->
                        <input type="number" class="form-control" id="stock_min" name="stock_min" autocomplete="off" required value="1">
                    </div>
                    <div class="form-group" style="display:none;">
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
                    <div class="form-group" style="display:none;">
                        <label for="fecha_vencimiento"><i class="fa fa-calendar"></i> Fecha de Vencimiento:</label> <!-- Icono para la etiqueta de fecha de vencimiento -->
                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" >
                    </div>
                    <div class="form-group">
                        <label for="almacen"><i class="fa fa-building"></i> Almacén:</label> <!-- Icono para la etiqueta de almacén -->
                        <select class="form-control" id="almacen" name="almacen">
                            <option value="1">Mollendo - Chumpitaz</option>
                            <option value="2">Mollendo - Linea Maritima</option>
                            <option value="3">Lima - Chumpitaz</option>
                            <option value="4">Lima - Linea Maritima</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button> <!-- Icono para el botón de Cerrar -->
                <button type="button" class="btn btn-primary" onclick="crearProducto();"><i class="fa fa-save"></i> Guardar(Enter)</button> <!-- Icono para el botón de Guardar -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.MODALS -->
<div id="modal-cargar-productos" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cargar Productos desde Archivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-cargar-productos" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fileProductos">Seleccione el archivo (CSV o Excel)</label>
                        <input type="file" class="form-control" id="fileProductos" name="fileProductos" accept=".csv, .xlsx, .xls">
                    </div>
                    <button type="submit" class="btn btn-primary">Cargar Productos</button>
                </form>
            </div>
        </div>
    </div>
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
                    <div class="form-group" style="display:none;">
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
                    <div class="form-group" style="display:none;">
                        <label for="fecha_vencimiento"><i class="fa fa-calendar"></i> Fecha de Vencimiento:</label> <!-- Icono para la etiqueta de fecha de vencimiento -->
                        <input type="date" class="form-control" id="Editarfecha_vencimiento" name="Editarfecha_vencimiento" >
                    </div>
                    <div class="form-group">
                        <label for="almacen"><i class="fa fa-building"></i> Almacén:</label> <!-- Icono para la etiqueta de almacén -->
                        <select class="form-control" id="Editaralmacen" name="Editaralmacen">
                            <option value="1">Mollendo - Chumpitaz</option>
                            <option value="2">Mollendo - Linea Maritima</option>
                            <option value="3">Lima - Chumpitaz</option>
                            <option value="4">Lima - Linea Maritima</option>
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
     // Obtener el elemento de entrada de fecha por su ID
var fechaVencimientoInput = document.getElementById('fecha_vencimiento');
   // Obtener la fecha actual
var fechaActual = new Date();

// Formatear la fecha actual en el formato YYYY-MM-DD (que es el formato esperado por el campo de entrada de fecha)
var formattedFechaActual = fechaActual.getFullYear() + '-' + ('0' + (fechaActual.getMonth() + 1)).slice(-2) + '-' + ('0' + fechaActual.getDate()).slice(-2);

// Establecer el valor del campo de entrada de fecha como la fecha actual
fechaVencimientoInput.value = formattedFechaActual;

document.addEventListener('keydown', function(event) {
        var modal = document.getElementById("modal-agregar-producto");
        if (modal) {
            var modalVisible = modal.classList.contains('in');
            if (event.key === 'Enter' && modalVisible) {
                event.preventDefault(); // Evita que el evento de presionar Enter se propague
                crearProducto();
            }
        }
        var modalEditar = document.getElementById("modal-editar-producto");
        if (modalEditar) {
            var modalEditarVisible = modalEditar.classList.contains('in');
            if (event.key === 'Enter' && modalEditarVisible) {
                event.preventDefault(); // Evita que el evento de presionar Enter se propague
                actualizarProducto();
            }
        }
    });

$(document).ready(function() {
    var tabla = $('#data-table-productos-inventario').DataTable({
        select: true,
        "searching": false, // Deshabilita la funcionalidad de búsqueda del lado del cliente
        "processing": true, // Muestra el indicador de procesamiento
        "pageLength": 50,
        "order": [],
        "language": {
            "processing": "Procesando...",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "emptyTable": "Ningún dato disponible en esta tabla",
            "info": "Mostrando _START_ al _END_ de _TOTAL_ datos",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "search": "Buscar:",
            "infoThousands": ",",
            "loadingRecords": "Cargando...",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "ajax": {
            "url": '../../Controlador/co_productos.php',
            "method": 'POST', // Cambiamos a método POST para enviar los datos
            "data": function (d) {
                // Configuramos los parámetros para paginación y búsqueda remota
                d.cargarProductos = 1; // Marca para indicar que se cargan productos
                d.searchValue = $('#search-input').val().trim(); // Valor de búsqueda
            },
            "dataSrc": ""
        },
        "columns": [
            { "data": "id_producto" },
            { "data": "descripcion" },
            { "data": "stock" },
            { "data": "stock_min" },
            { "data": "precio_compra" },
            { "data": "precio_venta" },
            { "data": "precio_venta_minimo" },
            {
                "data": "almacen",
                "render": function(data, type, row, meta) {
                    if (row.almacen == 1) 
                    {
                       return 'Mollendo - Chumpitaz';
                    } 
                    else if (row.almacen == 2) 
                    {
                       return 'Mollendo - Linea Maritima';
                    } 
                    else if (row.almacen == 2) 
                    {
                       return 'Lima - Chumpitaz';
                    } 
                    else if (row.almacen)
                    {
                       return 'Lima - Linea Maritima';
                    }
                }
            },
            { 
                // Columna de acción
                "data": null,
                "render": function (data, type, row, meta) {
                    // Botón o enlace para la acción
                    return '<button onclick="editarProducto(' + meta.row + ')" class="btn btn-primary"><i class="fa fa-pencil"></i></button><button onclick="eliminarProducto(' + meta.row + ')" class="btn btn-warning"><i class="fa fa-trash"></i></button>';
                }
            }
        ]
    });

    // Aplicar filtro al hacer clic en el botón "Filtrar"
    $('#filtrar').on('click', function() {
        tabla.ajax.reload();
    });

    $('#form-cargar-productos').on('submit', function(e) {
    e.preventDefault();

    var fileInput = document.getElementById('fileProductos');
    var file = fileInput.files[0];
    var reader = new FileReader();

    reader.onload = function(e) {
        var base64File = e.target.result;

        // Combinar datos del archivo y del formulario
        var dataForm = new FormData();
        dataForm.append('uploadArchivoProducto', 1);
        dataForm.append('file', base64File);
        dataForm.append('fileName', file.name);
        dataForm.append('fileType', file.type);

        Swal.fire({
            title: 'Cargando Productos...',
            text: 'Por favor, espere.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '../../Controlador/co_productos.php',
            type: 'POST',
            data: dataForm,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {
                Swal.close();
                console.log('Respuesta del servidor:', data);
                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error,
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Productos Cargados',
                        text: data.success,
                        confirmButtonText: 'OK'
                    });
                    /*$('#data-table-productos-inventario').DataTable().ajax.reload();*/
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al cargar los productos.',
                    confirmButtonText: 'OK'
                });
            }
        });
    };

    reader.readAsDataURL(file);
});
    
});

function buscarProducto() {
    var tabla = $('#data-table-productos-inventario').DataTable();
    tabla.ajax.reload();
}
    </script>
