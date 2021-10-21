<?php
require_once 'config/database.php';

session_name("loyola");
session_start();

if (!isset($_SESSION['usuario'])) {
  header('Location: index.php');
  die();
}
$usuario = $_SESSION['usuario'];

if (isset($_GET['id_socio'])) {
  $socio = ORM::for_table('user')
          ->where("id", $_GET["id_socio"])
          ->find_one();
  if ($socio == null) {
    die("No se encontro el socio");
  }
}
$socio_obs = ORM::for_table('activate_user')
        ->where("user_id", $_GET["id_socio"])
        ->order_by_desc("created_at")
        ->find_one();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/logo.png" />
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">  
    <link href="css/style.css?v=1.3" media="screen" rel="stylesheet" type="text/css" />

    <script src="js/libs/jquery-3.3.1.min.js"></script>     
    <script src="bootstrap/js/bootstrap.min.js"></script>            
    <script src="js/detalle_activacion_socio.js"></script>

    <script type="text/javascript">
      $(document).ready(function () {        
        iniciar();                                    
      });            
    </script>
    <title>Cooperativa LOYOLA</title>
  </head>
  <body>
    <div class="container-fluid">
      <?php $home = true;
      include ("cabecera.php"); ?>
      <input type="hidden" id="id_socio" value="<?=$_GET['id_socio']?>">
      <input type="hidden" id="socio" value="<?=$socio->names.' '.$socio->last_name_1.' '.$socio->last_name_2 ?>">
      <div class="fondo_app">  
        <div class="container">
          <div class="espacio_contenedor"></div>
          <div class="card bg-ligth">
            <div class="card-body">
              <div class="center color_datos"><h4>Datos Del Socio</h4></div>
              <div class="row">
                <?php if($socio->picture != null){
                  $foto = "<img src='uploads/$socio->id/$socio->picture' class='marco_perfil'>";
                } else {
                  $foto ="<img src= 'img/socio.png' class='marco_perfil'>";
                } ?>
                <div class="col-3 tm_foto_socio"><?=$foto?></div>
                <div class="col">
                  <div class="margen"></div>
                  <div><span class="descripcion">Socio: </span><span class="color_datos"> <?=$socio->names." ".$socio->last_name_1." ".$socio->last_name_2 ?></span></div>
                  <div><span class="descripcion">Fecha de Nacimiento: </span><span class="color_datos"> <?=$socio->birthday ?></span></div>
                  <div><span class="descripcion">CI: </span><span class="color_datos"> <?=$socio->id_number." ".$socio->id_extension ?></span></div>	
                  <div><span class="descripcion">Email: </span><span class="color_datos"><?=$socio->email ?></span></div>
                  <div><span class="descripcion">Número de Socio: </span><span class="color_datos"><?=$socio->id_member?></span></div>
                  <div><span class="descripcion">Código de Verificación: </span><span class="color_datos"><?=$socio->verification_code?></span></div>
                </div>
              </div>
              <br>
              <h6 class="descripcion">Foto Carnet de Identidad</h6>
              <div class="card">
                <div class="card-body">
                  <?php if($socio->picture_id_1 != null || $socio->picture_id_2){ ?>
                  <div class="row">
                    <div class="col-md">
                      <a href="uploads/<?=$socio->id."/".$socio->picture_id_1?>" target="_blank"><img src='uploads/<?=$socio->id."/".$socio->picture_id_1?>' class="tm_fotos"></a>
                    </div>
                    <div class="col-md">
                      <a href="uploads/<?=$socio->id."/".$socio->picture_id_2?>" target="_blank"><img src='uploads/<?=$socio->id."/".$socio->picture_id_2?>' class="tm_fotos"></a>
                    </div>
                  </div>
                  <?php } else { ?>
                  <div class="css_advertencia">Sin registro de fotos del documento de identidad</div>
                  <?php } ?>
                </div>
              </div>
              <div class="margen"></div>
              <h6 class="descripcion">Foto Selfie</h6>
              <div class="card">
                <div class="card-body">
                <?php if($socio->selfie != null){ ?>  
                  <a href="uploads/<?=$socio->id."/".$socio->selfie?>" target="_blank"><img src='uploads/<?=$socio->id."/".$socio->selfie?>' class="tm_fotos"></a>
                <?php } else { ?>
                  <div class="css_advertencia">Sin registro foto selfie</div>
                <?php } ?>
                </div>
              </div>
              <br>
              <div><span class="descripcion">Estado Actual: </span><span class="color_datos"><?=($socio->state == "inactivo")?'Rechazado':$socio->state ?></span></div>
              <?php if( $socio_obs != null && $socio->state == "inactivo" ){ ?>
              <div class="descripcion">Ultima Observación: <span class="color_datos"><?=$socio_obs->observations ?></span></div>
              <?php }?>
              <div class="descripcion">Observaciones:</div>
              <textarea class="fondo_textarea color_datos" id="observacion"><?=($socio_obs != null && $socio_obs->status == "activo")?$socio_obs->observations:''?></textarea>
            </div>
            
            <input type="hidden" id="estado_socio" value="<?=$socio->state?>">
            <div class="center">
              <div class="btns">
              <?php if($socio->state == "para verificacion" || $socio->state == "inactivo"){ ?>
                <button id="btn_activar" class="btn btn-success btns_tamano">Activar</button>
              <?php } 
              if($socio->state == "activo" || $socio->state == "para verificacion"){ ?>
                <button id="btn_rechazar" class="btn btn-success btns_tamano">Rechazado</button>
                <?php } ?>
                <button id="btn_eliminar_socio" class="btn btn-success btns_tamano">Eliminar</button>
                <button class="btn btn-success btn_volver btns_tamano">Volver</button>
              </div>
            </div>
            <div class="margen"></div>
          </div>
        </div>  
      </div>  
      <!--Ventana emergente contacto -->
      <div id="fondo_pop" class="popup-overlay"></div>
      <div id="mensaje_form" class="popup" >
        <div class="content-popup">
          <div>
            <div id="btn_cerrar"></div>
            <div id="texto_mensaje"> </div>
          </div>
          <div class="margen"></div>
          <div class="center">
            <div class="btn_confirmacion" style="display:none">
              <button id="btn_confirmar" class="btn btn-success btns_tamano">Aceptar</button>
              <button id="btn_cancelar" class="btn btn-dark btns_tamano">Cancelar</button>
            </div>
            <div class="confirmar_eliminacion" style="display:none">
              <button id="btn_confirmar_eliminacion" class="btn btn-success btns_tamano">Aceptar</button>
              <button id="btn_cancelar_eliminacion" class="btn btn-dark btns_tamano">Cancelar</button>
            </div>
          </div>
          <div class="margen"></div>
        </div>
      </div>
      <div id="div_cargando" class="fondo_block">
      <img src="img/cargando.gif" class="img_cargando">
    </div>
    </div>
  </body>
</html>
