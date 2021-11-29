<?php
require('../libs/fpdf.php');
require_once '../config/database.php';
require_once '../config/configure.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$certificateNumber = isset($_GET['certificate_number']) ? $_GET['certificate_number'] : null; //associate code
if ($certificateNumber == null) {
    die(json_encode([
        'error' => true,
        'errorMessage' => [
            "1" => 'Valor certificate_number no especificado'
        ],
        'errorCode' => 0,
        'result' => []
    ]));
}

$certificate = ORM::for_table('user_certificates')
    ->where('number', $certificateNumber)
    ->find_one();

$user = ORM::for_table('user')
    ->where('id', $certificate->user_id)
    ->find_one();


class PDF extends FPDF
{

    public $table;
    public $user;
    public function __construct($orientation = '', $unit = '', $size = '', $data)
    {
        parent::__construct($orientation, $unit, $size);
        $this->user = $data['user'];
    }

    public function Header()
    {
        $this->Image('../img/logo.png', 155, 18, 40);
        $this->SetFont('Arial', '', 12);
        $this->Cell(140, 8, 'COOPERATIVA DE AHORRO Y CREDITO LOYOLA', 0, 0, 'L');
        $this->Ln();
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(140, 10, utf8_decode('DETALLE CERTIFICADO APORTACIÓN'), 0, 0, 'L');
        $this->Ln();

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(30, 8, utf8_decode('Código Socio:'), 0);
        $this->SetFont('Arial', '', 12);
        $this->Cell(40, 8, $this->user->id_member, 0);

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(7, 8, 'CI:', 0);
        $this->SetFont('Arial', '', 12);
        $this->Cell(63, 8, $this->user->id_number, 0);
        $this->Ln();

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(14, 8, 'Socio:', 0);
        $this->SetFont('Arial', '', 12);
        $this->Cell(56, 8, "{$this->user->names} {$this->user->last_name_1} {$this->user->lastname_2}", 0);
        $this->Ln();
        $this->Ln();
    }

    public function Footer()
    {

        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 6, date('d/m/Y H:i'), 0, 0, 'C');
        $this->Ln();
        $this->Cell(0, 6, "Pagina {$this->PageNo()}/{nb}", 0, 0, 'C');
    }
}

$data =[
    'user' => $user
 ];

$pdf = new PDF('P', 'mm', 'Letter', $data);
$pdf->SetTitle('certificado-loyola');
$pdf->SetMargins(20,20,20);
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFillColor(156, 202, 134);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 8, "Detalle de Certificado", 'B', 0, 'L', true);
$pdf->Ln();


$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 8, utf8_decode('Nro. Certificado:'), 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(36, 8, $certificate->number, 0);

$pdf->Ln();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 8, utf8_decode('Gestión:'), 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 8, $certificate->year, 0);

$pdf->Ln();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 8, utf8_decode('Fecha Apertura:'), 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 8, $certificate->opening_date, 0);

$pdf->Ln();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 8, utf8_decode('Cantidad:'), 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 8, $certificate->amount, 0);

$pdf->Ln();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 8, utf8_decode('Monto:'), 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 8, $certificate->cost, 0);

$pdf->Ln();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(34, 8, utf8_decode('Estado:'), 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 8, $certificate->state, 0);


$pdf->Output('I', 'certificados-loyola.pdf');

