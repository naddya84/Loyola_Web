<?php header('X-Frame-Options: SAMEORIGIN'); 

if( !isset($_GET['token']) ){
  header('Location: index.php');
  die();
}

require_once 'config/database.php';

$user = ORM::for_table("user")
        ->where("token", $_REQUEST['token'])->find_one();

if( $user == null ){
  header('Location: index.php');
  die();
}

$actualizo = false;
$error="";

if( isset($_POST['password']) ){
  if(!empty($_POST["password"]) && ($_POST["password_c"] == $_POST["password"])) {
    $password = $_POST["password"];
    $cpassword = $_POST["password_c"];
    if (strlen($_POST["password"]) <= 8) {
        $error = "Tu clave debe tener al menos 8 letras";
    }
    else if(!preg_match("#[0-9]+#",$password)) {
        $error = "La clave debe tener al menos un número";
    }
    else if(!preg_match("#[A-Z]+#",$password)) {
        $error = "Debes usar por lo menos alguna letra en mayúsculas";
    }
    else if(!preg_match("#[a-z]+#",$password)) {
        $error = "Debes usar por lo menos alguna letra en minúscula";
    } else {
        $error = "Actualizando la clave";
        $user->password = $password;
        $user->token = null;
        if( !$user->save() ){
          $error = "No se pudo cambiar la clave";
        } else {
          $actualizo = true;
        }
    }
  } else {
    $error = "La nueva contraseña no debe ser vacia y debe ser igual a la confirmación";
  }
} 

?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">        
    <link rel="icon" type="image/png" href="img/logo.png" />
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">  
    <link href="css/index.css?v=1.3" media="screen" rel="stylesheet" type="text/css" />
    
    <script src="js/libs/jquery-3.3.1.min.js"></script>                
    <script src="bootstrap/js/bootstrap.min.js"></script>            
    <title>Restaurar clave Loyola Socio</title>
  </head>
  <body>    
    <div class="container-fluid">          
      <div class="center">
      <div class="fondo">
        <div class="center"><img src="img/logo.png" class="css_logo"></div>
        <br>
        <?php if(!$actualizo) { ?>
        <div class="center"><h4 class="font_verde">Restauración de Clave</h4></div>
        <div class="css_socio"><span class="font_verde">Socio: </span><?=$user->names." ".$user->last_name_1." ".$user->last_name_2?></div> 
        <div class="margen"></div>
        <div class="contenedor_login ">
          <?php if($error!=""){?>
          <div class="css_advertencia"><?=$error?></div>
          <?php } ?>
          <form method="POST">
            <div class="formulario_clave">
              <input id="token" type="hidden" value="<?=$_GET['token']?>" />              
              <div class="margen"></div>
              <input name="password" type="password" placeholder="Nueva Clave" class="datos_fom margen_left" autocomplete="off">
              <input name="password_c" type="password" placeholder="Confirmar Nueva Clave" class="datos_fom margen_left" autocomplete="off">
            </div>
            <br>
            <div class="center"><input type="submit" value="Restaurar" class="btn btn-md btn-success css_restaurar"/></div>
          </form>   
          <div class="clearfix"></div>          
        </div>
      </div>
    </div>
    <?php } else { ?>      
      <div class="css_aviso">Su clave se actualizo correctamente, ya puede ingresar a la aplicación</div>
    <?php }  ?>
    </div>    
  </body>
</html>