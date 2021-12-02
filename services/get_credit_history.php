<?php
require_once '../config/database.php';
require_once '../config/configure.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$docuCage = isset($_GET['docu_cage'])?$_GET['docu_cage']:null; //associate code

if(!is_null($docuCage)) {

    $credits = ORM::for_table('credit_history')
        ->select('credit_history.*')
        ->join('user',['credit_history.user_id','=','user.id'])
        ->where('user.id_member', $docuCage)
        ->order_by_asc('credit_history.credFechaDesem')
        ->find_array();

    echo json_encode([
        'error'=> false,
        'errorMessage'=> [],
        'errorCode' => 0,
        'result' => $credits
    ]);

} else {
    
    die(json_encode([
        'error'=> true,
        'errorMessage'=> [
            "1" =>'Valor docu-cage no especificado'
        ],
        'errorCode' => 0,
        'result' => []
    ]));
}

?>