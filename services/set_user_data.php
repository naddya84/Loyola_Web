<?php
require_once '../config/database.php';
require_once '../config/configure.php';

session_name(APP_NAME);
session_start();

$old_error_handler = set_error_handler("myErrorHandler");

$request_body = file_get_contents('php://input');
$data_json = json_decode($request_body);


//Primero se valida que la informacion este completa
if( !isset($data_json->names) ){
  die ( json_encode(array(
    "success" => false,
    "reason" => "No se envió el nombre del socio",
    "data " => $data_json 
  )));
}

if( !isset($data_json->last_name_1) ){
  die ( json_encode(array(
    "success" => false,
    "reason" => "No se envió el apellido del socio",
    "data " => $data_json     
  )));
}

if( !isset($data_json->id_number) ){
  die ( json_encode(array(
    "success" => false,
    "reason" => "No se envió el numero de identificación"    ,
    "data " => $data_json 
  )));
}

if( !isset($data_json->extension) ){
  die ( json_encode(array(
    "success" => false,
    "reason" => "No se envió la extensión del numero de identificación"    ,
    "data " => $data_json 
  )));
}

if( !isset($data_json->birthdate) ){
  die ( json_encode(array(
    "success" => false,
    "reason" => "No se envió la fecha de nacimiento"    ,
    "data " => $data_json 
  )));
}

if( !isset($data_json->phone_number) ){
  die ( json_encode(array(
    "success" => false,
    "reason" => "No se envió el número de teléfono"    ,
    "data " => $data_json 
  )));
}


//Aqui se deberia validar el correcto formato de la informacion enviada

//Verificamos que no exista informacion duplicada del usuario

$user_reg = null;
if( isset($data_json->oauth_uid) ){
  $user_reg = ORM::for_table("user")
        ->where("oauth_uid", $data_json->oauth_uid)
        ->where_null("deleted_at")  
        ->find_one();            
} else {
  if( isset($data_json->email) ){
    $user_reg = ORM::for_table("user")
          ->where("email", $data_json->email)
          ->where_null("deleted_at")  
          ->find_one();   
  }
}

if( !isset($data_json->update_user) ){  
  //Si ya existe no podemos volver a registrar
  if( $user_reg != null ){
    die ( json_encode(array(
      "success" => false,
      "reason" => "La cuenta ya se encuentra registrada, intente iniciar sesion"    
    )));
  }
  
  $user = ORM::for_table("user")->create();
    
} else {
  //Si no existe no podemos actualizar
  if( $user_reg == null ){
    die ( json_encode(array(
      "success" => false,
      "reason" => "No se encuentra el usuario para actualizar"    
    )));
  }
  
  $user = $user_reg;  
}

$user->state = STATUS_USER_UNREVISED;

$user->names = $data_json->names;
$user->last_name_1 = $data_json->last_name_1;
$user->id_number = $data_json->id_number;
$user->id_extension = $data_json->extension;

$user->birthday = $data_json->birthdate ;
//$user->phone_number = $data_json->phone_number;

if( isset($data_json->last_name_2) ){
  $user->last_name_2 = $data_json->last_name_2;
}

if( isset($data_json->id_member) ){
  $user->id_member = $data_json->id_member;
}

if( isset($data_json->phone_number) ){
  $user->phone_number = $data_json->phone_number;
}

if( isset($data_json->email) ){
  $user->email = $data_json->email;
}

if( isset($data_json->password) ){
  //$user->password = password_hash($data_json->password, PASSWORD_DEFAULT);
  $user->password = $data_json->password;
}
        
if( isset($data_json->oauth_uid) ){
  $user->oauth_uid = $data_json->oauth_uid;
}


if( $user->save() ){
  die ( json_encode(array(
      "success" => true
  )));
} else {
  die ( json_encode(array(
      "success" => false,
      "reason" => "No se pudo guardar en la base de datos"
  )));
}



function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }

    // $errstr may need to be escaped:
    $errstr = htmlspecialchars($errstr);

    $error = "";
    switch ($errno) {
      case E_USER_ERROR:
        $error = "<b>My ERROR</b> [$errno] $errstr<br />\n".
            "  Fatal error on line $errline in file $errfile".
            ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";                
        echo ( json_encode(array(
            "success" => false,
            "reason" => $error
        )));
        exit(1);

    case E_USER_WARNING:
        $error = "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        $error =  "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
        $error = "Unknown error type: [$errno] $errstr<br />\n";
        break;
    }
    
    if( $error != "" ){
      die ( json_encode(array(
            "success" => false,
            "reason" => $error
        )));
    }

    /* Don't execute PHP internal error handler */
    return true;
}