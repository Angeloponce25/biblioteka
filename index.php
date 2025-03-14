<?php
require "config/variablesGlobales.php";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $empresa ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $ruta_raiz ?>Vista/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $ruta_raiz ?>Vista/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $ruta_raiz ?>Vista/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $ruta_raiz ?>Vista/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $ruta_raiz ?>Vista/dist/css/skins/_all-skins.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo $ruta_raiz ?>Vista/bower_components/datatables.net-bs/css/responsive.bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo $ruta_raiz ?>Vista/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

  <!-- FullCalendar -->
  <link rel="stylesheet" href="<?php echo $ruta_raiz ?>Vista/bower_components/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="<?php echo $ruta_raiz ?>Vista/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">

   <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
    .password-toggle {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini login-page">

<img src="<?php echo $empresa_fondo ?>" alt="" class="img-responsive" style="width: 100%;height: 100%;object-fit: cover;position: absolute;top: 0;left: 0;z-index: -1;">
<div class="login-box" style="margin: auto; background: rgba(255, 255, 255, 0.8); border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
  <div class="login-box-body text-center">
    <img src="<?php echo $empresa_logo ?>" alt="" class="img-responsive" style="max-width: 100%; margin-bottom: 20px;">
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <form onSubmit="iniciarSesion(); return false;">

      <div class="form-group has-feedback">
        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" style="border-radius: 4px;">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input type="password" class="form-control" id="clave" name="clave" placeholder="Contraseña" style="border-radius: 4px;">
        <span class="glyphicon glyphicon-eye-open password-toggle" onclick="togglePasswordVisibility()"></span>
      </div>
        <div class="text-right" style="margin-top: 10px;">
          <div id="resultado" align="center"></div>
          <button type="submit" class="btn btn-link btn-flat" style="text-decoration: none; color: #333;">¿Olvidó su contraseña?</button>
          <p style="text-decoration: none; color: #333; margin-left: 10px;">¿Necesita ayuda? Consulte con nuestro<a href="#" style="text-decoration: none; color: #333; margin-left: 10px;" onmouseover="this.style.color='#007BFF'" onmouseout="this.style.color='#333'"> Soporte técnico</a></p>
        </div>
      </div>

      <div class="row">
       
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
        </div>
        <!-- /.col -->
      </div>

      <div class="text-center" style="margin-top: 10px;">
        <p style="color: #333; font-size: 14px;">© 2024 Todos los Derechos Reservados</p>
      </div>

    </form>

  </div>
  <!-- /.login-box-body -->
</div>

<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?php echo $ruta_raiz ?>Vista/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo $ruta_raiz ?>Vista/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo $ruta_raiz ?>Vista/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $ruta_raiz ?>Vista/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $ruta_raiz ?>Vista/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $ruta_raiz ?>Vista/js/sesion.js"></script>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
  function togglePasswordVisibility() {
    const passwordInput = document.getElementById('clave');
    const passwordToggle = document.querySelector('.password-toggle');
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      passwordToggle.classList.remove('glyphicon-eye-open');
      passwordToggle.classList.add('glyphicon-eye-close');
    } else {
      passwordInput.type = 'password';
      passwordToggle.classList.remove('glyphicon-eye-close');
      passwordToggle.classList.add('glyphicon-eye-open');
    }
  }
</script>
</body>
</html>

