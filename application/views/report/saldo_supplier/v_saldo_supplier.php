<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

foreach($rows->result() as $data)
{
    
}

$tanggal = isset($data->PaymentDate);
if ($tanggal)
{    
    global $vendor;
    $vendor = $data->Name;
}
else
{
    $pdf = new PDF();
    $pdf->Error('Data Tidak Ditemukan.');
}
global $tahun;
$tahun = $data->PaymentDate;

class PDF extends FPDF
{
    function tahun($tgl)
    {
        setlocale (LC_TIME, 'INDONESIAN');
        $tgl = strtotime($tgl);
        $st = strftime( "Tahun : %Y", $tgl);
        return strtoupper($st);
    }
    
    function Header()
    {        
        $this->SetTextColor(0, 0, 0);
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
        $this->Ln();
        
        $this->SetFont('Arial','',14);
        $this->Cell(0,0.5*1.5,'RINCIAN SALDO SUPPLIER',0,0,'C');
        $this->Ln(0.5*1.5);
        $vendor = $GLOBALS['vendor'];
        $this->Cell(0,0.5*1.5,$vendor,0,0,'C');
        $this->Ln(0.5*1.5);
        $tahun = $GLOBALS['tahun'];
        $this->Cell(0,0.5*1.5,$this->tahun($tahun),0,0,'C');
        $this->Ln(0.5*2);

        $this->SetFont('Arial','B',7);
        $this->Cell(2,0.5,'Tanggal',1,0,'C');
        $this->Cell(3,0.5,'No. Fakt. / Inv / Nota',1,0,'C');
        $this->Cell(3,0.5,'Keterangan',1,0,'C');
        $this->Cell(2,0.5,'DEBET USD',1,0,'C');
        $this->Cell(2,0.5,'DEBET IDR',1,0,'C');
        $this->Cell(2,0.5,'KREDIT USD',1,0,'C');
        $this->Cell(2,0.5,'KREDIT IDR',1,0,'C');
        $this->Cell(2,0.5,'SALDO USD',1,0,'C');
        $this->Cell(2,0.5,'SALDO IDR',1,0,'C');
        $this->Ln();
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
$pdf->SetMargins(0.5,1,0.5);
$pdf->AliasNbPages(); //untuk memunculkan halaman
$pdf->AddPage();

function format_date($tgl)
{
    setlocale (LC_TIME, 'INDONESIAN');
    $tgl = strtotime($tgl);
    $st = strftime( "%d %b %y", $tgl);
    return strtoupper($st);
}

// START LOKAL //
$height = 0.5;
$pdf->SetFont('Arial','',7);

foreach($rows->result() as $data)
{
    if ($data->DebetIDR > 0)
    {
        $pdf->SetTextColor(255, 0, 0);        
        $pdf->Cell(2,$height,format_date($data->PaymentDate),1,0,'L');
        $pdf->Cell(3,$height,$data->PaymentNumber,1,0,'L');
        $pdf->Cell(3,$height,$data->Note,1,0,'L');
        $pdf->Cell(2,$height,number_format($data->DebetUSD, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->DebetIDR, 0, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->KreditUSD, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->KreditIDR, 0, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->saldo_usd, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->saldo_IDR, 0, ',', '.'),1,0,'R');
        $pdf->Ln();
    }
    else
    {
        $pdf->SetTextColor(0, 0, 0);        
        $pdf->Cell(2,$height,format_date($data->PaymentDate),1,0,'L');
        $pdf->Cell(3,$height,$data->PaymentNumber,1,0,'L');
        $pdf->Cell(3,$height,$data->Note,1,0,'L');
        $pdf->Cell(2,$height,number_format($data->DebetUSD, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->DebetIDR, 0, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->KreditUSD, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->KreditIDR, 0, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->saldo_usd, 2, ',', '.'),1,0,'R');
        $pdf->Cell(2,$height,number_format($data->saldo_IDR, 0, ',', '.'),1,0,'R'); 
        $pdf->Ln();
    }
}

// END LOKAL //

$pdf->Output("Saldo Supplier.pdf","I");


/* 
 * End of file v_saldo_supplier.php
 * Location: ./views/report/saldo_supplier/v_saldo_supplier.php 
 */