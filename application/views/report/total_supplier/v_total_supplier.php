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
$pdf->SetMargins(3,1,1);
//$pdf->AliasNbPages();
$pdf->AddPage();


function format_date($tgl)
{
    setlocale (LC_TIME, 'INDONESIAN');
    $tgl = strtotime($tgl);
    $st = strftime( "%B %Y", $tgl);
    return strtoupper($st);
}

function hari_ini()
{
    setlocale (LC_TIME, 'INDONESIAN');
    $st = strftime( "%d %B %Y", strtotime(date('d-F-Y')));
    return $st;
}

function ppn($salesbalance, $tax)
{
    if ($tax == 'PPN')
    {
        return $salesbalance * 0.1;
    }
    else
    {
        return '';
    }
}

function amount($salesbalance, $tax)
{
    if ($tax == 'PPN')
    {
        return $salesbalance * 1.1;
    }
    else
    {
        return $salesbalance;
    }
}

foreach($rows->result() as $data)
{
    
}

$tanggal = isset($data->AcceptDate);
$height = 0.5;
$pdf->SetFont('Arial','',16);
$pdf->Cell(0,$height*1.5,'PEMBELIAN BARANG & JASA',0,0,'C');
$pdf->Ln($height*1.5);

if ($tanggal)
{
    $pdf->Cell(0,$height*1.5, format_date($data->AcceptDate),0,0,'C');
}
else
{
    $pdf->Error('Data Tidak Ditemukan.');
}
$pdf->Ln($height*3);

// START LOKAL //


$pdf->SetFont('Arial','B',9);
$pdf->Cell(0.7,$height,'NO',1,0,'C');
$pdf->Cell(7,$height,'NAMA SUPPLIER',1,0,'C');
$pdf->Cell(3,$height,'Amount USD',1,0,'C');
$pdf->Cell(3,$height,'Amount IDR',1,0,'C');
//$pdf->Cell(3,$height,'PPN',1,0,'C');
//$pdf->Cell(3,$height,'TOTAL',1,0,'C');

$noUrut     = 1;
$SumDppUsd  = 0;
$SumDppIdr  = 0;
$SumPpn     = 0;
$SumTotal   = 0;

$pdf->SetFont('Arial','',8);
foreach($rows->result() as $data)
{  
    if ($data->DPP_IDR < 0)
    {
        $pdf->SetTextColor(255, 0, 0);
        $pdf->Ln();
        $pdf->Cell(0.7,$height,$noUrut,1,0,'C');
        $pdf->Cell(7,$height,$data->Name,1,0,'L');
        $pdf->Cell(0.7,$height,'$','LTB',0,'L');
        $pdf->Cell(2.3,$height,number_format(round($data->DPP_USD, 2), 2, ',', '.') ,'TB',0,'R');
        $pdf->Cell(0.7,$height,'Rp.','LTB',0,'L');
        $pdf->Cell(2.3,$height,number_format(round($data->DPP_IDR), 0, ',', '.') ,'TRB',0,'R');
        //$pdf->Cell(0.7,$height,'Rp.','LTB',0,'L');
        //$pdf->Cell(2.3,$height,number_format(round($data->PPN), 0, ',', '.'),'TB',0,'R');
        //$pdf->Cell(0.7,$height,'Rp.','LTB',0,'L');
        //$pdf->Cell(2.3,$height,number_format(round($data->TOTAL), 0, ',', '.'),'TRB',0,'R');
    }
    else
    {
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln();
        $pdf->Cell(0.7,$height,$noUrut,1,0,'C');
        $pdf->Cell(7,$height,$data->Name,1,0,'L');
        $pdf->Cell(0.7,$height,'$','LTB',0,'L');
        $pdf->Cell(2.3,$height,number_format(round($data->DPP_USD, 2), 2, ',', '.') ,'TB',0,'R');
        $pdf->Cell(0.7,$height,'Rp.','LTB',0,'L');
        $pdf->Cell(2.3,$height,number_format(round($data->DPP_IDR), 0, ',', '.') ,'TRB',0,'R');
        //$pdf->Cell(0.7,$height,'Rp.','LTB',0,'L');
        //$pdf->Cell(2.3,$height,number_format(round($data->PPN), 0, ',', '.'),'TB',0,'R');
        //$pdf->Cell(0.7,$height,'Rp.','LTB',0,'L');
        //$pdf->Cell(2.3,$height,number_format(round($data->TOTAL), 0, ',', '.'),'TRB',0,'R');
    }       

    $noUrut++;
    $SumDppUsd  += round($data->DPP_USD, 2);
    $SumDppIdr  += round($data->DPP_IDR);
    //$SumPpn     += round($data->PPN);
    //$SumTotal   += round($data->TOTAL);
      
}

$pdf->SetFont('Arial','B',8);
$pdf->Ln($height);
$pdf->Cell(7.7,$height,'TOTAL',1,0,'C');
$pdf->Cell(0.7,$height,'$','LTB',0,'L');
$pdf->Cell(2.3,$height,number_format($SumDppUsd, 2, ',', '.'),'TB',0,'R');
$pdf->Cell(0.7,$height,'Rp.','LTB',0,'L');
$pdf->Cell(2.3,$height,number_format($SumDppIdr, 0, ',', '.'),'TRB',0,'R');
//$pdf->Cell(0.7,$height,'Rp.','LTB',0,'L');
//$pdf->Cell(2.3,$height,number_format($SumPpn, 0, ',', '.'),'TB',0,'R');
//$pdf->Cell(0.7,$height,'Rp.','LTB',0,'L');
//$pdf->Cell(2.3,$height,number_format($SumTotal, 0, ',', '.'),'TRB',0,'R');

// END LOKAL //
$pdf->Output("Total Supplier.pdf","I");


/* 
 * End of file v_total_supplier.php
 * Location: ./views/report/total_supplier/v_total_supplier.php 
 */