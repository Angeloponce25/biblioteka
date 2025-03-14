<?php
$modulo = $_POST['modulo'];
$opcion = $_POST['opcion'];
include("../../../config/conexion.php");
include("../../../config/variablesGlobales.php");
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
                    <input type="text" id="search-input" autocomplete="off" name="search-input" class="form-control pull-right" placeholder="Buscar Caja" onblur="buscarProducto()" onChange="buscarProducto()">                    
                    <input type="hidden" class="form-control" id="tipoUsuario" name="tipoUsuario" value="<?php echo $_SESSION["id_tipo_usuario"]; ?>">
                    <div class="input-group-btn">
                        <button id="filtrar" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
              </h3>
              <div class="box-tools">                
                <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-agregar-caja"><i class="fa fa-credit-card"></i> Agregar
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table id="data-table-caja" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nº Caja</th>
                            <th>FECHA APERTURA</th>
                            <th>FECHA CIERRE</th>
                            <th>MONTO APERTURA</th>
                            <th>MONTO CIERRE</th>
                            <th>ESTADO</th>
                            <th>ACCIONES</th>
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
<div class="modal fade" id="modal-agregar-caja">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-plus"></i>Registrar Nueva Caja </h4> <!-- Icono para el título del modal -->
            </div>
            <div class="modal-body">
                <form id="formAgregarCaja">
                    <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="caja_empresa"><i class="fa fa-pencil"></i> Empresa:</label> <!-- Icono para la etiqueta de descripción -->
                                        <input type="text" class="form-control" id="caja_empresa" name="caja_empresa" autocomplete="off" required value="<?php echo $empresa; ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" >
                                        <label for="caja_usuario"><i class="fa fa-cubes"></i> Usuario:</label> <!-- Icono para la etiqueta de caja_usuario -->
                                        <input type="text" class="form-control" id="caja_usuario" name="caja_usuario" autocomplete="off" required value="<?php echo $_SESSION["usuario"]; ?>" disabled>
                                    </div> 
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                            <label for="caja_fecha_pago"><i class="fa fa-calendar"></i> Fecha de Pago:</label> <!-- Icono para la etiqueta de fecha de vencimiento -->
                                            <input type="date" class="form-control" id="caja_fecha_pago" name="caja_fecha_pago" disabled>
                                    </div>    
                                </div>                       
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="caja_tipo_pago"><i class="fa fa-balance-scale"></i> Tipo De Pago:</label> <!-- Icono para la etiqueta de unidad de medida -->
                                        <select class="form-control" id="caja_tipo_pago" name="caja_tipo_pago" required disabled>
                                            <option value="EFECTIVO">EFECTIVO</option>
                                        </select>
                                    </div>  
                                </div>                       
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="caja_saldo_inicial"><i class="fa fa-level-down"></i> Saldo Inicial:</label> <!-- Icono para la etiqueta de stock mínimo -->
                                        <input type="number" class="form-control" id="caja_saldo_inicial" step="0.01" name="caja_saldo_inicial" autocomplete="off" required value="1.00">
                                    </div>      
                                </div> 
                        </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> CERRAR</button> <!-- Icono para el botón de Cerrar -->
                <button type="button" class="btn btn-primary" onclick="crearCaja();"><i class="fa fa-save"></i> CREAR CAJA</button> <!-- Icono para el botón de Guardar -->
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
    <div class="modal fade" id="modal-editar-caja">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> </h4> <!-- Icono para el título del modal -->
            </div>
            <div class="modal-body">
                <form id="formEditarCaja">
                    <div class="form-group">
                        <label for="editar_caja_empresa"><i class="fa fa-pencil"></i> Empresa:</label> <!-- Icono para la etiqueta de descripción -->
                        <input type="text" class="form-control" id="editar_caja_empresa" name="editar_caja_empresa" autocomplete="off" required value="<?php echo $empresa; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="editar_caja_usuario"><i class="fa fa-cubes"></i> Usuario:</label> <!-- Icono para la etiqueta de editar_caja_usuario -->
                        <input type="text" class="form-control" id="editar_caja_usuario" name="editar_caja_usuario" autocomplete="off" required value="<?php echo $_SESSION["usuario"]; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="editar_caja_saldo_inicial"><i class="fa fa-level-down"></i> Saldo Inicial:</label> <!-- Icono para la etiqueta de stock mínimo -->
                        <input type="number" class="form-control" id="editar_caja_saldo_inicial" step="0.01" name="editar_caja_saldo_inicial" autocomplete="off" required value="1">
                    </div>
                    <div class="form-group" >
                        <label for="editar_caja_tipo_pago"><i class="fa fa-balance-scale"></i> Tipo De Pago:</label> <!-- Icono para la etiqueta de unidad de medida -->
                        <select class="form-control" id="editar_caja_tipo_pago" name="editar_caja_tipo_pago" required disabled>
                            <option value="EFECTIVO">EFECTIVO</option>
                        </select>
                    </div>
                    <div class="form-group" >
                        <label for="editar_caja_fecha_pago"><i class="fa fa-calendar"></i> Fecha de Pago:</label> <!-- Icono para la etiqueta de fecha de vencimiento -->
                        <input type="date" class="form-control" id="editar_caja_fecha_pago" name="editar_caja_fecha_pago" disabled>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button> <!-- Icono para el botón de Cerrar -->
                <button type="button" class="btn btn-primary" onclick="crearCaja();"><i class="fa fa-save"></i> Abrir Caja(Enter)</button> <!-- Icono para el botón de Guardar -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



<script>
var fechaVencimientoInput = document.getElementById('caja_fecha_pago');
var fechaActual = new Date();
var formattedFechaActual = fechaActual.getFullYear() + '-' + ('0' + (fechaActual.getMonth() + 1)).slice(-2) + '-' + ('0' + fechaActual.getDate()).slice(-2);
fechaVencimientoInput.value = formattedFechaActual;

document.addEventListener('keydown', function(event) {
        var modal = document.getElementById("modal-agregar-caja");
        if (modal) {
            var modalVisible = modal.classList.contains('in');
            if (event.key === 'Enter' && modalVisible) {
                event.preventDefault(); // Evita que el evento de presionar Enter se propague
                crearCaja();
            }
        }
        var modalEditar = document.getElementById("modal-editar-caja");
        if (modalEditar) {
            var modalEditarVisible = modalEditar.classList.contains('in');
            if (event.key === 'Enter' && modalEditarVisible) {
                event.preventDefault(); // Evita que el evento de presionar Enter se propague
                actualizarCaja();
            }
        }
    });

$(document).ready(function() {    
    var isModalTriggered = false; // Flag para controlar el loop 
    <?php if($_SESSION["id_tipo_usuario"] == '1') {?>   
    $('#modal-agregar-caja').on('show.bs.modal', function (e) {
        if (!isModalTriggered) {
            e.preventDefault();
            isModalTriggered = true;
            
            Swal.fire({
                icon: 'warning',
                title: 'Cargar Monto',
                html: '<div style="text-align: center;">' +
                    '<div style="color:#4caf50!important;font-size: 18px;font-weight: bold;">¿Desea iniciar con el dinero de la ultima caja Cerrada?</div>' +                        
                    '</div>',
                confirmButtonText: 'Si, Cargar Monto',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonColor: '#28a745', // Color verde
                cancelButtonColor: '#dc3545' // Color rojo
            }).then((result) => {
                var saldoInicialInput = document.getElementById('caja_saldo_inicial');
                if (result.isConfirmed) {
                    $.ajax({
                        url:'../../Controlador/co_caja.php',
                        data:{cargarCantidadUltimaCaja:1},
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            saldoInicialInput.value = data.monto;
                            saldoInicialInput.disabled = true;
                        },
                        error: function (xhr, status, error) {
                            console.error('Error en la solicitud AJAX:', error);
                        }
                    });                    
                } 
                else 
                {
                    saldoInicialInput.value = 1.00;
                    saldoInicialInput.disabled = false;
                }
                $('#modal-agregar-caja').modal('show');
                $('#caja_saldo_inicial').focus();
            });
        } else {
            isModalTriggered = false;
        }
    });
    <?php } ?>
    var tabla = $('#data-table-caja').DataTable({
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
            "url": '../../Controlador/co_caja.php',
            "method": 'POST', // Cambiamos a método POST para enviar los datos
            "data": function (d) {
                // Configuramos los parámetros para paginación y búsqueda remota
                d.cargarCajas = 1; // Marca para indicar que se cargan productos
                d.searchValue = $('#search-input').val().trim(); // Valor de búsqueda
            },
            "dataSrc": ""
        },
        "columns": [
            { "data": "id" },
            { "data": "fecha_apertura" },
            { "data": "fecha_cierre" },
            { "data": "monto_apertura" },
            { "data": "monto_cierre" },
            {
                "data": "estado",
                "render": function(data, type, row, meta) {
                    if (row.estado == 1) 
                    {
                       return '<span class="label label-success">Aperturada</span>';
                    } 
                    else if (row.estado == 2) 
                    {
                       return '<span class="label label-danger">Cerrada</span>';
                    }
                }
            },
            { 
            "data": null,
            "render": function (data, type, row, meta) {
                let buttons = '';
                /*buttons += `<button onclick="editarCaja(${row.id})" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></button> `;*/
                if (row.estado == 1) 
                {
                    buttons += `<button onclick="cerrarCaja(${row.id})" class="btn btn-xs btn-info"><i class="fa fa-lock"></i></button> `;
                }
                if (row.estado == 2) 
                {
                    buttons += `<button onclick="reporteCajaUnitario(${row.id})" class="btn btn-xs btn-warning"><i class="fa fa-file-pdf-o"></i></button> `;
                }                
                return buttons;
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
function editarCaja(id)
{
    var tabla = $('#data-table-caja').DataTable();
    alert(id);
    
}
function reporteCajaUnitario(id)
{    
    window.open('../../Controlador/co_caja.php?reporteCajaUnitario&id_caja='+id,'_blank');        
}
function cerrarCaja(id)
{    
    var tabla = $('#data-table-caja').DataTable();
    Swal.fire({
        icon: 'warning',
        title: 'Cerrar caja chica',
        html: '<div style="text-align: center;">' +
            '<div style="color:#4caf50!important;font-size: 18px;font-weight: bold;">¿Está seguro de cerrar la caja '+id+'?</div>' +                        
            '</div>',
        confirmButtonText: 'Cerrar Caja',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745', // Color verde
        cancelButtonColor: '#dc3545' // Color rojo
    }).then((result) => {
        if (result.isConfirmed) 
        { 
                          $.ajax({
                                url:'../../Controlador/co_caja.php',
                                data:{cerrarCaja:1,id:id},
                                dataType:'json',
                                type:'POST',
                                success: function (datos){          
                                    if(datos.error)                              
                                    {
                                      Swal.fire({
                                      icon: 'error',
                                      title: 'Error',
                                      text: datos.error,
                                      confirmButtonText: 'OK'
                                      });                                      
                                    }
                                    else
                                    {                                      
                                      Swal.fire({
                                        icon: 'success',
                                        title: 'CAJA CERRADA CON EXITO!',
                                        html: '<div style="background-color: #e8f5e9; border-color: #4caf50; color: #43a047; padding: 10px; margin-top: 10px;">' +
                                            '<p style="margin: 0;">MENSAJE DE SISTEMA: '+ datos.success + '</p>' +                                            
                                            '</div>',
                                        showCancelButton: false,     
                                        showConfirmButton : true,
                                        allowOutsideClick: true, // No permite clic fuera de la ventana
                                        allowEscapeKey: true,
                                        backdrop: 'static' // Evita el cierre haciendo clic fuera de la ventana
                                      });
                                      tabla.ajax.reload();
                                    }
                                                                       
                                    
                                },
                                error:function(xhr, status, error){
                                    var err=(xhr.responseText+status+error);
                                    Swal.fire(
                                    'Oooops',
                                    'Ocurrio un error',
                                    'warning'
                                    )
                                    console(err);
                                }                                                            
                            });
        } 
    });
}
function buscarProducto() {
    var tabla = $('#data-table-productos-inventario').DataTable();
    tabla.ajax.reload();
}
//CAJAS
function crearCaja()
{
  var producto = [];
  var tabla = $('#data-table-caja').DataTable();
  var empresa= $('#caja_empresa').val();
  var usuario = $('#caja_usuario').val();
  var saldo_inicial = $('#caja_saldo_inicial').val();
  var tipo_pago = $('#caja_tipo_pago option:selected').text(); 
  var fecha_pago = $('#caja_fecha_pago').val();

   // Verificar si hay al menos un producto en la venta

   if (empresa.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de la empresa no puede estar vacio',
            confirmButtonText: 'OK'
        });
        return; // Detener la ejecución si no hay productos
    }
   if (saldo_inicial.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El saldo inicial no puede estar vacio',
            confirmButtonText: 'OK'
        });
        return; // Detener la ejecución si no hay productos
    }

   

     Swal.fire({
        icon: 'warning',
        title: 'Necesitamos tu Confirmación',
        html: '<div style="text-align: center;">' +
            '<div style="font-weight: bold;">Se creará una CAJA con los siguientes datos:</div>' +            
            '<div class="resultados">Empresa: '+empresa+'</div>' +
            '<div class="resultados">Usuario: '+usuario+'</div>' +         
            '<div class="resultados">Saldo Inicial: '+saldo_inicial+'</div>' +            
            '<div class="resultados">Tipo de Pago: '+tipo_pago+'</div>' +
            '<div class="resultados">Fecha Pago: '+fecha_pago+'</div>' +
            '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
            '</div>',
        confirmButtonText: 'Si, Abrir Caja',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745', // Color verde
        cancelButtonColor: '#dc3545' // Color rojo
    }).then((result) => {
        if (result.isConfirmed) 
        { 
                          $.ajax({
                                url:'../../Controlador/co_caja.php',
                                data:{crearCaja:1,empresa:empresa,usuario:usuario,saldo_inicial:saldo_inicial,tipo_pago:tipo_pago,fecha_pago:fecha_pago},
                                dataType:'json',
                                type:'POST',
                                success: function (datos){          
                                    if(datos.error)                              
                                    {
                                      Swal.fire({
                                      icon: 'error',
                                      title: 'Error',
                                      text: datos.error,
                                      confirmButtonText: 'OK'
                                      });                                      
                                    }
                                    else
                                    {                                      
                                      Swal.fire({
                                        icon: 'success',
                                        title: 'REGISTRO REALIZADA CON EXITO!',
                                        html: '<div style="background-color: #e8f5e9; border-color: #4caf50; color: #43a047; padding: 10px; margin-top: 10px;">' +
                                            '<p style="margin: 0;">MENSAJE DE SISTEMA: '+ datos.success + '</p>' +
                                            '<span style="font-size: 18px;margin: 0;">CODIGO: '+ datos.id_caja + '</span>' +
                                            '</div>',
                                        showCancelButton: false,     
                                        showConfirmButton : true,
                                        allowOutsideClick: true, // No permite clic fuera de la ventana
                                        allowEscapeKey: true,
                                        backdrop: 'static' // Evita el cierre haciendo clic fuera de la ventana
                                      });
                                      tabla.ajax.reload();
                                    }
                                                                       
                                    
                                },
                                error:function(xhr, status, error){
                                    var err=(xhr.responseText+status+error);
                                    Swal.fire(
                                    'Oooops',
                                    'Ocurrio un error',
                                    'warning'
                                    )
                                    console(err);
                                }                                                            
                            });
        } 
    });
}
</script>
