<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_payment','record');
    }
    
    function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid'])) 
            echo $this->record->index();        
         else 
            $this->load->view('transaksi/v_payment');        
    } 

    function update()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();        
        $InvoiceId              = addslashes($_POST['InvoiceId']);
        $PayDate                = addslashes($_POST['PayDate']);
       
        if($this->record->update($InvoiceId, $PayDate))
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
    
    function createVoucher()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();        
        
        if($this->record->createVoucher())
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
    
    
    /*
    function createVoucherInvoice()
    {
        $auth   = new Auth();
        $auth->restrict();
        
        if(!isset($_POST))	
            show_404();        
        //$id                 = intval(addslashes($_POST['id']));
        $OrderAccount       = addslashes($_POST['OrderAccount']);
        $PaymentNumber      = addslashes($_POST['PaymentNumber']);
        $PaymentDate        = addslashes($_POST['PaymentDate']); 
        $CurrencyCode       = addslashes($_POST['CurrencyCode']); 
        $ExchRate           = addslashes($_POST['ExchRate']);
        $InvoiceAmount      = addslashes($_POST['InvoiceAmount']);
        $InvoiceAmountMST   = addslashes($_POST['InvoiceAmountMST']);
        $PaymentCreateDate  = addslashes($_POST['PaymentCreateDate']);
        
        if($this->record->createVoucherInvoice())
        {
            echo json_encode(array('success'=>true));
        }
        else
        {
            echo json_encode(array('success'=>false));
        }
    }
     * 
     */   

}

/* End of file payment.php */
/* Location: ./application/controllers/transaksi/payment.php */