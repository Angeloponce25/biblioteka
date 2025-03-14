function eliminarProducto(index) {
    // Mueve esta línea dentro de la función editarProducto
    var tabla = $('#data-table-productos-inventario').DataTable();

    var rowData = tabla.row(index).data();
    var idProducto = rowData.id_producto;
    var descripcion = rowData.descripcion;

    Swal.fire({
          icon: 'warning',
          title: 'Necesitamos tu Confirmación',
          html: '<div style="text-align: center;">' +
              '<div style="font-weight: bold;">Deseas eliminar el siguiente producto</div>' +            
              '<div class="resultados">Producto: ' + descripcion + '</div>' +            
              '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
              '</div>',
          confirmButtonText: 'Si, Eliminar Producto',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#28a745', // Color verde
          cancelButtonColor: '#dc3545' // Color rojo
      }).then((result) => {
          if (result.isConfirmed) 
          { 
                            $.ajax({
                                  url:'../../Controlador/co_productos.php',
                                  data:{deleteProducto:1,idProducto:idProducto},
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
                                        // Eliminar el producto del array productosVenta                                        
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


  function editarProducto(index)
  { 
    // Mueve esta línea dentro de la función editarProducto
    var tabla = $('#data-table-productos-inventario').DataTable();

    var rowData = tabla.row(index).data();
    var idProducto = rowData.id_producto;
    $.ajax({
        url:'../../Controlador/co_productos.php',
        data:{editarProducto:1,idProducto:idProducto},
        dataType:'json',
        type:'POST',
        success: function (datos)
        {
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
                //CODIGO DE FECHA

                    // Fecha obtenida de PHP en formato '2024-12-31'
                    var fechaPHP = datos[0].fecha_vencimiento;

                    // Formatear la fecha para que coincida con el formato 'YYYY-MM-DD'
                    var partesFecha = fechaPHP.split('-');
                    var fechaFormateada = partesFecha[0] + '-' + partesFecha[1] + '-' + partesFecha[2];

                    // Asignar la fecha formateada al campo de entrada
                    


                    //FIN CODIGO FECHA
                $('#idEditarProducto').val(idProducto);
                $('#EditarnombreProducto').val(datos[0].descripcion);
                $('#Editarstock').val(datos[0].stock);
                $('#Editarstock_min').val(datos[0].stock_min);
                $('#Editarunidad_medida').attr('name', 'datos[0].unidad_medida');
            
                // Crear una opción "Seleccione" si no se ha seleccionado ninguna opción
                if ($('#Editarunidad_medida').find('option:selected').length === 0) {
                    $('#Editarunidad_medida').prepend($('<option>', {
                        value: '',
                        text: 'Seleccione'
                    }));
                }
                $('#Editarprecio_compra').val(datos[0].precio_compra);
                $('#Editarprecio_venta').val(datos[0].precio_venta);
                $('#Editarprecio_venta_min').val(datos[0].precio_venta_minimo);
                $('#Editarfecha_vencimiento').val(fechaFormateada);
                /*$('#Editaralmacen').val(datos[0].almacen);*/
                   // Cambiar el nombre del select para que coincida con el ID del HTML
                $('#Editaralmacen').attr('name', 'datos[0].almacen');
            
                // Crear una opción "Seleccione" si no se ha seleccionado ninguna opción
                if ($('#Editaralmacen').find('option:selected').length === 0) {
                    $('#Editaralmacen').prepend($('<option>', {
                        value: '',
                        text: 'Seleccione'
                    }));
                }
                $("#modal-editar-producto").modal();                          
                }
        },
        error:function(xhr,status,error)
        {
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
  
  function actualizarProducto()
  {    
    var idProducto= $('#idEditarProducto').val();
    var descripcion= $('#EditarnombreProducto').val();  
    var stock= $('#Editarstock').val();  
    var stockmin= $('#Editarstock_min').val();  
    var unidad_medida= $('#Editarunidad_medida').val();  
    var precio_compra= $('#Editarprecio_compra').val();  
    var precio_venta= $('#Editarprecio_venta').val();  
    var precio_venta_min= $('#Editarprecio_venta_min').val();  
    var fecha_vencimiento= $('#Editarfecha_vencimiento').val();  
    var almacen= $('#Editaralmacen').val();  
    Swal.fire({
          icon: 'warning',
          title: 'Necesitamos tu Confirmación',
          html: '<div style="text-align: center;">' +
              '<div style="font-weight: bold;">Deseas actualizar el siguiente producto</div>' +                                  
              '<div class="resultados">Producto: ' + descripcion + '</div>' +              
              '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
              '</div>',
          confirmButtonText: 'Si, Actualizar Producto',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#28a745', // Color verde
          cancelButtonColor: '#dc3545' // Color rojo
      }).then((result) => {
          if (result.isConfirmed) 
          { 
                            $.ajax({
                                  url:'../../Controlador/co_productos.php',
                                  data:{updateProductos:1,idProducto:idProducto,descripcion:descripcion,stock:stock,stockmin:stockmin,unidad_medida:unidad_medida,precio_compra:precio_compra,precio_venta:precio_venta,precio_venta_min:precio_venta_min,fecha_vencimiento:fecha_vencimiento,almacen:almacen},
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
                                        //Recargamos los datos del Array
                                        // Mueve esta línea dentro de la función editarProducto
                                        var tabla = $('#data-table-productos-inventario').DataTable();
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
function crearProducto()
{
  var producto = [];
  var tabla = $('#data-table-productos-inventario').DataTable();
  var descripcion= $('#descripcion').val();
  var stock = $('#stock').val();
  var stock_min = $('#stock_min').val();
  var unidad_medida = $('#unidad_medida').val(); 
  var selectedUnidad_medida = $('#unidad_medida option:selected').text(); 
  var precio_compra = $('#precio_compra').val(); 
  var precio_venta = $('#precio_venta').val(); 
  var precio_venta_min = $('#precio_venta_min').val(); 
  var fecha_vencimiento = $('#fecha_vencimiento').val(); 
  var almacen = $('#almacen').val(); 
   // Verificar si hay al menos un producto en la venta

   if (descripcion.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de producto no puede estar vacio',
            confirmButtonText: 'OK'
        });
        return; // Detener la ejecución si no hay productos
    }
   if (stock.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El stock no puede estar vacio',
            confirmButtonText: 'OK'
        });
        return; // Detener la ejecución si no hay productos
    }

   

     Swal.fire({
        icon: 'warning',
        title: 'Necesitamos tu Confirmación',
        html: '<div style="text-align: center;">' +
            '<div style="font-weight: bold;">Se creará el producto con los siguientes datos:</div>' +            
            '<div class="resultados">Producto: '+descripcion+'</div>' +
            '<div class="resultados">Stock: '+stock+'</div>' +         
            '<div class="resultados">Unidad: '+selectedUnidad_medida+'</div>' +            
            '<div class="resultados">Precio Compra: '+precio_compra+'</div>' +
            '<div class="resultados">Precio Venta: '+precio_venta+'</div>' +
            '<div class="resultados">Almacen: '+almacen+'</div>' +
            '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
            '</div>',
        confirmButtonText: 'Si, Crear Producto',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745', // Color verde
        cancelButtonColor: '#dc3545' // Color rojo
    }).then((result) => {
        if (result.isConfirmed) 
        { 
                          $.ajax({
                                url:'../../Controlador/co_productos.php',
                                data:{crearProducto:1,descripcion:descripcion,stock:stock,stock_min:stock_min,unidad_medida:unidad_medida,precio_compra:precio_compra,precio_venta:precio_venta,precio_venta_min:precio_venta_min,fecha_vencimiento:fecha_vencimiento,almacen:almacen},
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
                                            '<span style="font-size: 18px;margin: 0;">CODIGO: '+ datos.id_producto + '</span>' +
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