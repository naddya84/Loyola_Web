<?php 
  session_name("loyola");
  session_start();

  unset($_SESSION['usuario']);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/logo.png" />
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">  
    <link href="css/index.css?v=1.3" media="screen" rel="stylesheet" type="text/css" />

    <script src="js/libs/jquery-3.3.1.min.js"></script>    
    <script src="bootstrap/js/bootstrap.min.js"></script>            
    <script src="js/loguin.js"></script>

    <script type="text/javascript">
      $(document).ready(function () {        
        iniciar();                                    
      });            
    </script>
    <title>Cooperativa LOYOLA</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="center">
        <div class="contenedor_datos">
          <div class="center"><img src="img/logo.png" class="img_logo"></div>
          <br>
          <div class="center">
            <div class="datos_formulario">  
              <div class="datos_fom">
                <img src="img/ico_usr.png" class="left">
                <input id="usuario" type="text" placeholder="Usuario" class="css_input" autocomplete="off">
              </div>
              <div class="datos_fom">
                <img src="img/ico_password.png" class="left">
                <input id="clave" type="password" placeholder="Clave" class="css_input" autocomplete="off">
              </div>
              <div class="clearfix"></div>
              <div id="btn_ingresar" class="center"><div class="left">ENTRAR</div><img src="img/btn_entrar.png"></div>
            </div>
          </div>
        </div>
      </div>
      
      <!--Ventana emergente contacto -->
      <div id="fondo_pop" class="popup-overlay"></div>
      <div id="mensaje_form" class="popup" >
        <div class="content-popup">
          <div id="btn_cerrar"></div>
          <div>
            <div id="texto_mensaje"> </div>
          </div>
        </div>
      </div>
    </div>
    <div id="div_cargando" class="fondo_block">
      <img src="img/cargando.gif" class="img_cargando">
    </div>
  </body>
</html>