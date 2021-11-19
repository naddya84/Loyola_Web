<?php
require_once '../config/database.php';
require_once '../config/configure.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$docuCage = isset($_GET['docu-cage'])?$_GET['docu-cage']:null; //associate code
if ($docuCage == null) {
   die(json_encode([
      'error'=> true,
      'errorMessage'=> [
         "1" =>'Valor docu-cage no especificado'
      ],
      'errorCode' => 0,
      'result' => []
   ]));
}

$certificates = ORM::for_table('user_certificates')
   // ->select('user.id_member')
   ->select('user_certificates.year', 'certGestion')
   ->select('user_certificates.number', 'certNumero')
   ->select('user_certificates.opening_date', 'certFecApert')
   ->select('user_certificates.amount', 'certCanti')
   ->select('user_certificates.cost', 'certMonto')
   ->select('user_certificates.state', 'certEstado')
   ->join('user', ['user_certificates.user_id', '=', 'user.id'])
   ->where('user.id_member', $docuCage)
   ->order_by_desc('user_certificates.year')
   ->find_array();


echo json_encode([
      'error'=> false,
      'errorMessage'=> [
      ],
      'errorCode' => 0,
      'result' => $certificates
]);
