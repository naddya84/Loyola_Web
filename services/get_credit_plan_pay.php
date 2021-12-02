<?php
require_once '../config/database.php';
require_once '../config/configure.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$docuCage = isset($_GET['docu_cage'])?$_GET['docu_cage']:null; //associate code
$credNumber = isset($_GET['cred_number'])?$_GET['cred_number']:null; //credit number

if(!is_null($docuCage) && !is_null($credNumber)) {

    $creditPlanPay = ORM::for_table('credit_plan_pay')
        ->select('credit_plan_pay.*')
        ->join('user', ['credit_plan_pay.user_id','=','user.id'])
        ->join('credit_history', ['credit_plan_pay.id_credit','=','credit_history.id'])
        ->where('credit_history.id', $credNumber)
        ->where('user.id_member', $docuCage)
        ->find_array();

    if(!empty($creditPlanPay)) {

        $creditsPlanPayDetail = ORM::for_table('credit_plan_pay_detail')
        ->where("id_credit_plan_pay", $creditPlanPay[0]["id"])
        ->order_by_asc('credit_plan_pay_detail.credNumCuota')
        ->find_array();
        if(!empty($creditsPlanPayDetail)) {

            echo json_encode([
                'error'=> false,
                'errorMessage'=> [],
                'errorCode' => 0,
                'result' => $creditPlanPay,
                'detail' => $creditsPlanPayDetail
            ]);

        } else {
            echo json_encode([
                'error'=> false,
                'errorMessage'=> [],
                'errorCode' => 0,
                'result' => $creditPlanPay,
                'detail' => []
            ]);
        }
        

    } else {
        
        echo json_encode([
            'error'=> true,
            'errorMessage'=> [
                "1" =>'No se logro encontrar el plan de pagos asignado'
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