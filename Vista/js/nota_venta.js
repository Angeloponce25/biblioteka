var datosComprobante = [];
var productosVenta = [];

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
    embedElement.attr('toolbar', '1'); // Habilitar la barra de herramientas

    // Insertar el elemento embed en el contenedor deseado
    $('.pdf_preview').html(embedElement);
}
function imprimirVenta(id) {    
    window.open('../../Controlador/co_comprobantes.php?printPdfComprobante&id_venta='+id,'_blank');    
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
// Función para editar un campo de la tabla
function editarCampo(index, campo) {
    var valorActual = productosVenta[index][campo];

    // Utilizar SweetAlert para obtener el nuevo valor
    Swal.fire({
        title: '<div style="font-family: Arial; font-size: 18px; line-height: 1.6;">' +
           '<b style="font-family: Arial; font-size: 18px; line-height: 1.6;">'+productosVenta[index].descripcion + '</b><br>' +
           'Stock: ' + productosVenta[index].stock + '</b><br>' ,
        input: 'text',
        inputValue: valorActual,
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        allowOutsideClick: true,
        allowEscapeKey: true,
        width: '500px', // Ajusta el ancho del cuadro de diálogo
        inputAttributes: {
        placeholder: 'Ingrese la cantidad a vender aquí' // Agrega un marcador de posición al input
    }
    }).then((result) => {
        if (result.isConfirmed) {
            var nuevoValor = result.value;

            // Validar si se ingresó un nuevo valor numérico
            if (!isNaN(parseFloat(nuevoValor)) && isFinite(nuevoValor)) {
                // Convertir a número
                nuevoValor = parseFloat(nuevoValor);
                // Verificar que la cantidad sea menor o igual al stock
                if (campo === 'cantidad' && nuevoValor > productosVenta[index].stock) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La cantidad no puede ser mayor al stock disponible.'
                    });
                } else {
                    // Actualizar el valor y la tabla
                    productosVenta[index][campo] = nuevoValor;
                    productosVenta[index]['total'] = productosVenta[index]['cantidad']*productosVenta[index]['precio_venta'];
                    actualizarTablaVenta();
                    
                }
            } else {
                // Mostrar mensaje de error si no se ingresó un valor numérico
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, ingrese un valor numérico válido.'
                });
            }
        }
    });
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
function obtenerCaja() {    
    // Llamada AJAX
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
  }