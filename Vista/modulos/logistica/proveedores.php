<?php
$modulo = $_POST['modulo'];
$opcion = $_POST['opcion'];
include("../../../config/conexion.php");
// Consulta para obtener tipos de usuario
$sqlTusuario = "SELECT id_tipo_usuario, tpu_descripcion FROM usuario_tipo";
$queryTusuario = mysqli_query($coni, $sqlTusuario);

// Inicialización del array para almacenar los resultados
$dataTusuario = array();

// Procesamiento de los resultados de la consulta
while ($rowTusuario = mysqli_fetch_array($queryTusuario)) {
    $dataTusuario[] = $rowTusuario;
}

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
            <div class="contenedor-busqueda">
                        <div class="input-group-search">
                            <select class="selectpicker show-tick" data-style="btn-select" data-width="70px" id="selectnum" name="selectnum" onchange="loadClientes(1)">
                                <option value="5">5</option>
                                <option value="10">10</option>               
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                                <div class="input-search"> 
                                <input type="search" class="search" id="search" name="search" placeholder="Buscar..." onkeyup="loadClientes(1)" style="width: 100%;">
                                <span class="input-group-addo icon-search"><i class="fa fa-search"></i></span> 
                                </div>
                        </div>
                    </div>
            
            <h3 class="box-title">
            <button class="btn btn-success  pull-right btn-radius" data-toggle="modal" data-target="#modalAgregarCliente"><i class="fas fa-plus-square"></i>Nuevo cliente <i class="fa fa-th"></i>            
            </button>
            </h3>
            <div class="box-tools">                
            </div>
            <!-- /.box-header -->
             <input type="hidden" id="perfilOcultoc" value="Administrador">
             <div class="table-responsive">
                    <table class="table  dt-responsive tabla-clientes tbl-t" width="100%">

                    <thead>
                    <tr>
                        <th style="width:10px;">#</th>
                        <th>RUC</th>
                        <th>Razon Social</th>
                        <th>Direccion</th>
                        <th>Contacto</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th width="100px">Acciones</th>
                        </tr>
                    </thead>                    
                    
                        <?php
                        echo "<tbody class='body-clientes'></tbody>";

                        ?>
                
                </table>
                
            </div>


            <div class="box-body table-responsive no-padding">
                <!-- los table iban aqui -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>
    </section>
    <!-- /.content -->

            <!-- MODAL AGREGAR CLIENTE-->
  <!-- Modal -->
  <div id="modalAgregarCliente" class="modal fade modal-forms fullscreen-modal in" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">      

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">AGREGAR PROOVEDOR</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
              <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control " name="nuevoRazonSocial" id="nuevoRazonSocial" placeholder="Ingresar razon social" required>

              </div>

            </div>
            <!-- ENTRADA PARA EL RUC -->
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-address-card"></i></span> 

                <input type="number" maxlength="12" class="form-control " name="nuevoRuc" id="nuevoRuc" placeholder="Ingresar R.U.C." required>

              </div>

            </div>

          <!-- ENTRADA PARA EL DIRECCIÓN -->
          <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span> 

                <input type="text"  class="form-control " name="nuevaDireccion" id="nuevaDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>
           <!-- ENTRADA PARA EL NOMBRE -->
           <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control " name="nuevoContacto" id="nuevoContacto" placeholder="Ingresar contacto principal" required>

              </div>

            </div>

          <!-- ENTRADA PARA EL TELÉFONO -->
          <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-phone"></i></span> 

                <input type="text"  class="form-control " name="nuevoTelefono" id="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask='"mask": "999999999"' data-mask>

              </div>

            </div>

           <!-- ENTRADA PARA EL NOMBRE -->
           <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control " name="nuevoContacto2" id="nuevoContacto2" placeholder="Ingresar contacto secundario">

              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->
          <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-phone"></i></span> 

                <input type="text"  class="form-control " name="nuevoTelefono2" id="nuevoTelefono2" placeholder="Ingresar teléfono secundario" data-inputmask='"mask": "999999999"' data-mask>

              </div>

            </div>
            <!-- ENTRADA PARA EL EMAIL -->
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-at"></i></span> 

                <input type="email"  class="form-control " name="nuevoEmail" id="nuevoEmail" placeholder="Ingresar email Principal">

              </div>

            </div>
            <!-- ENTRADA PARA EL EMAIL -->
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-at"></i></span> 

                <input type="email"  class="form-control " name="nuevoEmail2" id="nuevoEmail2" placeholder="Ingresar email Secundario">

              </div>

            </div>

          <!--=====================================-->
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="fa fa-times fa-lg"></i> Salir</button>

          <button type="submit" class="btn btn-primary" onclick="crearProovedor();"><i class="fa fa-save"></i> Guardar cliente</button>

        </div>

</div>
  </div>
</div>

<script>
function loadClientes(page){
        let search= $("#search").val();
        let selectnum= $("#selectnum").val();
        let parametros={"action":"ajax","page":page,"search":search,"selectnum":selectnum, "dc":"dc"};
   
    $.ajax({
        url: '../tables/dataTables.php',        
        data: parametros,  
        beforeSend: function(){
            //  $("body").append(loadcl);
        },   
        success:function(data){           
          
                /*$(".reloadcl").hide();*/
                $('.body-clientes').html(data); 
                /*console.log(data);*/
             
             
        }
    })
};

loadClientes(1);


function crearProovedor()
{ 
  var razonSocial= $('#nuevoRazonSocial').val();
  var ruc= $('#nuevoRuc').val();
  var direccion= $('#nuevaDireccion').val();
  var contacto= $('#nuevoContacto').val();
  var telefono= $('#nuevoTelefono').val();
  var contacto2= $('#nuevoContacto2').val();
  var telefono2= $('#nuevoTelefono2').val();
  var email= $('#nuevoEmail').val();
  var email2= $('#nuevoEmail2').val();  
   // Verificar si hay al menos un producto en la venta
   if (razonSocial.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La razon social no puede estar vacio',
            confirmButtonText: 'OK'
        });
        return; // Detener la ejecución si no hay productos
    }
   if (ruc.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El ruc no puede estar vacio',
            confirmButtonText: 'OK'
        });
        return; // Detener la ejecución si no hay productos
    }

   

     Swal.fire({
        icon: 'warning',
        title: 'Necesitamos tu Confirmación',
        html: '<div style="text-align: center;">' +
            '<div style="font-weight: bold;">Se creará el proovedor con los siguientes datos:</div>' +            
            '<div class="resultados">Proovedor: '+razonSocial+'</div>' +
            '<div class="resultados">Tipo: '+ruc+'</div>' +            
            '<span style="font-size: 18px;color:#4caf50!important">¿Está Usted de Acuerdo?</span>'+
            '</div>',
        confirmButtonText: 'Si, Crear Proovedor',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#28a745', // Color verde
        cancelButtonColor: '#dc3545' // Color rojo
    }).then((result) => {
        if (result.isConfirmed) 
        { 
                          $.ajax({
                                url:'../../Controlador/co_logistica.php',
                                data:{crearProovedor:1,razonSocial:razonSocial,ruc:ruc,direccion:direccion,contacto:contacto,telefono:telefono,contacto2:contacto2,telefono2:telefono2,email:email,email2:email2},
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
                                      loadClientes(1);
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
</script>
