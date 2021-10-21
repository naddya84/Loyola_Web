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
$usuario_json = json_decode($request_body);

$user = ORM::for_table('user')->where('id', $usuario_json->id_socio)->find_one(); 
$user->state = $usuario_json->estado; 

ORM::get_db()->beginTransaction();

if($user->save()){ 
  
  $activar_usuario = ORM::for_table("activate_user")->create();
  
  $activar_usuario->user_id = $usuario_json->id_socio;
  $activar_usuario->platform_user_id = $usuario_plataforma->id;
  $activar_usuario->status = $usuario_json->estado;
  if( isset($usuario_json->observacion) ){
    $activar_usuario->observations = $usuario_json->observacion;
  }        
  
  if( !$activar_usuario->save() ){
    echo json_encode(array(
        "success" => false,
        "reason" => "No se pudo actualizar el estado del socio"
    ));
    ORM::get_db()->rollBack();  
    die();
  } else {
    echo json_encode(array(
    "success" => true      
    ));
    ORM::get_db()->commit();
  }    
} else { 
  echo json_encode(array(
      "success" => false,      
      "reason" => "No se puede guardar la activacion del socio en la base de datos"
  ));
  ORM::get_db()->rollBack();    
}