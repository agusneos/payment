<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PDF extends FPDF
{    
    function Header()
    {
        // Logo
        $this->Image('assets/images/saga_logo.jpg', 1, 1,7.5,1.2);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Ln(1);
        $this->Cell(7);
        // Title
        //$this->Cell(5,1,'Daftar PKWT',0,0,'C');
        // Line break
        $this->Ln(1);
    }   
    
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial Arial 10
        $this->SetFont('Arial','',10);;
        // Page number
        $this->SetTextColor(0, 0, 0);
        $this->Text(17.9,28.5,'Page '.$this->PageNo().' / {nb}');
        // Image
        //$this->Image('assets/images/confidential_1.jpg', 11, 27.7,6,1.5);
    }
}

// definisi class
$pdf = new PDF();
// variable awal
date_default_timezone_set('Asia/Jakarta');
$pdf->FPDF("P","cm","A4");
$pdf->SetMargins(0.7,1,1);
$pdf->AliasNbPages();
$pdf->AddPage();


function format_date($tgl)
{
    setlocale (LC_TIME, 'INDONESIAN');
    $tgl = strtotime($tgl);
    $st = strftime( "%B %Y", $tgl);
    return strtoupper($st);
}

function format_date_data($tgl)
{
    setlocale (LC_TIME, 'INDONESIAN');
    $tgl = strtotime($tgl);
    $st = strftime( "%d %b %y", $tgl);
    return strtoupper($st);
}

foreach($rows->result() as $data)
{
    
}

$tanggal = isset($data->PaymentDate);
$height = 0.5;
$pdf->SetFont('Arial','',16);
$pdf->Cell(0,$height*1.5,'LAPORAN PEMBAYARAN',0,0,'C');
$pdf->Ln($height*1.5);

if ($tanggal)
{
    $pdf->Cell(0,$height*1.5, format_date($data->PaymentDate),0,0,'C');
}
else
{
    $pdf->Error('Data Tidak Ditemukan.');
}
$pdf->Ln($height*3);
$pdf->SetFont('Arial','B',9);
$pdf->Cell(0.7,$height,'NO',1,0,'C');
$pdf->Cell(7,$height,'NAMA SUPPLIER',1,0,'C');
$pdf->Cell(2,$height,'TANGGAL',1,0,'C');
$pdf->Cell(4,$height,'NO. VOUCHER',1,0,'C');
$pdf->Cell(3,$height,'AMOUNT USD',1,0,'C');
$pdf->Cell(3,$height,'AMOUNT IDR',1,0,'C');

$noUrut         = 1;
$SumAmountUSD   = 0;
$SumAmountIDR   = 0;

$pdf->SetFont('Arial','',8);
foreach($rows->result() as $data)
{ 
        $pdf->Ln();
        $pdf->Cell(0.7,$height,$noUrut,1,0,'C');
        $pdf->Cell(7,$height,$data->Name,1,0,'L');
        $pdf->Cell(2,$height,  format_date_data($data->PaymentDate),1,0,'C');
        $pdf->Cell(4,$height,$data->PaymentNumber,1,0,'L');
        $pdf->Cell(0.7,$height,'$','LTB',0,'L');
        $pdf->Cell(2.3,$height,number_format(round($data->DebetUSD, 2), 2, ',', '.') ,'TB',0,'R');
        $pdf->Cell(0.7,$height,'Rp.','LTB',0,'L');
        $pdf->Cell(2.3,$height,number_format(round($data->DebetIDR), 0, ',', '.') ,'TBR',0,'R');   

    $noUrut++;
    $SumAmountUSD  += round($data->DebetUSD, 2);
    $SumAmountIDR  += round($data->DebetIDR);
      
}

$pdf->SetFont('Arial','B',8);
$pdf->Ln($height);
$pdf->Cell(13.7,$height,'TOTAL',1,0,'C');
$pdf->Cell(0.7,$height,'$','LTB',0,'L');
$pdf->Cell(2.3,$height,number_format($SumAmountUSD, 2, ',', '.'),'TB',0,'R');
$pdf->Cell(0.7,$height,'Rp.','LTB',0,'L');
$pdf->Cell(2.3,$height,number_format($SumAmountIDR, 0, ',', '.'),'TBR',0,'R');

$pdf->Output("Laporan Pembayaran.pdf","I");


/* 
 * End of file v_total_supplier.php
 * Location: ./views/report/total_supplier/v_total_supplier.php 
 */