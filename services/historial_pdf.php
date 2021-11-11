<?php
require_once '../libs/fpdf/fpdf.php';

class PDF extends FPDF{

    function createHeader($path_logo, $title, $subtitle) {
        // Logo
        $this->Image($path_logo,170,6,30);
        //Move
        $this->Cell(20);
        //Title
        $this->SetFont('Arial','',12);
        $this->Cell(30,10,$title);
        // SubTitle
        $this->SetFont('Arial','B', 12);
        $this->Cell(30,20,$subtitle);
        // Line break
        $this->Ln(20);
    }

    function createHeaderInformation($docu_cage,$name, $lastname,$ci) {
        $full_name = $name.' '.$lastname;
        //Code
        $this->SetFont('Arial','B',10);
        $this->Cell(25 ,5,'Codigo Socio:',0,0);
        $this->SetFont('Arial','',10);
        $this->Cell(5 ,5,$docu_cage,0,0);
        //SPACE
        $this->Cell(50 ,5,'',0,0);
        //CI
        $this->SetFont('Arial','B',10);
        $this->Cell(5 ,5,'CI:',0,0);
        $this->SetFont('Arial','',10);
        $this->Cell(5,5,$ci,0,1);
        //Name
        $this->SetFont('Arial','B',10);
        $this->Cell(11,5,'Socio:',0,0);
        $this->SetFont('Arial','',10);
        $this->Cell(34,5,$full_name,0,1);
    }

    function createHeaderTable(){
        $this->Cell(50 ,5,'',0,1);
        $this->SetFont('Arial','B',10);
        /*Heading Of the table*/
        $x = $this->x;
        $y = $this->y;
        $push_right = 0;
        $this->setFillColor(119, 184, 66); 
        $this->MultiCell($w = 25,4,"Nro.\nCredito",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 30,4,"Fecha\nDesembolso",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 30,4,"Monto\nDesembolsado",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 20,8,"Moneda",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 30,8,"Saldo",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 22,8,"Estado",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 30,4,"Fecha\nCancelacion",1,'C',true);
    }

    function createTableBody($data,$path_logo, $title, $subtitle,$docu_cage,$name, $lastname,$ci) {
        $count = 0;
        for ($i=0; $i < count($data); $i++) { 
            $this->SetFont('Arial','',10);
            if($count === 31) {
                $count = 0;
                $this->addPage();
                $this->createHeader($path_logo, $title, $subtitle);
                $this->createHeaderInformation($docu_cage,$name, $lastname,$ci);
                $this->createHeaderTable();
            }

            $this->Cell(25 ,7,$data[$i]["credNumero"],1,0,'C');
            $this->Cell(30 ,7,$data[$i]["credFechaDesem"],1,0,'C');
            $this->Cell(30 ,7,$data[$i]["credMontoDesem"],1,0,'C');
            $this->Cell(20 ,7,$data[$i]["credMoneda"],1,0,'C');
            $this->Cell(30 ,7,$data[$i]["credSaldo"],1,0,'C');  

            $this->SetFont('Arial','B',10);
            if ($data[$i]["credEstado"] === "Vigente") {
                $this->SetTextColor(34,187,51);
            } else {
                $this->SetTextColor(187,33,36);
            }          
            $this->Cell(22 ,7,$data[$i]["credEstado"],1,0,'C');
            
            $this->SetFont('Arial','',10);
            $this->SetTextColor(0,0,0);
            $this->Cell(30 ,7,$data[$i]["CredFechaCancel"],1,1,'C');
            $count++;
        }
    }

}

?>