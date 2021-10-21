<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../libs/phpmailer/phpmailer/src/Exception.php';
require_once '../libs/phpmailer/phpmailer/src/PHPMailer.php';    
require_once '../libs/phpmailer/phpmailer/src/SMTP.php';
  
require_once '../config/database.php';


$request_body = file_get_contents('php://input');
$usuario_json = json_decode($request_body);

if( !isset($usuario_json->email) ){
  echo json_encode(array(
      "success" => false,
      "reason" => "No se envió el mail del usuario"
  ));
  die();
}

$user = ORM::for_table("user")
        ->where("email", $usuario_json->email)
        ->where_null("deleted_at")  
        ->find_one();

if( $user== null ){
  echo json_encode(array(
      "success" => false,
      "reason" => "No se encontró a un usuario con esa dirección de correo"
  ));
  die();
}

$user->token = md5($user->id."-".$user->email);

if( !send_mail($user) ){  
  echo json_encode(array(
      "success" => false,
      "reason" => "No se pudo enviar el correo a la direccion especificada"
  ));
  die();
}

$user->save();

echo json_encode(array(
    "success" => true,      
));
die();

function send_mail($usuario){  

  $body = 
    '<div style="padding: 15px; font-size: 14px; width=80%;">
      <br>
      <div>Estimado(a):</div>
      <br>
      <div>'.$usuario->names.' te enviamos este correo debido a tu solicitud de restablecer '
          . 'la clave de tu cuenta en el sistema Socio Loyola, que puedes restablecer '
          . '<a href="'.NAME_SERVER.ROUTE_SERVER.'/restaurar_clave.php?token='. $usuario->token .'" style="color:#00884a; font-weight: bold;">Aquí</a> 
      </div>      
      <br><br>      
      <div>
        Si no puedes ingresar con el enlace, copia y pega esta direccion en tu navegador:
        <code>'.NAME_SERVER.ROUTE_SERVER.'/restaurar_clave.php?token='. $usuario->token .'</code>
      </div>
      <br>
      <br>
      <div>
        Si no solicistate cambiar de clave puedes ignorar este mensaje e informar a los administradores del sistema para reportar el caso
      </div>
      <br>      
    </div>';    
  
  
   try {
        $email = new PHPMailer();        
        
        if( SMTP_DEBUG ){
          $email->SMTPDebug = 3;          
        }
        
        if( SMTP_SERVER ){
          $email->isSMTP();            
          $email->Host = SMTP_HOST;
          $email->Port = SMTP_PORT;                 
          $email->SMTPAuth = true;                                    
          $email->Username = SMTP_USER_NAME;                
          $email->Password = SMTP_PASSWORD;
          
          if( SMTP_SECURE != ""){
            $email->SMTPSecure = SMTP_SECURE;
          } else  {          
            $email->SMTPOptions = array(
              'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
              )
            );
          }          
        }                                
        
        $email->From      = EMAIL_FROM;
        $email->FromName  = EMAIL_FROM_NAME;
        $email->Subject   = EMAIL_SUBJECT;
        $email->Body      = $body;
        
        if( EMAIL_CC != "" ){
          $email->AddAddress( EMAIL_CC );
        }
        
        $email->AddAddress( $usuario->email );
        $email->IsHTML(true);  
        $email->CharSet = 'UTF-8';

        if( $email->Send() ){
            return true;            
        } else {
          return false;
          /*echo json_encode(array(
              "success" => false,
              "reason" => "Error con el servicio de envio de correos, revise la configuracion",
              "url" => $url_tarjeta.$tipo_tarjeta.'?producto='. base64_encode ($_REQUEST['producto'])
            ));*/
        }
    } catch (Exception $e) {        
      return false;
        /*echo json_encode(array(
          "success" => false,
          "reason" => "Hubo un error al mandar el correo: ".$mail->ErrorInfo
        ));*/
    }
  
}