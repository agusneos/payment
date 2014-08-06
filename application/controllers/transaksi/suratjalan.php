<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suratjalan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_suratjalan','record');
    }
    
    function index()
    {
        $auth       = new Auth();
         // mencegah user yang belum login untuk mengakses halaman ini
        $auth->restrict();
        
        if (isset($_GET['grid'])) 
            echo $this->record->index();        
         else 
            $this->load->view('transaksi/suratjalan/v_suratjalan');        
    }   
    
    function update()
    {
        if(!isset($_POST))	
            show_404();
        
        $id     = intval(addslashes($_POST['id']));
        $close  = addslashes($_POST['close']);
        
        if($this->record->update($id))
            echo json_encode(array('success'=>true));
        else
            echo json_encode(array('msg'=>'Gagal menghapus data'));
    }
    
    function lotsj()
    {                
        $su = $this->uri->segment(4);
        $data['nilai'] = $su;        
        
        if (isset($_GET['grid']))          
            echo $this->record->lotsj($_GET['nilai']); 
        else             
            $this->load->view('transaksi/suratjalan/v_lotsj', $data);    
    }   
            
}

/* End of file suratjalan.php */
/* Location: ./application/controllers/transaksi/suratjalan.php */