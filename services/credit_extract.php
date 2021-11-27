<?php
require_once '../config/database.php';
require_once '../config/configure.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$docuCage = $_GET['docu-cage'];
$credNumber = $_GET['cred-number'];

if(!empty($docuCage)) {

    $data = ORM::for_table('credit_extract')
        ->select('credit_extract.*')
        ->join('user', ['credit_extract.user_id','=','user.id'])
        ->join('credit_history', ['credit_extract.id_credit','=','credit_history.id'])
        ->where('credit_history.id', $credNumber)
        ->where('user.id_member', $docuCage)
        ->find_array();
        
    if(!is_null($data) && !empty($data)) {

        $dataDetail = ORM::for_table('credit_extract_detail')
        ->where("id_credit_extract", $data[0]["id"])
        ->order_by_asc('credit_extract_detail.credNroTrans')
        ->find_array();
        
        $dataArray = array(
            "member" => $docuCage,
            "error" => false,
            "result" => $data,
            "detail" => $dataDetail
        );

        echo json_encode($dataArray);

    } else {
        
        $data = array("error" => true, "msg" => 'No se logro encontrar el extracto de credito');

        echo json_encode($data);
    }


} else {
    
    $data = array("error" => true, "msg" => 'ID usuario no definido');

    echo json_encode($data);
}

?>