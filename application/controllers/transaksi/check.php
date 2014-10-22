<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Check extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_check','record');
    }
    
    function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid'])) 
            echo $this->record->index();        
         else 
            $this->load->view('transaksi/v_check');        
    } 

    function update()
    {
        if(!isset($_POST))	
            show_404();
        
        $InvoiceId      = addslashes($_POST['InvoiceId']);
        $SalesBalance   = addslashes($_POST['SalesBalance']);
        $checkdate      = addslashes($_POST['checkdate']);        
        
        if($this->record->update($InvoiceId))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
    
    function createVoucher()
    {
        if(!isset($_POST))	
            show_404();
        
        $OrderAccount       = addslashes($_POST['OrderAccount']);
        $PaymentDate        = addslashes($_POST['PaymentDate']);
        $PaymentNumber      = addslashes($_POST['PaymentNumber']); 
        $InvoiceAmount      = addslashes($_POST['InvoiceAmount']);
        $CurrencyCode       = addslashes($_POST['CurrencyCode']);
        $ExchRate           = addslashes($_POST['ExchRate']);        
        
        if($this->record->createVoucher())
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
    
}

/* End of file check.php */
/* Location: ./application/controllers/transaksi/check.php */