<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_invoice','record');
    }
    
    function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid'])) 
            echo $this->record->index();        
         else 
            $this->load->view('master/v_invoice');        
    } 
    
    function create()
    {
        if(!isset($_POST))	
            show_404();

        if($this->record->create())
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal memasukkan data'));
    }     
    
    function update($InvoiceId=null)
    {
        if(!isset($_POST))	
            show_404();

        if($this->record->update($InvoiceId))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal mengubah data'));
    }
    
    function update2()
    {
        if(!isset($_POST))	
            show_404();
        
        $InvoiceId         = intval(addslashes($_POST['InvoiceId']));
        $checkdate  = addslashes($_POST['checkdate']);        
        
        if($this->record->update2($InvoiceId))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
        
    function delete()
    {
        if(!isset($_POST))	
            show_404();

        $InvoiceId = intval(addslashes($_POST['InvoiceId']));
        if($this->record->delete($InvoiceId))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
    
    function upload()
    {
        move_uploaded_file($_FILES["file"]["tmp_name"],
                "assets/temp_upload/" . $_FILES["file"]["name"]);
        $this->load->library('excel_reader');
        $this->excel_reader->setOutputEncoding('CP1251');
       // $this->excel_reader->read('./assets/temp_upload/'.$file);
        $this->excel_reader->read('assets/temp_upload/' . $_FILES["file"]["name"]);
        error_reporting(E_ALL ^ E_NOTICE);
        
        // Get the contents of the first worksheet
        $data = $this->excel_reader->sheets[0];
        
        // jumlah baris
        $baris  = $data['numRows'];
        //$kolom  = $data['numCols'];
        $ok = 0;
        $ng = 0;
        
        for ($i = 1; $i <= $baris; $i++)
        {
           $OrderAccount        = $data['cells'][$i][1];
           $InvoiceId           = $data['cells'][$i][2];
           $InvoiceDate         = $data['cells'][$i][3];
           $Qty                 = $data['cells'][$i][4];
           $SalesBalance        = $data['cells'][$i][5];
           //$InvoiceAmount       = $data['cells'][$i][6];
           $CurrencyCode        = $data['cells'][$i][6];
           $ExchRate            = $data['cells'][$i][7];
           //$InvoiceRoundOff     = $data['cells'][$i][9];
           //$TaxGroup            = $data['cells'][$i][10];
           //$SumTax              = $data['cells'][$i][11];
           //$InvoiceAmountMST    = $data['cells'][$i][12];
           
          /* $query = $this->record->upload($OrderAccount, $InvoiceId, $InvoiceDate, $Qty,
                                        $SalesBalance, $InvoiceAmount, $CurrencyCode, $ExchRate,
                                        $InvoiceRoundOff, $TaxGroup, $SumTax, $InvoiceAmountMST);
           * 
           */
           $query = $this->record->upload($OrderAccount, $InvoiceId, $InvoiceDate, $Qty,
                                        $SalesBalance, $CurrencyCode, $ExchRate);
           if ($query)
           {
               $ok++;
           }
           else
           {
               $ng++;
           }
        }
        unlink('assets/temp_upload/' . $_FILES["file"]["name"]);
        echo json_encode(array('success'=>true,
                                'total'=>'Total Data: '.($baris),
                                'ok'=>'Data OK: '.$ok,
                                'ng'=>'Data NG: '.$ng));
        //echo json_encode(array('total'=>$baris-1));
        //echo json_encode(array('berhasil'=>$sukses));
        //echo json_encode(array('gagal'=>$gagal));
    }

}

/* End of file invoice.php */
/* Location: ./application/controllers/master/invoice.php */