<?php
require_once '../config/database.php';
require_once '../config/configure.php';
require_once '../services/historial_pdf.php';

//Generate PDF
$docuCage = $_GET['docu-cage'];

if(!empty($docuCage)) {

    $user = ORM::for_table("user")
            ->where("id_member", $docuCage)
            ->find_one();
            
    $data = ORM::for_table('history_cred_cly')
            ->where("id_member",$docuCage)
            ->order_by_asc("credNumero")
            ->find_array();
        
    if(!is_null($data) && !empty($data)) {

        $pdf = new PDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->createHeader('../img/logo.png', 'COOPERATIVA DE AHORRA Y CREDITO LOYOLA', 'Historial Crediticio');
        $pdf->createHeaderInformation($user["id_member"],$user["names"],($user["last_name_1"].' '.$user["last_name_2"]),$user["id_number"]);
        $pdf->createHeaderTable();
        $pdf->createTableBody($data, '../img/logo.png', 'COOPERATIVA DE AHORRA Y CREDITO LOYOLA', 'Historial Crediticio',$user["id_member"],$user["names"],($user["last_name_1"].' '.$user["last_name_2"]),$user["id_number"]);
        echo $pdf->Output('I','historial_crediticio_'.$user["id_number"].'.pdf');


    } else {
        
        $data = array("error" => true, "msg" => 'No se logro crear pdf de los creditos asociados');

        echo json_encode($data);
    }


} else {
    
    $data = array("error" => true, "msg" => 'ID usuario no definido');

    echo json_encode($data);
}

?>