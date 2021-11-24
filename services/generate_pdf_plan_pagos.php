<?php
require_once '../config/database.php';
require_once '../config/configure.php';
require_once '../services/plan_pago_pdf.php';

//Generate PDF
$docuCage = $_GET['docu-cage'];
$credNumber = $_GET['cred-number'];

if(!empty($docuCage) && !empty($credNumber)) {

    $user = ORM::for_table("user")
            ->where("id_member", $docuCage)
            ->find_one();

    $data = ORM::for_table('plan_pago_cly')
            ->select('plan_pago_cly.*')
            ->join('user', ['plan_pago_cly.user_id','=','user.id'])
            ->join('history_cred_cly', ['plan_pago_cly.id_credito','=','history_cred_cly.id'])
            ->where('history_cred_cly.id', $credNumber)
            ->where('user.id_member', $docuCage)
            ->find_one();

    $dataDetail = ORM::for_table('detalle_plan_pago_cly')
            ->where("id_plan_pago_cly", $data["id"])
            ->order_by_asc("credNumCuota")
            ->find_array();
        
    if(
        !empty($user) &&
        !is_null($data) && 
        !empty($data) &&
        !empty($dataDetail)
    ) {

        $full_name = ($user["last_name_1"].' '.$user["last_name_2"]);
        $pdf = new PDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->createHeader('../img/logo.png', 'COOPERATIVA DE AHORRA Y CREDITO LOYOLA', 'Plan de Pagos General');
        $pdf->createHeaderInformation($user["id_member"],$user["names"],$full_name,$user["id_number"], $data);
        $pdf->createHeaderTable();
        $pdf->createTableBody(
            $dataDetail, 
            '../img/logo.png', 
            'COOPERATIVA DE AHORRA Y CREDITO LOYOLA', 
            'Plan de Pagos General',
            $user["id_member"],
            $user["names"],
            $full_name,
            $user["id_number"]
        );
        echo $pdf->Output('I','plan_pagos_'.$user["id_number"].'.pdf');


    } else {
        
        $data = array("error" => true, "msg" => 'No se logro crear pdf de los creditos asociados');

        echo json_encode($data);
    }


} else {
    
    $data = array("error" => true, "msg" => 'ID usuario no definido');

    echo json_encode($data);
}

?>