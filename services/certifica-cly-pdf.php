<?php
require('../libs/fpdf.php');
require_once('../config/database.php');

define('FPDF_FONTPATH', "../libs/font");


$docuCage = isset($_GET['docu-cage'])?$_GET['docu-cage']:null; //associate code
if ($docuCage == null) {
   die(json_encode([
      'error'=> true,
      'errorMessage'=> [
         "1" =>'Valor docu-cage no especificado'
      ],
      'errorCode' => 0,
      'result' => []
   ]));
}

$certificates = ORM::for_table('user_certificates')
   ->select('user_certificates.year')
   ->select('user_certificates.number')
   ->select('user_certificates.opening_date')
   ->select('user_certificates.amount')
   ->select('user_certificates.cost')
   ->select('user_certificates.state')
   ->join('user', ['user_certificates.user_id', '=', 'user.id'])
   ->where('user.id_member', $docuCage)
   ->order_by_desc('user_certificates.year')
   ->find_array();
   
$user = ORM::for_table('user')
   ->where('id_member', $docuCage)
   ->find_one();
$param = $user->names;
// construct PDF
class PDF extends FPDF {

   public $table;
   public $user;
   public function __construct($orientation = '', $unit = '', $size = '', $data) {
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
      $this->Cell(140, 10, utf8_decode('DETALLE CERTIFICADOS APORTACIÓN'), 0, 0, 'L');
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
      $this->Cell(56, 8,"{$this->user->names} {$this->user->last_name_1} {$this->user->lastname_2}", 0);
      $this->Ln();
      $this->Ln();
   }
   public function makeTable($data)
   {
      $this->SetFillColor(156, 202, 134);
      for($i=0;$i<count($this->table->header);$i++)
         $this->Cell( 
            $this->table->header[$i]['w'], 
            7,
            $this->table->header[$i]['name'],
            1,
            0,
           'C',
            true
      );
      $this->Ln();
      
      foreach ($data as $row) {
         for($i=0;$i<count($this->table->header);$i++)
           $this->Cell( 
              $this->table->header[$i]['w'], 
              6,
              $this->table->header[$i]['prefix'].$row[$this->table->header[$i]['value']],
              1,
              0,
              $this->table->header[$i]['align']
            );
         $this->Ln();
      }
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
$pdf->SetTitle('certificados-loyola');
$pdf->SetMargins(20,20,20);
$pdf->AliasNbPages();
$pdf->AddPage();

// TABLE HEADER
$pdf->table = new stdClass();
$pdf->table->header = [
   ['name'=> utf8_decode('Gestión'), 'w'=> '20', 'value'=> 'year','prefix'=>'','align'=>"C"],
   ['name'=>'Nro. Certificado', 'w'=>'36', 'value'=> 'number', 'prefix'=>'','align'=>"C"], 
   ['name'=>'Fecha Apertura', 'w'=>'34', 'value'=> 'opening_date','prefix'=>'', 'align'=>"C"], 
   ['name'=>'Cantidad', 'w'=> '25', 'value'=> 'amount','prefix'=>'', 'align'=>"C"],
   ['name'=>'Monto', 'w' => '30', 'value'=> 'cost', 'prefix'=>'Bs. ', 'align'=>"R"],
   ['name'=>'Estado', 'w'=> '30', 'value'=> 'state','prefix'=>'', 'align'=>"C"]
];
$pdf->makeTable($certificates);
echo $pdf->Output('I', 'certificados-loyola.pdf');
