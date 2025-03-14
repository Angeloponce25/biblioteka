function loadTable(dataToLoad) {
    var tbody = $('#data-table tbody');
    tbody.empty();

    if (!dataToLoad) {
        console.error('La Data no esta definida o esta vacia');
        return;
    }

    $.each(dataToLoad, function(index, item) {

        var estadoLabel;
        if (item.id_estado == 1) 
        {
            estadoLabel = '<span class="label label-success">Activo</span>';
        } else 
        {
            estadoLabel = '<span class="label label-warning">Desactivado</span>';
        }

        var row = '<tr>' +
        '<td data-column="id_usuario">' + item.id_usuario + '</td>' +
        '<td class="usuario">' + item.u_username + '</td>' +
        '<td>' + estadoLabel + '</td>' +
        '<td>' + item.tpu_descripcion + '</td>' +        
        '<td><button class="btn btn-primary btn-small" onclick="editarUsuario('+index+',' + item.id_usuario + ', \'' + item.u_username + '\', ' + item.id_estado + ', ' + item.id_tipo_usuario + ')"><i class="fa fa-pencil"></i></button>'+         
        '<button class="btn btn-danger btn-small" onclick="eliminarUsuario(' + index + ')"><i class="fa fa-trash"></i></button>'+
        '</td>'+        
        '</tr>';

        tbody.append(row);
    });
}
function eliminarUsuario(index) {
    var idUsuario = data[index]['id_usuario'];
    var nombreUsuario = data[index]['u_username'];
    Swal.fire({
          icon: 'warning',
          title: 'Necesitamos tu Confirmación',
          html: '<div style="text-align: center;">' +
              '<div style="font-weight: bold;">Deseas eliminar el siguiente usuario</div>' +            
              '<div class="resultados">Usuario: ' + nombreUsuario + '</div>' +            
              '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
              '</div>',
          confirmButtonText: 'Si, Eliminar Usuario',
          showCancelButton: true,
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#28a745', // Color verde
          cancelButtonColor: '#dc3545' // Color rojo
      }).then((result) => {
          if (result.isConfirmed) 
          { 
                            $.ajax({
                                  url:'../../Controlador/co_usuarios.php',
                                  data:{deleteUsuario:1,idUsuario:idUsuario},
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
                                        data.splice(index, 1);
                                        
                                        // Actualizar la tabla de venta
                                        loadTable(data);
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
  function editarUsuario(index,id_usuario,u_username,id_estado,id_tipo_usuario)
  {   
    var idUsuario = id_usuario;
    var nombreUsuario = u_username;
    var estado = id_estado;
    var tipoUsuario = id_tipo_usuario;  
    
    $('#indexUsuario').val(index);    
    $('#idEditarUsuario').val(idUsuario);    
    $('#EditarnombreUsuario').val(nombreUsuario);    
    $('#Editarestado').val(estado);
    $('#EditartipoUsuario').val(tipoUsuario); 
  
    // Cambiar el nombre del select para que coincida con el ID del HTML
    $('#Editarestado').attr('name', 'estado');
  
  // Crear una opción "Seleccione" si no se ha seleccionado ninguna opción
  if ($('#Editarestado').find('option:selected').length === 0) {
      $('#Editarestado').prepend($('<option>', {
          value: '',
          text: 'Seleccione'
      }));
  }
  
    // Cambiar el nombre del select para que coincida con el ID del HTML
    $('#EditartipoUsuario').attr('name', 'tipoUsuario');
  
  // Crear una opción "Seleccione" si no se ha seleccionado ninguna opción
  if ($('#EditartipoUsuario').find('option:selected').length === 0) {
      $('#EditartipoUsuario').prepend($('<option>', {
          value: '',
          text: 'Seleccione'
      }));
  }
  
  
    $("#modal-editar").modal();
    
  
    
  }
  
  function actualizarUsuario()
  {
    var indexUsuario= $('#indexUsuario').val();
    var idUsuario= $('#idEditarUsuario').val();
    var nombreUsuario= $('#EditarnombreUsuario').val();  
    var selectedEstado = $('#EditartipoUsuario option:selected').text(); 
    var estado = $('#Editarestado').val();
    var tipoUsuario = $('#EditartipoUsuario').val(); 
    var selectedTipoUsuario = $('#EditartipoUsuario option:selected').text(); 
    Swal.fire({
          icon: 'warning',
          title: 'Necesitamos tu Confirmación',
          html: '<div style="text-align: center;">' +
              '<div style="font-weight: bold;">Deseas actualizar el siguiente usuario</div>' +                                  
              '<div class="resultados">Usuario: ' + nombreUsuario + '</div>' +            
              '<div class="resultados">Estado: ' + selectedEstado + '</div>' +  
              '<div class="resultados">Tipo de Usuario: ' + selectedTipoUsuario + '</div>' +            
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
                                  url:'../../Controlador/co_usuarios.php',
                                  data:{updateUsuario:1,idUsuario:idUsuario,nombreUsuario:nombreUsuario,estado:estado,tipoUsuario:tipoUsuario},
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
                                        data.length = 0; // Limpiar el array data
                                        //Recargamos los datos del Array
                                        $.ajax({
                                              url:'../../Controlador/co_usuarios.php',
                                              data:{cargarUsuarios:1},
                                              type: 'GET',
                                              dataType: 'json',
                                              success: function (datosObtenidos) {
                                                  if (datosObtenidos && datosObtenidos.length > 0) {
                                                  Array.prototype.push.apply(data, datosObtenidos);
                                                  loadTable(datosObtenidos);
                                                  } else {
                                                      console.error('Error al obtener Usuarios');
                                                  }
                                              },
                                              error: function (xhr, status, error) {
                                                  console.error('Error en la solicitud AJAX:', error);
                                              }
                                          });
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
function crearUsuario()
{
  var usuario = [];
  var nombreUsuario= $('#nombreUsuario').val();
  var password = $('#password').val();
  var estado = $('#estado').val();
  var tipoUsuario = $('#tipoUsuario').val(); 
  var selectedTipoUsuario = $('#tipoUsuario option:selected').text(); 
   // Verificar si hay al menos un producto en la venta
   if (nombreUsuario.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de usuario no puede estar vacio',
            confirmButtonText: 'OK'
        });
        return; // Detener la ejecución si no hay productos
    }
   if (password.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La contraseña puede estar vacio',
            confirmButtonText: 'OK'
        });
        return; // Detener la ejecución si no hay productos
    }

   

     Swal.fire({
        icon: 'warning',
        title: 'Necesitamos tu Confirmación',
        html: '<div style="text-align: center;">' +
            '<div style="font-weight: bold;">Se creará el usuario siguientes datos:</div>' +            
            '<div class="resultados">Usuario: '+nombreUsuario+'</div>' +
            '<div class="resultados">Tipo: '+selectedTipoUsuario+'</div>' +            
            '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
            '</div>',
        confirmButtonText: 'Si, Crear Usuario',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745', // Color verde
        cancelButtonColor: '#dc3545' // Color rojo
    }).then((result) => {
        if (result.isConfirmed) 
        { 
                          $.ajax({
                                url:'../../Controlador/co_usuarios.php',
                                data:{crearUsuario:1,nombreUsuario:nombreUsuario,password:password,estado:estado,tipoUsuario:tipoUsuario},
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
                                            '</div>',
                                        showCancelButton: false,     
                                        showConfirmButton : true,
                                        allowOutsideClick: true, // No permite clic fuera de la ventana
                                        allowEscapeKey: true,
                                        backdrop: 'static' // Evita el cierre haciendo clic fuera de la ventana
                                      });
                                      usuario.id_usuario = datos.id_usuario;
                                      usuario.u_username = nombreUsuario;
                                      usuario.id_estado = estado;
                                      usuario.tpu_descripcion = selectedTipoUsuario;
                                      data.push(usuario);
                                      loadTable(data);
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