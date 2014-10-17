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

foreach($rows->result() as $data)
{
    
}

$tanggal = isset($data->InvoiceDate);
$height = 0.5;
$pdf->SetFont('Arial','',16);
$pdf->Cell(0,$height*1.5,'PEMBELIAN BARANG & JASA',0,0,'C');
$pdf->Ln($height*1.5);

if ($tanggal)
{
    $pdf->Cell(0,$height*1.5, format_date($data->InvoiceDate),0,0,'C');
}
else
{
    $pdf->Error('Data Tidak Ditemukan.');
}
$pdf->Ln($height*2);

// START LOKAL //

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,$height*1.5,'LOKAL',0,0,'L');
$pdf->Ln($height*1.5);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0.7,$height,'NO',1,0,'C');
$pdf->Cell(7,$height,'NAMA SUPPLIER',1,0,'C');
$pdf->Cell(3.5,$height,'DPP',1,0,'C');
$pdf->Cell(3.5,$height,'PPN',1,0,'C');
$pdf->Cell(3.5,$height,'TOTAL',1,0,'C');

$noUrut             = 1;
$SumSalesBalance    = 0;
$SumTax             = 0;
$SumInvoiceAmount   = 0;

foreach($rows->result() as $data)
{   
    if ($data->CurrencyCode == 'IDR')
    {
        if ($data->SalesBalance < 0)
        {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Ln();
            $pdf->Cell(0.7,$height,$noUrut,1,0,'C');
            $pdf->Cell(7,$height,$data->Name,1,0,'L');
            $pdf->Cell(1,$height,'Rp.','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round($data->SalesBalance), 0, ',', '.') ,'TB',0,'R');
            $pdf->Cell(1,$height,'Rp.','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round(ppn($data->SalesBalance,$data->Tax)), 0, ',', '.'),'TB',0,'R');
            $pdf->Cell(1,$height,'Rp.','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round(amount($data->SalesBalance,$data->Tax)), 0, ',', '.'),'TRB',0,'R');
        }
        else
        {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Ln();
            $pdf->Cell(0.7,$height,$noUrut,1,0,'C');
            $pdf->Cell(7,$height,$data->Name,1,0,'L');
            $pdf->Cell(1,$height,'Rp.','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round($data->SalesBalance), 0, ',', '.') ,'TB',0,'R');
            $pdf->Cell(1,$height,'Rp.','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round(ppn($data->SalesBalance,$data->Tax)), 0, ',', '.'),'TB',0,'R');
            $pdf->Cell(1,$height,'Rp.','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round(amount($data->SalesBalance,$data->Tax)), 0, ',', '.'),'TRB',0,'R');
        }       
        
        $noUrut++;
        $SumSalesBalance    += round($data->SalesBalance);
        $SumTax             += round(ppn($data->SalesBalance,$data->Tax));
        $SumInvoiceAmount   += round(amount($data->SalesBalance,$data->Tax));
    }  
}

$pdf->SetFont('Arial','B',9);
$pdf->Ln($height);
$pdf->Cell(7.7,$height,'TOTAL',1,0,'C');
$pdf->Cell(1,$height,'Rp.','LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumSalesBalance, 0, ',', '.'),'TB',0,'R');
$pdf->Cell(1,$height,'Rp.','LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumTax, 0, ',', '.'),'TB',0,'R');
$pdf->Cell(1,$height,'Rp.','LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumInvoiceAmount, 0, ',', '.'),'TRB',0,'R');

// END LOKAL //
// 
////////////////////
//
// START IMPORT //

$pdf->Ln($height*2);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,$height*1.5,'IMPORT',0,0,'L');
$pdf->Ln($height*1.5);
$pdf->SetFont('Arial','',9);
$pdf->Cell(0.7,$height,'NO',1,0,'C');
$pdf->Cell(7,$height,'NAMA SUPPLIER',1,0,'C');
$pdf->Cell(3.5,$height,'DPP',1,0,'C');
$pdf->Cell(3.5,$height,'PPN',1,0,'C');
$pdf->Cell(3.5,$height,'TOTAL',1,0,'C');

$noUrutImport             = 1;
$SumSalesBalanceImport    = 0;
$SumTaxImport             = 0;
$SumInvoiceAmountImport   = 0;

foreach($rows->result() as $data)
{   
    if ($data->CurrencyCode == 'USD')
    {
        if ($data->SalesBalance < 0)
        {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->Ln();
            $pdf->Cell(0.7,$height,$noUrutImport,1,0,'C');
            $pdf->Cell(7,$height,$data->Name,1,0,'L');
            $pdf->Cell(1,$height,'$','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round($data->SalesBalance,2), 2, ',', '.') ,'TB',0,'R');
            $pdf->Cell(1,$height,'$','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round(ppn($data->SalesBalance,$data->Tax),2), 2, ',', '.'),'TB',0,'R');
            $pdf->Cell(1,$height,'$','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round(amount($data->SalesBalance,$data->Tax),2), 2, ',', '.'),'TRB',0,'R');
        }
        else
        {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Ln();
            $pdf->Cell(0.7,$height,$noUrutImport,1,0,'C');
            $pdf->Cell(7,$height,$data->Name,1,0,'L');
            $pdf->Cell(1,$height,'$','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round($data->SalesBalance,2), 2, ',', '.') ,'TB',0,'R');
            $pdf->Cell(1,$height,'$','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round(ppn($data->SalesBalance,$data->Tax),2), 2, ',', '.'),'TB',0,'R');
            $pdf->Cell(1,$height,'$','LTB',0,'L');
            $pdf->Cell(2.5,$height,number_format(round(amount($data->SalesBalance,$data->Tax),2), 2, ',', '.'),'TRB',0,'R');
        }        
        
        $noUrutImport++;
        $SumSalesBalanceImport    += round($data->SalesBalance,2);
        $SumTaxImport             += round(ppn($data->SalesBalance,$data->Tax),2);
        $SumInvoiceAmountImport   += round(amount($data->SalesBalance,$data->Tax),2);
    }  
}

$pdf->SetFont('Arial','B',9);
$pdf->Ln($height);
$pdf->Cell(7.7,$height,'TOTAL',1,0,'C');
$pdf->Cell(1,$height,'$','LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumSalesBalanceImport, 2, ',', '.'),'TB',0,'R');
$pdf->Cell(1,$height,'$','LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumTaxImport, 2, ',', '.'),'TB',0,'R');
$pdf->Cell(1,$height,'$','LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumInvoiceAmountImport, 2, ',', '.'),'TRB',0,'R');

// END IMPORT //

$pdf->Output("Total Supplier.pdf","I");


/* 
 * End of file v_total_supplier.php
 * Location: ./views/report/total_supplier/v_total_supplier.php 
 */