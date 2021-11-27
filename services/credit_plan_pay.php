<?php
require_once '../config/database.php';
require_once '../config/configure.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$docuCage = $_GET['docu-cage'];
$credNumber = $_GET['cred-number'];

if(!empty($docuCage) && !empty($credNumber)) {

    $data = ORM::for_table('credit_plan_pay')
        ->select('credit_plan_pay.*')
        ->join('user', ['credit_plan_pay.user_id','=','user.id'])
        ->join('credit_history', ['credit_plan_pay.id_credit','=','credit_history.id'])
        ->where('credit_history.id', $credNumber)
        ->where('user.id_member', $docuCage)
        ->find_array();

    if(!is_null($data) && !empty($data)) {

        $dataDetail = ORM::for_table('credit_plan_pay_detail')
        ->where("id_credit_plan_pay", $data[0]["id"])
        ->order_by_asc('credit_plan_pay_detail.credNumCuota')
        ->find_array();
        
        $dataArray = array(
            "member" => $docuCage,
            "error" => false,
            "result" => $data,
            "detail" => $dataDetail
        );

        echo json_encode($dataArray);

    } else {
        
        $data = array("error" => true, "msg" => 'No se logro encontrar el plan de pago');

        echo json_encode($data);
    }


} else {
    
    $data = array("error" => true, "msg" => 'ID usuario no definido');

    echo json_encode($data);
}

?>