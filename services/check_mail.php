<?php
require_once '../config/database.php';
require_once '../config/configure.php';

session_name(APP_NAME);
session_start();

$request_body = file_get_contents('php://input');
$data_json = json_decode($request_body);

//Obtenemos el usuario en funcion a los parametros enviados
if( !isset($data_json->email) ){
  die ( json_encode(array(
    "success" => false,
    "reason" => "No se envio el correo a verificar"    
  )));
}

$user = ORM::for_table("user")
      ->where("email", $data_json->email)
      ->where_null("deleted_at")    
      ->find_one();   

if( $user == null ){
  die ( json_encode(array(
    "success" => true,
    "estado" => "sin_uso"    
  )));
} else {
  die ( json_encode(array(
    "success" => true,
    "estado" => "en_uso"    
  )));
}