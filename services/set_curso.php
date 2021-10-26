<?php
require_once '../config/database.php';

session_name("loyola");
session_start();

if( !isset($_SESSION['usuario']) ){
  die ( json_encode(array(
    "success" => false,
    "reason" => "sin_sesion"
  )));
} 

$usuario_plataforma = $_SESSION['usuario'];

$request_body = file_get_contents('php://input');
$curso_json = json_decode($request_body);

if( isset( $curso_json->id ) ){
  $curso = ORM::for_table('course')          
    ->where(array(
      'id' => $curso_json->id
  ))
    ->find_one();
} else {
  $curso = ORM::for_table('course')->create();
  $curso->status = "Activo";  
}
  
if( isset($curso_json->nombre) ){
  $curso->name = $curso_json->nombre;  
}
if( isset($curso_json->duracion) ){
  $curso->time = $curso_json->duracion;  
}
if( isset($curso_json->expositor) ){
  $curso->expositor = $curso_json->expositor;  
}
 
if( isset($curso_json->fecha_inicio) ){
  $curso->start_date = $curso_json->fecha_inicio;  
}

if( isset($curso_json->fecha_fin) ){
  $curso->end_date = $curso_json->fecha_fin;  
}
if( isset($curso_json->tipo) ){
  $curso->type = $curso_json->tipo;  
}
if( isset($curso_json->codigo) ){
  $curso->code = $curso_json->codigo;  
}

if( isset($curso_json->password) ){
  $curso->password = $curso_json->password;  
}
if( isset($curso_json->lugar) ){
  $curso->locale = $curso_json->lugar;  
}
ORM::get_db()->beginTransaction();

if( $curso->save() ){ 
  echo json_encode(array(
    "success" => true      
    ));
    ORM::get_db()->commit();    
 
} else { 
  echo json_encode(array(
      "success" => false,      
      "reason" => "No se puede guardar el curso en la base de datos"
  ));
  ORM::get_db()->rollBack();    
}

