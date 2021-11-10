<?php
require_once '../config/database.php';
require_once '../config/configure.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$docuCage = $_GET['docu-cage'];
$credNumber = $_GET['cred-number'];

if(!empty($docuCage)) {

    $data = ORM::for_table('plan_pago_cly')
        ->where("id_credito", $credNumber)
        ->where("id_member",$docuCage)
        ->find_array();
        
    if(!is_null($data) && !empty($data)) {

        $dataDetail = ORM::for_table('detalle_plan_pago_cly')
        ->where("id_plan_pago_cly", $data[0]["id"])
        ->find_array();
        
        $dataArray = array(
            "member" => $docuCage,
            "error" => false,
            "result" => $data,
            "detail" => $dataDetail
        );

        echo json_encode($dataArray);

    } else {
        
        $data = array("status" => 0, "msg" => 'No se logro encontrar el plan de pago');

        echo json_encode($data);
    }


} else {
    
    $data = array("status" => 0, "msg" => 'ID usuario no definido');

    echo json_encode($data);
}

?>