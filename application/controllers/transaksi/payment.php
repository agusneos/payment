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
    
    function split(){
        $auth   = new Auth();
        $auth->restrict();
        if(!isset($_POST))	
            show_404();
        
        $id             = addslashes($_POST['Id']);
        $sum            = addslashes($_POST['Sum']);
        $sisa           = addslashes($_POST['Sisa']);
        
        echo $this->record->split($id, $sum, $sisa);
    }
    
    function delete(){
        $auth   = new Auth();
        $auth->restrict();
        if(!isset($_POST))	
            show_404();
        
        $id             = addslashes($_POST['InvoiceId']);
        
        echo $this->record->delete($id);
    }
}

/* End of file payment.php */
/* Location: ./application/controllers/transaksi/payment.php */