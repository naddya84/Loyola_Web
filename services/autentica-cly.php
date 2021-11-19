<?php
require_once '../config/database.php';
require_once '../config/configure.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$docuNdid = isset($_GET['docundid'])?$_GET['docundid']:null;
$docuCage = isset($_GET['docucage'])?$_GET['docucage']:null;
$docuNomb = isset($_GET['docunomb'])?$_GET['docunomb']:null;

if ($docuNdid == null) {
    die(json_encode([
        'docuAcepta' => false,
        'docuMensaje' => 'docuNdid no definido'
    ]));
}
if ($docuCage == null) {
    die(json_encode([
        'docuAcepta' => false,
        'docuMensaje' => 'docuCage no definido'
    ]));
}
if ($docuNomb == null) {
    die(json_encode([
        'docuAcepta' => false,
        'docuMensaje' => 'docuNomb no definido'
    ]));
}

$fullName = explode(' ', $docuNomb);

$user = ORM::for_table('user')
    ->where([
        'names' => $fullName[0],
        'last_name_1' => $fullName[1],
        'last_name_2' => $fullName[2],
        'id_number' => $docuNdid,
        'id_member' => $docuCage
    ])->find_one();
if ($user) {
    echo json_encode([
        'docuAcepta' => true,
        'docuMensaje' => 'Exito'
    ]);
}
else {
    echo json_encode([
        'docuAcepta' => false,
        'docuMensaje' => 'Autenticación incorrecta'
    ]);
}