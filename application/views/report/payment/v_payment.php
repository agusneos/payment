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

}

// definisi class
$pdf = new PDF();
// variable awal
date_default_timezone_set('Asia/Jakarta');
$pdf->FPDF("P","cm","A4");
$pdf->SetMargins(0.2,1,1);
//$pdf->AliasNbPages();
$pdf->AddPage();

function tgal($date)
{
    setlocale (LC_TIME, 'INDONESIAN');
    $st = strftime( "%d %B %Y", strtotime($date));
    return strtoupper($st);
}

$height = 0.5;
$pdf->SetFont('Arial','',16);
$pdf->Cell(0,$height*1.5,'REKAP PAYMENT HARIAN',0,0,'C');
$pdf->Ln($height*1.5);
$row = $date->first_row();
$pdf->Cell(0,$height*1.5,'PER '.  tgal($row->Tanggal),0,0,'C');

// START LOKAL //


$pdf->Ln($height*2);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0.5,$height,'',0,0,'C');
$pdf->Cell(0.5,$height,'No',1,0,'C');
$pdf->Cell(6,$height,'NAMA SUPPLIER',1,0,'C');
$pdf->Cell(3,$height,'VOUCHER',1,0,'C');
$pdf->Cell(3,$height,'INVOICE',1,0,'C');
$pdf->Cell(2,$height,'TANGGAL',1,0,'C');
$pdf->Cell(2.5,$height,'AMOUNT',1,0,'C');
$pdf->Cell(2.5,$height,'AMOUNT IDR',1,0,'C');

$noUrut                 = 1;
$SumInvoiceAmount       = 0;
$SumInvoiceAmountIdr    = 0;
//$SumInvoiceAmount   = 0;

foreach($rows->result() as $data)
{   
    $pdf->Ln();
    $pdf->Cell(0.5,$height,'',0,0,'C');
    $pdf->Cell(0.5,$height,$noUrut,1,0,'C');
    $pdf->Cell(6,$height,$data->Name,1,0,'L');
    $pdf->Cell(3,$height,$data->PaymentNumber,1,0,'C');
    $pdf->Cell(3,$height,$data->Note,1,0,'C');
    $pdf->Cell(2,$height,$data->PaymentDate,1,0,'C');
    $pdf->Cell(0.5,$height,'$','LTB',0,'L');
    $pdf->Cell(2,$height,number_format($data->DebetUSD, 2, ',', '.'),'TB',0,'R');
    $pdf->Cell(0.5,$height,'Rp.','LTB',0,'L');    
    $pdf->Cell(2,$height,number_format($data->DebetIDR, 0, ',', '.'),'TRB',0,'R');
        
    $noUrut++;
    $SumInvoiceAmount       += round($data->DebetUSD, 2);
    $SumInvoiceAmountIdr    += round($data->DebetIDR);
      
}

$pdf->SetFont('Arial','B',9);
$pdf->Ln($height);
$pdf->Cell(0.5,$height,'',0,0,'C');
$pdf->Cell(14.5,$height,'TOTAL',1,0,'C');
$pdf->Cell(0.5,$height,'$','LTB',0,'L');
$pdf->Cell(2,$height,number_format($SumInvoiceAmount, 2, ',', '.'),'TB',0,'R');
$pdf->Cell(0.5,$height,'Rp.','LTB',0,'L');;
$pdf->Cell(2,$height,number_format($SumInvoiceAmountIdr, 0, ',', '.'),'TRB',0,'R');


// END LOKAL //


$pdf->Output("REKAP PAYMENT HARIAN","I");


/* 
 * End of file v_payment.php
 * Location: ./views/report/payment/v_payment.php 
 */