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

$usuario = $_SESSION['usuario'];

$request_body = file_get_contents('php://input');
$data_json = json_decode($request_body);

if( $data_json->id_socio > 0 ){
   
  $socio = ORM::for_table('user')
      ->where("id",$data_json->id_socio)
      ->find_one();
  $socio->deleted_at = date('Y-m-d H:i:s', time());
  ORM::get_db()->beginTransaction();
          
  if( !$socio->save() ){
    ORM::get_db()->rollBack();    
    die ( json_encode(array(
      "success" => false,
      "reason" => "No se pudo cambiar el estado del socio" 
    )));
  } else {
    ORM::get_db()->commit();
    echo json_encode(array(
      "success" => true    
    ));
    die();
  }
} else {  
  die ( json_encode(array(
      "success" => false,
      "reason" => "No enviaron el id de la asamblea" 
    )));
}
