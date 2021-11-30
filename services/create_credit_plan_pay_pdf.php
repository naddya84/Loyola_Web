<?php
require_once '../libs/fpdf/fpdf.php';

class PDF extends FPDF{

    function createHeader($path_logo, $title, $subtitle) {
        // Logo
        $this->Image($path_logo,170,6,30);
        //Move
        $this->Cell(1);
        //Title
        $this->SetFont('Arial','',12);
        $this->Cell(10,10,$title);
        // SubTitle
        $this->Cell(10);
        $this->SetFont('Arial','B', 12);
        $this->Cell(10,20,$subtitle);
        // Line break
        $this->Ln(20);
        $this->Cell(74,10,'Fecha de emision: '. date('d-m-Y H:m'),0,1,'R');  

    }

    function createHeaderInformation(
        $docu_cage,
        $name, 
        $lastname,
        $ci,
        $dataplanepay
    ) {
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
        //Informtaion Plan de pagos
        $this->SetFont('Arial','B',12);
        $this->Cell(29 ,5,'Informacion del plan de pago',0,1);
        $this->SetFont('Arial','B',10);
        $this->Cell(29 ,5,'Codigo Credito:',0,0);
        $this->SetFont('Arial','',10);
        $this->Cell(10 ,5,$dataplanepay["id_credito"],0,1);
        //Capital
        $this->SetFont('Arial','B',10);
        $this->Cell(29 ,5,'Capital:',0,0);
        $this->SetFont('Arial','',10);
        $this->Cell(10 ,5,$dataplanepay["credMontoDesem"],0,1);
        //Plazo
        $this->SetFont('Arial','B',10);
        $this->Cell(29 ,5,'Plazo:',0,0);
        $this->SetFont('Arial','',10);
        $this->Cell(10 ,5,$dataplanepay["credPlazo"].' MESES',0,1);
        //Taza
        $this->SetFont('Arial','B',10);
        $this->Cell(29 ,5,'Tasa:',0,0);
        $this->SetFont('Arial','',10);
        $this->Cell(10 ,5,$dataplanepay["credTasa"],0,1);
        //PPAGO
        $this->SetFont('Arial','B',10);
        $this->Cell(29 ,5,'P,Pago (K):',0,0);
        $this->SetFont('Arial','',10);
        $this->Cell(10 ,5,'30 '.$dataplanepay["credPeriPago"],0,1);
        //FPPAGO
        $this->SetFont('Arial','B',10);
        $this->Cell(29 ,5,'F.Pago:',0,0);
        $this->SetFont('Arial','',10);
        $this->Cell(10 ,5,$dataplanepay["credForPago"],0,1);
    }

    function createHeaderTable(){
        $this->Cell(50 ,5,'',0,1);
        $this->SetFont('Arial','B',10);
        /*Heading Of the table*/
        $x = $this->x;
        $y = $this->y;
        $push_right = 0;
        $this->setFillColor(119, 184, 66); 
        $this->MultiCell($w = 20,8,"Nro",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 25,4,"Fecha\nVcto.",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 25,8,"Capital",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 25,8,"Interes",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 25,8,"Cuota",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 25,8,"Cargos",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 25,8,"Total",1,'C',true);
        $push_right += $w;
        $this->SetXY($x + $push_right, $y);
        $this->MultiCell($w = 25,8,"Saldo",1,'C',true);
    }

    function createTableBody(
        $data,
        $path_logo, 
        $title, 
        $subtitle,
        $docu_cage,
        $name, 
        $lastname,
        $ci
    ) {
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
            
            $this->Cell(20 ,7,$data[$i]["credNumCuota"],1,0,'C');
            $this->Cell(25 ,7,$data[$i]["credFecVenci"],1,0,'C');
            $this->Cell(25 ,7,$data[$i]["credMontoCapi"],1,0,'R');
            $this->Cell(25 ,7,$data[$i]["credMontoInte"],1,0,'R');  
            $this->Cell(25 ,7,$data[$i]["crediTotaCuota"],1,0,'R');
            $this->Cell(25 ,7,$data[$i]["crediMontoCargos"],1,0,'R');

            $total = $data[$i]["credMontoCapi"] + $data[$i]["credMontoInte"] + $data[$i]["crediMontoCargos"];
            $this->Cell(25 ,7,$total,1,0,'R');

            $this->Cell(25 ,7,$data[$i]["crediSaldoCredi"],1,1,'R');
            $count++;
        }
    }

}

?>