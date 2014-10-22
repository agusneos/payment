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
$pdf->SetMargins(0.5,1,0.5);
//$pdf->AliasNbPages();
$pdf->AddPage();


function format_date($tgl)
{
    setlocale (LC_TIME, 'INDONESIAN');
    $tgl = strtotime($tgl);
    $st = strftime( "%d %b %y", $tgl);
    return strtoupper($st);
}

function hari_ini()
{
    setlocale (LC_TIME, 'INDONESIAN');
    $st = strftime( "%d %B %Y", strtotime(date('d-F-Y')));
    return $st;
}

foreach($rows->result() as $data)
{
    
}

//$tanggal = isset($data->InvoiceDate);
$height = 0.5;
$pdf->SetFont('Arial','',14);
$pdf->Cell(0,$height*1.5,'RINCIAN SALDO SUPPLIER',0,0,'C');
$pdf->Ln($height*1.5);
$pdf->Cell(0,$height*1.5,'GLOBAL INC',0,0,'C');
$pdf->Ln($height*1.5);
$pdf->Cell(0,$height*1.5,'TAHUN 2014',0,0,'C');
$pdf->Ln($height*2);

// START LOKAL //

$pdf->SetFont('Arial','',7);
//$pdf->Cell(2,$height,'OrderAccount',1,0,'C');
$pdf->Cell(2,$height,'PaymentDate',1,0,'C');
$pdf->Cell(3,$height,'PaymentNumber',1,0,'C');
$pdf->Cell(3,$height,'Note',1,0,'C');
$pdf->Cell(2,$height,'DebetUSD',1,0,'C');
$pdf->Cell(2,$height,'DebetIDR',1,0,'C');
$pdf->Cell(2,$height,'KreditUSD',1,0,'C');
$pdf->Cell(2,$height,'KreditIDR',1,0,'C');
$pdf->Cell(2,$height,'saldo_usd',1,0,'C');
$pdf->Cell(2,$height,'saldo_IDR',1,0,'C');


foreach($rows->result() as $data)
{
    if ($data->DebetIDR > 0)
    {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Ln();
        $pdf->Cell(2,$height,format_date($data->PaymentDate),1,0,'L');
        $pdf->Cell(3,$height,$data->PaymentNumber,1,0,'L');
        $pdf->Cell(3,$height,$data->Note,1,0,'L');
        $pdf->Cell(2,$height,number_format($data->DebetUSD, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->DebetIDR, 0, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->KreditUSD, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->KreditIDR, 0, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->saldo_usd, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->saldo_IDR, 0, ',', '.'),1,0,'R'); 
    }
    else
    {
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln();
        $pdf->Cell(2,$height,format_date($data->PaymentDate),1,0,'L');
        $pdf->Cell(3,$height,$data->PaymentNumber,1,0,'L');
        $pdf->Cell(3,$height,$data->Note,1,0,'L');
        $pdf->Cell(2,$height,number_format($data->DebetUSD, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->DebetIDR, 0, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->KreditUSD, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->KreditIDR, 0, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->saldo_usd, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->saldo_IDR, 0, ',', '.'),1,0,'R'); 
    }
}

// END LOKAL //

$pdf->Output("Saldo Supplier.pdf","I");


/* 
 * End of file v_saldo_supplier.php
 * Location: ./views/report/saldo_supplier/v_saldo_supplier.php 
 */