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

function currency($cur)
{
    if ($cur == 'IDR')
    {
        return 'Rp.';
    }
    else
    {
        return '$';
    }
}

function ppn($ppn)
{
    
}

foreach($rows->result() as $data)
{
    
}

$height = 0.5;
$pdf->SetFont('Arial','',16);
$pdf->Cell(0,$height*1.5,'PEMBELIAN BARANG & JASA',0,0,'C');
$pdf->Ln($height*1.5);
$pdf->Cell(0,$height*1.5,  format_date($data->InvoiceDate),0,0,'C');
$pdf->Ln($height*2);

$pdf->SetFont('Arial','',9);
$pdf->Cell(0.7,$height,'NO',1,0,'C');
$pdf->Cell(7,$height,'NAMA SUPPLIER',1,0,'C');
$pdf->Cell(3.5,$height,'DPP',1,0,'C');
$pdf->Cell(3.5,$height,'PPN',1,0,'C');
$pdf->Cell(3.5,$height,'TOTAL',1,0,'C');

$cur    = currency($data->CurrencyCode);
/*
foreach($rows->result() as $data)
{
    
}
$pdf->Cell(5,$height,': '.$data->Manager,0,0,'L');

$pdf->Ln();
$pdf->Cell(3,$height,'Dari',0,0,'L');
$pdf->Cell(3,$height,': HRD',0,0,'L');
$pdf->Ln();
$pdf->Cell(3,$height,'Perihal',0,0,'L');
$pdf->Cell(3,$height,': Habis Kontrak',0,0,'L');
$pdf->Ln($height*2);
$pdf->Cell(3,$height,'Dengan Hormat,',0,0,'L');
$pdf->Ln($height*2);
$kata1 = 'Berikut diberitahukan data karyawan yang habis kontraknya pada bulan '.
        periode().' sebagai berikut :';
$pdf->MultiCell(0,$height,$kata1,0,'J');

$pdf->SetFont('Arial','B',9);
$pdf->Ln($height);
$pdf->Cell(1,$height*2,'No',1,0,'C');
$pdf->Cell(5,$height*2,'Nama',1,0,'C');
$pdf->Cell(5,$height*2,'Bagian',1,0,'C');
$pdf->Cell(2.5,$height*2,'Tanggal Masuk',1,0,'C');
$pdf->Cell(2.5,$height*2,'Habis Kontrak',1,0,'C');
$pdf->Cell(3,$height,'Kontrak ke',1,2,'C');
$pdf->Cell(1,$height,'1',1,0,'C');
$pdf->Cell(1,$height,'P',1,0,'C');
$pdf->Cell(1,$height,'2',1,0,'C');
 * 
 */

//$pdf->SetFont('Arial','',9);
$noUrut             = 1;
$SumSalesBalance    = 0;
$SumTax             = 0;
$SumInvoiceAmount   = 0;

///Awal Data Ditampilkan
 

foreach($rows->result() as $data)
{
    
    $pdf->Ln();
    $pdf->Cell(0.7,$height,$noUrut,1,0,'C');
    $pdf->Cell(7,$height,$data->Name,1,0,'L');
    $pdf->Cell(1,$height,$cur,'LTB',0,'L');
    $pdf->Cell(2.5,$height,number_format($data->SalesBalance, 0, ',', '.') ,'TB',0,'R');
    $pdf->Cell(1,$height,$cur,'LTB',0,'L');
    $pdf->Cell(2.5,$height,number_format(round($data->SalesBalance*0.1), 0, ',', '.'),'TB',0,'R');
    $pdf->Cell(1,$height,$cur,'LTB',0,'L');
    $pdf->Cell(2.5,$height,number_format(round($data->SalesBalance*1.1), 0, ',', '.'),'TRB',0,'R');

    $noUrut++;
    $SumSalesBalance    += $data->SalesBalance;
    $SumTax             += $data->SalesBalance*0.1;
    $SumInvoiceAmount   += $data->SalesBalance*1.1;
   
}
$pdf->SetFont('Arial','B',9);
$pdf->Ln($height);
$pdf->Cell(7.7,$height,'TOTAL',1,0,'C');
$pdf->Cell(1,$height,$cur,'LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumSalesBalance, 0, ',', '.'),'TB',0,'R');
$pdf->Cell(1,$height,$cur,'LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumTax, 0, ',', '.'),'TB',0,'R');
$pdf->Cell(1,$height,$cur,'LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumInvoiceAmount, 0, ',', '.'),'TRB',0,'R');
/*
$pdf->Ln($height*3);
$kata2 = 'Demikian pemberitahuan ini disampaikan, harap memberitahukan ke bagian '. 
            'HRD atas kelanjutan kontrak-kontrak tersebut diatas. Terima kasih.';
$pdf->MultiCell(0,$height,$kata2,0,'J');
$pdf->Ln($height);

$pdf->SetX(10);
$pdf->Cell(3,$height,'Dibuat',0,0,'C');
$pdf->SetX(15);
$pdf->Cell(3,$height,'Diketahui',0,0,'C');

$pdf->Ln($height*5);
$pdf->SetX(10);
$pdf->Cell(3,$height,'Yuli',0,0,'C');
$pdf->SetX(15);
$pdf->Cell(3,$height,'Bambang T',0,0,'C');
 * 
 */

/*
$pdf->Ln($height*3);
$pdf->SetFont('Arial','',9);
foreach($rows->result() as $data)
{
    
    $pdf->Ln();
    $pdf->Cell(0.7,$height,$noUrut,1,0,'C');
    $pdf->Cell(7,$height,$data->Name,1,0,'L');
    $pdf->Cell(1,$height,$cur,'LTB',0,'L');
    $pdf->Cell(2.5,$height,number_format($data->SalesBalance, 0, ',', '.') ,'TB',0,'R');
    $pdf->Cell(1,$height,$cur,'LTB',0,'L');
    $pdf->Cell(2.5,$height,number_format(round($data->SalesBalance*0.1), 0, ',', '.'),'TB',0,'R');
    $pdf->Cell(1,$height,$cur,'LTB',0,'L');
    $pdf->Cell(2.5,$height,number_format(round($data->SalesBalance*1.1), 0, ',', '.'),'TRB',0,'R');

    $noUrut++;
    $SumSalesBalance    += $data->SalesBalance;
    $SumTax             += $data->SalesBalance*0.1;
    $SumInvoiceAmount   += $data->SalesBalance*1.1;
   
}
$pdf->SetFont('Arial','B',9);
$pdf->Ln($height);
$pdf->Cell(7.7,$height,'TOTAL',1,0,'C');
$pdf->Cell(1,$height,$cur,'LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumSalesBalance, 0, ',', '.'),'TB',0,'R');
$pdf->Cell(1,$height,$cur,'LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumTax, 0, ',', '.'),'TB',0,'R');
$pdf->Cell(1,$height,$cur,'LTB',0,'L');
$pdf->Cell(2.5,$height,number_format($SumInvoiceAmount, 0, ',', '.'),'TRB',0,'R');
 * 
 */

$pdf->Output("Total Supplier.pdf","I");


/* End of file v_habis_kontrak.php */
/* Location: ./views/report/habis_kontrak/v_habis_kontrak.php */