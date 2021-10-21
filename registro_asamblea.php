<?php
require_once 'config/database.php';

session_name("loyola");
session_start();

if (!isset($_SESSION['usuario'])) {
  header('Location: index.php');
  die();
}
$usuario = $_SESSION['usuario'];

if (isset($_GET['id_asamblea'])) {
$asamblea_edit = ORM::for_table('assembly')
        ->where("id", $_GET["id_asamblea"])
        ->find_one();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/logo.png" />
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="css/jquery-ui.css" media="screen" rel="stylesheet" type="text/css" >
    <link href="css/dropzone.css" rel="stylesheet"> 
    <link href="css/style.css?v=1.3" media="screen" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    
    <script src="js/libs/jquery-3.3.1.min.js"></script>     
    <script src="bootstrap/js/bootstrap.min.js"></script>   
    <script type="text/javascript" src="js/libs/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="js/libs/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
    <script src="js/libs/dropzone.js"></script>
    <script src="js/ckeditor/ckeditor.js"></script>
    <script src="js/registro_asamblea.js?v=1"></script>

    <script type="text/javascript">
      $(document).ready(function () {        
        iniciar();
        CKEDITOR.replace('editor');
      });            
    </script>
    <title>Cooperativa LOYOLA</title>
  </head>
  <body>
    <div class="container-fluid">
      <?php $home = true;
      include ("cabecera.php"); ?>
      <input type="hidden" id="id_asamblea" value="<?=$_GET['id_asamblea']?>">
      <input type="hidden" id="asamblea" value="<?=isset($asamblea_edit)?$asamblea_edit->name:""?>">
      <div class="fondo_app">  
        <div class="container">
          <div class="espacio_contenedor"></div>
          <div class="card bg-ligth">
            <div class="card-body">
              <?php if(isset($asamblea_edit)){ ?>
              <div class="center color_datos"><h4>Información de la Asamblea</h4></div>
              <?php } else { ?>
              <div class="center color_datos"><h4>Registro Asamblea</h4></div>  
              <?php } ?>
              <div class="margen"></div>
              <div class="desc_datos">Titulo Asamblea:</div> 
              <input type="text" id="nombre_asamblea" class="color_datos form-control" <?= isset($asamblea_edit) ? "value='" .$asamblea_edit->name. "'" : "" ?>>
              <div class="css_espacio_form"></div>
              <div class="row"> 
                <div class="col-6">
                  <div class="desc_datos left">Fecha: </div>
                  <div class="form-group">
                    <div class="input-group date form_datetime css_input"  data-date-format="yyyy-mm-dd hh:ii" >
                      <input id="fecha_asamblea" class="form-control" size="16" type="text" readonly 
                             <?= isset($asamblea_edit) ? "value='" .$asamblea_edit->datetime. "'" : "" ?>/>
                      <span class="input-group-addon"><span class="css_remove glyphicon-remove"><img src="img/ico_remove_datatime.png"></span></span>
                      <span class="input-group-addon"><span class="glyphicon-th"><img src="img/ico_datetime.png"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input1" value="" />
                  </div>            
                  <div class="clearfix"></div>
                </div>
              </div>              
              <div class="css_espacio_form"></div>
              <div class="desc_datos">Datos Zoom</div>
              <div class="margen"></div>
              <div class="card bg-ligth">
                <div class="card-body">
                  <span class="desc_zoom left">Código Zoom: </span>
                  <input type="text" id="codigo_zoom" class="form-control color_datos tm_input_zoom" value="<?=isset($asamblea_edit)?$asamblea_edit->zoom_code:''?>">
                  <div class="css_espacio_form"></div>
                  <span class="desc_zoom left">Contraseña Zoom: </span>
                  <input type="text" id="password_zoom" class="form-control color_datos tm_input_zoom" value="<?=isset($asamblea_edit)?$asamblea_edit->zoom_password:''?>">
                </div>
              </div>
              <br>
              <div class="descripcion">Orden del Día </div>
              <div class="margen"></div>
              <textarea name="editor" id="doc_jornada"><?=isset($asamblea_edit->journey)?$asamblea_edit->journey:""?></textarea>
            
              <div class="margen"></div>
              <div class="descripcion">Memoria </div>
              <?php if(isset($asamblea_edit)){ 
                if($asamblea_edit->memory != null){ 
                  $url_memoria = substr($asamblea_edit->memory , 3);
                  $nombre_doc = explode("/", $asamblea_edit->memory);
                ?>
              <div class="cont_doc_memoria">
                <input type="hidden" id="url_memory" value="<?=$asamblea_edit->memory?>">
                <img src="img/ico_eliminar.png" class="cursor left" id="eliminar_memoria" value="<?=$asamblea_edit->memory ?>">
                <a href="<?=$url_memoria?>" target="_blank"><?= $nombre_doc[4] ?></a>
              </div>
              <div class="margen"></div>
              <?php }
              } ?>
              
              <div class="margen"></div>
              <div id="doc_memoria">
                <div class="div_dropzone">
                  <form action="services/photoupload.php" class="dropzone" id="my-dropzone-d" method="POST"></form>
                </div>
              </div>
              <br>
               <?php if(isset($asamblea_edit)){ 
                if($asamblea_edit->statemts	 != null){ 
                  $url_declaracion = substr($asamblea_edit->statemts, 3);
                  $nombre_doc = explode("/", $asamblea_edit->statemts);
              ?>
              <div class="cont_doc_declaracion">
                <input type="hidden" id="url_dec" value="<?=$asamblea_edit->statemts?>">
                <img src="img/ico_eliminar.png" class="cursor left" id="eliminar_declaracion" value="<?=$asamblea_edit->statemts ?>">
                <a href="<?=$url_declaracion?>" target="_blank"><?= $nombre_doc[4] ?></a>
              </div>
              <div class="margen"></div>
              <?php }
              } ?>
              <div class="descripcion">Estado de Resultados</div>
              <div class="margen"></div>
              <div id="doc_declaracion">
                <div class="div_dropzone">
                  <form action="services/photoupload.php" class="dropzone" id="my-dropzone-doc" method="POST"></form>
                </div>
              </div>
                        
            <div class="margen"></div>
            <div class="center">
              <div class="btns">
                <?php if(isset($asamblea_edit)){ ?>
                <button id="btn_actualizar" class="btn btn-success btns_tamano">Actualizar</button>
                <button id="btn_Eliminar" class="btn btn-success btns_tamano">Eliminar</button>
                <?php } else { ?>
                <div id="btn_guardar" class="btn btn-success btn_volver">Guardar</div>
                <?php } ?>
                <a href="asamblea.php"><div class="btn btn-success btn_volver">Volver</div></a>
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
