<img src="fondo_login_1.jpg" alt="" class="img-responsive" style="width: 100%;height: 100%;object-fit: cover;position: absolute;top: 0;left: 0;z-index: -1;">
<div class="login-box" style="width: 500px; margin: auto; background: rgba(255, 255, 255, 0.8); border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
  <div class="login-box-body text-center">
    <img src="divino.png" alt="" class="img-responsive" style="max-width: 100%; margin-bottom: 20px;">
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
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
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