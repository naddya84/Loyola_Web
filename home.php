
<?php
require_once 'config/database.php';

session_name("loyola");
session_start();

if (!isset($_SESSION['usuario'])) {
  header('Location: index.php');
  die();
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="img/logo.png" />
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">  
    <link href="css/style.css?v=1.3" media="screen" rel="stylesheet" type="text/css" />

    <script src="js/libs/jquery-3.3.1.min.js"></script>     
    <script src="bootstrap/js/bootstrap.min.js"></script>            
    <script src="js/home.js"></script>

    <script type="text/javascript">
      $(document).ready(function () {        
        iniciar();                                    
      });            
    </script>
    <title>Cooperativa LOYOLA</title>
  </head>
  <body>
    <div class="container-fluid">
      <?php
      $home = false;
      include 'cabecera.php';?>
      <img src="img/img_central1.jpg" class="img_central">
      <img src="img/logo.png" class="logo_home"></div>
   </div>
  </body>
</html>
