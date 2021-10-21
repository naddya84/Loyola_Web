<?php
require_once '../config/database.php';
require_once '../config/configure.php';

session_name(APP_NAME);
session_start();

$request_body = file_get_contents('php://input');
$data_json = json_decode($request_body);

//Obtenemos el usuario en funcion a los parametros enviados

$assemblys = ORM::for_table("assembly")
        ->where_null("deleted_at")
        ->find_array();

  die (json_encode(array(
    "success" => true,
    "assemblys" => $assemblys
  )));



