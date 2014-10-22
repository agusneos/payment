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
        if(!isset($_POST))	
            show_404();        
        $InvoiceId              = addslashes($_POST['InvoiceId']);
        $PaymentSisa            = addslashes($_POST['PaymentSisa']);
       
        if($this->record->update($InvoiceId))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal Update data'));
    }
    
    function createVoucher()
    {
        if(!isset($_POST))	
            show_404();        
        //$id                 = intval(addslashes($_POST['id']));
        $OrderAccount       = addslashes($_POST['OrderAccount']);
        $PaymentDate        = addslashes($_POST['PaymentDate']);
        $PaymentNumber      = addslashes($_POST['PaymentNumber']); 
        $Note               = addslashes($_POST['Note']); 
        $InvoiceAmount      = addslashes($_POST['InvoiceAmount']);
        $CurrencyCode       = addslashes($_POST['CurrencyCode']);
        $ExchRate           = addslashes($_POST['ExchRate']);
        
        if($this->record->createVoucher())
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal'));
    }
    
    /*function createVoucher()
    {
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
        if($this->record->createVoucher())
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal'));
    }
     * 
     */
    
    function createVoucherInvoice()
    {
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
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal'));
    }
    
    function checkData()
    {
        if(!isset($_POST))	
            show_404();        
        //$id                 = intval(addslashes($_POST['id']));
        $paymentnumber      = addslashes($_POST['paymentnumber']);
        $paymentdate        = addslashes($_POST['paymentdate']);
        $invoiceamount      = addslashes($_POST['invoiceamount']); 
        $invoiceamountmst   = addslashes($_POST['invoiceamountmst']); 
        $paymentcreatedate  = addslashes($_POST['paymentcreatedate']);        
        if($this->record->checkVendor())
            echo json_encode(array('duplikat_vendor'=>true));
        else if ($this->record->checkCurrency())
            echo json_encode(array('duplikat_currency'=>true));
        else
            echo json_encode(array('msg'=>'Gagal'));
    }
    
    function bedavendor()
    {
        if(!isset($_POST))	
            show_404();        
        
        $OrderAccount      = addslashes($_POST['OrderAccount']);       
        if($this->record->bedavendor())
            echo json_encode(array('res'=>true));        
        else
            echo json_encode(array('res'=>false));
    }
}

/* End of file payment.php */
/* Location: ./application/controllers/transaksi/payment.php */