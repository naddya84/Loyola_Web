<?php
require_once 'config/database.php';

session_name("loyola");
session_start();

if (!isset($_SESSION['usuario'])) {
  header('Location: index.php');
  die();
}
$usuario = $_SESSION['usuario'];

$texto="";
$where_texto = "";
if( isset( $_GET['texto'] ) ){
  $texto = $_GET['texto'];
}

$pagina_actual = 0;
if( isset($_GET["pagina_actual"]) ){
  $pagina_actual = $_GET["pagina_actual"];
}
$items_x_pagina = 7;

$total_items = ORM::for_table('course')
        ->raw_query(
        " SELECT count(id) total ".
        " FROM course ".
        " WHERE deleted_at IS NULL AND LOWER(name) LIKE LOWER('%$texto%')")
        ->find_one();

$total_items = $total_items->total;

$cursos = ORM::for_table('course')
        ->raw_query(
        " SELECT * ".
        " FROM course ".
        " WHERE deleted_at IS NULL AND LOWER(name) LIKE LOWER('%$texto%')".
        " ORDER BY created_at desc ".
        " LIMIT ".($pagina_actual*$items_x_pagina).", $items_x_pagina")
        ->find_many();

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
    <script src="js/curso.js"></script>

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
      include ("cabecera.php");?>
      <div class="fondo_app">  
        <div class="container">
          <a href="registro_curso.php"></a>
          <div class="padding_top"></div>
          <div class="contenedor_datos">
            <div class="buscador row">
              <div class="col-9">
                <input type="text" id="buscar_texto" class="form-control css_text left" placeholder="Curso" value="<?=isset($_GET['texto'])?$_GET['texto']:""?>">
                <img src="img/ico_buscar.png" id="btn_buscar" class="pointer">
              </div>
              <div class="col">
                <a href="registro_curso.php"><div class="btn btn-warning btn-sm"><img src="img/ico_curso.png"> Nuevo Curso</div></a>
              </div>
            </div>
            <div class="margen"></div>
            <?php 
            if($cursos != null){
              $index = 1 + ($pagina_actual*$items_x_pagina); ?>
            <div class="row bg_cabecera_row">
              <div class="col-1">Nro</div>
              <div class="col-3">Nombre</div>
              <div class="col">Fecha Inicio</div>
              <div class="col">Fecha Fin</div>
              <div class="col"></div>
            </div>
            <?php foreach($cursos as $curso){ ?>
            <div class="row bg_col_row">
              <div class="col-1"><?=$index ++?></div>
              <div class="col-3"><?=$curso->name?></div>
              <div class="col"><?=$curso->start_date?> </div>
              <div class="col"><?=$curso->end_date?></div>
              <div class="col">
                <a href="#">
                  <img src="img/ico_revisar.png"><span class="font_accion"> Editar</span>
                </a>
              </div>
            </div>
            <?php }
            } else {
              echo "<div class=margen></div>";
              echo "<div class='color_datos'>&nbsp En este momento no existen cursos registrados</div>";
            } ?>
            <div class="margen_inf"></div>
            <?php
            include("paginacion.php");
            ?>
          </div>
        </div>
      </div>
   </div>
  </body>
</html>