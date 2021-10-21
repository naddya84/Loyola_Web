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
$asamblea_json = json_decode($request_body);

if( isset( $asamblea_json->id ) ){
  $asamblea = ORM::for_table('assembly')          
    ->where(array(
      'id' => $asamblea_json->id
  ))
    ->find_one();
} else {
  $asamblea = ORM::for_table('assembly')->create();
  $asamblea->status = "inactivo";
}
  
if( isset($asamblea_json->nombre) ){
  $asamblea->name = $asamblea_json->nombre;  
}

if( isset($asamblea_json->fecha_asamblea) ){
  $asamblea->datetime = $asamblea_json->fecha_asamblea;  
  $hora = (new DateTime($asamblea_json->fecha_asamblea))->format("h:i:s");
  $asamblea->time = $hora;  
}

if( isset($asamblea_json->codigo_zoom) ){
  $asamblea->zoom_code = $asamblea_json->codigo_zoom;  
}

if( isset($asamblea_json->password_zoom) ){
  $asamblea->zoom_password = $asamblea_json->password_zoom;  
}

if( isset($asamblea_json->vigente) ){
  //Si esta asamblea esta vigente deshabilitamos los demas
  if( $asamblea_json->vigente ){    
    $estado_asambleas = ORM::for_table("assembly")
            ->find_many();
    foreach ( $estado_asambleas as $estado_asamblea ){
      $estado_asamblea->status = "inactivo";
      $estado_asamblea->save();
    }    
    $asamblea->status = "activo";
  } else {    
    $asamblea->status = "inactivo";
  }    
}

if(isset($asamblea_json->doc_jornada)){
  $asamblea->journey = $asamblea_json->doc_jornada;
}

ORM::get_db()->beginTransaction();

if( $asamblea->save() ){ 
   /*eliminar documentos*/
  if( isset($asamblea_json->eliminar_memory) ){                
    if( file_exists($asamblea_json->eliminar_memory) ){
      unlink($asamblea_json->eliminar_memory);
    } 
    $asamblea->memory = NULL;
  }
    
  if( isset($asamblea_json->eliminar_declaracion) ){                
    if( file_exists($asamblea_json->eliminar_declaracion) ){
      unlink($asamblea_json->eliminar_declaracion);
    }    
    $asamblea->statemts = NULL;
  }
  
  /*guardamos el documento memory*/
  if( isset($asamblea_json->doc_memory) ){    
    $ds = "/";  
    $tempStoreFolder = '../uploads'.$ds.session_id().$ds;
    $storeFolder = '../uploads/documentos_asamblea'.$ds.$asamblea->id().$ds;
    
    if (!file_exists( $storeFolder )) {        
        if ( !mkdir( $storeFolder, 0777, true) ){
          ORM::get_db()->rollBack();    
          die ( json_encode(array(
            "success" => false,
            "reason" => "No se pudo crear el directorio para guardar los archivos"
          )));
        }
    }    
     
    if( !rename( $tempStoreFolder.$asamblea_json->doc_memory, $storeFolder.$asamblea_json->doc_memory) ){
      ORM::get_db()->rollBack();    
      echo json_encode(array(
          "success" => false,      
          "reason" => "No se puede copiar el documento"
      ));  
      die();
    }  
    $asamblea->memory = $storeFolder.$asamblea_json->doc_memory;
  }
    /*guardamos el documento declaracion*/
  if( isset($asamblea_json->doc_declaracion) ){    
    $ds = "/";  
    $tempStoreFolder = '../uploads'.$ds.session_id().$ds;
    $storeFolder = '../uploads/documentos_asamblea'.$ds.$asamblea->id().$ds;
    
    if (!file_exists( $storeFolder )) {        
        if ( !mkdir( $storeFolder, 0777, true) ){
          ORM::get_db()->rollBack();    
          die ( json_encode(array(
            "success" => false,
            "reason" => "No se pudo crear el directorio para guardar los archivos"
          )));
        }
    }      
    if( !rename( $tempStoreFolder.$asamblea_json->doc_declaracion, $storeFolder.$asamblea_json->doc_declaracion) ){
      ORM::get_db()->rollBack();    
      echo json_encode(array(
            "success" => false,      
            "reason" => "No se puede copiar el documento"
      ));  
      die();  
    }
    $asamblea->statemts= $storeFolder.$asamblea_json->doc_declaracion;
  }
  $asamblea->save();
    
  echo json_encode(array(
    "success" => true      
    ));
    ORM::get_db()->commit();    
 
} else { 
  echo json_encode(array(
      "success" => false,      
      "reason" => "No se puede guardar la asamblea en la base de datos"
  ));
  ORM::get_db()->rollBack();    
}

