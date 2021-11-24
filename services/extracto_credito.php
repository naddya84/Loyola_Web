<?php
require_once '../config/database.php';
require_once '../config/configure.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$docuCage = $_GET['docu-cage'];
$credNumber = $_GET['cred-number'];

if(!empty($docuCage)) {

    $data = ORM::for_table('extracto_credito')
        ->select('extracto_credito.*')
        ->join('user', ['extracto_credito.user_id','=','user.id'])
        ->join('history_cred_cly', ['extracto_credito.id_credito','=','history_cred_cly.id'])
        ->where('history_cred_cly.id', $credNumber)
        ->where('user.id_member', $docuCage)
        ->find_array();
        
    if(!is_null($data) && !empty($data)) {

        $dataDetail = ORM::for_table('detalle_extracto_credito')
        ->where("id_extracto_credito", $data[0]["id"])
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