<?php
$modulo = $_POST['modulo'];
$opcion = $_POST['opcion'];
include("../../../config/conexion.php");
include("../../../config/variablesGlobales.php");

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
                    <input type="text" id="search-input" autocomplete="off" name="search-input" class="form-control pull-right" placeholder="Buscar Movimiento" onblur="buscarMovimiento()" onChange="buscarMovimiento()">                    
                    <input type="hidden" class="form-control" id="tipoUsuario" name="tipoUsuario" value="<?php echo $_SESSION["id_tipo_usuario"]; ?>">
                    <div class="input-group-btn">
                        <button id="filtrar" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
              </h3>
              <div class="box-tools">                
                <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-agregar-movimientos"><i class="fa fa-credit-card"></i> Agregar
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table id="data-table-movimientos" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>TIPO</th>                            
                            <th>FECHA</th>
                            <th>DESCRIPCION</th>                            
                            <th>MONTO</th>                                                        
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
<div class="modal fade" id="modal-agregar-movimientos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Registrar Movimiento</h4> <!-- Icono para el título del modal -->
            </div>
            <div class="modal-body">
                <form id="formAgregarMovimientos">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" >
                                <label for="tipo_movimiento"><i class="fa fa-balance-scale"></i> Tipo de Movimiento:</label> <!-- Icono para la etiqueta de unidad de medida -->
                                <select class="form-control" id="tipo_movimiento" name="tipo_movimiento" required>
                                    <option value="1">INGRESO</option>
                                    <option value="0">EGRESO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="movimientos_empresa"><i class="fa fa-building-o"></i> Empresa:</label> <!-- Icono para la etiqueta de descripción -->
                                <input type="text" class="form-control" id="movimientos_empresa" name="movimientos_empresa" autocomplete="off" required value="<?php echo $empresa; ?>" disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" >
                                <label for="movimientos_fecha"><i class="fa fa-calendar"></i> Fecha:</label> <!-- Icono para la etiqueta de fecha de vencimiento -->
                                <input type="date" class="form-control" id="movimientos_fecha" name="movimientos_fecha" disabled>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="movimientos_usuario"><i class="fa fa-user"></i> Usuario:</label> <!-- Icono para la etiqueta de movimientos_usuario -->
                                <input type="text" class="form-control" id="movimientos_usuario" name="movimientos_usuario" autocomplete="off" required value="<?php echo $_SESSION["usuario"]; ?>" disabled>
                            </div>   
                        </div>                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="movimientos_descripcion"><i class="glyphicon glyphicon-list-alt"></i> Descripción / Concepto:</label> <!-- Icono para la etiqueta de stock mínimo -->
                                <input type="text" class="form-control" id="movimientos_descripcion" name="movimientos_descripcion" autocomplete="off" required placeholder="Descripcion">
                            </div>  
                        </div>                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="movimientos_monto"><i class="fa fa-money"></i> Monto:</label> <!-- Icono para la etiqueta de stock mínimo -->
                                <input type="number" class="form-control" id="movimientos_monto" step="0.01" name="movimientos_monto" autocomplete="off" required value="0">
                            </div>    
                        </div>                       
                        <div class="col-md-12">                            
                            <div class="form-group">
                                <label for="movimientos_detalle"><i class="fa fa-sticky-note-o"></i> Detalle:</label> <!-- Icono para la etiqueta de stock mínimo -->
                                <textarea type="number" class="form-control" id="movimientos_detalle" name="movimientos_detalle" autocomplete="off" required></textarea>
                            </div>     
                        </div>                       
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> CERRAR</button> <!-- Icono para el botón de Cerrar -->
                <button type="button" class="btn btn-primary" onclick="crearMovimiento();"><i class="fa fa-save"></i> GUARDAR REGISTRO</button> <!-- Icono para el botón de Guardar -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
</div>

<!-- /.MODALS EDICION-->
<div class="modal fade" id="modal-editar-movimientos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-plus"></i>Editar Movimiento </h4> <!-- Icono para el título del modal -->
                <input type="hidden" class="form-control" id="editar_movimientos_id" name="editar_movimientos_id">
            </div>
            <div class="modal-body">
                <form id="formEditarCaja">
                    <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" >
                                    <label for="editar_tipo_movimiento"><i class="fa fa-balance-scale"></i> Tipo de Movimiento:</label> <!-- Icono para la etiqueta de unidad de medida -->
                                    <select class="form-control" id="editar_tipo_movimiento" name="editar_tipo_movimiento" required>
                                        <option value="1">INGRESO</option>
                                        <option value="0">EGRESO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                            <div class="form-group">
                                <label for="editar_movimientos_empresa"><i class="fa fa-building-o"></i> Empresa:</label> <!-- Icono para la etiqueta de descripción -->
                                <input type="text" class="form-control" id="editar_movimientos_empresa" name="editar_movimientos_empresa" autocomplete="off" required disabled>
                            </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" >
                                    <label for="editar_movimientos_fecha"><i class="fa fa-calendar"></i> Fecha:</label> <!-- Icono para la etiqueta de fecha de vencimiento -->
                                    <input type="date" class="form-control" id="editar_movimientos_fecha" name="editar_movimientos_fecha" disabled>
                                </div> 
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="editar_movimientos_usuario"><i class="fa fa-user"></i> Usuario:</label> <!-- Icono para la etiqueta de editar_movimientos_usuario -->
                                    <input type="text" class="form-control" id="editar_movimientos_usuario" name="editar_movimientos_usuario" autocomplete="off" required disabled>
                                </div>   
                            </div>                       
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editar_movimientos_descripcion"><i class="glyphicon glyphicon-list-alt"></i> Descripción / Concepto:</label> <!-- Icono para la etiqueta de stock mínimo -->
                                    <input type="text" class="form-control" id="editar_movimientos_descripcion" name="editar_movimientos_descripcion" autocomplete="off" required>
                                </div>  
                            </div>                       
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="editar_movimientos_monto"><i class="fa fa-money"></i> Monto:</label> <!-- Icono para la etiqueta de stock mínimo -->
                                    <input type="number" class="form-control" id="editar_movimientos_monto" step="0.01" name="editar_movimientos_monto" autocomplete="off" required value="0">
                                </div>    
                            </div>                       
                            <div class="col-md-12">                            
                                <div class="form-group">
                                    <label for="editar_movimientos_detalle"><i class="fa fa-sticky-note-o"></i> Detalle:</label> <!-- Icono para la etiqueta de stock mínimo -->
                                    <textarea type="number" class="form-control" id="editar_movimientos_detalle" name="editar_movimientos_detalle" autocomplete="off" required></textarea>
                                </div>     
                            </div> 
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> CERRAR</button> <!-- Icono para el botón de Cerrar -->
                <button type="button" class="btn btn-success" onclick="actualizarMovimientos();"><i class="fa fa-save"></i> ACTUALIZAR REGISTRO</button> <!-- Icono para el botón de Guardar -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



<script>
var fechaVencimientoInput = document.getElementById('movimientos_fecha');
var fechaActual = new Date();
var formattedFechaActual = fechaActual.getFullYear() + '-' + ('0' + (fechaActual.getMonth() + 1)).slice(-2) + '-' + ('0' + fechaActual.getDate()).slice(-2);
fechaVencimientoInput.value = formattedFechaActual;

/*
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
*/
$(document).ready(function() {
    $.ajax({          
      type: 'GET',      
      url: "../../Controlador/co_comprobantes.php",
      data: { correlativoCaja:1},
      dataType: 'json',
      success: function(data) {
        if (data.idCaja == null)
        {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, registre una CAJA.'
            });
            menu_principal('caja/listado_cajas_chicas.php','VENTAS','CAJA CHICA');
        }
        else
        {
            datosComprobante.idCaja = data.idCaja;
        }                      
      },
      error: function(error) {
        console.log('Error en la llamada AJAX:', error);
      }
    });
    
    var tabla = $('#data-table-movimientos').DataTable({
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
                d.cargarMovimientos = 1; // Marca para indicar que se cargan productos
                d.searchValue = $('#search-input').val().trim(); // Valor de búsqueda
            },
            "dataSrc": ""
        },
        "columns": [
            { "data": "id" },
            {
                "data": "tipo",
                "render": function(data, type, row, meta) {
                    if (row.tipo == 1) 
                    {
                       return '<span class="label label-success">INGRESO</span>';
                    } 
                    else if (row.tipo == 0) 
                    {
                       return '<span class="label label-danger">EGRESO</span>';
                    }
                }
            },
            { "data": "fecha" },
            { "data": "descripcion" },
            {
                "data": "monto",
                "render": function(data, type, row, meta) 
                {     
                    return parseFloat(row.monto).toFixed(2);              
                }
            },           
            { 
            "data": null,
            "render": function (data, type, row, meta) 
            {
                let buttons = '';
                buttons += `<button onclick="editarMovimiento(${row.id})" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></button> `;                
                buttons += `<button onclick="eliminarMovimiento(${row.id})" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button> `;                
                /*if (row.estado == 1) 
                {
                    buttons += `<button onclick="cerrarCaja(${row.id})" class="btn btn-xs btn-info"><i class="fa fa-lock"></i></button> `;
                } */              
                return buttons;
            }
            }
        ]
    });

    // Aplicar filtro al hacer clic en el botón "Filtrar"
    $('#filtrar').on('click', function() {
        tabla.ajax.reload();
    });
    
});
/*function editarMovimiento(id)
{
    var tabla = $('#data-table-movimientos').DataTable();        
    $('#modal-editar-movimientos').modal('show');
    /*alert(id);*/
    
/*}*/
function actualizarMovimientos()
{    
    var id= $('#editar_movimientos_id').val();    
    var descripcion = $('#editar_movimientos_descripcion').val(); 
    var monto = $('#editar_movimientos_monto').val();
    var detalle = $('#editar_movimientos_detalle').val();    
    var tipo_movimiento = $('#editar_tipo_movimiento option:selected').text(); 
    var tabla = $('#data-table-movimientos').DataTable();
    

    Swal.fire({
          icon: 'warning',
          title: 'Necesitamos tu Confirmación',
          html: '<div style="text-align: center;">' +
              '<div style="font-weight: bold;">Deseas actualizar el siguiente movimiento</div>' +                                  
              '<div class="resultados">Movimiento: ' + tipo_movimiento + '</div>' +            
              '<div class="resultados">Descripcion: ' + descripcion + '</div>' +  
              '<div class="resultados">Monto: ' + monto + '</div>' +            
              '<div class="resultados">Detalle: ' + detalle + '</div>' +            
              '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
              '</div>',
          confirmButtonText: 'Si, Actualizar Usuario',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#28a745', // Color verde
          cancelButtonColor: '#dc3545' // Color rojo
      }).then((result) => {
          if (result.isConfirmed) 
          { 
                            $.ajax({
                                  url:'../../Controlador/co_caja.php',
                                  data:{updateMovimiento:1,id:id,descripcion:descripcion,monto:monto,detalle:detalle},
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
                                          title: 'ACTUALIZADO CON EXITO!',
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
                                        $('#modal-editar-movimientos').modal('toggle');                                   
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
function eliminarMovimiento(id)
{
    var tabla = $('#data-table-movimientos').DataTable();

    Swal.fire({
          icon: 'warning',
          title: 'Necesitamos tu Confirmación',
          html: '<div style="text-align: center;">' +
              '<div style="font-weight: bold;">Deseas eliminar el siguiente movimiento</div>' +            
              '<div class="resultados">Movimiento: ' + id + '</div>' +            
              '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
              '</div>',
          confirmButtonText: 'Si, Eliminar Movimiento',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#28a745', // Color verde
          cancelButtonColor: '#dc3545' // Color rojo
      }).then((result) => {
          if (result.isConfirmed) 
          { 
                            $.ajax({
                                  url:'../../Controlador/co_caja.php',
                                  data:{deleteMovimiento:1,id:id},
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
                                          title: 'ELIMINADO CON EXITO!',
                                          html: '<div style="background-color: #e8f5e9; border-color: #4caf50; color: #43a047; padding: 10px; margin-top: 10px;">' +
                                              '<p style="margin: 0;">MENSAJE DE SISTEMA: '+ datos.success + '</p>' +
                                              '</div>',
                                          showCancelButton: false,     
                                          showConfirmButton : true,
                                          allowOutsideClick: true, // No permite clic fuera de la ventana
                                          allowEscapeKey: true,
                                          backdrop: 'static' // Evita el cierre haciendo clic fuera de la ventana
                                        });                                      
                                        // Actualizar la tabla de venta
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
function editarMovimiento(id) {
    var tabla = $('#data-table-movimientos').DataTable();    
    // Hacer una solicitud AJAX para obtener los datos del movimiento seleccionado
    $.ajax({
        url: '../../Controlador/co_caja.php',
        data: {obtenerMovimiento: 1, id: id },
        dataType: 'json',
        type: 'POST',
        success: function(datos) {
            if (datos.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: datos.error,
                    confirmButtonText: 'OK'
                });
            } else {
                // Llenar los campos del modal con los datos obtenidos


                var fechaVencimientoInput = datos[0].fecha;
                var fechaActual = new Date();
                var formattedFechaUpdate = fechaActual.getFullYear() + '-' + ('0' + (fechaActual.getMonth() + 1)).slice(-2) + '-' + ('0' + fechaActual.getDate()).slice(-2);                


                $('#editar_movimientos_id').val(datos[0].id);
                $('#editar_movimientos_empresa').val(datos[0].empresa);
                $('#editar_movimientos_usuario').val(datos[0].u_username);
                $('#editar_movimientos_monto').val(datos[0].monto);
                $('#editar_movimientos_tipo').val(datos[0].tipo);
                $('#editar_movimientos_fecha').val(formattedFechaUpdate);                
                $('#editar_movimientos_descripcion').val(datos[0].descripcion);                
                $('#editar_movimientos_detalle').val(datos[0].detalle);
                // Actualizar y bloquear el campo del tipo de movimiento
                $('#editar_tipo_movimiento').val(datos[0].tipo).prop('disabled', true);                
                // Mostrar el modal
                $('#modal-editar-movimientos').modal('show');
            }
        },
        error: function(xhr, status, error) {
            var err = xhr.responseText + status + error;
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al obtener los datos del movimiento.',
                confirmButtonText: 'OK'
            });
            console.error(err);
        }
    });
}
function buscarMovimiento() {
    var tabla = $('#data-table-productos-inventario').DataTable();
    tabla.ajax.reload();
}
//CAJAS
function crearMovimiento()
{  
  var tabla = $('#data-table-movimientos').DataTable();
  var tipo_movimiento = $('#tipo_movimiento option:selected').text();
  var tipo = $('#tipo_movimiento').val();
  var empresa= $('#movimientos_empresa').val();
  var usuario = $('#movimientos_usuario').val();
  var fecha = $('#movimientos_fecha').val();
  var descripcion = $('#movimientos_descripcion').val();  
  var monto = $('#movimientos_monto').val();  
  var detalle = $('#movimientos_detalle').val();  
  

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
   if (monto.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El monto no puede estar vacio',
            confirmButtonText: 'OK'
        });
        return; // Detener la ejecución si no hay productos
    }

   

     Swal.fire({
        icon: 'warning',
        title: 'Necesitamos tu Confirmación',
        html: '<div style="text-align: center;">' +
            '<div style="font-weight: bold;">Se creará un MOVIMIENTO con los siguientes datos:</div>' +     
            '<div class="resultados">Tipo: '+tipo_movimiento+'</div>' +       
            '<div class="resultados">Empresa: '+empresa+'</div>' +
            '<div class="resultados">Usuario: '+usuario+'</div>' +         
            '<div class="resultados">Monto: '+monto+'</div>' +                        
            '<div class="resultados">Fecha: '+fecha+'</div>' +
            '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
            '</div>',
        confirmButtonText: 'Si, Crear Movimiento',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745', // Color verde
        cancelButtonColor: '#dc3545' // Color rojo
    }).then((result) => {
        if (result.isConfirmed) 
        { 
                          $.ajax({
                                url:'../../Controlador/co_caja.php',
                                data:{crearMovimiento:1,tipo:tipo,tipo_movimiento:tipo_movimiento,empresa:empresa,usuario:usuario,fecha:fecha,descripcion:descripcion,monto:monto,detalle:detalle},
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
                                            '<span style="font-size: 18px;margin: 0;">CODIGO: '+ datos.id_movimiento + '</span>' +
                                            '</div>',
                                        showCancelButton: false,     
                                        showConfirmButton : true,
                                        allowOutsideClick: true, // No permite clic fuera de la ventana
                                        allowEscapeKey: true,
                                        backdrop: 'static' // Evita el cierre haciendo clic fuera de la ventana
                                      });
                                      tabla.ajax.reload();
                                      $('#modal-agregar-movimientos').modal('toggle'); 
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
