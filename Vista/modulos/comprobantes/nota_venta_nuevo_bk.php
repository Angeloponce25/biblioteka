<?php
$modulo=$_POST['modulo'];
$opcion=$_POST['opcion'];
include("../../../config/conexion.php");
?>
<!-- Incluye los estilos de jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/select2/dist/css/select2.min.css">
<style>

#producto-list {
    float: left;
    list-style: none;
    margin-top: -3px;
    padding: 0;
    width: 400px;
    position: absolute;
    z-index: 99999;
}

#producto-list li {
    padding: 10px;
    background: #f0f0f0;
    border-bottom: #bbb9b9 1px solid;
}

#producto-list li:hover {
    background: #ece3d2;
    cursor: pointer;
}

#search-box {
    padding: 10px;
    border: #a8d4b1 1px solid;
    border-radius: 4px;
}
.cajaComprobante {
    position: absolute;
    /* left: 10px; */
    margin: 0px 0px 0px 0px;
    width: 33%;
    /*	height:50%;*/
    max-height: 100%;
    padding: 0.5%;
    font: small/1.5 "tahoma",verdana;
    font-size: 9px;
    background-color: #2BA9E6;
    -moz-border-radius: 7px;
    -webkit-border-radius: 7px;
    border: 0px solid #000;
    color: #fff;
    overflow: auto;
    z-index: 150;
    display: none;
}
</style>


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
                <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-vender-producto"><i class="fa fa-plus"></i> AGREGAR (F2)</button> 
                <button type="button" id="boton-demo" class="btn btn-success pull-right"><i class="fa fa-plus"></i> DEMO</button>
                <input type='text' id="txtNumFilasAo" name="txtNumFilasAo" value='0'>
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
                  <th>CANTIDAD</th>                  
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
$(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
      });
$(document).ready(function() {
  //NUEVO SISTEMA ANGELO

$('#boton-demo').click(function() {

var cantidad=parseInt($("#txtNumFilasAo").val());
c=cantidad+1;
$("#txtNumFilasAo").val(c);


        // Crear el nuevo TR con los TDs correspondientes para el input y el botón
        var nuevaFila = '<tr id="'+c+'">' +            
            '<td>'+
            '<input type="text" id="txtIdProducto'+c+'" name="txtIdProducto[]" >'+
            '<input id="txtproducto'+c+'" type="text" class="form-control" placeholder="Ingresar Texto" aria-label="Ingresar Texto" autocomplete="off" onkeyup="findProducto('+c+');return false;" required>'+
            '<div id="divCajaPrinc'+c+'" class="cajaComprobante"></div>'+
            '</td>'+ 
            '<td>' +
            '<select id="selectProducto'+c+'" class="form-control autocomplete-input" style="width: 100%;" onchange="findProducto('+c+', this.value)">' +
            '</select>' +
            '</td>' + // Columna vacía para el producto
            '<td>' +
            '<div class="input-group input-group-sm" style="width: 100%;">' +
            '<input type="text" class="form-control autocomplete-input" autocomplete="off" placeholder="Buscar producto">' +
            '</div>' +
            '</td>' +
            '<td></td>' + // Columna vacía para el producto            	
            '<td></td>' + // Columna vacía para el precio
            '<td></td>' +
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

                // Agregar opciones al select
                $.each(data, function(index, item) {
                    select.append('<option value="' + item.id_producto + '">' + item.descripcion + '</option>');
                });

                // Inicializar Select2
                select.select2();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });


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


function findProducto(id) {
    var producto = $("#txtproducto" + id).val(); // Obtener el valor del campo de búsqueda y eliminar espacios en blanco
    if (producto === '' || producto === '0') {
        $("#divCajaPrinc" + id).hide();
        return false;
    }

    var parametros = {
        findProducto: 1,
        id_tabla: id,
        producto: producto
    };

    $("#divCajaPrinc" + id).show().empty().append("Cargando...");

    $.ajax({
        url: '../../Controlador/co_comprobantes.php',
        data: parametros,
        dataType: 'html',
        type: 'POST',
        success: function (datos) {
            $("#divCajaPrinc" + id).empty().append(datos);
        },
        error: function (xhr, status, error) {
            var err = "Ocurrió un error: " + xhr.responseText + error;
            $("#divCajaPrinc" + id).empty().append(err);
        }
    });
}

function takeProducto(id_tabla, id,desc){

let cadena = desc;

let cadena_string = cadena.replace(/_/g, ' ');

$("#txtIdProducto"+id_tabla).val(id);
$("#txtproducto"+id_tabla).val(cadena_string);
$("#divCajaPrinc"+id_tabla).hide();

/*getUnidad(id_tabla);*/

}


function quitarItem(tr,id)
{
    var objHijo = document.getElementById(tr);

    var idDetalleElement = document.getElementById("txtIdDetalle"+tr);    
    var objPadre = objHijo.parentNode;    
    objPadre.removeChild(objHijo);    
    /*var totalImportes = 0;
        $("span[name='txtimporte[]']").each(function () {
            var valor = parseFloat($(this).text()) || 0;
            totalImportes += valor;
        });
    $('#txtsubtotal').text((totalImportes/1.18).toFixed(2));
    $('#txtigv').text((totalImportes-(totalImportes/1.18)).toFixed(2));
    $('#txttotal').text((totalImportes).toFixed(2));*/
	

   
}
$(document).on('click', '.agregar-venta', function() {
    var tabla = $('#data-table-productos').DataTable();
    // Obtener el índice de la fila
    var index = $(this).data('index');
    // Obtener los datos del producto desde la tabla de DataTables
    var producto = tabla.row(index).data();
    // Llamar a la función agregarAVenta con el producto obtenido
    agregarAVenta(producto);
});
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
</script>
<script src="https://adminlte.io/themes/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>