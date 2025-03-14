var datosComprobante = [];
var pageSize = 10; // Define el tamaño de la página
function loadTableVentas(dataToLoad, page) {
    var tbody = $('#data-table-ventas tbody');
    tbody.empty();

    if (!dataToLoad) {
        console.error('La Data no esta definida o esta vacia');
        return;
    }

     // Calcula el índice de inicio y fin para la página actual
     var startIndex = (page - 1) * pageSize;
     var endIndex = Math.min(startIndex + pageSize, dataToLoad.length);
 
     // Muestra solo los productos de la página actual
     var currentPageData = dataToLoad.slice(startIndex, endIndex);

    $.each(currentPageData, function(index, item) {

        var tFormateado = parseFloat(item.total_vendido).toFixed(2);

        var row = '<tr>' +
        '<td data-column="id_comprobante_venta">' + item.id_comprobante_venta + '</td>' +
        '<td class="comprobante">' + item.comprobante + '</td>' +
        '<td>' + item.serie +'-' + item.numero +'</td>' +
        '<td>' + item.cliente + '</td>' +        
        '<td>' + item.tipo_pago + '</td>' +        
        '<td> S/ ' + tFormateado + '</td>' +                      
        '<td>' + item.fecha_creacion + '</td>' +        
        '<td>' + item.u_username + '</td>' +        
        '<td>'+ item.editar+item.eliminar+'</td>'+        
        '</tr>';

        tbody.append(row);
    });
    // Actualizar controles de paginación
    updatePaginationControlsVentas(dataToLoad.length, page);

}

// Función para actualizar los controles de paginación
function updatePaginationControlsVentas(totalItems, currentPage) {
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
        loadTableVentas(data, currentPage);        
    }
});

// Evento para ir a la página siguiente
$('#next-page').click(function() {
    var totalItems = dataToLoad.length;
    var totalPages = Math.ceil(totalItems / pageSize);
    alert('lol');
    if (currentPage < totalPages) {
        currentPage++;
        loadTableVentas(data, currentPage);
        
    }
});
function next_page()
{
    if (data && data.length > 0) { // Verifica si dataToLoad está definido y tiene elementos
        var totalItems = data.length;
        var totalPages = Math.ceil(totalItems / pageSize);
        
        if (currentPage < totalPages) {
            currentPage++;
            loadTableVentas(data, currentPage);
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
            loadTableVentas(data, currentPage);        
        }
    }
    else
    {
        console.log('no existe data');
    }
}

function eliminarVenta(id_comprobante_venta) {
    var idVenta = id_comprobante_venta;    
    Swal.fire({
          icon: 'warning',
          title: 'Necesitamos tu Confirmación',
          html: '<div style="text-align: center;">' +
              '<div style="font-weight: bold;">Deseas eliminar la siguiente venta</div>' +            
              '<div class="resultados">CODIGO VENTA: ' + id_comprobante_venta + '</div>' +            
              '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
              '</div>',
          confirmButtonText: 'Si, Eliminar Venta',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#28a745', // Color verde
          cancelButtonColor: '#dc3545' // Color rojo
      }).then((result) => {
          if (result.isConfirmed) 
          { 
                            $.ajax({
                                  url:'../../Controlador/co_comprobantes.php',
                                  data:{deleteVenta:1,idVenta:idVenta},
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
                                        recargarComprobantes();                                        
                                      }
                                                                         
                                      
                                  },
                                  error:function(xhr, status, error){
                                      var err=(xhr.responseText+status+error);
                                      Swal.fire(
                                      'Oooops',
                                      'Ocurrio un error',
                                      'warning'
                                      )
                                      console.log(err);
                                  }                                                            
                              });
          } 
      });
  
}
function recargarComprobantes()
{
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
                console.error('Error al obtener Comprobantes');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}