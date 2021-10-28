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
if( isset($curso_json->estado) ){
  $curso->status = $curso_json->estado;  
}
if( isset($curso_json->url) ){
  $curso->url = $curso_json->url;  
} 
/*Eliminar foto curso*/
if( isset($curso_json->eliminar_foto) ){        
  if( file_exists('../uploads/foto_curso/'.$curso->id."/".$curso_json->eliminar_foto) ){
    unlink('../uploads/foto_curso/'.$curso->id."/".$curso_json->eliminar_foto);
  } 
  $curso->photo = NULL;
}

ORM::get_db()->beginTransaction();

if( $curso->save() ){ 
  if( isset($curso_json->foto) ){
    $ds = "/";  
    $tempStoreFolder = '..'.$ds.'uploads'.$ds.session_id().$ds;
    $storeFolder = '..'.$ds.'uploads/foto_curso'.$ds.$curso->id.$ds;

    if (!file_exists( $storeFolder )) {
      if ( !mkdir( $storeFolder, 0777, true) ){
        ORM::get_db()->rollBack();
        die ( json_encode(array(
          "success" => false,
          "reason" => "No se pudo crear el directorio para subir los archivos"
        )));
      }
    }

    if( !rename( $tempStoreFolder.$curso_json->foto, $storeFolder.$curso_json->foto) ){    
      echo json_encode(array(
        "success" => false,      
        "reason" => "No se puede copiar la foto"
      ));  
      die();
    }
    $curso->photo = $curso_json->foto;
    $curso->save();   
  }
  
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