<?php
require_once 'config/database.php';

session_name("loyola");
session_start();

if (!isset($_SESSION['usuario'])) {
  header('Location: index.php');
  die();
}
$usuario = $_SESSION['usuario'];

if (isset($_GET['id_curso'])) {
$curso_edit = ORM::for_table('course')
        ->where("id", $_GET["id_curso"])
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
    <link href="css/style.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    
    <script src="js/libs/jquery-3.3.1.min.js"></script>     
    <script src="bootstrap/js/bootstrap.min.js"></script>   
    <script type="text/javascript" src="js/libs/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="js/libs/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
    <script src="js/libs/dropzone.js"></script>
    <script src="js/registro_curso.js"></script>

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
      <input type="hidden" id="id_curso" value="<?=$_GET['id_curso']?>">
      <input type="hidden" id="curso" value="<?=isset($curso_edit)?$curso_edit->name:""?>">
      <div class="fondo_app">  
        <div class="container">
          <div class="espacio_contenedor"></div>
          <div class="card bg-ligth">
            <div class="card-body">
              <div class="center color_datos"><h4><?=isset($curso_edit)?"Registro Curso":"Información del Curso"?></h4></div>  
              <div class="margen"></div>
              <div class="row">
                
              <?php if(isset($curso_edit) && $curso_edit->photo){ ?>
              <div class="col-4 contiene_foto">
                <input type="hidden" id="foto" value="<?=$curso_edit->photo?>">
                <img src="img/ico_eliminar.png" class="cursor" id="btn_eliminar_foto">
                <img src="uploads/curso/<?=$curso_edit->id."/".$curso_edit->photo?>" class="css_foto">
              </div>
              <?php }?>
                
              <div class="col-3">
                <form action="services/photoupload.php" class="dropzone" id="my-dropzone" method="POST"></form>
              </div>
              </div>
              <div class="margen"></div>
              <div class="color_datos"><strong>Datos del Curso:</strong></div>
              <div class="card bg_green">
                <div class="card-body">
                  <div class="row">
                    <div class="col-6">
                      <div class="desc_datos left">Titulo Curso:</div> 
                      <input id="nombre_curso" class="form-control" <?= isset($curso_edit) ? "value='" .$curso_edit->name. "'" : "" ?>>
                    </div>
                    <div class="col-6">
                      <div class="desc_datos left">Horario:</div>
                      <input type="text" id="horario" class="form-control" <?=isset($curso_edit)? "value='" .$curso_edit->schedule. "'" : ""?>>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-1">
                      <label class="desc_datos">Expositor:</label>
                    </div>
                    <div class="col">
                      <input type="text" id="expositor" class="form-control" <?=isset($curso_edit)? "value='" .$curso_edit->expositor. "'" : ""?>> 
                    </div>
                  </div>
                  <br>
                  <div class="row"> 
                    <div class="col-6">
                      <div class="desc_datos left">Fecha Inicio: </div>
                      <div class="form-group">
                        <div class="input-group date form_datetime css_input"  data-date-format="yyyy-mm-dd hh:ii" >
                          <input id="fecha_inicio" class="form-control" size="16" type="text" readonly 
                                 <?= isset($curso_edit) ? "value='" .$curso_edit->start_date. "'" : "" ?>/>
                          <span class="input-group-addon"><span class="css_remove glyphicon-remove"><img src="img/ico_remove_datatime.png"></span></span>
                          <span class="input-group-addon"><span class="glyphicon-th"><img src="img/ico_datetime.png"></span></span>
                        </div>
                        <input type="hidden" id="dtp_input1" value="" />
                      </div>            
                      <div class="clearfix"></div>
                    </div>
                    <div class="col-6">
                      <div class="desc_datos left">Fecha Fin: </div>
                      <div class="form-group">
                        <div class="input-group date form_datetime css_input"  data-date-format="yyyy-mm-dd hh:ii" >
                          <input id="fecha_fin" class="form-control" size="16" type="text" readonly 
                                 <?= isset($curso_edit) ? "value='" .$curso_edit->end_date. "'" : "" ?>/>
                          <span class="input-group-addon"><span class="css_remove glyphicon-remove"><img src="img/ico_remove_datatime.png"></span></span>
                          <span class="input-group-addon"><span class="glyphicon-th"><img src="img/ico_datetime.png"></span></span>
                        </div>
                        <input type="hidden" id="dtp_input2" value="" />
                      </div>            
                      <div class="clearfix"></div>
                    </div>
                  </div>      
                  <br>
                  <div class="form-group row">
                    <div class="col-lg">
                      <span class="margen_desc desc_datos">Modalidad:</span>
                      <strong class="desc_datos"> Virtual</strong> <input id="virtual" type="radio" name="radio" value="0" class="radio" <?=isset($curso_edit)?($curso_edit->type == "Virtual")?'checked="checked"':'':''?>/>
                      <strong class="desc_datos margen_left">Presencial </strong><input id="presencial" type="radio" name="radio" value="1" class="radio" <?=isset($curso_edit)?($curso_edit->type == "Presencial")?'checked="checked"':'':''?>/>
                    </div>
                  </div>
                  <div class="css_espacio_form"></div>
                  <div id="datos_curso" <?=isset($curso_edit)?($curso_edit->type == "Virtual")?'style="display: block"':'style="display: none"':'style="display: none"'?>>
                    <div class="card bg_url">
                      <div class="card-body">
                        <div class="color_datos"><?=isset($curso_edit)?'Información del curso:':'Completa la siguiente información:'?></div>
                        <div class="margen"></div>
                        <div class="desc_zoom left">URL:</div>
                        <input type="text" id="url_curso" class="form-control tm_input color_datos" <?=(isset($curso_edit)?"value='" .$curso_edit->url. "'" : "")?>>
                        <div class="css_espacio_form"></div>
                        <span class="desc_zoom left">Código: </span>
                        <input type="text" id="codigo" class="form-control color_datos tm_input" <?=(isset($curso_edit)?"value='" .$curso_edit->code. "'" : "")?>>
                        <div class="css_espacio_form"></div>
                        <span class="desc_zoom left">Contraseña: </span>
                        <input type="text" id="password" class="form-control color_datos tm_input" <?=(isset($curso_edit)?"value='" .$curso_edit->password. "'" : "")?>>
                      </div> 
                    </div>
                  </div>
                  <div id="lugar_curso" <?=isset($curso_edit)?($curso_edit->type == "Presencial")?'style="display: block"':'style="display: none"':'style="display: none"'?>>
                    <label class="desc_datos">Dirección y Lugar:</label>
                    <input type="text" id="lugar" class="form-control"<?=(isset($curso_edit)?"value='" .$curso_edit->location. "'" : "")?>>
                  </div>
                  <div class="margen"></div>
                  <label class="desc_datos">Documento del curso ( subir en Formato PDF ):</label>
                  <?php if(isset($curso_edit) && $curso_edit->document != null){   ?>
                  <div class="contenedor_doc">
                    <input type="hidden" id="documento" value="<?=$curso_edit->document?>">
                    <img src="img/ico_eliminar.png" class="cursor" id="btn_eliminar_documento">
                    <a href="<?='uploads/curso/'.$curso_edit->id.'/'.$curso_edit->document?>" target="_blank"><?=$curso_edit->document?></a>
                  </div>
                  <?php } ?>
                  <div class="margen"></div>
                  <div class="div_dropzone_doc">
                    <form action="services/photoupload.php" class="dropzone" id="my-dropzone-d" method="POST"></form>
                  </div>
                  <br>
                  <label class="left">Estado:</label>
                  <select id="estado" class="form-control tm_estado">
                    <option value="activo" <?=isset($curso_edit)?$curso_edit->status=="activo"?"selected":"":""?>>Activo</option>
                    <option value="inactivo" <?=isset($curso_edit)?$curso_edit->status=="inactivo"?"selected":"":""?>>Inactivo</option>
                  </select>
                </div>
              </div>        
              <div class="margen"></div>
              <div class="center">
                <div class="btns">
                  <?php if(isset($curso_edit)){ ?>
                  <button id="btn_actualizar" class="btn btn-success btns_tamano">Actualizar</button>
                  <button id="btn_Eliminar" class="btn btn-success btns_tamano">Eliminar</button>
                  <?php } else { ?>
                  <div id="btn_guardar" class="btn btn-success btn_volver">Guardar</div>
                  <?php } ?>
                  <a href="curso.php"><div class="btn btn-success btn_volver">Volver</div></a>
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
    </div>
  </body>
</html>
