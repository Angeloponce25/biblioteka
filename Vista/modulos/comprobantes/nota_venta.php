<style>
        /* Estilos locales */
        #inputProducto {
            width: 300px;
            padding: 8px;
            font-size: 16px;
        }
        #listaProductos {
            display: none;
            max-height: 120px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background-color: #fff;
            position: absolute;
            z-index: 9999;
            width: 98%;
        }
        .producto {
            padding: 8px;
            cursor: pointer;
        }
        .producto:hover {
            background-color: #f0f0f0;
        }
        .custom-swal-width .swal2-popup {
            width: 60% !important;
        }
    </style>
<?php
$modulo=$_POST['modulo'];
$opcion=$_POST['opcion'];
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
        <div class="row">
            <div class="col-xs-12">
                <!-- Agregar el Select2 -->
                  <div class="form-group">
                    <label for="selectProductoInicial">Seleccione un producto:</label>
                    <input type="text" id="inputProducto" class="form-control" style="width: 100%;" placeholder="Escribe el nombre del producto" autocomplete="off">
                    <!-- Lista de productos autocompletada -->
                    <div id="listaProductos"></div>
                  </div>   
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
    obtenerCaja(); // Llamada inicial al cargar la página
    var productos = [];

    var inputProducto = document.getElementById('inputProducto');
    var listaProductos = document.getElementById('listaProductos');
    var tablaProductos = document.getElementById('data-table-productos-vender-body');

    // Función para decodificar entidades HTML
    function decodeHTMLEntities(text) {
        var txt = document.createElement('textarea');
        txt.innerHTML = text;
        return txt.value;
    }

    // Función para mostrar la lista de productos
    function mostrarLista(productos) {
        // Limpiar lista anterior
        listaProductos.innerHTML = '';

        // Mostrar lista de productos
        productos.forEach(function(producto) {
            var item = document.createElement('div');
            item.classList.add('producto');
            item.textContent = decodeHTMLEntities(producto.id + '-' + producto.nombre);
            item.addEventListener('click', function() {
                // Acción al seleccionar un producto
                manejarProductoSeleccionado(producto);
                inputProducto.value = ''; // Limpiar el input
                inputProducto.focus(); // Mantener el focus en el input
                listaProductos.style.display = 'none'; // Ocultar la lista
                calcularSumaTotal();
            });
            listaProductos.appendChild(item);
        });

        // Mostrar la lista
        listaProductos.style.display = 'block';
    }

    // Ocultar lista al hacer clic fuera de ella o al presionar ESC
    document.addEventListener('click', function(e) {
        if (!listaProductos.contains(e.target) && e.target !== inputProducto) {
            listaProductos.style.display = 'none';
        }
    });

    // Función para manejar la selección del producto
    function manejarProductoSeleccionado(producto) {
        var filas = tablaProductos.getElementsByTagName('tr');
        var encontrado = false;

        for (var i = 0; i < filas.length; i++) {
            var fila = filas[i];
            var celdaDescripcion = fila.getElementsByTagName('td')[0];
            var celdaUnidad = fila.getElementsByTagName('td')[1];
            var celdaCantidad = fila.getElementsByTagName('td')[2];
            var inputCantidad = celdaCantidad.getElementsByTagName('input')[0];

            // Verificar si el producto ya está en la tabla
            if (celdaDescripcion.textContent.includes(producto.nombre)) {
                // Actualizar cantidad y total
                var cantidadActual = parseFloat(inputCantidad.value);
                inputCantidad.value = cantidadActual + 1;
                var celdaPrecio = fila.getElementsByTagName('td')[3];
                var celdaTotal = fila.getElementsByTagName('td')[4];
                var precio = parseFloat(celdaPrecio.textContent);
                var total = (cantidadActual + 1) * precio;
                celdaTotal.textContent = total.toFixed(2);
                encontrado = true;
                break;
            }
        }

        // Si el producto no está en la tabla, agregar una nueva fila
        if (!encontrado) {
            agregarProductoATabla(producto);
        }
    }

    // Función para agregar producto a la tabla
    function agregarProductoATabla(producto) {
        var fila = document.createElement('tr');
        // Agregar atributo ID al elemento tr
        fila.setAttribute('data-id', producto.id);

        var celdaDescripcion = document.createElement('td');
        celdaDescripcion.textContent = producto.nombre;
        fila.appendChild(celdaDescripcion);

        var celdaUnidad = document.createElement('td');
        celdaUnidad.textContent = producto.unidad_medida;
        fila.appendChild(celdaUnidad);

        var celdaCantidad = document.createElement('td');
        var inputCantidad = document.createElement('input');
        inputCantidad.type = 'number';
        inputCantidad.value = 1;
        inputCantidad.min = 1;
        celdaCantidad.appendChild(inputCantidad);
        fila.appendChild(celdaCantidad);

        var celdaPrecio = document.createElement('td');
        celdaPrecio.textContent = producto.precio;
        fila.appendChild(celdaPrecio);

        var celdaTotal = document.createElement('td');
        celdaTotal.textContent = producto.precio;
        fila.appendChild(celdaTotal);

        // Actualizar total cuando cambia la cantidad
        inputCantidad.addEventListener('input', function() {
            var cantidad = parseFloat(inputCantidad.value);
            var total = cantidad * producto.precio;
            celdaTotal.textContent = total.toFixed(2);
            calcularSumaTotal();
        });

        var celdaAccion = document.createElement('td');
        var botonEliminar = document.createElement('button');
        botonEliminar.innerHTML = '<i class="fa fa-trash"></i>';
        botonEliminar.className = 'btn btn-danger btn-sm'; // Puedes agregar clases para estilizar el botón
        botonEliminar.addEventListener('click', function() {
            fila.remove();
            calcularSumaTotal();
        });
        celdaAccion.appendChild(botonEliminar);
        fila.appendChild(celdaAccion);

        tablaProductos.appendChild(fila);
    }

    function obtenerProductos() {
        // Realizar la llamada Ajax para obtener los productos
        $.ajax({
            url: '../../Controlador/co_comprobantes.php', // Reemplaza 'ruta_a_tu_backend.php' con la ruta correcta a tu backend
            data: { ObtenerProductos: 1 },
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, item) {
                    var producto = {
                        id: item.id_producto,
                        nombre: item.descripcion,
                        precio: item.precio_venta,
                        unidad_medida: item.unidad_medida
                    };

                    // Agregar el objeto producto al array productos
                    productos.push(producto);
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    obtenerProductos();
    obtenerSiguienteNumero(); // Llamada inicial al cargar la página    

    // Escuchar cambios en el input de producto
    inputProducto.addEventListener('input', function() {
        var filtro = inputProducto.value.toLowerCase();
        var productosFiltrados = productos.filter(function(producto) {
            return producto.nombre.toLowerCase().includes(filtro);
        });
        mostrarLista(productosFiltrados);
    });

    // Hacer focus en el input al cargar la página
    inputProducto.focus();

    // Escuchar cambios al input (para lector de códigos de barras)
    inputProducto.addEventListener('change', function() {
        var filtro = inputProducto.value.toLowerCase();
        var productoSeleccionado = productos.find(function(producto) {
          return decodeHTMLEntities(producto.id.toLowerCase()) === filtro || decodeHTMLEntities(producto.nombre.toLowerCase()) === filtro;
        });
        if (productoSeleccionado) {            
            manejarProductoSeleccionado(productoSeleccionado);
            calcularSumaTotal();
            inputProducto.value = ''; // Limpiar el input
            inputProducto.focus(); // Mantener el focus en el input
            listaProductos.style.display = 'none'; // Ocultar la lista
        } else {
            console.log('Producto no encontrado');
        }
    });

    // Lógica adicional para el evento keydown
    document.addEventListener('keydown', function(event) {
        if (event.key === 'F4') {
            // Lógica que deseas ejecutar al presionar F4
            completarVenta();
        }
    });
});
function calcularSumaTotal() {    
var tablaProductos = document.getElementById('data-table-productos-vender-body');
var filas = tablaProductos.getElementsByTagName('tr');
var total = 0;

for (var i = 0; i < filas.length; i++) {
        var fila = filas[i];
        var celdaTotal = fila.getElementsByTagName('td')[4];
        total += parseFloat(celdaTotal.textContent);
    }

$('#suma_subtotal').text('S/' +(total*0.82).toFixed(2));
$('#suma_igv').text('S/' +(total-(total/1.18)).toFixed(2));
$('#suma_total').text('S/' +total.toFixed(2));

return total.toFixed(2);

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
function completarVenta() {
        var productosEnVenta = [];
        var tablaProductos = document.getElementById('data-table-productos-vender-body');
        var filas = tablaProductos.getElementsByTagName('tr');

        // Verificar si hay al menos un producto en la tabla
    if (filas.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debe haber al menos un producto en la tabla',
            confirmButtonText: 'OK'
        });
        return; // Detener la ejecución
    }

        for (var i = 0; i < filas.length; i++) {
            var fila = filas[i];
            var cantidad = fila.getElementsByTagName('input')[0].value;
            if (!cantidad)
            {                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La cantidad de algún producto está vacía',
                    confirmButtonText: 'OK'
                });
                return; // Detener la ejecución
            }
            var producto = {
                id_producto: fila.getAttribute('data-id'), // Asumiendo que el ID está en la primera celda
                nombre: fila.getElementsByTagName('td')[0].textContent, // Asumiendo que el nombre está en la primera celda
                unidad_medida: fila.getElementsByTagName('td')[1].textContent,
                cantidad: cantidad,
                precio_venta: fila.getElementsByTagName('td')[3].textContent,
                total: fila.getElementsByTagName('td')[4].textContent
            };
            productosEnVenta.push(producto);
        }
        var tipo_comprobante= 6; //NOTA DE VENTA
        var ndocumento = $('#txtndocumento').val() || '00000000';
        var cliente = $('#nombreCliente').val() || 'Clientes Varios';    
        var serie = datosComprobante.serie;
        var numero = datosComprobante.ultimoNumero;
        var caja = datosComprobante.idCaja;
        var tpago = $('#tpago').val();
        var sumaTotal = calcularSumaTotal();
        
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
                          $.ajax({
                                url: "../../Controlador/co_comprobantes.php",
                                data:{completarVenta:1,tipo_comprobante:tipo_comprobante,ndocumento:ndocumento,cliente:cliente,serie:serie,numero:numero,caja:caja,total_vendido:sumaTotal,productos:productosEnVenta,tpago:tpago},
                                dataType:'html',
                                type:'POST',
                                success: function (datos){                                         
                                    // Redirigir a una nueva página en blanco
                                    /*window.open('about:blank', '_blank');*/

                                    // Realizar otras acciones si es necesario, como imprimir el comprobante
                                    imprimirVenta(datos);

                                    // Opcional: Cerrar el SweetAlert
                                    Swal.close();

                                    // Opcional: Redireccionar a la lista de comprobantes u otra página
                                    menu_principal('comprobantes/lista.php', 'COMPROBANTES', 'LISTA COMPROBANTES');                             
                                    
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
</script>
<script src="https://adminlte.io/themes/AdminLTE/bower_components/select2/dist/js/select2.full.min.js"></script>