<?php
$modulo=$_POST['modulo'];
$opcion=$_POST['opcion'];
include("../../../config/conexion.php");
/**Comprobante */    
$sqlTusuario="SELECT id_tipo_usuario,tpu_descripcion FROM usuario_tipo"; 
$queryTusuario=mysqli_query($coni,$sqlTusuario);

$dataTusuario = array();
while($rowTusuario=mysqli_fetch_array($queryTusuario))
{ 
  $dataTusuario[]=$rowTusuario;
}
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
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" id="search-input" autocomplete="off" name="search-input" class="form-control pull-right" placeholder="Buscar usuario">

                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
              </h3>
              <div class="box-tools">                
                <button type="button" class="btn btn-success pull-right" style="margin-left: 10px;" data-toggle="modal" data-target="#modal-default"><i class="fa fa-credit-card"></i> Agregar
                </button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-registro"><i class="fa fa-credit-card"></i> Registro Personal
                </button>               
              </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table id="data-table" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Usuario</th>
                  <th>Estado</th>
                  <th>Tipo</th>
                  <th>Accion</th>
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
    <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-user-plus"></i> Agregar Usuario</h4> <!-- Icono para el título del modal -->
            </div>
            <div class="modal-body">
                <form id="formAgregarUsuario">
                    <div class="form-group">
                        <label for="nombreUsuario"><i class="fa fa-user"></i> Nombre de Usuario:</label> <!-- Icono para la etiqueta de nombre de usuario -->
                        <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="password"><i class="fa fa-lock"></i> Contraseña:</label> <!-- Icono para la etiqueta de contraseña -->
                        <input type="password" class="form-control" id="password" name="password" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="estado"><i class="fa fa-toggle-on"></i> Estado:</label> <!-- Icono para la etiqueta de estado -->
                        <select class="form-control" id="estado" name="estado">
                            <option value="1">Activo</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipoUsuario"><i class="fa fa-users"></i> Tipo de Usuario:</label> <!-- Icono para la etiqueta de tipo de usuario -->
                        <select class="form-control" id="tipoUsuario" name="tipoUsuario">
                            <?php foreach($dataTusuario as $tusuario)
                                            { echo '<option value="'.$tusuario['id_tipo_usuario'].'">'.$tusuario['tpu_descripcion'].'</option>';}
                            ?>
                            <!-- Agrega más opciones según tus necesidades -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button> <!-- Icono para el botón de Cerrar -->
                <button type="button" class="btn btn-primary" onclick="crearUsuario();"><i class="fa fa-save"></i> Guardar</button> <!-- Icono para el botón de Guardar -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="modal-registro" tabindex="-1" role="dialog" aria-labelledby="modalRegistroLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalRegistroLabel"><i class="fa fa-user-plus"></i> Registro </h4>
            </div>
            <div class="modal-body">
                <form id="formReg" class="form-horizontal">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Profesión</label>
                            <div class="col-sm-8">
                                <select class="form-control">
                                    <option selected="selected">Seleccione</option>
                                    <option value="1">Empleado</option>
                                    <option value="2">Enfermero</option>
                                    <option value="3">Ingeniero de Sistemas</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nombres" class="col-sm-4 control-label">Nombres</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombres" placeholder="Nombres">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ap_materno" class="col-sm-4 control-label">Ap. Materno</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="ap_materno" placeholder="Ap. Materno">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Género</label>
                            <div class="col-sm-8">
                                <select class="form-control">
                                    <option selected="selected">Seleccione</option>
                                    <option value="5">F</option>
                                    <option value="6">M</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tipo Doc. Identidad</label>
                            <div class="col-sm-8">
                                <select class="form-control">
                                    <option selected="selected">Seleccione</option>
                                    <option value="7">Carnet de Extranjeria</option>
                                    <option value="8">DNI</option>
                                    <option value="9">Pasaporte</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ap_paterno" class="col-sm-4 control-label">Ap. Paterno</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="ap_paterno" placeholder="Ap. Paterno">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fecha" class="col-sm-4 control-label">Fec. Nac</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="fecha">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Estado</label>
                            <div class="col-sm-8">
                                <select class="form-control">
                                    <option selected="selected">Seleccione</option>
                                    <option value="10">Activo</option>
                                    <option value="11">No Activo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                <button type="button" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>


    <!-- /.MODALS EDICION-->
    <div class="modal fade" id="modal-editar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-pencil"></i> Editar Usuario</h4> <!-- Icono para el título del modal -->
            </div>
            <div class="modal-body">
                <form id="formEditarUsuario">
                    <div class="form-group">      
                        <!-- Icono para la etiqueta de nombre de usuario -->                  
                        <input type="hidden" class="form-control" id="idEditarUsuario" name="idEditarUsuario">
                        <input type="hidden" class="form-control" id="indexUsuario" name="indexUsuario">
                    </div>
                    <div class="form-group">
                        <label for="nombreUsuario"><i class="fa fa-user"></i> Nombre de Usuario:</label> <!-- Icono para la etiqueta de nombre de usuario -->
                        <input type="text" class="form-control" id="EditarnombreUsuario" name="EditarnombreUsuario" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="estado"><i class="fa fa-toggle-on"></i> Estado:</label> <!-- Icono para la etiqueta de estado -->
                        <select class="form-control" id="Editarestado" name="Editarestado">
                            <option value="1">Activo</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipoUsuario"><i class="fa fa-users"></i> Tipo de Usuario:</label> <!-- Icono para la etiqueta de tipo de usuario -->
                        <select class="form-control" id="EditartipoUsuario" name="EditartipoUsuario">
                            <?php foreach($dataTusuario as $tusuario)
                                            { echo '<option value="'.$tusuario['id_tipo_usuario'].'">'.$tusuario['tpu_descripcion'].'</option>';}
                            ?>
                            <!-- Agrega más opciones según tus necesidades -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button> <!-- Icono para el botón de Cerrar -->
                <button type="button" class="btn btn-primary" onclick="actualizarUsuario();"><i class="fa fa-save"></i> Guardar</button> <!-- Icono para el botón de Guardar -->
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->




<script>
  var data = [];
  var filteredData = [];
  // Inicializar la tabla con los datos iniciales
  $(document).ready(function() {        
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
        
      // Evento keyup para el input de búsqueda
    $('#search-input').keyup(function() {
        var searchText = $(this).val().toLowerCase(); // Obtener el texto de búsqueda y convertirlo a minúsculas
        var filteredData = data.filter(function(item) {
            return item.u_username.toLowerCase().indexOf(searchText) !== -1; // Filtrar los datos en base al nombre de usuario
        });
        loadTable(filteredData); // Cargar la tabla con los datos filtrados*/
        
    });
        
    });


// Función para actualizar la tabla de venta
/*function actualizarTabla() {
    var tbody = $('#data-table tbody');
    tbody.empty();
    
    

    $.each(data, function(index, item) {
      var estadoLabel;
        if (item.id_estado == 1) 
        {
            estadoLabel = '<span class="label label-success">Activo</span>';
        } else 
        {
            estadoLabel = '<span class="label label-warning">Desactivado</span>';
        }

        var row = '<tr>' +
        '<td>' + item.id_usuario + '</td>' +
        '<td class="usuario">' + item.u_username + '</td>' +
        '<td>' + estadoLabel + '</td>' +
        '<td>' + item.tpu_descripcion + '</td>' +        
        '<td><button class="btn btn-primary btn-small editar-usuario" data-index="' + index + '"><i class="fa fa-pencil"></i></button>'+ 
        '<button class="btn btn-danger btn-small eliminar-usuario" data-index="' + index + '"><i class="fa fa-trash"></i></button>'+
        '</td>'+        
        '</tr>';

        tbody.append(row);
    });
     // Asignar el manejador de clic para los botones "AGREGAR A VENTA"
     $('.editar-usuario').click(function() {
        /*var index = $(this).data('index');
        agregarAVenta(dataToLoad[index]);*/        
    /*});

    // Asignar el manejador de clic para los botones "AGREGAR A VENTA"
    $('.eliminar-usuario').click(function() {
        var index = $(this).data('index');
        eliminarProductoVenta(data[index]);        
    });
}*/


</script>