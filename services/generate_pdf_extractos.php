<?php
require_once '../config/database.php';
require_once '../config/configure.php';
require_once '../services/extracto_credito_pdf.php';

//Generate PDF
$docuCage = $_GET['docu-cage'];
$credNumber = $_GET['cred-number'];

if(!empty($docuCage) && !empty($credNumber)) {

    $user = ORM::for_table("user")
            ->where("id_member", $docuCage)
            ->find_one();

    $data = ORM::for_table('extracto_credito')
            ->select('extracto_credito.*')
            ->join('user', ['extracto_credito.user_id','=','user.id'])
            ->join('history_cred_cly', ['extracto_credito.id_credito','=','history_cred_cly.id'])
            ->where('history_cred_cly.id', $credNumber)
            ->where('user.id_member', $docuCage)
            ->find_one();

    $dataDetail = ORM::for_table('detalle_extracto_credito')
            ->where("id_extracto_credito", $data["id"])
            ->order_by_asc("credNroTrans")
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
        $pdf->createHeader('../img/logo.png', 'COOPERATIVA DE AHORRA Y CREDITO LOYOLA', 'Extracto de Creditos');
        $pdf->createHeaderInformation($user["id_member"],$user["names"],$full_name,$user["id_number"], $data);
        $pdf->createHeaderTable();
        $pdf->createTableBody(
            $dataDetail, 
            '../img/logo.png', 
            'COOPERATIVA DE AHORRA Y CREDITO LOYOLA', 
            'Extracto de Creditos',
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