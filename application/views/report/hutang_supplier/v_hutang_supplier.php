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
$pdf->SetMargins(1,1,1);
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

//foreach($rows->result() as $data)
//{
    
//}

//$tanggal = isset($data->InvoiceDate);
$height = 0.5;
$pdf->SetFont('Arial','',16);
$pdf->Cell(0,$height*1.5,'HUTANG USAHA',0,0,'C');
$pdf->Ln($height*1.5);
$pdf->Ln($height*2);

// START LOKAL //


$pdf->Ln($height*1.5);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0.7,$height,'NO',1,0,'C');
$pdf->Cell(7,$height,'NAMA SUPPLIER',1,0,'C');
$pdf->Cell(2,$height,'BULAN',1,0,'C');
$pdf->Cell(2,$height,'TAHUN',1,0,'C');
$pdf->Cell(3.5,$height,'INVOICE AMOUNT',1,0,'C');
$pdf->Cell(3.5,$height,'INVOICE AMOUNT IDR',1,0,'C');

$noUrut             = 1;
//$SumSalesBalance    = 0;
//$SumTax             = 0;
//$SumInvoiceAmount   = 0;

foreach($rows->result() as $data)
{   
    $pdf->Ln();
    $pdf->Cell(0.7,$height,$noUrut,1,0,'C');
    $pdf->Cell(7,$height,$data->Name,1,0,'L');
    $pdf->Cell(2,$height,$data->Bulan,1,0,'C');
    $pdf->Cell(2,$height,$data->Tahun,1,0,'C');
    $pdf->Cell(1,$height,'$','LTB',0,'L');
    $pdf->Cell(2.5,$height,number_format($data->InvoiceAmount, 2, ',', '.'),'TB',0,'R');
    $pdf->Cell(1,$height,'Rp.','LTB',0,'L');    
    $pdf->Cell(2.5,$height,number_format($data->InvoiceAmountIdr, 0, ',', '.'),'TRB',0,'R');
        
    $noUrut++;
    //$SumSalesBalance    += round($data->SalesBalance);
    //$SumTax             += round(ppn($data->SalesBalance,$data->Tax));
   // $SumInvoiceAmount   += round(amount($data->SalesBalance,$data->Tax));
      
}
/*
$pdf->SetFont('Arial','B',9);
$pdf->Ln($height);
$pdf->Cell(7.7,$height,'TOTAL',1,0,'C');
$pdf->Cell(1,$height,'Rp.','LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumSalesBalance, 0, ',', '.'),'TB',0,'R');
$pdf->Cell(1,$height,'Rp.','LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumTax, 0, ',', '.'),'TB',0,'R');
$pdf->Cell(1,$height,'Rp.','LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumInvoiceAmount, 0, ',', '.'),'TRB',0,'R');
 * 
 */

// END LOKAL //


$pdf->Output("Hutang Supplier.pdf","I");


/* 
 * End of file v_hutang_supplier.php
 * Location: ./views/report/hutang_supplier/v_hutang_supplier.php 
 */