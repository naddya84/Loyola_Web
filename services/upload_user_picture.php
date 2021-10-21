<?php
require_once '../config/database.php';

session_name(APP_NAME);
session_start();

//first confirming that we have the image and tags in the request parameter
if( !isset($_FILES['imageFile']['name'])){
  die( json_encode(array(
      "success" => false,
      "reason" => "No se envio la foto"
  )));  
}

if( !isset($_POST["type_photo"]) ){
  die( json_encode(array(
      "success" => false,
      "reason" => "No se envio el tipo de foto"
  )));  
}
$type = $_POST["type_photo"];  
  
$user = null;  
if( isset($_POST["oauth_uid"]) ){
  $user = ORM::for_table("user")
          ->where("oauth_uid", $_POST["oauth_uid"])
          ->where_null("deleted_at")
          ->find_one();  
} else {
  if( isset($_POST["email"]) ){
    $user = ORM::for_table("user")
            ->where("email", $_POST["email"])
            ->where_null("deleted_at")
            ->find_one();  
  }
}

if( $user == null ){
  die( json_encode(array(
      "success" => false,
      "reason" => "No se encontro el usuario para subir su foto"
  )));  
}
  

$directorio = "/uploads/".$user->id."/";        
    
if( !file_exists( "../".$directorio) ){
    if(!mkdir("../".$directorio, 0777, true)) {
        echo json_encode(array(
            "success" => false,
            "reason" => "Fallo al crear el directorio para las fotografias"
        ));
        die();
    }
}

$tempFile = $_FILES['imageFile']['tmp_name'] ;          
if( IS_LINUX ){
  $targetFile =  "../".$directorio.$_FILES['imageFile']['name']; 
} else {
  $targetFile =  "..\\".str_replace("/", "\\",$directorio.$_FILES['imageFile']['name']); 
}


if( !file_exists($tempFile) ){
  die( json_encode(array(
        "success" => false,
        "reason" => "No se encontro el archivo a subir".$tempFile
  )));
}

//Guardamos la referencia en la bd
if( $type == "picture_1"){  
  //Antes de eliminar preguntamos si habia antes una imagen para borrarla
  if( $user->picture_id_1 != "" && file_exists("../uploads/".$user->id."/".$user->picture_id_1 ) ){
    unlink("../uploads/".$user->id."/".$user->picture_id_1);
  }
  
  $user->picture_id_1 = $_FILES['imageFile']['name'];
}
if( $type == "picture_2"){
  //Antes de eliminar preguntamos si habia antes una imagen para borrarla
  if( $user->picture_id_2 != "" && file_exists("../uploads/".$user->id."/".$user->picture_id_2 ) ){
    unlink("../uploads/".$user->id."/".$user->picture_id_2);
  }
  
  $user->picture_id_2 = $_FILES['imageFile']['name'];
}
if( $type == "selfie"){
  //Antes de eliminar preguntamos si habia antes una imagen para borrarla
  if( $user->selfie != "" && file_exists("../uploads/".$user->id."/".$user->selfie ) ){
    unlink("../uploads/".$user->id."/".$user->selfie);
  }
  
  $user->selfie = $_FILES['imageFile']['name'];
}

if( !move_uploaded_file($tempFile, $targetFile) ){
  die( json_encode(array(
        "success" => false,
        "reason" => "No se pudo guardar la fotografia en el servidor ".$tempFile
  )));
}

if( !$user->save() ){
  die( json_encode(array(
        "success" => false,
        "reason" => "No se pudo guardar lo datos de la foto del usuario"
  )));
}
  
die( json_encode(array(
    "success" => true    
)));
        
?>