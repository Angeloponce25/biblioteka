var datosComprobante = [];
var productosVenta = [];
var pageSize = 10; // Define el tamaño de la página

function verVentapdf(id) {    
    // Obtener el contenido del PDF mediante una petición AJAX
    // Obtener el enlace del PDF
    var pdfUrl = '../../Controlador/co_comprobantes.php?verPdfComprobante&id_venta='+id;
    
    // Crear un elemento embed
    var embedElement = $('<embed>');

    // Establecer los atributos del elemento embed
    embedElement.attr('src', pdfUrl);
    embedElement.attr('type', 'application/pdf');
    embedElement.attr('width', '100%');
    embedElement.attr('height', '300px');

    // Insertar el elemento embed en el contenedor deseado
    $('.pdf_preview').html(embedElement);
}
function imprimirVenta(id) {    
    window.open('../../Controlador/co_comprobantes.php?printPdfComprobante&id_venta='+id,'_blank');    
}
function completarVenta()
{
    var tipo_comprobante= 6; //NOTA DE VENTA
    var ndocumento = $('#txtndocumento').val() || '00000000';
    var cliente = $('#nombreCliente').val() || 'Clientes Varios';    
    var serie = datosComprobante.serie;
    var numero = datosComprobante.ultimoNumero;
    var tpago = $('#tpago').val();

    
     // Verificar si hay al menos un producto en la venta
    if (productosVenta.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Debes agregar al menos un ítem al documento electrónico...',
            confirmButtonText: 'OK'
        });
        return; // Detener la ejecución si no hay productos
    }

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
                                data:{completarVenta:1,tipo_comprobante:tipo_comprobante,ndocumento:ndocumento,cliente:cliente,serie:serie,numero:numero,total_vendido:sumaTotal,productos:productosVenta,tpago:tpago},
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
var currentPage = 1; // Página actual

// Función para cargar los datos de la página especificada
function loadTableProductosVender(dataToLoad, page) {
    var tbody = $('#data-table-productos tbody');
    tbody.empty();

    if (!dataToLoad || dataToLoad.length === 0) {
        console.error('La Data no está definida o está vacía');
        return;
    }

    // Calcula el índice de inicio y fin para la página actual
    var startIndex = (page - 1) * pageSize;
    var endIndex = Math.min(startIndex + pageSize, dataToLoad.length);

    // Muestra solo los productos de la página actual
    var currentPageData = dataToLoad.slice(startIndex, endIndex);

    $.each(currentPageData, function(index, item) {
        var almacen;
        if (item.almacen == 1) {
            almacen = 'TIENDA';
        } else if (item.almacen == 2) {
            almacen = 'DETALLES';
        } else {
            almacen = 'ALMACEN';
        }
        
        var row = '<tr>' +
            '<td data-column="id_producto">' + item.id_producto + '</td>' +
            '<td class="descripcion">' + item.descripcion + '</td>' +
            '<td>' + item.stock + '</td>' +
            '<td>' + item.stock_min + '</td>' +        
            '<td>' + item.unidad_medida + '</td>' +        
            '<td>' + item.precio_compra + '</td>' +        
            '<td>' + item.precio_venta + '</td>' +        
            '<td>' + item.precio_venta_minimo + '</td>' +        
            '<td>' + item.fecha_vencimiento + '</td>' +        
            '<td>' + almacen + '</td>' +        
            '<td><button class="btn btn-success btn-small agregar-venta" data-index="' + (startIndex + index) + '"><i class="fa fa-plus"></i></button></td>' +              
            '</td>'+        
            '</tr>';

        tbody.append(row);
    });

    // Asignar el manejador de clic para los botones "AGREGAR A VENTA"
    $('.agregar-venta').click(function() {
        var index = $(this).data('index');
        agregarAVenta(dataToLoad[index]);
    });

    // Actualizar controles de paginación
    updatePaginationControls(dataToLoad.length, page);
}

// Función para actualizar los controles de paginación
function updatePaginationControls(totalItems, currentPage) {
    var totalPages = Math.ceil(totalItems / pageSize);

    // Actualizar texto de la página actual y total de páginas
    $('#current-page').text(currentPage);
    $('#total-pages').text(totalPages);

    // Habilitar/deshabilitar botones de paginación según la página actual
    $('#prev-page').prop('disabled', currentPage === 1);
    $('#next-page').prop('disabled', currentPage === totalPages);
}

// Evento para ir a la página anterior
$('#prev-page').click(function() {
    if (currentPage > 1) {
        currentPage--;
        loadTableProductosVender(data, currentPage);        
    }
});

// Evento para ir a la página siguiente
$('#next-page').click(function() {
    var totalItems = dataToLoad.length;
    var totalPages = Math.ceil(totalItems / pageSize);
    alert('lol');
    if (currentPage < totalPages) {
        currentPage++;
        loadTableProductosVender(data, currentPage);
        
    }
});
function next_page()
{
    if (data && data.length > 0) { // Verifica si dataToLoad está definido y tiene elementos
        var totalItems = data.length;
        var totalPages = Math.ceil(totalItems / pageSize);
        
        if (currentPage < totalPages) {
            currentPage++;
            loadTableProductosVender(data, currentPage);
        }
    }
    else
    {
        console.log('no existe data');
    }
}
function prev_page()
{
    if (data && data.length > 0) { // Verifica si dataToLoad está definido y tiene elementos
        if (currentPage > 1) {
            currentPage--;
            loadTableProductosVender(data, currentPage);        
        }
    }
    else
    {
        console.log('no existe data');
    }
}


function agregarAVenta(producto) {
    // Verificar si la descripción ya existe en el array
    var existe = productosVenta.some(function(item) {
        return item.id_producto === producto.id_producto;
    });

    if (existe) {
        // Si la descripción ya existe, mostrar un mensaje y no agregar el producto
        Swal.fire(
            'Producto Existente',
            'Este producto ya fue agregado a la venta.',
            'warning'
        );
    } else {
    // Si la descripción no existe, solicitar la cantidad a insertar
    Swal.fire({
        title: '<div style="font-family: Arial; font-size: 18px; line-height: 1.6;">' +
           '<b style="font-family: Arial; font-size: 18px; line-height: 1.6;">'+producto.descripcion + '</b><br>' +
           'Stock: ' + producto.stock + '</b><br>' ,
        input: 'number',
        inputAttributes: {
            autocapitalize: 'off',
            max: producto.stock,  // Establecer el valor máximo del campo de entrada como el stock disponible
            placeholder: 'Ingrese la cantidad a vender aquí' // Agrega un marcador de posición al input
        },
        showCancelButton: true,
        confirmButtonText: 'Agregar a Venta',
        showLoaderOnConfirm: true,
        allowOutsideClick: true,
        allowEscapeKey: true,
        width: '500px',
        preConfirm: (cantidad) => {
            // Verificar si se ingresó una cantidad válida
            cantidad = parseFloat(cantidad); // Convertir a número

            if (isNaN(cantidad) || cantidad <= 0 || cantidad > producto.stock) {
                Swal.showValidationMessage(`Por favor, ingrese una cantidad válida menor o igual a ${producto.stock}.`);
            }
            return cantidad;
        },
        allowOutsideClick: () => !Swal.isLoading()
      }).then((result) => {
          if (result.isConfirmed) {
              // Obtener la cantidad ingresada
              var cantidad = result.value;
              
              // Actualizar la cantidad en el objeto del producto

              producto.cantidad = cantidad;
              producto.total = (producto.cantidad*producto.precio_venta).toFixed(2);

              // Agregar el producto al array global
              productosVenta.push(producto);

              // Actualizar la tabla de venta
              actualizarTablaVenta();
              
          }
      });
    }

}
// Función para actualizar la tabla de venta
function actualizarTablaVenta() {
    var tbodyVenta = $('#data-table-productos-vender-body');
    tbodyVenta.empty();
    
    

    $.each(productosVenta, function(index, producto) {
        var pvFormateado = parseFloat(producto.precio_venta).toFixed(2);
        var totalFormateado = parseFloat(producto.total).toFixed(2);

        var row = '<tr>' +
            '<td style="font-size: 14px;">' + producto.id_producto + '</td>' +                     
            '<td style="font-size: 14px;">' + producto.descripcion + '</td>' +
            '<td style="font-size: 14px;">' + producto.unidad_medida + '</td>' +
            '<td style="font-size: 14px;" class="editable" ondblclick="editarCampo(' + index + ', \'cantidad\')">' + producto.cantidad + '</td>' +
            '<td style="font-size: 14px;">S/ ' + pvFormateado + '</td>' +
            '<td style="font-size: 14px;">S/ ' + totalFormateado + '</td>' +
            '<td style="font-size: 14px;"><button onclick="eliminarProductoVenta(' + index + ')" style="color: red;border: none;background-color: white;"><i class="fa fa-close"></i></button></td>' +            
            '</tr>';

        tbodyVenta.append(row);
    });

    // Actualizar el elemento HTML con la suma total
    calcularSumaTotal();
}
function eliminarProductoVenta(index) {
    // Eliminar el producto del array productosVenta
    productosVenta.splice(index, 1);
    
    // Actualizar la tabla de venta
    actualizarTablaVenta();
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

function obtenerSiguienteNumero() {    
    // Llamada AJAX
    $.ajax({          
      type: 'GET',      
      url: "../../Controlador/co_comprobantes.php",
      data: { correlativoDocumento:1,tipoDocumento: 6 },
      dataType: 'json',
      success: function(data) {
        datosComprobante.serie = data.serie;
        datosComprobante.ultimoNumero = data.ultimoNumero;
        $('#correlativo').text(data.serie+'-'+data.ultimoNumero);
      },
      error: function(error) {
        console.log('Error en la llamada AJAX:', error);
      }
    });
  }