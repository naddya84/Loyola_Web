<?php
require_once '../config/database.php';
require_once '../config/configure.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$docuCage = isset($_GET['docu_cage'])?$_GET['docu_cage']:null; //associate code
$credNumber = isset($_GET['cred_number'])?$_GET['cred_number']:null; //credit number

if(!is_null($docuCage) && !is_null($credNumber)) {

    $creditsExtract = ORM::for_table('credit_extract')
        ->select('credit_extract.*')
        ->join('user', ['credit_extract.user_id','=','user.id'])
        ->join('credit_history', ['credit_extract.id_credit','=','credit_history.id'])
        ->where('credit_history.id', $credNumber)
        ->where('user.id_member', $docuCage)
        ->find_array();
        
    if(!is_null($creditsExtract) && !empty($creditsExtract)) {

        $creditsExtractDetail = ORM::for_table('credit_extract_detail')
        ->where("id_credit_extract", $creditsExtract[0]["id"])
        ->order_by_asc('credit_extract_detail.credNroTrans')
        ->find_array();
        
        if(!empty($creditsExtractDetail)) {

            echo json_encode([
                'error'=> false,
                'errorMessage'=> [],
                'errorCode' => 0,
                'result' => $creditsExtract,
                'detail' => $creditsExtractDetail
            ]);

        } else {
            echo json_encode([
                'error'=> false,
                'errorMessage'=> [],
                'errorCode' => 0,
                'result' => $creditsExtract,
                'detail' => []
            ]);
        }

    } else {
        
        echo json_encode([
            'error'=> true,
            'errorMessage'=> [
                "1" =>'No se logro encontrar algun extracto de credito registrado'
            ],
            'errorCode' => 0,
            'result' => []
        ]);
    }

} else {
    
    die(json_encode([
        'error'=> true,
        'errorMessage'=> [
            "1" =>'Valor docu-cage no especificado',
            "2" =>'Valor cred-number no especificado'
        ],
        'errorCode' => 0,
        'result' => []
    ]));
}
?>