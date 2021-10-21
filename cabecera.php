<div class="cabecera">
  <a href="cerrar_sesion.php"><div  class="cerrar_sesion">Cerrar Sesión<img src="img/ico_cerrar_session.png"></div></a>
  <img src="img/logo.png" class="cabecera_logo">
  <div class="css_usuario">
    <div class="row">
      <div class="col-2 tm_col">
        <?php if( $usuario->picture != null ){ ?>
        <img src="foto_perfil/<?= $usuario->id ?>/<?= $usuario->picture ?>"  class="perfil">
        <?php }else{ ?>
        <img src='img/perfil.png'> 
        <?php } ?>
      </div>
      <div class="col margen_img_usr">
        <div id="nombre_usuario"><a href="#" class="font_usuario"><?=$usuario->fullname?></a></div>
        <div class="font_cabecera">PLATAFORMA - ATENCIÓN AL SOCIO</div>
      </div>
    </div>
  </div>
</div>
<div class="contenedor_menu">
  <div class="center">
  <div class="row tm_menu"> 
    <?php if($home){ ?>
    <div class="col-1"><a href="home.php" class="font_opt_menu"><img src="img/ico_home.png"></a></div>
    <?php } ?>
    <div class="col col-md-3"><a href="lista_activacion_socio.php" class="font_opt_menu" id="act_socio"><img src="img/ico_actividad.png">Activación Socios</a></div>
    <div class="col"><a href="asamblea.php" class="font_opt_menu" id="asamblea"><img src="img/ico_asamblea.png">Asambleas</a></div>
    <div class="col"><a href="#" class="font_opt_menu" id="actividades"><img src="img/ico_socios.png">Actividades</a></div>
    <div class="col"><a href="#" class="font_opt_menu" id="configuracion"><img src="img/ico_config.png">Configuración</a></div>
  </div>
  </div>
</div>
