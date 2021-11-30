<?php
require_once '../config/database.php';
require_once '../config/configure.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$docuCage = $_GET['docu-cage'];

if(!empty($docuCage)) {

    $data = ORM::for_table('credit_history')
        ->select('credit_history.*')
        ->join('user',['credit_history.user_id','=','user.id'])
        ->where('user.id_member', $docuCage)
        ->order_by_asc('credit_history.credFechaDesem')
        ->find_array();

    if(empty($data)) {
        
        $data = array("status" => 0, "error" => true,"msg" => 'No existe creditos asignados');

        echo json_encode($data);
        
    } else {

        $dataArray = array(
            "member" => $docuCage,
            "error" => false,
            "result" => $data
        );
        echo json_encode($dataArray);
    
    }
    

} else {
    
    $data = array("status" => 0, "msg" => 'ID usuario no definido');

    echo json_encode($data);
}

?>