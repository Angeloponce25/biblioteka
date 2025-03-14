<?php
$modulo=$_POST['modulo'];
$opcion=$_POST['opcion'];
include("../../../config/conexion.php");
?>

<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/select2/dist/css/select2.min.css">


<section class="content-header">
      <h1>
        <?php echo $modulo; ?>
        <small><?php echo $opcion; ?></small>
      </h1>
</section>
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            VENTA #<span id="correlativo"></span>
            <div class="pull-right">                
                <button type="button" id="AgregarProducto" class="btn btn-success pull-right"><i class="fa fa-plus"></i> AGREGAR (F2)</button>
                <input type='hidden' id="txtNumFilasAo" name="txtNumFilasAo" value='0'>
            </div>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
      <form role="form">
        <div class="form-group">
            <div class="col-sm-3 invoice-col">            
                <label>TIPO DOCUMENTO</label>
                <div class="input-group input-group-sm" >   
                    <div class="input-group-btn">
                        <span class="btn btn-default"><i class="fa fa-list"></i></span>
                    </div>                 
                    <select id="tipoDoc" name="tipoDoc" class="form-control input-sm">
                            <option value="DNI">DNI</option>
                    </select> 
                    
                </div>                 
            </div>                   
            <!-- /.col -->
            <div class="col-sm-3 invoice-col">
                <label>N° DE DOCUMENTO</label>
                <div class="input-group input-group-sm" style="width: 250px;">   
                    <div class="input-group-btn">
                        <span class="btn btn-default" onclick="ConsultarTitular();"><i class="fa fa-search"></i></span>
                    </div>                 
                    <input type="text" class="form-control" id="txtndocumento" name="txtndocumento" autocomplete="off" placeholder="N° de Documento">
                    
                </div>                
            </div>
            <div class="col-sm-6 invoice-col">            
                <label>NOMBRE DEL CLIENTE</label>                
                <div class="input-group input-group-sm" >   
                    <div class="input-group-btn">
                        <span class="btn btn-default"><i class="fa fa-user"></i></span>
                    </div>                 
                    <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" >
                    
                </div>  
            </div>    
        </div> 
       </form>
        <!-- /.col -->
      </div>
      <!-- /.row -->
        <div class="row">
            <div class="col-xs-12">
                <br>    
                <br>    
                <br>    
                <br>    
            <center><h1>LISTA DE PRODUCTOS</h1></center>
            </div>
        </div>
      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
            <table id="data-table-productos-vender" class="table table-bordered">
              <thead>
                <tr>
                  <th>DESCRIPCION</th>
                  <th>UNIDAD MEDIDA</th>
                  <th style="width: 20%;">CANTIDAD</th>                  
                  <th>PRECIO</th>
                  <th>TOTAL</th>
                  <th>ACCION</th>
                </tr>
              </thead>
              <tbody id="data-table-productos-vender-body">
                    <!-- Los datos se cargarán aquí dinámicamente -->
              </tbody>
              </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-7">
        <p class="lead" style="font-size: 25px;"><i class="fa fa-credit-card"></i> FORMA DE PAGO</p>
        <div class="input-group input-group-sm" style="width: 40%">   
                    <div class="input-group-btn">
                        <span class="btn btn-default"><i class="fa fa-list"></i></span>
                    </div>                 
                    <select id="tpago" name="tpago" class="form-control input-sm">
                            <option value="1">EFECTIVO</option>
                            <option value="2">YAPE</option>                            
                            <option value="3">PLIN</option>                            
                    </select> 
                    
                </div>  
        </div>
        <!-- /.col -->
        <div class="col-xs-5">          

          <div class="table-responsive">
            <table class="table" style="font-size: 25px;">
              <tr>
                <th style="width:50%;border-top:0px">SUBTOTAL:</th>
                <td id="suma_subtotal" style="border-top:0px"></td>
              </tr>
              <tr>
                <th style="border-top:0px">IGV (18%):</th>
                <td id="suma_igv" style="border-top:0px"></td>
              </tr>
              <tr>
                <th style="border-top:0px">TOTAL:</th>
                <td id="suma_total" style="border-top:0px"></td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">          
          <center><button onclick="completarVenta()" class="btn btn-app"><i class="fa fa-save"></i> Guardar Comprobante (F4)</button></center>          
          
        </div>
      </div>


    </section>
    <!-- /.content -->
    <div class="clearfix"></div>



<script>
$(document).ready(function() {
  //NUEVO SISTEMA ANGELO
// Llamar a la función para actualizar los totales
actualizarTotales();

////
$('#AgregarProducto').click(function() {

// Deshabilitar el botón mientras se realiza la solicitud AJAX
$(this).prop('disabled', true);


var cantidad=parseInt($("#txtNumFilasAo").val());
c=cantidad+1;
$("#txtNumFilasAo").val(c);


        // Crear el nuevo TR con los TDs correspondientes para el input y el botón
        var nuevaFila = '<tr id="'+c+'">' +            
            '<td>' +
            '<select id="selectProducto'+c+'" class="form-control autocomplete-input" style="width: 100%;">' +
            '<option value="">Seleccionar</option>' + // Opción por defecto
            '</select>' +
            '</td>' + // Columna vacía para el producto
            '<td></td>' + // Columna vacía para el producto   
            '<td>' +
            '<div class="input-group input-group-sm">' +
            '<input type="number" class="form-control autocomplete-input" autocomplete="off" placeholder="Cantidad" disabled>' +
            '</div>' +
            '</td>' +
            '<td></td>' + // Columna vacía para el producto            	
            '<td></td>' + // Columna vacía para el precio
            '<td>' +
            '<button class="btn btn-danger btn-sm" onclick="quitarItem(\''+c+'\')"><i class="fa fa-trash"></i></button>' +
            '</td>' +
            '</tr>';

        // Agregar la nueva fila al cuerpo de la tabla
        $('#data-table-productos-vender-body').append(nuevaFila);

        // Enfocar el input recién agregado
        /*$('#data-table-productos-vender-body tr:last-child input:nth-child(2)').focus();
        $('#data-table-productos-vender-body tr:last-child select').select2('open');*/

        // Realizar la llamada Ajax para obtener los productos
        $.ajax({
            url: '../../Controlador/co_comprobantes.php', // Reemplaza 'ruta_a_tu_backend.php' con la ruta correcta a tu backend
            data: { ObtenerProductos: 1 },
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Limpiar el select
                var select = $('#selectProducto'+c);
                select.empty();

                // Agregar opción por defecto al select
                select.append('<option value="">Seleccionar o Escribir Producto - Codigo</option>');

                // Agregar opciones al select
                $.each(data, function(index, item) {
                    select.append('<option value="' + item.id_producto + '">' + item.id_producto + '-' + item.descripcion + '('+item.stock+')</option>');
                    // Almacenar datos adicionales del producto en atributos data del option
                    select.find('option[value="' + item.id_producto + '"]').data('stock', item.stock);
                    select.find('option[value="' + item.id_producto + '"]').data('precioVenta', item.precio_venta);
                    select.find('option[value="' + item.id_producto + '"]').data('unidadMedida', item.unidad_medida);
                });

                // Inicializar Select2
                select.select2();

                // Habilitar nuevamente el botón después de que se complete la solicitud AJAX
                $('#AgregarProducto').prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Habilitar nuevamente el botón en caso de error
                $('#AgregarProducto').prop('disabled', false);
            }
        });


    });

  
    // Al seleccionar un producto en el Select2
    $(document).on('change', 'select[class^="form-control autocomplete-input"]', function() {
        var c = $(this).attr('id').replace('selectProducto', ''); // Obtener el número de fila
        var data = $(this).find('option:selected').data(); // Obtener los datos adicionales del producto seleccionado
        

        // Actualizar las columnas correspondientes en la fila de la tabla
        $('#'+c+' td:nth-child(2)').text(data.unidadMedida); // Actualizar columna de stock
        $('#'+c+' td:nth-child(4)').text(data.precioVenta); // Actualizar columna de precio de venta        
        $('#'+c+' td:nth-child(3) input').prop('disabled', false);
        setTimeout(function() {
            $('#'+c+' td:nth-child(3) input').focus();
        }, 100); 
        // Restricción para el input de cantidad
        $('#'+c+' td:nth-child(3) input').on('input', function() {
            var cantidad = parseInt($(this).val()); // Obtener la cantidad ingresada
            var stock = parseInt(data.stock); // Obtener el stock del producto
            var precioVenta = parseFloat(data.precioVenta); // Obtener el precio de venta del producto

            // Verificar si la cantidad ingresada es menor o igual al stock disponible
            if (cantidad <= stock) {
                // Calcular el total multiplicando la cantidad por el precio de venta
                var total = cantidad * precioVenta;

                // Mostrar el total en el child(6)
                $(this).focus();                
                $('#'+c+' td:nth-child(5)').text(total.toFixed(2));
                
            } else {
                // Si la cantidad ingresada supera el stock, mostrar un mensaje de error o realizar la acción necesaria
                // Mostrar mensaje de error con SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La cantidad ingresada supera el stock disponible'
                });

                // Limpiar el valor del input
                $(this).val('');
                $(this).prop('disabled', false);                


                // Enfocar nuevamente en el input
                $(this).focus();
            }
        // Llamar a la función para actualizar los totales
        actualizarTotales();
        });

        // Bloquear el select2
    $(this).prop('disabled', true);

        
    });
    
    

    // FIN
  obtenerSiguienteNumero(); // Llamada inicial al cargar la página  
  document.addEventListener('keydown', function(event) {
    if (event.key === 'F4') {
        // Lógica que deseas ejecutar al presionar F12
        completarVenta();
    }
    });
    
});

// Variables para guardar los totales
var subtotal = 0;
var igv = 0;
var total = 0;


// Función para actualizar los totales
function actualizarTotales() {
    // Actualizar el subtotal sumando los valores en las columnas de total
    total = 0;
    $('#data-table-productos-vender-body td:nth-child(5)').each(function() {
        var valor = parseFloat($(this).text());
        if (!isNaN(valor)) {
            total += valor;
        }
    });
    
    // Calcular el IGV (18%)
    subtotal = total / 1.18;
    
    // Calcular el total sumando el subtotal y el IGV
    igv = total - subtotal;

    // Mostrar los totales en la tabla
    $('#suma_subtotal').text(subtotal.toFixed(2));
    $('#suma_igv').text(igv.toFixed(2));
    $('#suma_total').text(total.toFixed(2));

    return total.toFixed(2);

}


function quitarItem(tr,id)
{
    var objHijo = document.getElementById(tr);

    var idDetalleElement = document.getElementById("txtIdDetalle"+tr);    
    var objPadre = objHijo.parentNode;    
    objPadre.removeChild(objHijo);    
    actualizarTotales();
   
}
function ConsultarTitular()
{  
  var nro_documento = $('#txtndocumento').val();
  var tipoDoc = 1;
  $.ajax({          
          type: 'POST',
          url: "../../Controlador/co_comprobantes.php",
          data: { ConsultarDoc:1,tipoDoc:tipoDoc, nro_documento:nro_documento},
          dataType: 'json',
          success: function(data) {       
            if (tipoDoc == 1) //DNI
            {              
              $('#nombreCliente').val(data.nombres + ' ' + data.apellidoPaterno + ' ' + data.apellidoMaterno);
            }          

          },
          error: function(error) {
            console.log('Error en la llamada AJAX:', error);
          }
        });


}
function completarVenta()
{
    // Buscar todos los selects creados dinámicamente
    var productosSeleccionados = $('select[id^="selectProducto"]');
    var productosArray = []; // Array para almacenar los valores seleccionados
    // Variable para verificar si hay algún producto seleccionado
    var hayProductoSeleccionado = false;

    // Variable para verificar si todos los productos seleccionados tienen cantidad
var todosConCantidad = true;

// Mostrar cada select encontrado en la consola si tiene un valor
productosSeleccionados.each(function() {
    var selectId = $(this).attr('id');
    var selectValue = $(this).val();
    var rowId = selectId.replace('selectProducto', '');
    var cantidadInput = $('#' + rowId + ' td:nth-child(3) input');
    var cantidad = cantidadInput.val();
    var precioVenta = parseFloat($('#' + rowId + ' td:nth-child(4)').text());
    var total = parseFloat($('#' + rowId + ' td:nth-child(5)').text());
    
    // Verificar si el select tiene un valor y si la cantidad no está vacía
    if (selectValue && cantidad.trim() !== "") {
        productosArray.push({
            id_producto: selectValue,
            cantidad: cantidad,
            precio_venta: precioVenta.toFixed(2),
            total: total.toFixed(2)
        });
    } else {
        // Si algún producto no tiene cantidad, marcar que no todos tienen cantidad
        todosConCantidad = false;
    }
});

// Si no hay productos seleccionados o no todos tienen cantidad, mostrar un mensaje de error y detener la ejecución
if (productosArray.length === 0 || !todosConCantidad) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Debes seleccionar productos y asignar una cantidad a cada uno...',
        confirmButtonText: 'OK'
    });
    return; // Detener la ejecución si no hay productos seleccionados o no todos tienen cantidad
}

    var tipo_comprobante= 6; //NOTA DE VENTA
    var ndocumento = $('#txtndocumento').val() || '00000000';
    var cliente = $('#nombreCliente').val() || 'Clientes Varios';    
    var serie = datosComprobante.serie;
    var numero = datosComprobante.ultimoNumero;
    var tpago = $('#tpago').val();

    var sumaTotal = actualizarTotales();

    
     // Mostrar resumen de ventas en un SweetAlert
     Swal.fire({
        icon: 'warning',
        title: 'Necesitamos tu Confirmación',
        html: '<div style="text-align: center;">' +
            '<div style="font-weight: bold;">Se creará el documento electrónico con los siguientes datos:</div>' +
            '<div class="resultados" style="margin-top: 10px;">Resumen:</div>' +
            '<div class="resultados">SubTotal: '+(sumaTotal/1.18).toFixed(2)+'</div>' +
            '<div class="resultados">IGV: '+(sumaTotal-(sumaTotal/1.18)).toFixed(2)+'</div>' +
            '<div class="resultados" style="color:#2196f3;">Total: '+sumaTotal+'</div>' +
            '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
            '</div>',
        confirmButtonText: 'Si, Completar Venta',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745', // Color verde
        cancelButtonColor: '#dc3545' // Color rojo
    }).then((result) => {
        if (result.isConfirmed) {
          
          console.log(productosArray);                          
                          $.ajax({
                                url: "../../Controlador/co_comprobantes.php",
                                data:{completarVenta:1,tipo_comprobante:tipo_comprobante,ndocumento:ndocumento,cliente:cliente,serie:serie,numero:numero,total_vendido:sumaTotal,productos:productosArray,tpago:tpago},
                                dataType:'html',
                                type:'POST',
                                success: function (datos){                                         
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'VENTA REALIZADA CON EXITO!',
                                        html: '<div style="background-color: #e8f5e9; border-color: #4caf50; color: #43a047; padding: 10px; margin-top: 10px;">' +
                                            '<p style="margin: 0;">CODIGO DE VENTA: '+ datos + '</p>' +
                                            '</div>' +
                                            '<a onclick="imprimirVenta(\'' + datos + '\')" style="margin-top: 10px; display: inline-block; margin-left: 10px;">' +
                                            '<img src="https://sistema.facturamas.pe/facturacion/img/svg/ticket_cpe.svg" style="width: 24px; height: 24px; margin-right: 5px;" alt="Ticket">' +
                                            '<span>Ticket</span>' +
                                            '<div class="pdf_preview"></div>' +                                            
                                            '</a>' +
                                            '<br>' +                                            
                                            '<a class="btn bg-success" onclick="Swal.close(); menu_principal(\'comprobantes/lista.php\', \'COMPROBANTES\', \'LISTA COMPROBANTES\');" style="margin-top: 10px;">Lista de Comprobantes</a>',
                                        showCancelButton: false,     
                                        showConfirmButton : false,
                                        allowOutsideClick: false, // No permite clic fuera de la ventana
                                        allowEscapeKey: false,
                                        backdrop: 'static', // Evita el cierre haciendo clic fuera de la ventana
                                        
                                    });      
                                    verVentapdf(datos);                               
                                    
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
        else 
        {
            // Si el usuario cancela, no hacer nada o realizar alguna acción adicional si es necesario
        }
    });

}
function calcularSumaTotal() {    

var sumaTotal = 0;
// Iterar sobre el array productosVenta y sumar las cantidades
$.each(productosVenta, function(index, producto) {
    sumaTotal += parseFloat(producto.total) || 0; // Asegurarse de que el valor sea numérico
});

$('#suma_subtotal').text('S/' +(sumaTotal*0.82).toFixed(2));
$('#suma_igv').text('S/' +(sumaTotal-(sumaTotal/1.18)).toFixed(2));
$('#suma_total').text('S/' +sumaTotal.toFixed(2));

return sumaTotal.toFixed(2);

}
</script>
<script src="https://adminlte.io/themes/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>